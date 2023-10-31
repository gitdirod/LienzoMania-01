<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StorePaymentRequest;
use Illuminate\Support\Facades\File;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePaymentRequest $request)
    {
        //
        $datos = $request->validated();
        $payment = new Payment;
        $payment = $payment->insertImages($datos);
        return [
            "message" => "Revision pendiente",
            'state' => true
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment)
    {
        return [
            'message' => "Comprobante eliminado mentira",
            'state' => true
        ];
        $user = Auth::user();
        if (isset($user) && $user->role === 'admin') {
            $path_file = "payments/" . $payment->name;
            if (File::exists($path_file)) {
                File::delete($path_file);
            }
        } else {
            return ['message' => "Usuario no autorizado."];
        }
        $payment->delete();
        return [
            'message' => "Comprobante eliminado",
            'state' => true
        ];
    }
}
