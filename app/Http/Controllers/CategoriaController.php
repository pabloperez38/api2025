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
    public function show($id)
    {
        // Buscar la categoría por id y devolverla en JSON.
        $categoria = Categoria::find($id);

        if (! $categoria) {
            return response()->json([
                'success' => false,
                'message' => 'Categoria no encontrada.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $categoria
        ], 200);
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
