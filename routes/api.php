<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BienController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EntidadController;
use App\Http\Controllers\PlantelController;
use App\Http\Controllers\ReparacionController;
use App\Http\Controllers\TecnicoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Autenticación
Route::post('/login', [AuthController::class, 'login']);

// Rutas públicas (catálogos)
Route::get('/planteles', [PlantelController::class, 'index']);
Route::get('/planteles/{plantel}', [PlantelController::class, 'show']);
Route::get('/planteles/{plantel}/bienes', [PlantelController::class, 'bienes']);

Route::get('/entidades', [EntidadController::class, 'index']);
Route::get('/entidades/{entidad}', [EntidadController::class, 'show']);
Route::get('/entidades/{entidad}/bienes', [EntidadController::class, 'bienes']);

Route::get('/tecnicos', [TecnicoController::class, 'index']);
Route::get('/tecnicos/{tecnico}', [TecnicoController::class, 'show']);
Route::get('/tecnicos/{tecnico}/historial', [TecnicoController::class, 'historial']);

Route::get('/clientes', [ClienteController::class, 'index']);
Route::get('/clientes/{cliente}', [ClienteController::class, 'show']);
Route::get('/clientes/{cliente}/historial', [ClienteController::class, 'historial']);

Route::get('/bienes', [BienController::class, 'index']);
Route::get('/bienes/{bien}', [BienController::class, 'show']);
Route::get('/bienes/{bien}/historial', [BienController::class, 'historial']);

Route::get('/reparaciones', [ReparacionController::class, 'index']);
Route::get('/reparaciones/{reparacion}', [ReparacionController::class, 'show']);

// Rutas protegidas (requieren autenticación)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/reparaciones', [ReparacionController::class, 'store']);
    Route::patch('/reparaciones/{reparacion}/estado', [ReparacionController::class, 'actualizarEstado']);
});
