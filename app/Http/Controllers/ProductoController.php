<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $datos = Producto::with('categoria:id,nombre')->get()->map(function ($producto) {
            return [
                'id' => $producto->id,
                'nombre' => $producto->nombre,
                'descripcion' => $producto->descripcion,
                //'stock' => $producto->stock,
                'precio' => $producto->precio,
                //'peso' => $producto->peso,
                //'disponible' => $producto->disponible,
                'categoria' => $producto->categoria->nombre, // solo el nombre
            ];
        });
        return response()->json($datos, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Crea el producto directamente con los datos recibidos
        $producto = Producto::create($request->all());
        return response()->json([
            'message' => 'Producto creado correctamente',
            'producto' => $producto
        ], 201);
    }

    /**
     * Display the specified resource.
     * Mostrar un solo producto
     */
    public function show(Producto $producto)
    {
        $producto->load('categoria');
        return response()->json($producto, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Producto $producto)
    {
        // Actualiza el producto con todos los datos recibidos
        $producto->update($request->all());
        return response()->json([
            'message' => 'Producto actualizado correctamente',
            'producto' => $producto
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producto $producto)
    {
        $producto->delete();
        return response()->json([
            'message' => 'Producto eliminado correctamente'
        ], 200);
    }
}
