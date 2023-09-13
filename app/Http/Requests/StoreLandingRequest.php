<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\LandingImage;

class StoreLandingRequest extends FormRequest
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
            'type' => [
                'required',
                'numeric',
                'in:' . LandingImage::TYPE_MOBILE . ',' . LandingImage::TYPE_TABLET . ',' . LandingImage::TYPE_DESKTOP
            ],
            'image' => [
                'required',
                'image',
            ]
        ];
    }
}
