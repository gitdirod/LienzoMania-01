<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\PurchaseOrderProduct;

class PurchaseOrderProductService
{
    public function getPurchasedOrderProductsTotalsById(array $idProducts)
    {
        return PurchaseOrderProduct::whereIn('product_id', $idProducts)
            ->groupBy('product_id')
            ->selectRaw('product_id, SUM(quantity) as units')
            ->get()
            ->map(function ($product) {
                $product->units = (int) $product->units;
                return $product;
            })
            ->keyBy('product_id');
    }
    public function insertPurchaseOrderProducts($products, $order_id)
    {
        $Array_products = [];
        foreach ($products as $pro) {
            $Array_products[] = [
                'purchase_order_id' => $order_id,
                'product_id' => $pro['id'],
                'quantity' => $pro['quantity'],
                'price' => $pro['price'],
                'subtotal' => round($pro['quantity'] * (int)$pro['price'], 2),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }
        PurchaseOrderProduct::insert($Array_products);
        return $Array_products;
    }
    public function getIdsProductToUpdate($products, $IdproductsAndIdPurchaseOrderProducts)
    {
        $productsPurchaseOrderIds = array_column($IdproductsAndIdPurchaseOrderProducts, 'product_id');
        $productIds = array_column($products, 'id');
        $collection1 = collect($productsPurchaseOrderIds);
        $collection2 = collect($productIds);
        return $collection1->union($collection2);
    }
    public function syncPurchaseOrderProducts($products, $IdproductsAndIdPurchaseOrderProducts, $purchase_order_id)
    {
        // $idProducts = array_column($products, 'id');
        $productsPurchaseOrderIds = array_column($IdproductsAndIdPurchaseOrderProducts, 'id');

        $existingProducts = PurchaseOrderProduct::whereIn('id', $productsPurchaseOrderIds)
            ->with('product')
            ->get()
            ->keyBy(function ($item) {
                return $item->product->id;
            });
        $this->deleteRemovedProductsFromOrder($products, $existingProducts);
        $this->addNewProductsToOrder($products, $existingProducts, $purchase_order_id);
        $this->updateExistingProductsInOrder($products, $existingProducts);
    }

    private function addNewProductsToOrder($products, $existingProducts, $purchase_order_id)
    {
        $newProducts = array_filter($products, function ($product) use ($existingProducts) {
            return !isset($existingProducts[$product['id']]);
        });

        foreach ($newProducts as $product) {
            PurchaseOrderProduct::create([
                'product_id' => $product['id'],
                'purchase_order_id' => $purchase_order_id,
                'quantity' => $product['quantity'],
                'price' => $product['price'],
                'subtotal' => round($product['price'] * $product['quantity'], 2),
            ]);
        }
    }

    private function deleteRemovedProductsFromOrder($products, $existingProducts)
    {
        // Obtén los product_ids de la lista proporcionada
        $productIds = array_column($products, 'id');

        // Determina qué productos existentes no están en la lista proporcionada
        $productsToDelete = $existingProducts->filter(function ($product) use ($productIds) {
            return !in_array($product->product_id, $productIds);
        });

        // Elimina esos productos
        foreach ($productsToDelete as $productToDelete) {
            $productToDelete->delete();
        }
    }



    private function updateExistingProductsInOrder($products, $existingProducts)
    {
        // Usar un bucle foreach para iterar sobre los productos que quieres actualizar
        foreach ($products as $product) {
            // Comprobar si el producto ya existe en la colección $existingProducts
            if ($existingProduct = $existingProducts->get($product['id'])) {
                // Si existe, actualiza sus valores
                $existingProduct->quantity = $product['quantity'];
                $existingProduct->price = $product['price'];
                $existingProduct->subtotal = round($product['price'] * $product['quantity'], 2);
                $existingProduct->save();
            }
        }
    }
}
