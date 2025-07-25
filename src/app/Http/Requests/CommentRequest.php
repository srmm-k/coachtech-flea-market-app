<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'listing_id' => 'required|integer|exists:listings,id',
            'content' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
        'content.required' => 'コメントを入力してください',
        'content.max' => 'コメントは255文字以内で入力してください',
        ];
    }

    public function attributes()
    {
        return [
            'content' => 'コメント',
        ];
    }
}
