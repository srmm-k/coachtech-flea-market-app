<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Listing;

class LikeController extends Controller
{
    public function like(Listing $listing)
    {
        $user = auth()->user();
        if (!$user->likedListings->contains($listing->id)) {
            $user->likedListings()->attach($listing->id);
        }

        if ($listing->user_id === $user->id) {
            return back()->with('error', '自分の出品にはいいねできません。');
        }

        if (($user->likedListings->contains($listing->id))) {
            $user->likedListings()->attach($listing->id);
        }

        return back();
    }

    public function unlike(Listing $listing)
    {
        $user = auth()->user();

        if ($listing->user_id === $user->id) {
            return back()->with('error', '自分の出品には操作できません。');
        }

        $user->likedListings()->detach($listing->id);

        return back();
    }
}
