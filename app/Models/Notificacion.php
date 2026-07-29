<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notificacion extends Model
{
    protected $table = 'notificaciones';

    protected $fillable = ['reporte_id', 'tipo', 'destinatario', 'estado', 'mensaje'];

    public function reporte(): BelongsTo
    {
        return $this->belongsTo(Reporte::class);
    }
}