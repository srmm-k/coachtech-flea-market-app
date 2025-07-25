@extends('layouts.default')

@section('css')
<link rel="stylesheet" href="{{ asset('css/convenience.css') }}">
@endsection

@section('content')
<div class="convenience-container">
    <h2>コンビニ支払い情報</h2>

    <p>以下の情報を使用して、コンビニにてお支払いください。</p>

    <ul>
        <li><strong>支払い金額：</strong>¥{{ number_format($paymentIntent->amount) }}</li>
        <li>
            <strong>支払い期限：</strong>{{ isset($paymentIntent->next_action->konbini_display_details->expires_at) ? \Carbon\Carbon::parse($paymentIntent->next_action->konbini_display_details->expires_at)->format('Y年m月d日 H:i') : '情報がありません'}}
        </li>
        <li>
            <strong>バーコード番号：</strong>{{ isset($paymentIntent->next_action->konbini_display_details->barcode_number) ? ($paymentIntent->next_action->konbini_display_details->barcode_number ?: '取得できませんでした') : '取得できませんでした' }}
        </li>
    </ul>

    <p>お支払いが完了すると、自動的に注文が確定されます。</p>

    <a href="{{ route('mypage') }}" class="btn btn-primary">マイページに戻る</a>
</div>
@endsection

