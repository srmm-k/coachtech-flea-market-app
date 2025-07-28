@extends('layouts.default')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}" />
<link rel="stylesheet" href="{{ asset('css/toppage.css') }}" />
@endsection

@section('content')

<div class="mypage-container">
    <!-- ユーザー情報 -->
    <div class="mypage-header">
        <div class="profile-info">
            @if (!empty($user->profile) && !empty($user->profile->profile_image))
            <img class="profile-icon" src="{{ asset('storage/' . $user->profile->profile_image) }}" alt="アイコン" >
            @else
            <img class="profile-icon" src="{{ asset('images/profile_icon.png') }}" alt="デフォルトアイコン">
            @endif

            <span class="username">{{ $user->name ?? '名前未設定' }}</span>
        </div>
        <div class="edit-profile">
            <a class="btn-primary" href="{{ route('profile.show') }}">プロフィールを編集</a>
        </div>
    </div>

    @php
        $activeTab = request('tab', 'sell');
    @endphp

    <div class="product-switcher">
        <a href="{{ route('mypage', ['tab' => 'sell']) }}">
            <button class="tab-button {{ $activeTab === 'sell' ? 'active' : '' }}" data-tab="selling">出品した商品</button>
        </a>
        <a href="{{ route('mypage', ['tab' => 'buy']) }}">
        <button class="tab-button {{ $activeTab === 'buy' ? 'active' : '' }}" data-tab="purchased">購入した商品</button>
        </a>
    </div>

    @if ($activeTab === 'sell')
    <div class="product-list">
        @forelse ($listings as $listing)
        <div class="product-card">
            <a href="{{ route('item.show', ['item_id' => $listing->id]) }}">
                @include('partials.product_image', ['listing' => $listing])
                <p class="product-name">{{ $listing->product_name }}</p>
            </a>
        </div>
        @empty
            <p>出品した商品はありません。</p>
        @endforelse
    </div>
    @elseif ($activeTab === 'buy')
    <div class="product-list">
        @forelse ($purchasedListings as $listing)
        <div class="product-card">
            <a href="{{ route('item.show', ['item_id' => $listing->id]) }}">
                @include('partials.product_image', ['listing' => $listing])
                <p class="product-name">{{ $listing->product_name }}</p>
            </a>
        </div>
        @empty
        <p>購入した商品はまだありません。</p>
        @endforelse
    </div>
    @endif
</div>
@endsection