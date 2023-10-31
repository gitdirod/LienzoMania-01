<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'ccruc' => $this->ccruc,
            'phone' => $this->phone,
            'email' => $this->email,
            'addresses' => $this->getAddressesAttribute(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];

        return $data;
    }
}
