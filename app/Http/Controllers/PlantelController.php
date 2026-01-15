<?php

namespace App\Http\Controllers;

use App\Models\Plantel;
use Illuminate\Http\JsonResponse;

class PlantelController extends Controller
{
    public function index(): JsonResponse
    {
        $planteles = Plantel::withCount('bienes')->get();

        return response()->json([
            'exito' => true,
            'mensaje' => 'Lista de planteles obtenida correctamente.',
            'total' => $planteles->count(),
            'datos' => $planteles->map(function ($plantel) {
                return [
                    'id' => $plantel->id,
                    'nombre' => $plantel->nombre,
                    'total_bienes' => $plantel->bienes_count,
                ];
            }),
        ]);
    }

    public function show(Plantel $plantel): JsonResponse
    {
        $plantel->loadCount('bienes');

        return response()->json([
            'exito' => true,
            'mensaje' => 'Detalle del plantel obtenido correctamente.',
            'datos' => [
                'id' => $plantel->id,
                'nombre' => $plantel->nombre,
                'total_bienes' => $plantel->bienes_count,
            ],
        ]);
    }

    public function bienes(Plantel $plantel): JsonResponse
    {
        $bienes = $plantel->bienes()->with('entidad')->withCount('reparaciones')->get();

        return response()->json([
            'exito' => true,
            'mensaje' => 'Bienes del plantel obtenidos correctamente.',
            'datos' => [
                'plantel' => [
                    'id' => $plantel->id,
                    'nombre' => $plantel->nombre,
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
                        'entidad' => $bien->entidad->nombre,
                        'total_reparaciones' => $bien->reparaciones_count,
                    ];
                }),
            ],
        ]);
    }
}
