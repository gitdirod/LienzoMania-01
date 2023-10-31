<?php

namespace App\Http\Requests;

use App\Models\CustomerAddress;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCustomerAddressRequest extends FormRequest
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
            'customer_id' => [
                'required',
                'numeric'
            ],
            'type' => [
                'required',
                Rule::in([CustomerAddress::TYPE_ENVOICE, CustomerAddress::TYPE_SEND])
            ],
            'ccruc' => [
                'required',
                'string',
                'max:15'
            ],
            'people' => [
                'required',
                'string',
                'max:255'
            ],
            'phone' => [
                'required',
                'string',
                'max:17'
            ],
            'city' => [
                'required',
                'string',
                'max:255'
            ],
            'address' => [
                'required',
                'string',
                'max:255'
            ],

        ];
    }
}
