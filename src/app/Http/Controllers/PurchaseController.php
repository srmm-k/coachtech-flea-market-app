<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Listing;
use App\Models\Purchase;
use App\Models\Profile;
use App\Http\Requests\PurchaseRequest;

class PurchaseController extends Controller
{
    public function start($id)
    {
        $listing = Listing::findOrFail($id);
        $user = Auth::user();

        if ($listing->user_id === auth()->id()) {
            return redirect()->route('mypage')->with('error', '自分の商品は購入できません。'); //出品者が自分だったらマイページにリダイレクト
        }

        if ($listing->is_sold) {
            return redirect()->route('mypage')->with('error', 'この商品は既に購入されています。'); //既に購入されていたら
        }

        $profile = $user->profile()->firstOrCreate(
            [],
            [
                'postcode' => '',
                'address' => '',
                'building_name' => '',
                'profile_image' => null,
            ]
        );


        return view('purchase', compact('listing', 'profile'));
    }

    public function store(PurchaseRequest $request, $id)
    {
        $user = Auth::user();
        $listing = Listing::findOrFail($id);

        if ($listing->is_sold || $listing->user_id === $user->id) {
            return response()->json(['error' => 'この商品は購入できません。'], 400);
        }

        $stripePaymentController = new StripePaymentController();
        return $stripePaymentController->processPayment($request, $id);
    }

    public function success(Request $request)
    {
        return redirect()->route('purchase.complete')->with('success', '決済が完了しました！');
    }

    public function complete()
    {
        return view('purchase_complete');
    }
}
