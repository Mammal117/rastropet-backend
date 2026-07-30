<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Avistamientos\StoreAvistamientoRequest;
use App\Models\Reporte;
use App\Services\NotificacionService;
use Illuminate\Http\Request;
use App\Http\Resources\AvistamientoResource;
use Illuminate\Support\Facades\Http;

class AvistamientoController extends Controller
{
    public function store(StoreAvistamientoRequest $request, Reporte $reporte)
    {
        $avistamiento = $reporte->avistamientos()->create([
            'user_id' => $request->user()->id,
            'comentario' => $request->comentario,
            'lat' => $request->lat,
            'lng' => $request->lng,
            'fecha' => now(),
        ]);

        $reporte->load('dueno');
        
        // REGISTRO 1: Verificamos qué datos trae el dueño del reporte
        \Log::info('DEBUG DUEÑO: ' . json_encode($reporte->dueno));

        // Notificación interna existente del sistema si la usas
        NotificacionService::enviarAvisoAvistamiento($reporte, $avistamiento);

        // Envío automático del bot de WhatsApp mediante Twilio
        if ($reporte->dueno && $reporte->dueno->telefono) {
            $telefonoLimpio = preg_replace('/\D/', '', $reporte->dueno->telefono);
            $telefonoDestino = 'whatsapp:+' . $telefonoLimpio;
            
            // REGISTRO 2: Confirmamos a qué número exacto se intenta enviar
            \Log::info('DEBUG TWILIO: Intentando enviar WhatsApp a ' . $telefonoDestino);
            
            try {
                $response = Http::withBasicAuth(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'))->asForm()->post(
                    'https://api.twilio.com/2010-04-01/Accounts/' . env('TWILIO_SID') . '/Messages.json', [
                        'From' => 'whatsapp:+14155238886', // Número del Sandbox de Twilio
                        'To' => $telefonoDestino,
                        'Body' => "🤖 *RastroPet - Bot*\n\n¡Hola! Han avistado a tu mascota *{$reporte->mascota}*.\n📝 Detalle: {$request->comentario}",
                    ]
                );

                // REGISTRO 3: Estado de la respuesta de la API de Twilio
                \Log::info('DEBUG TWILIO: Status HTTP ' . $response->status());

                if ($response->failed()) {
                    \Log::error('Error Twilio Body: ' . $response->body());
                } else {
                    \Log::info('DEBUG TWILIO: ¡Mensaje aceptado por la API de Twilio exitosamente!');
                }
            } catch (\Exception $e) {
                \Log::error('Excepción Twilio: ' . $e->getMessage());
            }
        } else {
            \Log::warning('DEBUG TWILIO: El reporte ID ' . $reporte->id . ' no tiene un dueño asignado o carece de teléfono.');
        }

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