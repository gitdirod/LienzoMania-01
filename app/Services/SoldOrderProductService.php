<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\SoldOrder;
use App\Models\SoldOrderProduct;

class SoldOrderProductService
{
    public function insertSoldOrderProducts($products, $order_id)
    {
        $Array_products = [];
        foreach ($products as $pro) {
            $Array_products[] = [
                'sold_order_id' => $order_id,
                'product_id' => $pro['id'],
                'quantity' => $pro['quantity'],
                'price' => $pro['price'],
                'subtotal' => $pro['subtotal'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }
        SoldOrderProduct::insert($Array_products);
        return $Array_products;
    }
    public function getSelledProductsTotalsById(array $idProducts)
    {
        // Primero, obtenemos los ID de las órdenes vendidas que tienen estado "PAGADO"
        $paidSoldOrdersIds = SoldOrder::whereHas('soldOrderPayment', function ($query) {
            $query->where('state', 'PAGADO');
        })->pluck('id')->toArray();

        // Luego, filtramos los productos vendidos basándonos en esos IDs
        return SoldOrderProduct::whereIn('product_id', $idProducts)
            ->whereIn('sold_order_id', $paidSoldOrdersIds) // Filtramos por los IDs de ordenes vendidas pagadas
            ->groupBy('product_id')
            ->selectRaw('product_id, SUM(quantity) as units')
            ->get()
            ->map(function ($product) {
                $product->units = (int) $product->units;
                return $product;
            })
            ->keyBy('product_id');
    }

    public function getSelledOrderProductsTotalsById(array $idProducts)
    {
        return SoldOrderProduct::whereIn('product_id', $idProducts)
            ->groupBy('product_id')
            ->selectRaw('product_id, SUM(quantity) as units')
            ->get()
            ->map(function ($product) {
                $product->units = (int) $product->units;
                return $product;
            })
            ->keyBy('product_id');
    }
}
