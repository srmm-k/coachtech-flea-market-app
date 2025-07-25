<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'profile_image' => 'nullable|file|mimes:jpeg,png|max:2048',
            // 最大２MBまでに制限
        ];
    }

    /**
     * バリデーションエラー
     */
    public function messages(): array
    {
        return [
            'profile_image.mimes' => 'プロフィール画像は.jpegまたは、.png形式でアップロードしてください。',
            'profile_image.max' => 'プロフィール画像のサイズは2MB以内にしてください'
        ];
    }
}
