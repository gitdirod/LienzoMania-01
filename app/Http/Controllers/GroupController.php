<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Traits\ApiResponse;
use App\Services\GroupService;
use App\Http\Resources\GroupCollection;
use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\UpdateGroupRequest;

class GroupController extends Controller
{
    use ApiResponse;
    protected $groupService;

    public function __construct(GroupService $groupService)
    {
        // Se aplica el middleware de autenticación a todos los métodos excepto 'index' y 'show'
        $this->middleware('auth')->except(['index', 'show']);

        // Asegura que el usuario puede realizar acciones de administrador solo para los métodos 'store' y 'update'
        $this->middleware('can:admin')->only(['store', 'update']);
        $this->groupService = $groupService;
    }

    public function index()
    {
        $groups = new GroupCollection(Group::all());
        return $this->successResponse("Grupos recuperados con exito.", $groups);
    }

    public function store(StoreGroupRequest $request)
    {
        try {
            $datos = $request->validated();
            $group = $this->groupService->createGroup($datos['name']);
            return $this->successResponse('Grupo creado.', $group, 201);
        } catch (\Exception $e) {
            return $this->errorResponse('Error al crear el grupo.', $e->getMessage());
        }
    }


    public function show(Group $group)
    {
        return [
            'id' => $group->id,
            'name'  => $group->name,
        ];
    }

    public function update(UpdateGroupRequest $request, Group $group)
    {
        try {
            $datos = $request->validated();
            $group = $this->groupService->updateGroup($group->id, $datos['name']);
            return $this->successResponse('Grupo actualizado.');
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->errorResponse('Error al actualizar el grupo en base de datos.', $e->getMessage());
        } catch (\Exception $e) {
            return $this->errorResponse('Error inesperado al actualizar el grupo.', $e->getMessage());
        }
    }
}
