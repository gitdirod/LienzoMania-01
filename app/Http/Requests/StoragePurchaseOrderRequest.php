<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoragePurchaseOrderRequest extends FormRequest
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
            'envoice' => [
                'required',
                'string',
                'max:255'
            ],
            'subtotal' => [
                'required',
                'numeric'
            ],
            'products' => [
                'required',
                'array',
                'min:1'
            ],
            'products.*.id' => [
                'required',
                'numeric',
            ],
            'products.*.quantity' => [
                'required',
                'numeric',
            ],
            'products.*.price' => [
                'required',
                'numeric',
            ],
        ];
    }
}
