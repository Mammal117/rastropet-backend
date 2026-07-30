<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        // Si no se especifica un rol, asignamos por defecto el rol de usuario normal (ej. 'user' o 'cliente')
        $roleName = $request->role ?? 'user'; 
        $role = Role::where('name', $roleName)->first();

        // Por seguridad, si el rol no existe, buscamos el primer rol disponible o creamos una alternativa
        if (!$role) {
            $role = Role::first(); 
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone ?? null, // Por si el teléfono es opcional en el registro
            'password' => Hash::make($request->password),
            'role_id' => $role ? $role->id : 2, // ID por defecto en caso de emergencia
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user' => $user->load('role'),
            'token' => $token,
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Credenciales incorrectas.',
            ], 401);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user' => $user->load('role'),
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Sesión cerrada correctamente.']);
    }

    public function me(Request $request)
    {
        return response()->json($request->user()->load('role'));
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => ['required', 'email']]);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => 'Enlace de recuperación enviado a tu correo.'])
            : response()->json(['message' => 'No se pudo enviar el enlace.'], 422);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => 'Contraseña actualizada correctamente.'])
            : response()->json(['message' => 'El token es inválido o expiró.'], 422);
    }

    /**
     * Paso 1 del login con Google: manda al usuario a la pantalla de
     * consentimiento de Google. Es una ruta "web" normal (no /api), porque
     * es el navegador el que navega hacia allá, no una petición AJAX.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Paso 2: Google regresa aquí después de que el usuario acepta.
     * Como esto no es una petición AJAX del frontend (Google hace un
     * redirect normal de navegador), no podemos "devolver JSON con el
     * token" como en login(): en vez de eso, generamos el token de Sanctum
     * igual que en un login normal, y mandamos al navegador de vuelta al
     * frontend con el token pegado en la URL. El frontend (ruta
     * /auth/google/callback) lo recoge de ahí y lo guarda como si hubiera
     * hecho login con correo y contraseña.
     */
    public function handleGoogleCallback()
    {
        $driver = Socialite::driver('google')->stateless();

        // En algunos entornos locales de Windows falla la verificación SSL
        // contra la API de Google por no tener el certificado CA configurado.
        // Esto se relaja solo fuera de producción; en el VPS real no aplica.
        if (! app()->environment('production')) {
            $driver->setHttpClient(new \GuzzleHttp\Client(['verify' => false]));
        }

        $googleUser = $driver->user();

        // Si el correo ya existe, respetamos la cuenta tal cual está (no le
        // tocamos el rol ni la contraseña); si es nueva, entra con el mismo
        // rol por defecto que usa el registro normal ("dueño").
        $user = User::firstOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name' => $googleUser->getName() ?: 'Usuario de Google',
                'password' => Hash::make(Str::random(32)),
                'role_id' => Role::where('name', 'dueño')->first()?->id,
            ]
        );

        $token = $user->createToken('api-token')->plainTextToken;

        $frontendUrl = rtrim(env('FRONTEND_URL', 'http://localhost:5173'), '/');

        return redirect("{$frontendUrl}/auth/google/callback?token={$token}");
    }
}