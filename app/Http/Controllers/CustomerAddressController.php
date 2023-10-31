<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerAddressRequest;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Models\CustomerAddress;
use Illuminate\Support\Facades\DB;
use App\Services\CustomerAddressService;

class CustomerAddressController extends Controller
{
    use ApiResponse;
    protected $customerAddressService;

    public function __construct(CustomerAddressService $customerAddressService)
    {
        // Se aplica el middleware de autenticación a todos los métodos excepto 'index' y 'show'
        $this->middleware('auth')->except(['index', 'show']);

        // Asegura que el usuario puede realizar acciones de administrador solo para los métodos 'store' y 'update'
        $this->middleware('can:admin')->only(['store', 'update', 'destroy']);
        $this->customerAddressService = $customerAddressService;
    }
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
    public function store(StoreCustomerAddressRequest $request)
    {
        $customerAddressService = $this->customerAddressService;

        try {
            DB::beginTransaction();

            $data = $request->validated();
            $customerAddress = $customerAddressService->createCustomerAddress($data);

            DB::commit();

            return $this->successResponse('Dirección de cliente creada.', $customerAddress, 201);
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return $this->errorResponse("Error al crear dirección de cliente en la base de datos", $e->getMessage(), 500);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse("Error inesperado al crear dirección cliente", $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CustomerAddress  $customerAddress
     * @return \Illuminate\Http\Response
     */
    public function show(CustomerAddress $customerAddress)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CustomerAddress  $customerAddress
     * @return \Illuminate\Http\Response
     */
    public function update(StoreCustomerAddressRequest $request, CustomerAddress $customerAddress)
    {
        DB::beginTransaction();

        try {
            $datos = $request->validated();
            $this->customerAddressService->updateCustomerAddress($customerAddress->id, $datos);
            DB::commit();

            return $this->successResponse('Dirección de cliente actualizada');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return $this->errorResponse('Error al actualizar dirección en la base de datos', $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Error inesperado al actualizar dirección en la base de datos', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CustomerAddress  $customerAddress
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerAddress $customerAddress)
    {
        //
    }
}
