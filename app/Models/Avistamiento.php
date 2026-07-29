<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Avistamiento extends Model
{
    protected $fillable = ['reporte_id', 'user_id', 'comentario', 'lat', 'lng', 'fecha'];

    public function reporte(): BelongsTo
    {
        return $this->belongsTo(Reporte::class);
    }

    public function voluntario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}