<?php

namespace App\Services;

use App\Models\TypeProduct;

class TypeProductService
{
    public function createTypeProduct($data, $image)
    {
        return TypeProduct::create([
            'name' => $data['name'],
            'image' => $image
        ]);
    }
    public function updateTypeProduct($id, $data, $image = null)
    {
        $type_product = TypeProduct::find($id);
        if (!$type_product) {
            throw new \Exception("Tipo de producto con ID $id no encontrado");
        }
        $type_product->name = $data['name'];
        if ($image) {
            $type_product->image = $image;
        }
        $type_product->save();
        return $type_product;
    }
}
