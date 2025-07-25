@extends('layouts.default')

@section('css')
<link rel="stylesheet" href="{{ asset('css/listing.css') }}" />
@endsection

@section('content')
<div class="listing__content">
            <div class="listing__heading">
                <h2>商品の出品</h2>
            </div>

</div>
        <form id="listingForm" class="imageWrapper" method="POST" action="{{ route('sell.store') }}" enctype="multipart/form-data">
            @csrf

        <div class="listing__image">
            <h3 class="listing-img__title">商品画像</h3>
            <!-- <label class="ImageUpload__label"> -->
                <div class="upload-box">
                <label class="fileWrap" for="file_upload">画像を選択する</label>
                <input class="input" type="file" name="image" id="file_upload" accept="image/*" onchange="previewImage(event)">
                <img class="img" id="preview"  alt="商品画像" onclick="document.getElementById('file_upload').click()">
                </div>

                <script>
                    function previewImage(event) {
                        var file = event.target.files[0];
                        var reader = new FileReader();

                        reader.onload = function(e) {
                            var preview = document.getElementById('preview');
                            preview.src = e.target.result;
                            preview.style.display = 'block';
                        };

                        reader.readAsDataURL(file);

                        const fileWrap = document.querySelector('.fileWrap');
                        if (fileWrap) {
                            fileWrap.style.display = 'none';
                        }
                    };
                </script>

            @error('image')
                <p class="error">{{ $message }}</p>
            @enderror

        </div>
        <div class="merchandise-category">
            <h3 class="merchandise-detail">商品の詳細</h3>

            <h3 class="category-title">カテゴリー</h3>
                <div class="example2">
                    <input class="category" type="checkbox" name="category[]" id="category1" value="ファッション" {{ is_array(old('category')) && in_array('ファッション', old('category')) ? 'checked' : '' }}>
                    <label class="category-tag" for="category1">ファッション</label>

                    <input class="category" type="checkbox" name="category[]" id="category2" value="家電" {{ is_array(old('category')) && in_array('家電', old('category')) ? 'checked' : '' }}>
                    <label class="category-tag" for="category2">家電</label>

                    <input class="category" type="checkbox" name="category[]" id="category3" value="インテリア" {{ is_array(old('category')) && in_array('インテリア', old('category')) ? 'checked' : '' }}>
                    <label class="category-tag" for="category3">インテリア</label>

                    <input class="category" type="checkbox" name="category[]" id="category4" value="レディース" {{ is_array(old('category')) && in_array('レディース', old('category')) ? 'checked' : '' }}>
                    <label class="category-tag" for="category4">レディース</label>

                    <input class="category" type="checkbox" name="category[]" id="category5" value="メンズ" {{ is_array(old('category')) && in_array('メンズ', old('category')) ? 'checked' : '' }}>
                    <label class="category-tag" for="category5">メンズ</label>

                    <input class="category" type="checkbox" name="category[]" id="category6" value="コスメ" {{ is_array(old('category')) && in_array('コスメ', old('category')) ? 'checked' : '' }}>
                    <label class="category-tag" for="category6">コスメ</label>

                    <input class="category" type="checkbox" name="category[]" id="category7" value="本" {{ is_array(old('category')) && in_array('本', old('category')) ? 'checked' : '' }}>
                    <label class="category-tag" for="category7">本</label>

                    <input class="category" type="checkbox" name="category[]" id="category8" value="ゲーム" {{ is_array(old('category')) && in_array('ゲーム', old('category')) ? 'checked' : '' }}>
                    <label class="category-tag" for="category8">ゲーム</label>

                    <input class="category" type="checkbox" name="category[]" id="category9" value="スポーツ" {{ is_array(old('category')) && in_array('スポーツ', old('category')) ? 'checked' : '' }}>
                    <label class="category-tag" for="category9">スポーツ</label>

                    <input class="category" type="checkbox" name="category[]" id="category10" value="キッチン" {{ is_array(old('category')) && in_array('キッチン', old('category')) ? 'checked' : '' }}>
                    <label class="category-tag" for="category10">キッチン</label>

                    <input class="category" type="checkbox" name="category[]" id="category11" value="ハンドメイド" {{ is_array(old('category')) && in_array('ハンドメイド', old('category')) ? 'checked' : '' }}>
                    <label class="category-tag" for="category11">ハンドメイド</label>

                    <input class="category" type="checkbox" name="category[]" id="category12" value="アクセサリー" {{ is_array(old('category')) && in_array('アクセサリー', old('category')) ? 'checked' : '' }}>
                    <label class="category-tag" for="category12">アクセサリー</label>

                    <input class="category" type="checkbox" name="category[]" id="category13" value="おもちゃ" {{ is_array(old('category')) && in_array('おもちゃ', old('category')) ? 'checked' : '' }}>
                    <label class="category-tag" for="category13">おもちゃ</label>

                    <input class="category" type="checkbox" name="category[]" id="category14" value="ベビー・キッズ" {{ is_array(old('category')) && in_array('ベビー・キッズ', old('category')) ? 'checked' : '' }}>
                    <label class="category-tag" for="category14">ベビー・キッズ</label>
                </div>

                @error('category')
                    <p class="error">{{ $message }}</p>
                @enderror

                <h3 class="product-situation">商品の状態</h3>
                <select name="condition">
                    <option value="" selected disabled>選択してください</option>
                    <option value="良好" {{ old('condition') == '良好' ? 'selected' : '' }}>良好</option>
                    <option value="目立った傷や汚れなし" {{ old('condition') == '目立った傷や汚れなし' ? 'selected' : '' }}>目立った傷や汚れなし</option>
                    <option value="やや傷や汚れあり" {{ old('condition') == 'やや傷や汚れあり' ? 'selected' : '' }}>やや傷や汚れあり</option>
                    <option value="状態が悪い" {{ old('condition') == '状態が悪い' ? 'selected' : '' }}>状態が悪い</option>
                </select>

                @error('condition')
                    <p class="error">{{ $message }}</p>
                @enderror

                <h3 class="product-description">商品名と説明</h3>
                <div class="form-group">
                        <span class="form__label--item">商品名</span>
                                <input class="input-tag" type="text" name="product_name" value="{{ old('product_name') }}" >
                </div>

                @error('product_name')
                    <p class="error">{{ $message }}</p>
                @enderror

                <div class="form-group">
                        <span class="form__label--item">ブランド名</span>
                                <input class="input-tag" type="text" name="brand_name" value="{{ old('brand_name') }}">
                </div>
                <div class="form-group">
                        <span class="form__label--item">商品の説明</span>
                                <textarea class="textarea" name="description" cols="30" rows="3" placeholder="説明文は255文字以内で記入してください">{{ old('description') }}</textarea>

                @error('description')
                    <p class="error">{{ $message }}</p>
                @enderror
                </div>
                <div class="form-group">
                        <span class="form__label--item">販売価格</span>
                    <div class="input-price-wrapper">
                        <span class="yen-mark">¥</span>
                                <input class="input-tag price-input" type="text" name="price" value="{{ old('price') }}" placeholder="1円以上から記入してください">
                @error('price')
                    <p class="error">{{ $message }}</p>
                @enderror
                    </div>
                </div>
                <button class="listing-button" type="submit">出品する</button>
            </div>
        </form>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('listingForm');
            const priceInput = document.querySelector('input[name="price"]');

            if (form && priceInput) {
                priceInput.addEventListener('input', function (e) {
                    const raw = e.target.value.replace(/,/g, '').replace(/[^\d]/g, '');
                    e.target.value = raw ? Number(raw).toLocaleString() : '';
                });

                form.addEventListener('submit', function () {
                    priceInput.value = priceInput.value.replace(/,/g, '');
                });
            }
        });
        </script>
@endsection
