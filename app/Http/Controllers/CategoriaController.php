<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //return Categoria::all();
       /*  $datos = Categoria::all();
            return response()->json([
            'success' => true,
            'data' => $datos
            ]);
 */
        //return Categoria::select('id', 'nombre')->get();
        $datos = Categoria::orderBy('nombre', 'desc')->select('id', 'nombre')->get();
        return response()->json($datos, 200);
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Categoria $categoria)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Categoria $categoria)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categoria $categoria)
    {
        //
    }
}
