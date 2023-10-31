<?php

namespace App\Http\Requests;

use App\Models\Address;
use App\Models\Phone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class StoreAddressRequest extends FormRequest
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

            'address' => [
                'required',
                'string',
                'max:255'
            ],
            'ccruc' => [
                'required',
                'string',
                'max:255'
            ],
            'city' => [
                'required',
                'string',
                'max:255'
            ],
            'type' => [
                'required',
                Rule::in([Address::TYPE_MAIN, Address::TYPE_ENVOICE, Address::TYPE_SEND])
            ],
            'people' => [
                'required',
                'string',
                'max:255'
            ],
            'phone' => [
                'required',
                'array'
            ],
            'phone.number' => 'required|string',
            'phone.type' => [
                'required',
                Rule::in([Phone::TYPE_MAIN, Phone::TYPE_ENVOICE, Phone::TYPE_SEND])
            ],
        ];
    }
}
