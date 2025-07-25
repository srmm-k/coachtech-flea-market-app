<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Listing;
use App\Http\Requests\PurchaseRequest;
// use Illuminate\Validation\ValidationException;

class PurchaseController extends Controller
{
    public function start($id)
    {
        $listing = Listing::findOrFail($id);

        if ($listing->user_id === auth()->id()) {
            return redirect()->route('mypage')->with('error', '自分の商品は購入できません。'); //出品者が自分だったらマイページにリダイレクト
        }

        if ($listing->is_sold) {
            return redirect()->route('mypage')->with('error', 'この商品は既に購入されています。'); //既に購入されていたら
        }

        $profile = auth()->user()->profile;
        return view('purchase', compact('listing', 'profile'));
    }
}
