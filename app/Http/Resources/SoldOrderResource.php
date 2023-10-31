<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SoldOrderResource extends JsonResource
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
            $product['price'] = (float)$product->price;
            $product['subtotal'] = (float)$product->subtotal;
            $product['code'] = $pro->code;
            $product['name'] = $pro->name;
            $product['image'] = $pro->image->name;
            return $product;
        });
        $user = $this->user;
        $user['phones'] = $user->phones;

        $data = [
            'id' => $this->id,
            'total' => (float)$this->total,
            'subtotal' => (float)$this->subtotal,
            'envoice' => $this->envoice,
            'addresses' => $this->getAddressesAttribute(),
            'products' => $products,
            'soldOrderTracking' => $this->soldOrderTracking,
            'soldOrderPayment' => $this->soldOrderPayment,
            'payments' => $this->soldOrderPaymentImages,
            'user' => $user
        ];
        return $data;
    }
}
