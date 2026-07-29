<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificacionResource;
use App\Models\Notificacion;

class NotificacionController extends Controller
{
    public function index()
    {
        return NotificacionResource::collection(
            Notificacion::with('reporte')->latest()->paginate(15)
        );
    }
}

