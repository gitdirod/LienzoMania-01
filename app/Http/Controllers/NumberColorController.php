<?php

namespace App\Http\Controllers;

use Faker\Core\Number;
use App\Models\NumberColor;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Services\NumberColorService;
use App\Http\Requests\StoreNumberColorRequest;
use App\Http\Requests\UpdateNumberColorRequest;
use App\Models\Category;

class NumberColorController extends Controller
{
    use ApiResponse;
    protected $numberColorService;


    public function __construct(NumberColorService $numberColorService)
    {
        // Se aplica el middleware de autenticación a todos los métodos excepto 'index' y 'show'
        $this->middleware('auth')->except(['index', 'show']);

        // Asegura que el usuario puede realizar acciones de administrador solo para los métodos 'store' y 'update'
        $this->middleware('can:admin')->only(['store', 'update', 'destroy']);

        $this->numberColorService = $numberColorService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $number_colors = NumberColor::all();
        return $this->successResponse('Potencias retrieved successfully.', $number_colors);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreNumberColorRequest $request)
    {
        try {
            $data = $request->validated();
            $this->numberColorService->createNumberColor($data['name']);
            return $this->successResponse('Número de colores creado correctamente.', 201);
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->errorResponse('Error al crear número de colores en base de datos', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\NumberColor  $numberColor
     * @return \Illuminate\Http\Response
     */
    public function show(NumberColor $numberColor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\NumberColor  $numberColor
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateNumberColorRequest $request, NumberColor $numberColor)
    {
        try {
            $data = $request->validated();
            $this->numberColorService->updateNumberColor($numberColor->id, $data['name']);
            return $this->successResponse('Numero de colores actualizado.');
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->errorResponse('Error al guardar núnero de colores en base de datos', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\NumberColor  $numberColor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, NumberColor $numberColor)
    {
        try {
            $numberColor->delete();
            return $this->numberColorService->deleteNumberColor($numberColor->id);
            return $this->successResponse('Número de colores eliminado');
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->errorResponse('Error al eliminar número de colores de base de datos');
        }
    }
}
