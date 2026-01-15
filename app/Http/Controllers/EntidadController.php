<?php

namespace App\Http\Controllers;

use App\Models\Entidad;
use Illuminate\Http\JsonResponse;

class EntidadController extends Controller
{
    public function index(): JsonResponse
    {
        $entidades = Entidad::withCount('bienes')->get();

        return response()->json([
            'exito' => true,
            'mensaje' => 'Lista de entidades obtenida correctamente.',
            'total' => $entidades->count(),
            'datos' => $entidades->map(function ($entidad) {
                return [
                    'id' => $entidad->id,
                    'nombre' => $entidad->nombre,
                    'total_bienes' => $entidad->bienes_count,
                ];
            }),
        ]);
    }

    public function show(Entidad $entidad): JsonResponse
    {
        $entidad->loadCount('bienes');

        return response()->json([
            'exito' => true,
            'mensaje' => 'Detalle de la entidad obtenido correctamente.',
            'datos' => [
                'id' => $entidad->id,
                'nombre' => $entidad->nombre,
                'total_bienes' => $entidad->bienes_count,
            ],
        ]);
    }

    public function bienes(Entidad $entidad): JsonResponse
    {
        $bienes = $entidad->bienes()->with('plantel')->withCount('reparaciones')->get();

        return response()->json([
            'exito' => true,
            'mensaje' => 'Bienes de la entidad obtenidos correctamente.',
            'datos' => [
                'entidad' => [
                    'id' => $entidad->id,
                    'nombre' => $entidad->nombre,
                ],
                'total_bienes' => $bienes->count(),
                'bienes' => $bienes->map(function ($bien) {
                    return [
                        'id' => $bien->id,
                        'numero_inventario' => $bien->numero_inventario,
                        'tipo_bien' => $bien->tipo_bien,
                        'marca' => $bien->marca,
                        'modelo' => $bien->modelo,
                        'numero_serie' => $bien->numero_serie,
                        'plantel' => $bien->plantel->nombre,
                        'total_reparaciones' => $bien->reparaciones_count,
                    ];
                }),
            ],
        ]);
    }
}
