<?php

namespace App\Services;

use App\Models\Product;
use App\Exceptions\ProductNotFoundException;
use App\Exceptions\InsufficientProductQuantityException;
use App\Models\PurchaseOrderProduct;


class ProductService
{
    /**
     * Check product quantities.
     *
     * @param array $products
     * @return bool
     * @throws ProductNotFoundException
     * @throws InsufficientProductQuantityException
     */
    public function validateStockAndPreparePurchaseDetails(array $products): \Illuminate\Support\Collection
    {
        $productIds = array_column($products, 'id');
        $existingProducts = Product::whereIn('id', $productIds)->get()->keyBy('id');
        $Array_products_to_buy = [];
        foreach ($products as $pro) {
            $product = $existingProducts->get($pro['id']);
            if (!$product) {
                throw new ProductNotFoundException("Producto no encontrado con ID {$pro['id']}");
            }

            if ((int) $product->units < (int) $pro['quantity']) {
                throw new InsufficientProductQuantityException($product['code'], $pro['quantity'], $product->units);
            }
            $pro['price'] = number_format((float)$product->price, 2, '.', '');
            $pro['subtotal'] = number_format((float)($pro['price'] * (int)$pro['quantity']), 2, '.', '');
            $Array_products_to_buy[] = $pro;
        }
        return collect($Array_products_to_buy);
    }


    // public function purchaseOrderProduct()
    // {
    //     return $this->hasOne(PurchaseOrderProduct::class, 'product_id')->latestOfMany();
    // }


    public function calculateProductsUnits($purchasedList, $soldList)
    {
        $result = [];

        // Iteramos sobre los productos comprados
        foreach ($purchasedList as $productId => $purchasedProduct) {
            $purchasedUnits = $purchasedProduct->units ?? 0;  // Unidades compradas
            $soldUnits = $soldList[$productId]->units ?? 0;    // Unidades vendidas

            $totalUnits = $purchasedUnits - $soldUnits;  // Calculamos el total de unidades

            // Añadimos al resultado
            $result[$productId] = [
                'product_id' => $productId,
                'units' => $totalUnits
            ];
        }

        return collect($result);
    }
    public function updateProductsStatsByIds(array $productsIds, $purchasedList, $soldList)
    {
        // Obtener todos los productos basados en los IDs proporcionados.
        $products = Product::whereIn('id', $productsIds)->get();

        // Iterar sobre cada producto y actualizar sus estadísticas.
        foreach ($products as $product) {
            $purchasedUnits = $purchasedList[$product->id]->units ?? 0;
            $soldUnits = $soldList[$product->id]->units ?? 0;

            $product->purchased = $purchasedUnits;
            $product->sold = $soldUnits;
            $product->units = $product->purchased - $product->sold;
            $product->price = $product->getPrice();

            $product->save();
        }
    }
}
