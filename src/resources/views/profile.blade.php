@extends('layouts.default')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}" />
@endsection

@section('content')
<div class="profile__content">
            <div class="profile__heading">
                <h2>プロフィール設定</h2>
            </div>
                <form class="form" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="profile__image">
                        <input class="input" type="file" name="profile_image" id="file_upload" accept="image/*" onchange="previewImage(event)">
                        <label class="fileWrap" for="file_upload">画像を選択する</label>
                        <img class="img" id="preview" src="{{ $profile && $profile->profile_image ? asset('storage/' . $profile->profile_image) : asset('images/profile_icon.png') }}">

                        <script>
                            function previewImage(event) {
                                var file = event.target.files[0];
                                var reader = new FileReader();

                                reader.onload = function(e) {
                                    var preview = document.getElementById('preview');
                                    preview.src = e.target.result;
                                };

                                reader.readAsDataURL(file);
                            }
                        </script>
                </div>
                
                <div class="form__error image-error">
                    @error('profile_image')
                        <p>{{ $message }}</p>
                    @enderror

                        </div>
                        <div class="form__group">
                            <div class="form__group-title">
                                <span class="form__label--item">ユーザー名</span>
                                <div class="form__group-content">
                                    <div class="form__input--text">
                                        <input type="text" name="name" value="{{ old('name', $user->name) }}" />
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
                                <span class="form__label--item">郵便番号</span>
                                <div class="form__group-content">
                                    <div class="form__input--text">
                                        <input type="text" name="postcode" value="{{ old('postcode', $user->profile->postcode ?? '') }}" placeholder="123-4567のようなハイフンありで入力" />
                                    </div>
                                </div>
                            </div>
                            <div class="form__error">
                                @error('postcode')
                                <p>{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="form__group">
                            <div class="form__group-title">
                                <span class="form__label--item">住所</span>
                                <div class="form__group-content">
                                    <div class="form__input--text">
                                        <input type="text" name="address" value="{{ old('address', $user->profile->address ?? '') }}" placeholder="都道府県・市区町村・番地まで入力" />
                                    </div>
                                </div>
                            </div>
                            <div class="form__error">
                                @error('address')
                                <p>{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="form__group">
                            <div class="form__group-title">
                                <span class="form__label--item">建物名</span>
                                <div class="form__group-content">
                                    <div class="form__input--text">
                                        <input type="text" name="building_name" value="{{ old('building_name', $user->profile->building_name ?? '') }}" placeholder="建物名・部屋番号など（無い場合は無しでも可）" />
                                    </div>
                                </div>
                            </div>
                            <div class="form__error">
                                @error('building_name')
                                <p>{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    <button class="update" type="submit">更新する</button>
                </form>
            </div>
@endsection
