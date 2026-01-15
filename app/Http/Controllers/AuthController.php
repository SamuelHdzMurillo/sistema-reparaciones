<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $usuario = User::where('email', $request->email)->first();

        if (!$usuario || !Hash::check($request->password, $usuario->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales son incorrectas.'],
            ]);
        }

        $token = $usuario->createToken('auth_token')->plainTextToken;

        $estadisticas = $usuario->reparaciones()
            ->selectRaw('estado, COUNT(*) as total')
            ->groupBy('estado')
            ->pluck('total', 'estado');

        return response()->json([
            'exito' => true,
            'mensaje' => 'Inicio de sesión exitoso.',
            'datos' => [
                'usuario' => [
                    'id' => $usuario->id,
                    'nombre' => $usuario->name,
                    'numero' => $usuario->numero,
                    'email' => $usuario->email,
                ],
                'estadisticas' => [
                    'recibido' => $estadisticas['recibido'] ?? 0,
                    'proceso' => $estadisticas['proceso'] ?? 0,
                    'listo' => $estadisticas['listo'] ?? 0,
                    'entregado' => $estadisticas['entregado'] ?? 0,
                ],
                'token' => $token,
                'tipo_token' => 'Bearer',
            ],
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'exito' => true,
            'mensaje' => 'Sesión cerrada correctamente.',
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        $usuario = $request->user();
        $usuario->loadCount('reparaciones');

        $estadisticas = $usuario->reparaciones()
            ->selectRaw('estado, COUNT(*) as total')
            ->groupBy('estado')
            ->pluck('total', 'estado');

        return response()->json([
            'exito' => true,
            'mensaje' => 'Información del usuario obtenida correctamente.',
            'datos' => [
                'id' => $usuario->id,
                'nombre' => $usuario->name,
                'numero' => $usuario->numero,
                'email' => $usuario->email,
                'total_reparaciones' => $usuario->reparaciones_count,
                'estadisticas_por_estado' => [
                    'recibido' => $estadisticas['recibido'] ?? 0,
                    'proceso' => $estadisticas['proceso'] ?? 0,
                    'listo' => $estadisticas['listo'] ?? 0,
                    'entregado' => $estadisticas['entregado'] ?? 0,
                ],
                'registrado_en' => $usuario->created_at->format('Y-m-d H:i:s'),
            ],
        ]);
    }
}
