<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AvistamientoController;
use App\Http\Controllers\Api\NotificacionController;
use App\Http\Controllers\Api\ReporteController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ZonaController;
use Illuminate\Support\Facades\Route;

// --- Autenticación (públicas) ---
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/auth/reset-password', [AuthController::class, 'resetPassword']);

// --- Rutas protegidas: requieren token válido (Sanctum) ---
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);

   Route::get('/zonas', [ZonaController::class, 'index']);

Route::middleware('role:admin')->group(function () {
    Route::post('/zonas', [ZonaController::class, 'store']);
    Route::put('/zonas/{zona}', [ZonaController::class, 'update']);
    Route::delete('/zonas/{zona}', [ZonaController::class, 'destroy']);
});

    // Reportes: cualquier usuario autenticado puede ver/buscar
    Route::get('/reportes', [ReporteController::class, 'index']);
    Route::get('/reportes/{reporte}', [ReporteController::class, 'show']);

    // Solo dueños y admins pueden crear reportes
    Route::middleware('role:dueño,admin')->group(function () {
        Route::post('/reportes', [ReporteController::class, 'store']);
    });

    // Editar/eliminar: la Policy decide si es el dueño real o un admin
    Route::put('/reportes/{reporte}', [ReporteController::class, 'update']);
    Route::delete('/reportes/{reporte}', [ReporteController::class, 'destroy']);

    // Solo voluntarios y admins pueden registrar avistamientos
    Route::middleware('role:voluntario,admin')->group(function () {
        Route::post('/reportes/{reporte}/avistamientos', [AvistamientoController::class, 'store']);
    });

    Route::get('/reportes/{reporte}/avistamientos', [AvistamientoController::class, 'index']);

    // Solo admin puede gestionar usuarios y ver el historial de notificaciones
    Route::middleware('role:admin')->group(function () {
        Route::apiResource('users', UserController::class)->except(['store']);
        Route::get('/notificaciones', [NotificacionController::class, 'index']);
    });
});