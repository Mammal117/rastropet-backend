<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificacionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tipo' => $this->tipo,
            'destinatario' => $this->destinatario,
            'estado' => $this->estado,
            'mensaje' => $this->mensaje,
            'reporte' => [
                'id' => $this->reporte->id,
                'numero_reporte' => $this->reporte->numero_reporte,
                'mascota' => $this->reporte->mascota,
            ],
            'created_at' => $this->created_at,
        ];
    }
}