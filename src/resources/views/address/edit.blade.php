@extends('layouts.default')

@section('css')
<link rel="stylesheet" href="{{ asset('css/edit.css') }}">
@endsection

@section('content')
<div class="address-edit-container">
    <h2>住所の変更</h2>

    <form action="{{ route('address.update', ['item_id' => $item_id]) }}" method="POST">
        @csrf
        @method('PUT')
        <label for="postcode">郵便番号</label>
        <input type="text" name="postcode" id="postcode" value="{{ old('postcode', optional($profile)->postcode) }}">

        @error('postcode')
        <p class="form-error">{{ $message }}</p>
        @enderror

        <label for="address">住所</label>
        <input type="text" name="address" id="address" value="{{ old('address', optional($profile)->address) }}">

        @error('address')
        <p class="form-error">{{ $message }}</p>
        @enderror

        <label for="building_name">建物名</label>
        <input type="text" name="building_name" id="building_name" value="{{ old('building_name', optional($profile)->building_name) }}">

        @error('building_name')
        <p class="form-error">{{ $message }}</p>
        @enderror

        <button class="update" type="submit">更新する</button>
    </form>
</div>
@endsection