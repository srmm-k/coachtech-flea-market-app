<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
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
            'payment_method' => 'required|string|in:card,convenience', //必要に応じて選択肢は調整
            'postcode' => ['required', 'regex:/^\d{3}-\d{4}$/'],
            'address' => 'required|string|max:255',
            'building_name' => 'required|string|max:255',
            'price' => 'required|integer|max:300000',
        ];
    }

    public function messages(): array
    {
        return [
            'payment_method.required' => '支払い方法を選択してください',
            'postcode.required' => '郵便番号を入力してください',
            'postcode.regex' => '郵便番号は「123-4567」の形式で入力してください',
            'address.required' => '住所を入力してください',
            'building_name.required' => '建物名を入力してください',
            'price.required' => '30万円以上の商品はカード支払いへ変更してください'
        ];
    }
}
