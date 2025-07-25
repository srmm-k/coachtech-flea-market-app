@extends('layouts/default2')

@section('css')
<link rel="stylesheet" href="{{ asset('css/login.css') }}" />
@endsection

@section('content')

<div class="login__content">
        <div class="login__heading">
            <h2>ログイン</h2>
        </div>

        <form class="form" action="{{ route('login') }}" method="POST">
            @csrf

            <div class="form__group">
                <div class="form__group-title">
                    <label class="form__label--item">メールアドレス</label>
                    <div class="form__group-content">
                        <div class="form__input--text">
                            <input type="email" name="email" value="{{ old('email') }}" />
                        </div>
                    </div>
                </div>
                <div class="form__error">
                    @error('email')
                    <p>{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="form__group">
                <div class="form__group-title">
                    <label class="form__label--item">パスワード</label>
                    <div class="form__group-content">
                        <div class="form__input--text">
                            <input type="password" name="password" />
                        </div>
                    </div>
                </div>
                <div class="form__error">
                    @error('password')
                    <p>{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <button class="login-button" type="submit">ログインする</button>
            <a class="registration" href="{{ route('register') }}">会員登録はこちら</a>
        </form>
    </div>
@endsection
