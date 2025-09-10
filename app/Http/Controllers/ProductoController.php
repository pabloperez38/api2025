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
                'stock' => $producto->stock,
                'precio' => $producto->precio,
                'peso' => $producto->peso,
                'disponible' => $producto->disponible,
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
        try {
            // Validación
            $validated = $request->validate([
                'nombre'            => 'required|string|max:150',
                'descripcion'       => 'nullable|string',
                'stock'             => 'required|integer|min:0',
                'precio'            => 'required|numeric|min:0',
                'peso'              => 'nullable|numeric|min:0.01',
                'disponible'        => 'boolean',
                'fecha_vencimiento' => 'nullable|date|after:today',
                'publicado_en'      => 'nullable|date|before_or_equal:now',
                'categoria_id'      => 'required|exists:categorias,id',
            ]);

            // Crear producto
            $producto = new Producto();
            $producto->nombre            = $validated['nombre'];
            $producto->descripcion       = $validated['descripcion'] ?? null;
            $producto->stock             = $validated['stock'];
            $producto->precio            = $validated['precio'];
            $producto->peso              = $validated['peso'] ?? null;
            $producto->disponible        = $validated['disponible'] ?? true;
            $producto->fecha_vencimiento = $validated['fecha_vencimiento'] ?? null;
            $producto->publicado_en      = $validated['publicado_en'] ?? null;
            $producto->categoria_id      = $validated['categoria_id'];
            $producto->save();

            return response()->json([
                'message'  => 'Producto creado correctamente',
                'producto' => $producto
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Captura errores de validación
            return response()->json([
                'message' => 'Datos inválidos',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // Captura cualquier otro error
            return response()->json([
                'message' => 'Error interno al crear el producto',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
    /* public function store(Request $request)
    {
        try {
            // Validación
            $validated = $request->validate([
                'nombre'            => 'required|string|max:150',
                'descripcion'       => 'nullable|string',
                'stock'             => 'required|integer|min:0',
                'precio'            => 'required|numeric|min:0',
                'peso'              => 'nullable|numeric|min:0.01',
                'disponible'        => 'boolean',
                'fecha_vencimiento' => 'nullable|date|after:today',
                'publicado_en'      => 'nullable|date|before_or_equal:now',
                'categoria_id'      => 'required|exists:categorias,id',
            ]);

            // Crear producto directamente
            $producto = Producto::create($validated);

            return response()->json([
                'message'  => 'Producto creado correctamente',
                'producto' => $producto
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Datos inválidos',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error interno al crear el producto',
                'error'   => $e->getMessage()
            ], 500);
        }
    } */
    /*  public function store(Request $request)
    {
        // Crea el producto directamente con los datos recibidos
        $producto = Producto::create($request->all());

        return response()->json([
            'message'  => 'Producto creado correctamente',
            'producto' => $producto
        ], 201);
    } */
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
    /*  public function update(Request $request, Producto $producto)
    {
        try {
            // Validación
            $validated = $request->validate([
                'nombre'            => 'required|string|max:150',
                'descripcion'       => 'nullable|string',
                'stock'             => 'required|integer|min:0',
                'precio'            => 'required|numeric|min:0',
                'peso'              => 'nullable|numeric|min:0.01',
                'disponible'        => 'boolean',
                'fecha_vencimiento' => 'nullable|date|after:today',
                'publicado_en'      => 'nullable|date|before_or_equal:now',
                'categoria_id'      => 'required|exists:categorias,id',
            ]);

            // Actualiza directamente con los datos validados
            $producto->update($validated);

            return response()->json([
                'message'  => 'Producto actualizado correctamente',
                'producto' => $producto
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Datos inválidos',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error interno al actualizar el producto',
                'error'   => $e->getMessage()
            ], 500);
        }
    } */
    /* public function update(Request $request, Producto $producto)
    {
        // Actualiza el producto con todos los datos recibidos
        $producto->update($request->all());

        return response()->json([
            'message'  => 'Producto actualizado correctamente',
            'producto' => $producto
        ], 200);
    } */
    public function update(Request $request, Producto $producto)
    {
        try {
            // Validación
            $validated = $request->validate([
                'nombre'            => 'required|string|max:150',
                'descripcion'       => 'nullable|string',
                'stock'             => 'required|integer|min:0',
                'precio'            => 'required|numeric|min:0',
                'peso'              => 'nullable|numeric|min:0.01',
                'disponible'        => 'boolean',
                'fecha_vencimiento' => 'nullable|date|after:today',
                'publicado_en'      => 'nullable|date|before_or_equal:now',
                'categoria_id'      => 'required|exists:categorias,id',
            ]);

            // Actualizar producto
            $producto->nombre            = $validated['nombre'];
            $producto->descripcion       = $validated['descripcion'] ?? null;
            $producto->stock             = $validated['stock'];
            $producto->precio            = $validated['precio'];
            $producto->peso              = $validated['peso'] ?? null;
            $producto->disponible        = $validated['disponible'] ?? true;
            $producto->fecha_vencimiento = $validated['fecha_vencimiento'] ?? null;
            $producto->publicado_en      = $validated['publicado_en'] ?? null;
            $producto->categoria_id      = $validated['categoria_id'];
            $producto->save();

            return response()->json([
                'message'  => 'Producto actualizado correctamente',
                'producto' => $producto
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Datos inválidos',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error interno al actualizar el producto',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(?Producto $producto)
    {
        try {
            if (!$producto) {
                return response()->json([
                    'message' => 'Producto no encontrado'
                ], 404);
            }

            $producto->delete();

            return response()->json([
                'message' => 'Producto eliminado correctamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error interno al eliminar el producto',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /*  public function destroy(Producto $producto)
    {
        $producto->delete();

        return response()->json([
            'message' => 'Producto eliminado correctamente'
        ], 200);
    } */
}