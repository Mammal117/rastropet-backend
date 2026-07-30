<?php

namespace App\Services;

use App\Mail\ReporteEncontradoMail;
use App\Mail\ReporteRegistradoMail;
use App\Models\Avistamiento;
use App\Models\Notificacion;
use App\Models\Reporte;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Twilio\Rest\Client;

class NotificacionService
{
    /**
     * Se dispara al crear un reporte: correo + SMS + WhatsApp de confirmación
     * a los datos de contacto que se llenaron en el formulario (no a la cuenta que reportó).
     */
    public static function enviarConfirmacionReporte(Reporte $reporte): void
    {
        $mensaje = "Tu reporte {$reporte->numero_reporte} de {$reporte->mascota} fue registrado en RastroPet.";

        self::enviarEmail($reporte, $mensaje);
        self::enviarSms($reporte, $mensaje);
        self::enviarWhatsapp($reporte, $mensaje);
    }

    /**
     * Se dispara cuando un voluntario avista una mascota: avisa al dueño por los 3 canales.
     */
    public static function enviarAvisoAvistamiento(Reporte $reporte, Avistamiento $avistamiento): void
    {
        $mensaje = "¡Buenas noticias! Alguien reportó haber visto a {$reporte->mascota} "
            . "(reporte {$reporte->numero_reporte}). Entra a RastroPet para ver el detalle.";

        self::enviarEmail($reporte, $mensaje);
        self::enviarSms($reporte, $mensaje);
        self::enviarWhatsapp($reporte, $mensaje);
    }

    /**
     * Se dispara cuando un reporte pasa de "Perdido" a "Encontrado": avisa
     * por correo a los datos de contacto que se llenaron en el reporte.
     */
    public static function enviarAvisoEncontrado(Reporte $reporte): void
    {
        $mensaje = "¡Buenas noticias! Tu reporte {$reporte->numero_reporte} de {$reporte->mascota} "
            . "fue marcado como Encontrado en RastroPet.";

        self::enviarEmail($reporte, $mensaje, new ReporteEncontradoMail($reporte));
    }

    private static function enviarEmail(Reporte $reporte, string $mensaje, ?Mailable $mailable = null): ?Notificacion
    {
        $destinatario = $reporte->email_contacto;

        if (!$destinatario) {
            return null;
        }

        $mailable ??= new ReporteRegistradoMail($reporte);
        $estado = 'enviado';

        try {
            Mail::to($destinatario)->send($mailable);
        } catch (\Throwable $e) {
            Log::error('Error enviando correo de reporte: ' . $e->getMessage());
            $estado = 'fallido';
        }

        return Notificacion::create([
            'reporte_id' => $reporte->id,
            'tipo' => 'email',
            'destinatario' => $destinatario,
            'estado' => $estado,
            'mensaje' => $mensaje,
        ]);
    }

    private static function enviarSms(Reporte $reporte, string $mensaje): ?Notificacion
    {
        if (!self::numeroEsValido($reporte->telefono_contacto)) {
            return null;
        }

        $numero = self::formatearNumero($reporte->telefono_contacto);
        $estado = 'enviado';

        try {
            self::twilioClient()->messages->create($numero, [
                'from' => config('services.twilio.sms_from'),
                'body' => $mensaje,
            ]);
        } catch (\Throwable $e) {
            Log::error('Error enviando SMS: ' . $e->getMessage());
            $estado = 'fallido';
        }

        return Notificacion::create([
            'reporte_id' => $reporte->id,
            'tipo' => 'sms',
            'destinatario' => $numero,
            'estado' => $estado,
            'mensaje' => $mensaje,
        ]);
    }

    private static function enviarWhatsapp(Reporte $reporte, string $mensaje): ?Notificacion
    {
        if (!self::numeroEsValido($reporte->telefono_contacto)) {
            return null;
        }

        $numero = self::formatearNumeroWhatsapp($reporte->telefono_contacto);
        $estado = 'enviado';

        try {
            self::twilioClient()->messages->create('whatsapp:' . $numero, [
                'from' => 'whatsapp:' . config('services.twilio.whatsapp_from'),
                'body' => $mensaje,
            ]);
        } catch (\Throwable $e) {
            Log::error('Error enviando WhatsApp: ' . $e->getMessage());
            $estado = 'fallido';
        }

        return Notificacion::create([
            'reporte_id' => $reporte->id,
            'tipo' => 'whatsapp',
            'destinatario' => $numero,
            'estado' => $estado,
            'mensaje' => $mensaje,
        ]);
    }

    private static function twilioClient(): Client
    {
        return new Client(config('services.twilio.sid'), config('services.twilio.token'));
    }

    // Valida que el teléfono tenga una cantidad de dígitos razonable (10 a 15, estándar E.164)
    // antes de intentar mandar cualquier cosa. Si no es válido, no se llama a Twilio.
    private static function numeroEsValido(?string $telefono): bool
    {
        if (!$telefono) {
            return false;
        }

        $soloDigitos = preg_replace('/\D/', '', $telefono);

        return strlen($soloDigitos) >= 10 && strlen($soloDigitos) <= 15;
    }

    // Normaliza el teléfono a formato E.164 que exige Twilio para SMS.
    // Si ya viene con "+" se respeta; si no, se asume número mexicano (+52).
    private static function formatearNumero(string $telefono): string
    {
        $limpio = preg_replace('/[^0-9+]/', '', $telefono);

        if (str_starts_with($limpio, '+')) {
            return $limpio;
        }

        return '+52' . $limpio;
    }

    // WhatsApp en México requiere el "1" extra después del 52 (ej. +521XXXXXXXXXX),
    // aunque para SMS normal ese "1" ya no se use. Por eso es un formateo aparte.
    private static function formatearNumeroWhatsapp(string $telefono): string
    {
        $numero = self::formatearNumero($telefono);

        if (str_starts_with($numero, '+52') && !str_starts_with($numero, '+521')) {
            $numero = '+521' . substr($numero, 3);
        }

        return $numero;
    }
}