@extends('layouts.default2')

@section('css')
<link rel="stylesheet" href="{{ asset('css/member.css') }}" />
@endsection

@section('content')
<div class="member-registration__content">
            <div class="member-registration__heading">
                <h2>会員登録</h2>
            </div>
            <form class="form" action="{{ route('register') }}" method="POST" novalidate>
                @csrf
                <div class="form__group">
                    <div class="form__group-title">
                        <span class="form__label--item">ユーザー名</span>
                        <div class="form__group-content">
                            <div class="form__input--text">
                                <input type="text" name="name" value="{{ old('name') }}" />
                            </div>
                        </div>
                    </div>
                    <div class="form__error">
                        @error('name')
                        <p>{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="form__group">
                    <div class="form__group-title">
                        <span class="form__label--item">メールアドレス</span>
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
                        <span class="form__label--item">パスワード</span>
                        <div class="form__group-content">
                            <div class="form__input--text">
                                <input type="password" id="password" name="password" />
                            </div>
                        </div>
                        <p class="form__note">※英字と数字を含む8文字以上で入力してください</p>
                    </div>
                    <div class="form__error">
                        @error('password')
                        <p>{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="form__group">
                    <div class="form__group-title">
                        <span class="form__label--item">確認用パスワード</span>
                        <div class="form__group-content">
                            <div class="form__input--text">
                                <input type="password" name="password_confirmation" />
                            </div>
                        </div>
                    </div>
                </div>
                <button class="registration-button" type="submit">登録する</button>
                <a class="login" href="{{ route('login') }}">ログインはこちら</a>
            </form>
        </div>
@endsection
