<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\UserCollection;
use PhpParser\ErrorHandler\Collecting;

class AuthController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user() && Auth::user()->role == 'admin') {
            return new UserCollection(User::orderBy('id', 'DESC')->with('phones')->get());
        }
        return Auth::user();
    }
    public function register(RegisterRequest $request)
    {
        //Validar el registro
        $data = $request->validated();

        //Crear el usuario
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ]);
        //Retorna una respuesta
        return [
            'token' => $user->createToken('token')->plainTextToken,
            'user' => $user
        ];
    }
    public function login(LoginRequest $request)
    {

        $data = $request->validated();

        //Revisar el password
        if (!Auth::attempt($data)) {
            return response()->json(array(
                'message' => "El email o password son incorrectos",
                'errors' => [
                    'password' => "El email o password son incorrectos."
                ]
            ), 422);
        }
        //Autenticar al usuario
        $user = Auth::user();
        return [
            'token' => $user->createToken('token')->plainTextToken,
            'user' => $user
        ];
    }
    public function logout(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();
        return [
            'user' => null
        ];
    }
}
