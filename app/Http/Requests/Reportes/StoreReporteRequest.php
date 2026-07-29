<?php

namespace App\Http\Requests\Reportes;

use Illuminate\Foundation\Http\FormRequest;

class StoreReporteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'zona_id' => ['required', 'exists:zonas,id'],
            'mascota' => ['required', 'string', 'max:255'],
            'especie' => ['required', 'in:Perro,Gato,Ave,Otro'],
            'estado' => ['nullable', 'in:Perdido,Encontrado'],
            'fecha_perdida' => ['required', 'date'],
            'lat' => ['nullable', 'numeric'],
            'lng' => ['nullable', 'numeric'],
        ];
    }
}