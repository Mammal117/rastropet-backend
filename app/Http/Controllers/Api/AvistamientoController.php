<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Avistamientos\StoreAvistamientoRequest;
use App\Models\Reporte;
use Illuminate\Http\Request;
use App\Http\Resources\AvistamientoResource;

class AvistamientoController extends Controller
{
    // Un voluntario reporta que vio a la mascota de un reporte específico
    public function store(StoreAvistamientoRequest $request, Reporte $reporte)
    {
        $avistamiento = $reporte->avistamientos()->create([
            'user_id' => $request->user()->id,
            'comentario' => $request->comentario,
            'lat' => $request->lat,
            'lng' => $request->lng,
            'fecha' => now(),
        ]);

        return response()->json([
            'message' => 'Avistamiento registrado correctamente.',
            'avistamiento' => $avistamiento->load('voluntario'),
        ], 201);
    }

    // Lista todos los avistamientos de un reporte específico
   public function index(Reporte $reporte)
{
    return AvistamientoResource::collection(
        $reporte->avistamientos()->with('voluntario')->latest('fecha')->get()
    );
}
}