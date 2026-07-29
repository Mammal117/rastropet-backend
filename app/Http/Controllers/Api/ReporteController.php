<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reportes\StoreReporteRequest;
use App\Http\Requests\Reportes\UpdateReporteRequest;
use App\Http\Resources\ReporteResource;
use App\Models\Reporte;
use App\Services\NotificacionService;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 10);

        $query = Reporte::with(['dueno.role', 'zona'])
            ->withCount('avistamientos');

        if ($search = $request->query('search')) {
            $query->where('mascota', 'like', "%{$search}%");
        }

        if ($especie = $request->query('especie')) {
            $query->where('especie', $especie);
        }

        if ($estado = $request->query('estado')) {
            $query->where('estado', $estado);
        }

        if ($zonaId = $request->query('zona_id')) {
            $query->where('zona_id', $zonaId);
        }

        $reportes = $query->latest()->paginate($perPage);

        return ReporteResource::collection($reportes);
    }

    public function store(StoreReporteRequest $request)
    {
        $reporte = Reporte::create([
            ...$request->validated(),
            'user_id' => $request->user()->id,
            'numero_reporte' => 'RP-' . now()->year . '-' . random_int(1000, 9999),
            'estado' => $request->input('estado', 'Perdido'),
        ]);

        $reporte->load(['dueno', 'zona']);

        NotificacionService::enviarConfirmacionReporte($reporte);

        return new ReporteResource($reporte);
    }

    public function show(Reporte $reporte)
    {
        return new ReporteResource(
            $reporte->load(['dueno.role', 'zona', 'avistamientos.voluntario'])
        );
    }

    public function update(UpdateReporteRequest $request, Reporte $reporte)
    {
        $this->authorize('update', $reporte);

        $reporte->update($request->validated());

        return new ReporteResource($reporte->load(['dueno', 'zona']));
    }

    public function destroy(Request $request, Reporte $reporte)
    {
        $this->authorize('delete', $reporte);

        $reporte->delete();

        return response()->json(['message' => 'Reporte eliminado correctamente.']);
    }
}