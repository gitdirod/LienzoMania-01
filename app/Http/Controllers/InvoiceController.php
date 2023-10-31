<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SoldOrder;

class InvoiceController extends Controller
{
    public function generatePDF($soldOrderId)
    {
        // Obtén la factura por su ID
        $soldOrder = SoldOrder::find($soldOrderId);

        if (!$soldOrder) {
            // Manejo de error si la factura no se encuentra
            return abort(404);
        }
    }

    public function showPDF($soldOrderId)
    {
        // Obtén la factura por su ID
        $soldOrder = SoldOrder::find($soldOrderId);

        if (!$soldOrder) {
            // Manejo de error si la factura no se encuentra
            return abort(404);
        }
    }
}
