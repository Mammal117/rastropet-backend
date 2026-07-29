<?php

namespace App\Http\Requests\Reportes;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReporteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'zona_id' => ['sometimes', 'exists:zonas,id'],
            'mascota' => ['sometimes', 'string', 'max:255'],
            'especie' => ['sometimes', 'in:Perro,Gato,Ave,Otro'],
            'estado' => ['sometimes', 'in:Perdido,Encontrado'],
            'fecha_perdida' => ['sometimes', 'date'],
            'lat' => ['nullable', 'numeric'],
            'lng' => ['nullable', 'numeric'],
        ];
    }
}