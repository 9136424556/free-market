<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemRequest extends FormRequest
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
            'img_url' => 'required|image|mimes:jpg,svg|max:2048',
            'name' => 'required',
            'price' => 'required|integer',
            'description' => 'required',
            'category' => 'required',
            'condition_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'img_url.required' => '画像を添付してください',
            'img_url.mines:jpg,svg' => '画像はjpgまたはsvg形式のもののみ添付できます',
            'img_url.max' => '画像のサイズが大きすぎます',
            'name.required' =>'名前を入力してください',
            'price.required' => '価格を設定してください',
            'price.integer' => '価格は半角数字で入力してください',
            'description.required' => '商品の詳細を記入してください',
            'category.required' => 'カテゴリーを選択してください',
            'condition_id.required' => '商品の状態を選択してください',
        ];
    }
}
