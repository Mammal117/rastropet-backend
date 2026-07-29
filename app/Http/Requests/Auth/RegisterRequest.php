<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)->mixedCase()->numbers()->symbols(),
            ],
            'role' => ['required', 'in:dueño,voluntario'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Este correo ya está registrado.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ];
    }
}