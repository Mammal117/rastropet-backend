<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        // El acceso general a esta ruta ya lo filtra el middleware
        // 'role:dueño,admin' en routes/api.php. Aquí solo falta la regla
        // fina de "qué rol le está permitido asignar a quién".
        return true;
    }

    public function rules(): array
    {
        // Un dueño solo puede invitar voluntarios (por ejemplo, para que le
        // ayuden a buscar a su mascota). Solo un admin puede crear otro admin.
        $rolesPermitidos = $this->user()?->isAdmin()
            ? ['voluntario', 'admin']
            : ['voluntario'];

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)->mixedCase()->numbers()->symbols(),
            ],
            'role' => ['required', Rule::in($rolesPermitidos)],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Este correo ya está registrado.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'role.in' => 'No tienes permiso para asignar ese rol.',
        ];
    }
}
