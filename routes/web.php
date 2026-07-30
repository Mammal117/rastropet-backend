<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

// 1. Ruta para iniciar sesión con Google (Evita el error 404)
Route::get('/auth/google', function () {
    return Socialite::driver('google')->redirect();
});

// 2. Ruta a donde Google regresa después de autenticarse
Route::get('/auth/google/callback', function () {
    $driver = Socialite::driver('google');
    
    $googleUser = $driver->stateless()->setHttpClient(new \GuzzleHttp\Client([
        'verify' => false,
    ]))->user();

    $user = User::updateOrCreate([
        'email' => $googleUser->getEmail(),
    ], [
        'name' => $googleUser->getName(),
        'password' => Hash::make(rand(100000, 999999)),
        'role_id' => Role::where('name', 'user')->first()->id ?? 2,
    ]);

    Auth::login($user);

    return redirect('http://localhost:5173/dashboard');
});