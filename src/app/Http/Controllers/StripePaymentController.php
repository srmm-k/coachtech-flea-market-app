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


class StripePaymentController extends Controller
{
    public function process(PurchaseRequest $request, $id)
    {
        $method = $request->payment_method;
        $listing = Listing::findOrFail($id); //商品情報を取得

        //自分の商品は購入できないようにする
        if ($listing->user_id === auth()->id()) {
            return redirect()->route('mypage')->with('error', '自分の商品は購入できません。');
        }
        // 既に購入されているかチェック
        if ($listing->is_sold) {
            return redirect()->route('mypage')->with('error', 'この商品は既に購入されています。');
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        DB::beginTransaction();

        try {
            // Purchaseモデルのインスタンスを準備
            // StripePaymentIntentIDがない場合はnullでOK
            $purchase = Purchase::create([
                'user_id' => auth()->id(),
                'listing_id' => $listing->id,
                'price' => $request->price,
                'payment_method' => $method,
                // 'stripe_payment_intent_id' は後でセットするか、カード決済ではSession IDを使う
                'status' => 'pending', // 支払い開始時は保留
                'shipping_postcode' => $request->postcode, // Requestから取得
                'shipping_address' => $request->address,
                'shipping_building' => $request->building_name,
            ]);

            // Listingのis_soldとbuyer_idを更新(共通処理として先に実行)
            $listing->buyer_id = auth()->id();
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

        } elseif ($method ===  'convenience') {
            //コンビニ決済
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

            //確認後削除
            \Log::info('Stripeから受け取ったPaymentIntentのステータス: ' . $confirmedPaymentIntent->status);
            \Log::info('Stripeから受け取ったPaymentIntentのnext_actionの中身: ' . json_encode($confirmedPaymentIntent->next_action));


            // if ($confirmedPaymentIntent->status === 'requires_action' && isset($confirmedPaymentIntent->next_action->konbini_display_details)) {
            $purchase->stripe_payment_intent_id = $confirmedPaymentIntent->id;
            $purchase->status = 'requires_payment_method';
            $purchase->save();

            DB::commit(); // トランザクションをコミット

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
        return view('purchase_complete');
    }

    public function cancel(Request $request)
    {
        return redirect()->route('top')->with('error', '支払いがキャンセルされました');
    }
}
