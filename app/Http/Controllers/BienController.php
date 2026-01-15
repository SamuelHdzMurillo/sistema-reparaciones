<?php

namespace App\Http\Controllers;

use App\Models\Bien;
use Illuminate\Http\JsonResponse;

class BienController extends Controller
{
    public function index(): JsonResponse
    {
        $bienes = Bien::with(['plantel', 'entidad'])
            ->withCount('reparaciones')
            ->get();

        return response()->json([
            'exito' => true,
            'mensaje' => 'Lista de bienes obtenida correctamente.',
            'total' => $bienes->count(),
            'datos' => $bienes->map(function ($bien) {
                return [
                    'id' => $bien->id,
                    'numero_inventario' => $bien->numero_inventario,
                    'tipo_bien' => $bien->tipo_bien,
                    'marca' => $bien->marca,
                    'modelo' => $bien->modelo,
                    'numero_serie' => $bien->numero_serie,
                    'especificaciones' => $bien->especificaciones,
                    'plantel' => $bien->plantel->nombre,
                    'entidad' => $bien->entidad->nombre,
                    'total_reparaciones' => $bien->reparaciones_count,
                    'creado_en' => $bien->created_at->format('Y-m-d H:i:s'),
                ];
            }),
        ]);
    }

    public function show(Bien $bien): JsonResponse
    {
        $bien->load(['plantel', 'entidad', 'reparaciones.cliente', 'reparaciones.tecnico']);

        return response()->json([
            'exito' => true,
            'mensaje' => 'Detalle del bien obtenido correctamente.',
            'datos' => [
                'id' => $bien->id,
                'numero_inventario' => $bien->numero_inventario,
                'tipo_bien' => $bien->tipo_bien,
                'marca' => $bien->marca,
                'modelo' => $bien->modelo,
                'numero_serie' => $bien->numero_serie,
                'especificaciones' => $bien->especificaciones,
                'plantel' => [
                    'id' => $bien->plantel->id,
                    'nombre' => $bien->plantel->nombre,
                ],
                'entidad' => [
                    'id' => $bien->entidad->id,
                    'nombre' => $bien->entidad->nombre,
                ],
                'total_reparaciones' => $bien->reparaciones->count(),
                'creado_en' => $bien->created_at->format('Y-m-d H:i:s'),
            ],
        ]);
    }

    public function historial(Bien $bien): JsonResponse
    {
        $historial = $bien->reparaciones()
            ->with(['cliente', 'tecnico:id,name,numero,email'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'exito' => true,
            'mensaje' => 'Historial del bien obtenido correctamente.',
            'datos' => [
                'bien' => [
                    'id' => $bien->id,
                    'numero_inventario' => $bien->numero_inventario,
                    'tipo_bien' => $bien->tipo_bien,
                    'marca' => $bien->marca,
                    'modelo' => $bien->modelo,
                ],
                'total_reparaciones' => $historial->count(),
                'historial' => $historial->map(function ($rep) {
                    return [
                        'id' => $rep->id,
                        'estado' => $rep->estado,
                        'falla_reportada' => $rep->falla_reportada,
                        'accesorios_incluidos' => $rep->accesorios_incluidos,
                        'cliente' => $rep->cliente->nombre_completo,
                        'telefono_cliente' => $rep->cliente->telefono,
                        'tecnico' => $rep->tecnico->name,
                        'numero_tecnico' => $rep->tecnico->numero,
                        'fecha_recepcion' => $rep->created_at->format('Y-m-d H:i:s'),
                        'ultima_actualizacion' => $rep->updated_at->format('Y-m-d H:i:s'),
                    ];
                }),
            ],
        ]);
    }
}
