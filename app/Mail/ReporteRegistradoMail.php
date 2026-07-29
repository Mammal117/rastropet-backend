<?php

namespace App\Mail;

use App\Models\Reporte;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReporteRegistradoMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Reporte $reporte) {}

    public function build()
    {
        return $this->subject('Reporte registrado - RastroPet #' . $this->reporte->numero_reporte)
            ->view('emails.reporte-registrado');
    }
}