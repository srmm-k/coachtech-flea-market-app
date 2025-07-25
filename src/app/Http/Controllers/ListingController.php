<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Listing;
use App\Http\Requests\ExhibitionRequest;

class ListingController extends Controller
{
    public function index()
    {
        $listings = \App\Models\Listing::all();
        return view('listing', compact('listings'));
    }

    public function create()
    {
        return view('listing');
    }

    public function store(ExhibitionRequest $request)
    {
        $validated = $request->validated();

        $listing = new Listing();
        $listing -> category = $validated['category'];
        $listing -> condition = $validated['condition'];
        $listing -> product_name = $validated['product_name'];
        $listing -> brand_name = $validated['brand_name'] ?? null;
        $listing -> description = $validated['description'];
        $listing -> price = $validated['price'];
        $listing -> user_id = auth()->id();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
            $listing->image_path = $path;
        }
        $listing->save();

        return redirect()->route('mypage')->with('success', '商品を出品しました！');
    }

    public function __construct()
    {
        $this->middleware('auth')->only(['create', 'store']);
    }

    public function show($item_id)
    {
        $listing = Listing::with(['comments.user.profile', 'likedByUsers'])->findOrFail($item_id);
        $listing->loadCount('likedByUsers');
        $user = auth()->user();
        $liked = $user && $listing->likedByUsers->contains($user->id);

        return view('product_details', compact('listing', 'liked'));
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $listing = Listing::where('product_name', 'like', "%{$keyword}%")->get();
        return view('search_results', compact('listings', 'keyword'));
        }
}
