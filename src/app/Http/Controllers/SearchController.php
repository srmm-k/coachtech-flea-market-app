<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Listing;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->input('tab', 'recommend');
        $keyword = $request->input('keyword');

        $listings = collect();
        $title = 'おすすめ';

        if ($keyword) {
            $query = Listing::query();
            if(auth()->check()) {
                $query->where('user_id', '!=', auth()->id());
            }

            $results = $query
                ->where('product_name', 'like', '%' . $keyword . '%')
                ->get();

        //履歴としてセッションに保存(listing_idベース)
        $history = session('search_listing_history', []);
        foreach ($results as $item) {
            if (!in_array($item->id, $history)) {
                $history[] = $item->id;
            }
        }
        session(['search_listing_history' => $history]);

        $listings = $results;
        $title = '検索結果';

        } elseif ($tab === 'mypage') {
            //セッション履歴といいね一覧の両方を取得して表示
            $historyIds = session('search_listing_history', []);
            $likedIds = auth()->check() ? auth()->user()->likedListings->pluck('id')->toArray() : [];

            $mergedIds = array_unique(array_merge($historyIds, $likedIds));

            $listings = Listing::whereIn('id', $mergedIds)->get();
            $title = 'マイリスト';

        } else {
            $listings = Listing::where('user_id', '!=', auth()->id())//自分以外が出品した商品を対象
            ->latest()
            ->inRandomOrder()//ランダム表示
            ->get();

            $title = 'おすすめ';
        }

        return view('toppage', compact('listings', 'title', 'keyword'));
        }

        public function deleteHistory(Request $request)
        {
            $listingId = (int) $request->input('listing_id');
            $history = session('search_listing_history', []);
            $history = array_filter($history, fn($id) => $id != $listingId);
            session(['search_listing_history' => array_values($history)]);//インデックス再振り直し
            return redirect()->back();
        }
}
