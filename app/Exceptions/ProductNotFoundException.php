<?php

namespace App\Exceptions;

use Exception;

class ProductNotFoundException extends Exception
{
    private $productId;

    public function __construct($productId)
    {
        $this->productId = $productId;
        parent::__construct("Producto con ID {$productId} no encontrado.");
    }

    public function getProductId()
    {
        return $this->productId;
    }
}
