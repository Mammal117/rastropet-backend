<?php

namespace App\Mail;

use App\Models\Reporte;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReporteEncontradoMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Reporte $reporte) {}

    public function build()
    {
        return $this->subject('¡Buenas noticias! ' . $this->reporte->mascota . ' fue encontrado - RastroPet #' . $this->reporte->numero_reporte)
            ->view('emails.reporte-encontrado');
    }
}
