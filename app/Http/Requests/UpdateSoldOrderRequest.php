<?php

namespace App\Http\Requests;

use App\Models\SoldOrderPayment;
use App\Models\SoldOrderTracking;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSoldOrderRequest extends FormRequest
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
            'id' => [
                'numeric',
                'required'
            ],
            'envoice' => [
                'string',
                'max:255'
            ],
            'sold_order_tracking' => [
                'required',
                'in:' . SoldOrderTracking::STATE_BODEGA . ',' . SoldOrderTracking::STATE_ENTREGADO . ',' . SoldOrderTracking::STATE_TRAYECTO
            ],
            'sold_order_payment' => [
                'required',
                'in:' . SoldOrderPayment::STATE_PAGADO . ',' . SoldOrderPayment::STATE_POR_PAGAR
            ],
        ];
    }
}
