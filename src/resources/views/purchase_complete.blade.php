@extends('layouts.default')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase_complete.css') }}">
@endsection

@section('content')
<div class="thank-you-container">
    <h2>ご購入ありがとうございます！</h2>
    <p>商品の準備が整い次第、発送いたします。</p>
    <a href="{{ route('mypage') }}" class="btn btn-primary">マイページに戻る</a>
</div>
@endsection