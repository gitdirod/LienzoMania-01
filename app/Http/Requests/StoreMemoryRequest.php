<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMemoryRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                'unique:memories',
                'string',
                'max:255'
            ],
            'description' => [
                'required',
                'max:2024'
            ],
            'images' => [
                'required',
                'array',
                'max:1'
            ],
            'images.*' => [
                'required',
                'image',
                'distinct'
            ]
        ];
    }
}
