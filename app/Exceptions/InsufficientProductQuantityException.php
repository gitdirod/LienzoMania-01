<?php

namespace App\Exceptions;

use Exception;

class InsufficientProductQuantityException extends Exception
{
    private $productCode;
    private $requestedQuantity;
    private $availableQuantity;

    /**
     * InsufficientProductQuantityException constructor.
     *
     * @param string $productCode
     * @param int $requestedQuantity
     * @param int $availableQuantity
     */
    public function __construct(string $productCode, int $requestedQuantity, int $availableQuantity)
    {
        $this->productCode = $productCode;
        $this->requestedQuantity = $requestedQuantity;
        $this->availableQuantity = $availableQuantity;
        $message = "Para el producto con código {$productCode}, solo hay {$availableQuantity} unidades disponibles, pero se solicitaron {$requestedQuantity} unidades.";

        parent::__construct($message);
    }

    /**
     * Obtiene el código del producto.
     *
     * @return string
     */
    public function getProductCode(): string
    {
        return $this->productCode;
    }

    /**
     * Obtiene la cantidad solicitada.
     *
     * @return int
     */
    public function getRequestedQuantity(): int
    {
        return $this->requestedQuantity;
    }

    /**
     * Obtiene la cantidad disponible.
     *
     * @return int
     */
    public function getAvailableQuantity(): int
    {
        return $this->availableQuantity;
    }
}
