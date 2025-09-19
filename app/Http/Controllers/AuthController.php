<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            // Validación de datos
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|unique:users',
                'password' => 'required|string|min:6',
                'role' => 'in:admin,user,client', // roles permitidos
            ]);

            // Crear usuario
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'] ?? 'user', // por defecto "user"
            ]);
            // Respuesta exitosa
            return response()->json([
                'success' => true,
                'message' => 'Usuario creado correctamente',
                'user' => $user
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Errores de validación
            return response()->json([
                'success' => "error",
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // Otros errores
            return response()->json([
                'success' => "error",
                'message' => 'Ocurrió un error al crear el usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    //Login
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|string|min:6',
            ]);

            $credentials = $request->only('email', 'password');

            // Intentar generar token
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Credenciales inválidas'
                ], 401);
            }

            // Expiración del token
            //$expiresIn = JWTAuth::factory()->getTTL() * 60;

            return response()->json([
                'success' => true,
                'message' => 'Login exitoso',
                'token' => $token,
                'token_type' => 'bearer',
               // 'expires_in' => $expiresIn
            ]);
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo crear el token',
                'error' => $e->getMessage()
            ], 500);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de servidor',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function logout()
    {
        try {
            JWTAuth::parseToken()->invalidate();

            return response()->json([
                'success' => true,
                'message' => 'Logout exitoso'
            ]);
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo cerrar sesión',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
