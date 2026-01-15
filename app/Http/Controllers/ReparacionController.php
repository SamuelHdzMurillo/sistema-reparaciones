<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReparacionRequest;
use App\Models\Bien;
use App\Models\Cliente;
use App\Models\Reparacion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReparacionController extends Controller
{
    public function index(): JsonResponse
    {
        $reparaciones = Reparacion::with([
            'bien.plantel',
            'bien.entidad',
            'cliente',
            'tecnico:id,name,numero,email'
        ])->orderBy('created_at', 'desc')->get();

        return response()->json([
            'exito' => true,
            'mensaje' => 'Lista de reparaciones obtenida correctamente.',
            'total' => $reparaciones->count(),
            'datos' => $reparaciones,
        ]);
    }

    public function show(Reparacion $reparacion): JsonResponse
    {
        $reparacion->load([
            'bien.plantel',
            'bien.entidad',
            'cliente',
            'tecnico:id,name,numero,email'
        ]);

        return response()->json([
            'exito' => true,
            'mensaje' => 'Detalle de reparación obtenido correctamente.',
            'datos' => [
                'id' => $reparacion->id,
                'estado' => $reparacion->estado,
                'falla_reportada' => $reparacion->falla_reportada,
                'accesorios_incluidos' => $reparacion->accesorios_incluidos,
                'fecha_recepcion' => $reparacion->created_at->format('Y-m-d H:i:s'),
                'ultima_actualizacion' => $reparacion->updated_at->format('Y-m-d H:i:s'),
                'bien' => [
                    'id' => $reparacion->bien->id,
                    'numero_inventario' => $reparacion->bien->numero_inventario,
                    'tipo_bien' => $reparacion->bien->tipo_bien,
                    'marca' => $reparacion->bien->marca,
                    'modelo' => $reparacion->bien->modelo,
                    'numero_serie' => $reparacion->bien->numero_serie,
                    'especificaciones' => $reparacion->bien->especificaciones,
                    'plantel' => $reparacion->bien->plantel->nombre,
                    'entidad' => $reparacion->bien->entidad->nombre,
                ],
                'cliente' => [
                    'id' => $reparacion->cliente->id,
                    'nombre_completo' => $reparacion->cliente->nombre_completo,
                    'telefono' => $reparacion->cliente->telefono,
                ],
                'tecnico' => [
                    'id' => $reparacion->tecnico->id,
                    'nombre' => $reparacion->tecnico->name,
                    'numero' => $reparacion->tecnico->numero,
                    'email' => $reparacion->tecnico->email,
                ],
            ],
        ]);
    }

    public function actualizarEstado(Request $request, Reparacion $reparacion): JsonResponse
    {
        $request->validate([
            'estado' => ['required', 'in:recibido,proceso,listo,entregado'],
        ]);

        $estadoAnterior = $reparacion->estado;
        $reparacion->update(['estado' => $request->estado]);

        return response()->json([
            'exito' => true,
            'mensaje' => 'Estado actualizado correctamente.',
            'datos' => [
                'id' => $reparacion->id,
                'estado_anterior' => $estadoAnterior,
                'estado_nuevo' => $reparacion->estado,
                'actualizado_en' => $reparacion->updated_at->format('Y-m-d H:i:s'),
            ],
        ]);
    }

    public function store(StoreReparacionRequest $request): JsonResponse
    {
        $datosValidados = $request->validated();

        $reparacion = DB::transaction(function () use ($datosValidados) {
            $cliente = Cliente::firstOrCreate(
                ['nombre_completo' => $datosValidados['nombre_cliente']],
                ['telefono' => $datosValidados['telefono_cliente'] ?? null]
            );

            $bien = Bien::firstOrCreate(
                ['numero_inventario' => $datosValidados['numero_inventario']],
                [
                    'tipo_bien' => $datosValidados['tipo_bien'],
                    'marca' => $datosValidados['marca'],
                    'modelo' => $datosValidados['modelo'] ?? '',
                    'numero_serie' => $datosValidados['numero_serie'] ?? null,
                    'especificaciones' => $datosValidados['especificaciones'] ?? null,
                    'plantel_id' => $datosValidados['plantel_id'],
                    'entidad_id' => $datosValidados['entidad_id'],
                ]
            );

            return Reparacion::create([
                'bien_id' => $bien->id,
                'cliente_id' => $cliente->id,
                'tecnico_id' => auth()->id(),
                'falla_reportada' => $datosValidados['falla_reportada'],
                'accesorios_incluidos' => $datosValidados['accesorios_incluidos'] ?? null,
                'estado' => 'recibido',
            ]);
        });

        $reparacion->load(['bien.plantel', 'bien.entidad', 'cliente', 'tecnico']);

        return response()->json([
            'exito' => true,
            'mensaje' => 'Reparación registrada exitosamente.',
            'datos' => $reparacion,
        ], 201);
    }
}
