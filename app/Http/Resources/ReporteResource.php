<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReporteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'numero_reporte' => $this->numero_reporte,
            'mascota' => $this->mascota,
            'especie' => $this->especie,
            'estado' => $this->estado,
            'fecha_perdida' => $this->fecha_perdida,
            'telefono_contacto' => $this->telefono_contacto,
            'nombre_dueno' => $this->nombre_dueno,
            'email_contacto' => $this->email_contacto,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'dueno' => new UserResource($this->whenLoaded('dueno')),
            'zona' => new ZonaResource($this->whenLoaded('zona')),
            'total_avistamientos' => $this->whenCounted('avistamientos'),
            'created_at' => $this->created_at,
        ];
    }
}