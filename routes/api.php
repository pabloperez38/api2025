<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource('categorias', App\Http\Controllers\CategoriaController::class);
Route::apiResource('productos', App\Http\Controllers\ProductoController::class);