<?php

namespace App\Http\Requests;

use App\Models\OrderPayment;
use App\Models\OrderState;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
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
            'order_state' => [
                'required',
                'in:' . OrderState::STATE_BODEGA . ',' . OrderState::STATE_ENTREGADO . ',' . OrderState::STATE_TRAYECTO
            ],
            'order_payment' => [
                'required',
                'in:' . OrderPayment::STATE_PAGADO . ',' . OrderPayment::STATE_POR_PAGAR
            ],
        ];
    }
}
