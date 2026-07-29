<?php

namespace App\Http\Requests\Avistamientos;

use Illuminate\Foundation\Http\FormRequest;

class StoreAvistamientoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'comentario' => ['required', 'string', 'max:500'],
            'lat' => ['required', 'numeric'],
            'lng' => ['required', 'numeric'],
        ];
    }
}