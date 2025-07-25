@extends('layouts.default')

@section('css')
<link rel="stylesheet" href="{{ asset('css/product_details.css') }}" />
@endsection

@section('content')
<div class="product-detail-container">
    <div class="product-detail-left">
        <img src="{{ asset('storage/' . $listing->image_path) }}" alt="{{ $listing->product_name }}">
        <!-- <p>is_sold: {{ $listing->is_sold ? 'true' : 'false' }}</p> -->
        @if($listing->is_sold)
        <div class="sold-ribbon"></div>
        @endif
    </div>
    <div class="product-detail-right">
        <h2>{{ $listing->product_name }}</h2>
        <p class="brand-name">{{ $listing->brand_name ?? 'ブランド名なし' }}</p>
        <p class="price-tag">¥<span class="price">{{ number_format($listing->price) }}</span>（税込）</p>


        @php
            $user = auth()->user();
            $liked = false;
            $isOwner = false;

            if($user){

            $liked = $user->likedListings->contains($listing->id);

            $isOwner = $user->id === $listing->user_id;
            }
        @endphp

        <div class="product-actions">
            @if (!$isOwner)
            <form action="{{ $liked ? route('unlike', $listing->id) : route('like', $listing->id) }}" method="POST">
                @csrf
                @if($liked)
                    @method('DELETE')
                @endif
            <button type="submit" class="like-button {{ $liked ? 'liked' : ''}}">
                <span class="like-icon">{{ $liked ? '★' : '☆' }}</span>
                <span class="like-count">{{ $listing->liked_by_users_count }}</span>
            </button>
            </form>
        @else
        <button class="like-button" disabled title="自分の商品にはいいねできません">
                <span class="like-icon">{{ $liked ? '★' : '☆' }}</span>
                <span class="like-count">{{ $listing->liked_by_users_count }}</span>
            </button>
        @endif

            <button class="comment-button" disabled>
                💬<span class="comment-count">{{ $listing->comments->count() }}</span>
            </button>
        </div>

        @if(is_null($listing->buyer_id))
            <form action="{{ route('purchase.start', ['id' => $listing->id]) }}" method="GET">
            @csrf
            <button type="submit" class="purchase-button">購入手続きへ</button>
            </form>
            @else
            <p class="sold-label">売り切れました</p>
        @endif
        <div class="product-description">
            <h4>商品説明</h4>
            <p>{!! nl2br(e($listing->description)) !!}</p>
        </div>

        <div class="product-info">
            <h4>商品の情報</h4>
            <p class="category">
                <span class="category-label">カテゴリー</span>
                <span class="category-list">
                @if(is_array($listing->category))
                    @foreach($listing->category as $cat)
                    <span class="category-badge">{{ $cat }}</span>
                    @endforeach
                </span>
                @else
                    <span class="category-badge">未設定</span>
                @endif
            </p>

            <p class="condition">商品の状態
                <span class="condition-tag">{{ $listing->condition ?? '未設定' }}</span>
            </p>
        </div>

        @if(auth()->check() && auth()->id() === $listing->user_id && $listing->buyer_id)
            <div class="shipping-info">
                <h4>購入者の配送先情報</h4>
                <p>〒{{ $listing->shipping_postcode }}</p>
                <p>{{ $listing->shipping_address }}</p>
                <p>{{ $listing->shipping_building }}</p>
            </div>
        @endif

        <div class="comments-section">
            <h4>コメント ({{ $listing->comments->count() }})</h4>
            @foreach($listing->comments as $comment)
            <div class="comment">
                <img class="comment-user-icon" src="{{ optional($comment->user->profile)->profile_image ? asset('storage/' . $comment->user->profile->profile_image) : asset('images/profile_icon.png') }}" alt="アイコン">
                <span class="comment-username">{{ $comment->user->name }}</span>
                <p class="comment-content">{{ $comment->content }}</p>

                @if(auth()->check() && auth()->id() === $comment->user_id)
                <form action="{{ route('comment.destroy', $comment->id) }}" method="POST" onsubmit="return confirm('コメントを削除しますか？');">
                @csrf
                @method('DELETE')
                <button type="submit" class="comment-delete-button">
                    削除
                </button>
                </form>
                @endif
            </div>
            @endforeach

            <div class="comment-form">
                <h4>商品へのコメント</h4>
                @if (!$isOwner)
                <form action="{{ route('comment.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="listing_id" value="{{ $listing->id }}">
                    <textarea name="content" rows="3" placeholder="コメントを入力してください"></textarea>
                    @error('content')
                        <p class="error-message">{{ $message }}</p>
                    @enderror

                    <button type="submit">コメントを送信</button>
                </form>
                @else
                <p class="comment-disabled">※自分の商品にはコメントできません。</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
