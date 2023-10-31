<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Services\AddressService;
use App\Http\Requests\StoreAddressRequest;
use App\Services\PhoneService;

class AddressController extends Controller
{
    use ApiResponse;
    protected $addressService;

    public function __construct(AddressService $addressService)
    {
        $this->middleware('auth');
        $this->addressService = $addressService;
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
    // public function store(Request $request)
    // return response()->json(['dato' => $data]);
    public function store(StoreAddressRequest $request, PhoneService $phoneService)
    {
        try {
            $data = $request->validated();
            $user = $request->user();

            $addres = $this->addressService->findAddress($user, $data);
            if ($addres) {
                $phone = $phoneService->updatePhone($addres->phone_id, $data['phone']);
                $this->addressService->updateAddress($addres, $data, $phone);
                return $this->successResponse('Datos Actualizados.');
            }

            $phone = $phoneService->createPhone($data['phone'], $user);
            $this->addressService->createAddress($user, $data, $phone);
            return $this->successResponse('Dirección creada.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorResponse('Error modelo no encontrado.', $e->getMessage());
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->errorResponse('Error al guardar en base de datos.', $e->getMessage());
        } catch (\Exception $e) {
            return $this->errorResponse('Error inesperado al guardar.', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function show(Address $address)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Address $address)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function destroy(Address $address)
    {
        //
    }
}
