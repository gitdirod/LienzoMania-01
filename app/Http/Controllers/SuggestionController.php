<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSuggestionRequest;
use App\Http\Requests\UpdateSuggestionRequest;
use App\Models\Suggestion;
use Illuminate\Http\Request;

class SuggestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $suggestions = Suggestion::all();

        $suggestions = $suggestions->map(function ($suggestion) {
            $suggesteds = $suggestion->suggesteds;

            $products = $suggesteds->map(function ($suggested) {
                $product = $suggested->product;
                $product['images'] = $product->images;
                $product['type_product'] = [
                    'name' => $product->typeProduct->name,
                    'image' => $product->typeProduct->image,
                ];
                $product['group'] = ['name' => $product->category->group->name];
                $product['available'] = $product->units > 0 ? $product->available : false;
                return $product;
            });

            return [
                'id' => $suggestion->id,
                'name' => $suggestion->name,
                'products' => $products
            ];
        });
        return response()->json(['data' => $suggestions]);
        // return [
        //     "data" => Suggestion::with('suggesteds.product')->get()
        // ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSuggestionRequest $request)
    {
        $user = $request->user();
        $datos =  $request->validated();
        if ($user->role == 'admin') {
            $suggestion = new Suggestion;
            $suggestion->name = $datos['name'];
            $suggestion->save();
            return [
                "message" => "Sugerencia creada.",
                "state" => true
            ];
        }
        return [
            "message" => "Usuario no valido.",
            "state" => false
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Suggestion  $suggestion
     * @return \Illuminate\Http\Response
     */
    public function show(Suggestion $suggestion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Suggestion  $suggestion
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSuggestionRequest $request, Suggestion $suggestion)
    {
        $user = $request->user();
        $datos = $request->validated();
        if ($user->role == "admin") {
            $suggestion->name = $datos['name'];
            $suggestion->save();
            return [
                "message" => "Nombre actualizado.",
                "state" => true
            ];
        } else {
            return [
                "message" => "Usuario NO autorizado.",
                "state" => false
            ];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Suggestion  $suggestion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Suggestion $suggestion)
    {
        //
    }
}
