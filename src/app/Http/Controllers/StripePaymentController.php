<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\PaymentIntent;
use App\Models\Purchase;
use App\Models\Listing;
use App\Http\Requests\PurchaseRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;


class StripePaymentController extends Controller
{
    public function process(PurchaseRequest $request, $id)
    {
        $method = $request->payment_method;
        $listing = Listing::findOrFail($id); //商品情報を取得
        $user = Auth::user();

        //自分の商品は購入できないようにする
        if ($listing->user_id === $user->id) {
            return response()->json(['error' => '自分の商品は購入できません。'], 400);
        }
        // 既に購入されているかチェック
        if ($listing->is_sold) {
            return response()->json(['error', 'この商品は既に購入されています。'], 400);
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        DB::beginTransaction();

        try {
            // Purchaseモデルのインスタンスを準備
            // StripePaymentIntentIDがない場合はnullでOK
            $purchase = Purchase::create([
                'user_id' => $user->id,
                'listing_id' => $listing->id,
                'price' => $request->price,
                'payment_method' => $method,
                // 'stripe_payment_intent_id' は後でセットするか、カード決済ではSession IDを使う
                'stripe_payment_intent_id' => null,
                'status' => 'pending', // 支払い開始時は保留
                'shipping_postcode' => $request->postcode, // Requestから取得
                'shipping_address' => $request->address,
                'shipping_building' => $request->building_name,
            ]);

            // Listingのis_soldとbuyer_idを更新(共通処理として先に実行)
            $listing->buyer_id = $user->id;
            $listing->is_sold = true;
            $listing->save();


        // カード決済処理
        if ($method === 'card') {
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'jpy',
                        'product_data' => [
                            'name' => $listing->product_name, //商品名をstripeに渡す
                        ],
                        'unit_amount' => $request->price,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                //購入者IDをSessionのmetadataに含めることで、後続のWebhook等で識別可能にする
                'metadata' => [
                    'purchase_id' => $purchase->id,
                    'listing_id' => $listing->id,
                ],
                //success_urlとcancel_urlには、購入IDをクエリパラメータとして渡す
                'success_url' => route('purchase.success', ['purchase_id' => $purchase->id]) . '&session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('purchase.cancel', ['purchase_id' => $purchase->id]),
            ]);

            DB::commit(); // トランザクションをコミット
            return response()->json(['redirect_url' => $session->url]);

        //コンビニ決済
        } elseif ($method ===  'convenience') {
            //コンビニ払いの金額の上限チェック
            if ($request->price > 300000) {
                DB::rollBack(); //トランザクションをロールバック
                $purchase->delete(); 
                return response()->json(['error' => 'コンビニ払いは30万円未満の商品にのみ対応しています。'], 400);
                }
        

            $paymentIntent = PaymentIntent::create([
                'amount' => $request->price,
                'currency' => 'jpy',
                'payment_method_types' => ['konbini'],
                'payment_method_options' => [
                    'konbini' => [
                        'expires_after_days' => 3, // 支払い期限
                    ],
                ],
                'description' => 'コンビニ支払い: ' . $listing->product_name,
                'metadata' => [
                    'purchase_id' => $purchase->id, // Purchase IDをmetadataに含める
                    'listing_id' => $listing->id,
                ],
            ]);

            //paymentIntentを確定
            $confirmedPaymentIntent = PaymentIntent::retrieve($paymentIntent->id);

            $purchase->stripe_payment_intent_id = $confirmedPaymentIntent->id;
            $purchase->status = 'requires_payment_method';
            $purchase->save();

            DB::commit(); // トランザクションをコミット

            //Stripe.jsにclient_secretを返す
            return response()->json([
                'client_secret' => $confirmedPaymentIntent->client_secret,
                'purchase_id' => $purchase->id,
            ]);

        } else {
                DB::rollBack();
                $purchase->delete();
                Log::error('Invalid payment method selected.');
                return response()->json(['error' => '有効な支払い方法が選択されていません。'], 400);
            }

        } catch (\Stripe\Exception\ApiErrorException $e) {
            DB::rollBack(); // Stripe APIエラーが発生したらロールバック
            Log::error('Stripe API Error: ' . $e->getMessage());
            return response()->json(['error' => '決済処理中にエラーが発生しました。時間をおいて再度お試しください。'], 500);
        } catch (\Exception $e) {
            DB::rollBack(); // その他のエラーが発生したらロールバック
            Log::error('Purchase Process Error: ' . $e->getMessage());
            return response()->json(['error' => '購入処理中に予期せぬエラーが発生しました。'], 500);
        }
    }

    public function success(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $user = Auth::user(); // 認証済みユーザーを取得

        // Stripe Checkoutからのリダイレクトの場合 (session_id がクエリパラメータに含まれる)
        if ($request->has('session_id')) {
            try {
                $session = Session::retrieve($request->session_id);

                $purchaseId = $request->input('purchase_id');
                $purchase = Purchase::where('id', $purchaseId)
                                    ->where('user_id', $user->id)
                                    ->first();
                

                if ($purchase && $session->payment_status === 'paid') {
                    $purchase->stripe_payment_intent_id = $session->payment_intent;
                    $purchase->update(['status' => 'succeeded']);

                    return redirect()->route('purchase.complete')->with('success', 'ご購入が完了しました！');
                } else {
                    Log::warning('Checkout Sessionの決済が完了していないか、Purchaseレコードが見つかりません。', ['purchase_found' => (bool)$purchase, 'payment_status' => $session->payment_status]);
                    return redirect()->route('top')->with('error', '決済が完了していません。');
                }

            } catch (\Stripe\Exception\ApiErrorException $e) {
                Log::error('Stripe API Error in success (Checkout Session): ' . $e->getMessage(), ['exception' => $e]);
                return redirect()->route('top')->with('error', '決済情報の取得に失敗しました: ' . $e->getMessage());
            }
        }
        // コンビニ決済からのリダイレクトの場合 (payment_intent がクエリパラメータに含まれる)
        elseif ($request->has('payment_intent')) {
            try {
                $paymentIntent = PaymentIntent::retrieve($request->payment_intent);

                $purchaseId = $request->input('purchase_id');
                $purchase = Purchase::where('id', $purchaseId)
                                    ->where('user_id', $user->id)
                                    ->first();

                if ($purchase && $paymentIntent->status === 'succeeded') {

                    $purchase->update(['status' => 'succeeded']);

                    return redirect()->route('purchase.complete')->with('success', 'ご購入が完了しました！');
                } else {
                    Log::warning('Payment Intentの決済が完了していないか、Purchaseレコードが見つかりません。', ['purchase_found' => (bool)$purchase, 'payment_intent_status' => $paymentIntent->status]);
                    return redirect()->route('top')->with('error', 'コンビニ決済が完了していません。ステータス: ' . $paymentIntent->status);
                }

            } catch (\Stripe\Exception\ApiErrorException $e) {
                Log::error('Stripe API Error in success (Payment Intent): ' . $e->getMessage(), ['exception' => $e]);
                return redirect()->route('top')->with('error', '決済情報の取得に失敗しました: ' . $e->getMessage());
            }
        }

        Log::warning('無効な決済完了リクエストです。session_idもpayment_intentもありません。', ['request_params' => $request->all()]);
        return redirect()->route('top')->with('error', '無効な決済完了リクエストです。');
    }

        // return view('purchase_complete');

    public function cancel(Request $request)
    {
        return redirect()->route('top')->with('error', '支払いがキャンセルされました');
    }
}
