<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource('categorias', CategoriaController::class);
//Route::apiResource('productos', ProductoController::class)->middleware('auth:api');

// GET → Listar todos
//Route::get('productos', [ProductoController::class, 'index'])->middleware('auth:api');
// POST → Crear nuevo
//Route::post('productos', [ProductoController::class, 'store']);
// GET → Mostrar uno en particular
//Route::get('productos/{producto}', [ProductoController::class, 'show']);
// PUT → Actualizar completamente
//Route::put('productos/{producto}', [ProductoController::class, 'update']);
// PATCH → Actualizar parcialmente
//Route::patch('productos/{producto}', [ProductoController::class, 'update']);

// DELETE → Eliminar
//Route::delete('productos/{producto}', [ProductoController::class, 'destroy']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:api']], function () {
 Route::get('/productos', [ProductoController::class, 'index']);
 Route::post('/logout', [AuthController::class, 'logout']);
});
