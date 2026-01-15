<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\JsonResponse;

class ClienteController extends Controller
{
    public function index(): JsonResponse
    {
        $clientes = Cliente::withCount('reparaciones')->get();

        return response()->json([
            'exito' => true,
            'mensaje' => 'Lista de clientes obtenida correctamente.',
            'total' => $clientes->count(),
            'datos' => $clientes->map(function ($cliente) {
                return [
                    'id' => $cliente->id,
                    'nombre_completo' => $cliente->nombre_completo,
                    'telefono' => $cliente->telefono,
                    'total_reparaciones' => $cliente->reparaciones_count,
                    'registrado_en' => $cliente->created_at->format('Y-m-d H:i:s'),
                ];
            }),
        ]);
    }

    public function show(Cliente $cliente): JsonResponse
    {
        $cliente->loadCount('reparaciones');

        return response()->json([
            'exito' => true,
            'mensaje' => 'Detalle del cliente obtenido correctamente.',
            'datos' => [
                'id' => $cliente->id,
                'nombre_completo' => $cliente->nombre_completo,
                'telefono' => $cliente->telefono,
                'total_reparaciones' => $cliente->reparaciones_count,
                'registrado_en' => $cliente->created_at->format('Y-m-d H:i:s'),
            ],
        ]);
    }

    public function historial(Cliente $cliente): JsonResponse
    {
        $historial = $cliente->reparaciones()
            ->with(['bien.plantel', 'bien.entidad', 'tecnico:id,name,numero,email'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'exito' => true,
            'mensaje' => 'Historial del cliente obtenido correctamente.',
            'datos' => [
                'cliente' => [
                    'id' => $cliente->id,
                    'nombre_completo' => $cliente->nombre_completo,
                    'telefono' => $cliente->telefono,
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
