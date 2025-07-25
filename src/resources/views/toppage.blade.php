@extends('layouts.default')

@section('css')
<link rel="stylesheet" href="{{ asset('css/toppage.css') }}" />
@endsection

@section('content')
<div class="tag-list">
    <div class="example2">
        <form method="GET" action="{{ route('search') }}">
            <input type="hidden" name="tab" value="recommend">
            <input type="radio" id="item1" name="product" {{ request('tab', 'recommend') === 'recommend' ? 'checked' : '' }} onchange="this.form.submit()">
            <label for="item1">おすすめ</label>
        </form>

        <form method="GET" action="{{ route('search') }}">
            <input type="hidden" name="tab" value="mypage">
            <input type="hidden" name="keyword" value="{{ request('keyword') }}">
            <input type="radio" id="item2" name="product" {{ request('tab') === 'mypage' ? 'checked' : '' }} onchange="this.form.submit()">
        <label for="item2">マイリスト</label>
        </form>
    </div>
</div>

<div class="product-list">
    @forelse ($listings as $listing)
        <div class="product-item">
            <a href="{{ route('item.show', ['item_id' => $listing->id]) }}">
                @include('partials.product_image', ['listing' => $listing])
                <h3>{{ $listing->product_name }}</h3>
            </a>

            @if (request('tab') === 'mypage'
            && in_array($listing->id, session('search_listing_history', []))
            && !(auth()->check() && auth()->user()->likedListings->pluck('id')->contains($listing->id)))
            <!-- 検索履歴かつ、いいねされていない商品だけ削除ボタンを表示 -->
                <form action="{{ route('search.history.delete') }}" method="POST" style="position:absolute; top: 4px; right: 4px;">
                    @csrf
                    <input type="hidden" name="listing_id" value="{{ $listing->id }}">
                    <button type="submit" style="background: #f55; color: white; border: none; padding:4px 8px; border-radius:4px;">削除</button>
                </form>
            @endif
        </div>
    @empty
        <p>商品が見つかりませんでした。</p>
    @endforelse
</div>
@endsection
