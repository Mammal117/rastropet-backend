<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'email', 'unique:users,email,' . $this->route('user')->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'role_id' => ['sometimes', 'exists:roles,id'],
        ];
    }
}