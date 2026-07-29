<?php

namespace Database\Seeders;

use App\Models\Notificacion;
use App\Models\Reporte;
use Illuminate\Database\Seeder;

class NotificacionSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Reporte::all() as $reporte) {
            Notificacion::create([
                'reporte_id' => $reporte->id,
                'tipo' => 'email',
                'destinatario' => $reporte->dueno->email,
                'estado' => 'enviado',
                'mensaje' => "Tu reporte {$reporte->numero_reporte} de {$reporte->mascota} fue registrado exitosamente.",
            ]);
        }
    }
}