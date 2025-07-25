<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'image' => 'required|image|mimes:jpeg,png|max:5120',
            'category' => 'required|array',
            'category.*' => 'string|max:255',
            'condition' => 'required|in:良好,目立った傷や汚れなし,やや傷や汚れあり,状態が悪い',
            'product_name' => 'required|string|max:255',
            'brand_name' => 'nullable|string|max:255',
            'description' => 'required|string|max:255',
            'price' => 'required|numeric|min:0', //0円以上
        ];
    }

    public function messages()
    {
        return [
            'image.required' => '商品画像は必須です',
            'image.mimes' => '画像は.jpegもしくは.png形式でアップロードしてください',
            'category.required' => 'カテゴリーを選択してください',
            'condition.required' => '商品の状態を選択してください',
            'product_name.required' => '商品名を入力してください',
            'description.required' => '説明文を入力してください',
            'description.max' => '説明文は255文字以内で入力してください',
            'price.required' => '価格を入力してください',
            'price.numeric' => '価格は数値で入力してください',
            'price.min' => '価格は0円以上で入力してください',
        ];
    }

    public function attributes()
    {
        return [
            'image' => '商品画像',
            'category' => 'カテゴリー',
            'condition' => '状態',
            'product_name' => '商品名',
            'brand_name' => 'ブランド名',
            'description' => '説明文',
            'price' => '価格',
        ];
    }
}