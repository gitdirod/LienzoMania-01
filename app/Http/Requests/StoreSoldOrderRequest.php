<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSoldOrderRequest extends FormRequest
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
            'total' => [
                'required',
                'numeric'
            ],
            'subtotal' => [
                'required',
                'numeric'
            ],


            'customer_addresses' => [
                'required_without_all:addresses',
                'array',
            ],
            'customer_addresses.send_id' => [
                'required_with:customer_addresses',
                'numeric',
            ],
            'customer_addresses.envoice_id' => [
                'required_with:customer_addresses',
                'numeric',
            ],

            'addresses' => [
                'required_without_all:customer_addresses',
                'array',
            ],
            'addresses.send_id' => [
                'required_with:addresses',
                'numeric',
            ],
            'addresses.envoice_id' => [
                'required_with:addresses',
                'numeric',
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

            'image' => [
                'image',
                'sometimes'
            ]
        ];
    }
}
