@extends('layouts.default2')

@section('css')
<link rel="stylesheet" href="{{ asset('css/verify-email.css') }}" />
@endsection

@section('content')
<div class="verify__container">

    <p class="verify__message">
        登録していただいたメールアドレスに認証メールを送付しました。
    </p>
    <p class="verify__message">
        メール認証を完了してください。
    </p>

    {{-- 再送メッセージ --}}
@if (session('message'))
    <div class="verify__message">
        {{ session('message') }}
    </div>
@endif

<form method="POST" action="{{ route('verification.send') }}">
    @csrf
    <button type="submit" class="verify__resend-button">認証メールを再送する</button>
</form>


<script>
    let intervalId = null;

    function stopChecking() {
        if (intervalId) clearInterval(intervalId);
    }

</script>

@endsection