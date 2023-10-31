<?php

namespace App\Http\Controllers;

use App\Models\SoldOrderPayment;
use Illuminate\Http\Request;

class SoldOrderPaymentController extends Controller
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
    public function store(Request $request)
    {
        //
        return 'ok';
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SoldOrderPayment  $soldOrderPayment
     * @return \Illuminate\Http\Response
     */
    public function show(SoldOrderPayment $soldOrderPayment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SoldOrderPayment  $soldOrderPayment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SoldOrderPayment $soldOrderPayment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SoldOrderPayment  $soldOrderPayment
     * @return \Illuminate\Http\Response
     */
    public function destroy(SoldOrderPayment $soldOrderPayment)
    {
        //
    }
}
