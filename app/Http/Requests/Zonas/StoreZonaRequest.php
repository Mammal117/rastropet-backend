<?php

namespace App\Http\Requests\Zonas;

use Illuminate\Foundation\Http\FormRequest;

class StoreZonaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255'],
            'lat' => ['required', 'numeric'],
            'lng' => ['required', 'numeric'],
        ];
    }
}