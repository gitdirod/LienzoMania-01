<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Resources\CustomerCollection;
use App\Models\Customer;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Services\CustomerService;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    use ApiResponse;
    protected $customerService;

    public function __construct(CustomerService $customerService)
    {
        // Se aplica el middleware de autenticación a todos los métodos excepto 'index' y 'show'
        $this->middleware('auth')->except(['index', 'show']);

        // Asegura que el usuario puede realizar acciones de administrador solo para los métodos 'store' y 'update'
        $this->middleware('can:admin')->only(['store', 'update', 'destroy']);
        $this->customerService = $customerService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = new CustomerCollection(Customer::orderBy('id', 'DESC')->get());
        return $this->successResponse('pendiente aun', $customers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCustomerRequest $request)
    {
        $customerService = $this->customerService;

        try {
            DB::beginTransaction();

            $data = $request->validated();
            $customer = $customerService->createCustomer($data);

            DB::commit();

            return $this->successResponse('Cliente creado.', $customer, 201);
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return $this->errorResponse("Error al crear cliente en la base de datos", $e->getMessage(), 500);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse("Error inesperado al crear cliente", $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        try {
            $datos = $request->validated();

            $this->customerService->updateCustomer($customer->id, $datos);
            return $this->successResponse("Cliente actualizado");
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->errorResponse('Error al actualizar cliente en la base de datos', $e->getMessage());
        } catch (\Exception $e) {
            return $this->errorResponse('Error inesperado actualizando cliente', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        //
    }
}
