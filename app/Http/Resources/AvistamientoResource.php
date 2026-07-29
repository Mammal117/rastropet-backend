<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AvistamientoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'comentario' => $this->comentario,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'fecha' => $this->fecha,
            'voluntario' => new UserResource($this->whenLoaded('voluntario')),
        ];
    }
}