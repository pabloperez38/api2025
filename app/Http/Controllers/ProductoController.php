<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
     /**
     * @OA\Get(
     *     path="/api/productos",
     *     summary="Obtener lista de productos",
     *     security={{"bearerAuth":{}}},
     *     tags={"Productos"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de productos obtenida correctamente"
     *     ),
     *    @OA\Response(
     *         response=401,
     *         description="No autorizado - Token ausente o inválido",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="No autenticado o token inválido")
     *         )
     *     ),
     * )
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
     * @OA\Post(
     *     path="/api/productos",
     *     summary="Crear un nuevo producto",
     *     description="Crea un producto con los datos enviados en el cuerpo de la petición.",
     *     tags={"Productos"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre","stock","precio","categoria_id"},
     *             @OA\Property(property="nombre", type="string", maxLength=150, example="Laptop Gamer"),
     *             @OA\Property(property="descripcion", type="string", nullable=true, example="Laptop con 16GB RAM y RTX 4060"),
     *             @OA\Property(property="stock", type="integer", minimum=0, example=20),
     *             @OA\Property(property="precio", type="number", format="float", minimum=0, example=1500.50),
     *             @OA\Property(property="peso", type="number", format="float", nullable=true, minimum=0.01, example=2.5),
     *             @OA\Property(property="disponible", type="boolean", example=true),
     *             @OA\Property(property="fecha_vencimiento", type="string", format="date", nullable=true, example="2025-12-31"),
     *             @OA\Property(property="publicado_en", type="string", format="date-time", nullable=true, example="2025-09-30T12:30:00Z"),
     *             @OA\Property(property="categoria_id", type="integer", example=3, description="ID de una categoría existente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Producto creado correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Producto creado correctamente"),
     *             @OA\Property(property="producto", type="object",
     *                 @OA\Property(property="id", type="integer", example=10),
     *                 @OA\Property(property="nombre", type="string", example="Laptop Gamer"),
     *                 @OA\Property(property="descripcion", type="string", example="Laptop con 16GB RAM y RTX 4060"),
     *                 @OA\Property(property="stock", type="integer", example=20),
     *                 @OA\Property(property="precio", type="number", format="float", example=1500.50),
     *                 @OA\Property(property="peso", type="number", format="float", example=2.5),
     *                 @OA\Property(property="disponible", type="boolean", example=true),
     *                 @OA\Property(property="fecha_vencimiento", type="string", format="date", example="2025-12-31"),
     *                 @OA\Property(property="publicado_en", type="string", format="date-time", example="2025-09-30T12:30:00Z"),
     *                 @OA\Property(property="categoria_id", type="integer", example=3),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-09-30T13:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-09-30T13:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Datos inválidos"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error interno al crear el producto"),
     *             @OA\Property(property="error", type="string", example="SQLSTATE[23000]: Integrity constraint violation...")
     *         )
     *     )
     * )
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
     /**
     * @OA\Get(
     *     path="/api/productos/{id}",
     *     summary="Obtener un producto por ID",
     *     description="Devuelve la información de un producto específico según su ID.",
     *     tags={"Productos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del producto",
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Producto encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=10),
     *             @OA\Property(property="nombre", type="string", example="Laptop Gamer"),
     *             @OA\Property(property="descripcion", type="string", example="Laptop con 16GB RAM y RTX 4060"),
     *             @OA\Property(property="stock", type="integer", example=20),
     *             @OA\Property(property="precio", type="number", format="float", example=1500.50),
     *             @OA\Property(property="peso", type="number", format="float", example=2.5),
     *             @OA\Property(property="disponible", type="boolean", example=true),
     *             @OA\Property(property="fecha_vencimiento", type="string", format="date", example="2025-12-31"),
     *             @OA\Property(property="publicado_en", type="string", format="date-time", example="2025-09-30T12:30:00Z"),
     *             @OA\Property(property="categoria_id", type="integer", example=1),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2025-09-30T13:00:00Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2025-09-30T13:00:00Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Producto no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Producto no encontrado")
     *         )
     *     )
     * )
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

       /**
     * @OA\Put(
     *     path="/api/productos/{id}",
     *     summary="Actualizar un producto existente",
     *     description="Actualiza los datos de un producto según su ID.",
     *     tags={"Productos"},
     *      security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del producto a actualizar",
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre","stock","precio","categoria_id"},
     *             @OA\Property(property="nombre", type="string", maxLength=150, example="Laptop Gamer Actualizada"),
     *             @OA\Property(property="descripcion", type="string", nullable=true, example="Laptop con 16GB RAM y RTX 4060"),
     *             @OA\Property(property="stock", type="integer", minimum=0, example=25),
     *             @OA\Property(property="precio", type="number", format="float", minimum=0, example=1550.75),
     *             @OA\Property(property="peso", type="number", format="float", nullable=true, minimum=0.01, example=2.5),
     *             @OA\Property(property="disponible", type="boolean", example=true),
     *             @OA\Property(property="fecha_vencimiento", type="string", format="date", nullable=true, example="2025-12-31"),
     *             @OA\Property(property="publicado_en", type="string", format="date-time", nullable=true, example="2025-09-30T12:30:00Z"),
     *             @OA\Property(property="categoria_id", type="integer", example=3, description="ID de una categoría existente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Producto actualizado correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Producto actualizado correctamente"),
     *             @OA\Property(property="producto", type="object",
     *                 @OA\Property(property="id", type="integer", example=10),
     *                 @OA\Property(property="nombre", type="string", example="Laptop Gamer Actualizada"),
     *                 @OA\Property(property="descripcion", type="string", example="Laptop con 16GB RAM y RTX 4060"),
     *                 @OA\Property(property="stock", type="integer", example=25),
     *                 @OA\Property(property="precio", type="number", format="float", example=1550.75),
     *                 @OA\Property(property="peso", type="number", format="float", example=2.5),
     *                 @OA\Property(property="disponible", type="boolean", example=true),
     *                 @OA\Property(property="fecha_vencimiento", type="string", format="date", example="2025-12-31"),
     *                 @OA\Property(property="publicado_en", type="string", format="date-time", example="2025-09-30T12:30:00Z"),
     *                 @OA\Property(property="categoria_id", type="integer", example=3),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-09-30T13:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-09-30T14:15:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Datos inválidos"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error interno al actualizar el producto"),
     *             @OA\Property(property="error", type="string", example="SQLSTATE[23000]: Integrity constraint violation...")
     *         )
     *     )
     * )
     */
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
                'categoria_id'      => 'required|exists:categorias,id',
            ]);

            // Actualizar producto
            $producto->nombre            = $validated['nombre'];
            $producto->descripcion       = $validated['descripcion'] ?? null;
            $producto->stock             = $validated['stock'];
            $producto->precio            = $validated['precio'];
            $producto->peso              = $validated['peso'] ?? null;
            $producto->disponible        = $validated['disponible'] ?? true;          
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
     * @OA\Delete(
     *     path="/api/productos/{id}",
     *     summary="Eliminar un producto",
     *     description="Elimina un producto existente según su ID.",
     *     tags={"Productos"},
     *      security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del producto a eliminar",
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Producto eliminado correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Producto eliminado correctamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Producto no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Producto no encontrado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error interno al eliminar el producto"),
     *             @OA\Property(property="error", type="string", example="SQLSTATE[23000]: Integrity constraint violation...")
     *         )
     *     )
     * )
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
