<?php

use App\Http\Controllers\Api\ApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - AppSalon
|--------------------------------------------------------------------------
|
| Endpoints documentados:
| GET    /api/servicios              - Listar servicios activos
| GET    /api/citas/usuario/{id}     - Citas de un usuario (auth)
| POST   /api/citas                  - Crear nueva cita (auth)
| PUT    /api/citas/{id}             - Actualizar cita (auth)
|
*/

// Público
Route::get('/servicios', [ApiController::class, 'servicios']);

// Autenticado (usando Sanctum o session)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/citas/usuario/{id}', [ApiController::class, 'citasUsuario']);
    Route::post('/citas', [ApiController::class, 'crearCita']);
    Route::put('/citas/{id}', [ApiController::class, 'actualizarCita']);
});
