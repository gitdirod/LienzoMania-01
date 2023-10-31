<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        $products = $this->products->map(function ($pro) {
            $product = $pro->pivot;
            $product['code'] = $pro->code;
            $product['name'] = $pro->name;
            $product['image'] = $pro->image->name;
            return $product;
        });

        $data = [
            'id' => $this->id,
            'total' => $this->total,
            'subtotal' => $this->subtotal,
            'envoice' => $this->envoice,
            'addresses' => $this->getAddressesAttribute(),
            'products' => $products,
            'orderState' => $this->orderState,
            'orderPayment' => $this->orderPayment,
            'payments' => $this->payments,
            'user' => $this->user
        ];
        return $data;
    }
}
