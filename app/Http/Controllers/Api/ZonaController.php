<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Zonas\StoreZonaRequest;
use App\Http\Resources\ZonaResource;
use App\Models\Zona;

class ZonaController extends Controller
{
    public function index()
    {
        return ZonaResource::collection(Zona::all());
    }

    public function store(StoreZonaRequest $request)
    {
        $zona = Zona::create($request->validated());

        return new ZonaResource($zona);
    }

    public function update(StoreZonaRequest $request, Zona $zona)
    {
        $zona->update($request->validated());

        return new ZonaResource($zona);
    }

    public function destroy(Zona $zona)
    {
        $zona->delete();

        return response()->json(['message' => 'Zona eliminada correctamente.']);
    }
}