<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;

class TecnicoController extends Controller
{
    public function index(): JsonResponse
    {
        $tecnicos = User::withCount('reparaciones')->get();

        return response()->json([
            'exito' => true,
            'mensaje' => 'Lista de técnicos obtenida correctamente.',
            'total' => $tecnicos->count(),
            'datos' => $tecnicos->map(function ($tecnico) {
                return [
                    'id' => $tecnico->id,
                    'nombre' => $tecnico->name,
                    'numero' => $tecnico->numero,
                    'email' => $tecnico->email,
                    'total_reparaciones' => $tecnico->reparaciones_count,
                    'registrado_en' => $tecnico->created_at->format('Y-m-d H:i:s'),
                ];
            }),
        ]);
    }

    public function show(User $tecnico): JsonResponse
    {
        $tecnico->loadCount('reparaciones');

        $estadisticas = $tecnico->reparaciones()
            ->selectRaw('estado, COUNT(*) as total')
            ->groupBy('estado')
            ->pluck('total', 'estado');

        return response()->json([
            'exito' => true,
            'mensaje' => 'Detalle del técnico obtenido correctamente.',
            'datos' => [
                'id' => $tecnico->id,
                'nombre' => $tecnico->name,
                'numero' => $tecnico->numero,
                'email' => $tecnico->email,
                'total_reparaciones' => $tecnico->reparaciones_count,
                'estadisticas_por_estado' => [
                    'recibido' => $estadisticas['recibido'] ?? 0,
                    'proceso' => $estadisticas['proceso'] ?? 0,
                    'listo' => $estadisticas['listo'] ?? 0,
                    'entregado' => $estadisticas['entregado'] ?? 0,
                ],
                'registrado_en' => $tecnico->created_at->format('Y-m-d H:i:s'),
            ],
        ]);
    }

    public function historial(User $tecnico): JsonResponse
    {
        $historial = $tecnico->reparaciones()
            ->with(['bien.plantel', 'bien.entidad', 'cliente'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'exito' => true,
            'mensaje' => 'Historial del técnico obtenido correctamente.',
            'datos' => [
                'tecnico' => [
                    'id' => $tecnico->id,
                    'nombre' => $tecnico->name,
                    'numero' => $tecnico->numero,
                    'email' => $tecnico->email,
                ],
                'total_reparaciones' => $historial->count(),
                'historial' => $historial->map(function ($rep) {
                    return [
                        'id' => $rep->id,
                        'estado' => $rep->estado,
                        'falla_reportada' => $rep->falla_reportada,
                        'accesorios_incluidos' => $rep->accesorios_incluidos,
                        'bien' => [
                            'numero_inventario' => $rep->bien->numero_inventario,
                            'tipo_bien' => $rep->bien->tipo_bien,
                            'marca' => $rep->bien->marca,
                            'modelo' => $rep->bien->modelo,
                            'plantel' => $rep->bien->plantel->nombre,
                            'entidad' => $rep->bien->entidad->nombre,
                        ],
                        'cliente' => [
                            'nombre' => $rep->cliente->nombre_completo,
                            'telefono' => $rep->cliente->telefono,
                        ],
                        'fecha_recepcion' => $rep->created_at->format('Y-m-d H:i:s'),
                        'ultima_actualizacion' => $rep->updated_at->format('Y-m-d H:i:s'),
                    ];
                }),
            ],
        ]);
    }
}
