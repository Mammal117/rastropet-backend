<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

// Login con Google (OAuth vía Socialite). Son rutas "web" porque son
// redirects de navegador, no peticiones JSON — por eso no viven en api.php.
// La lógica real está en AuthController::redirectToGoogle() y
// AuthController::handleGoogleCallback().
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);
