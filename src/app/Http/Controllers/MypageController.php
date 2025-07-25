<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Listing;

class MypageController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $tab = $request->query('tab', 'sell');

        if ($tab === 'sell') {
            $listings = Listing::where('user_id', $user->id)->get();
            return view('mypage', [
                'user' => $user,
                'tab' => 'sell',
                'listings' => $listings,
            ]);
        }
        
        if ($tab === 'buy') {
            $purchasedListings = $user->purchases()->get();
            return view('mypage', [
                'user' => $user,
                'tab' => 'buy',
                'purchasedListings' => $purchasedListings,
            ]);
            }

        return redirect()->route('mypage', ['tab' => 'sell']);
    }
}
