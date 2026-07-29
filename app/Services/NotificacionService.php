<?php

namespace App\Services;

use App\Mail\ReporteRegistradoMail;
use App\Models\Notificacion;
use App\Models\Reporte;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotificacionService
{
    public static function enviarConfirmacionReporte(Reporte $reporte): Notificacion
    {
        $mensaje = "Tu reporte {$reporte->numero_reporte} de {$reporte->mascota} fue registrado.";
        $estado = 'enviado';

        try {
            Mail::to($reporte->dueno->email)->send(new ReporteRegistradoMail($reporte));
        } catch (\Throwable $e) {
            Log::error('Error enviando correo de reporte: ' . $e->getMessage());
            $estado = 'fallido';
        }

        return Notificacion::create([
            'reporte_id' => $reporte->id,
            'tipo' => 'email',
            'destinatario' => $reporte->dueno->email,
            'estado' => $estado,
            'mensaje' => $mensaje,
        ]);
    }
}