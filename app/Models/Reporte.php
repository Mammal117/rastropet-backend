<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reporte extends Model
{
    protected $fillable = [
        'user_id', 'zona_id', 'numero_reporte', 'mascota',
        'especie', 'estado', 'fecha_perdida', 'lat', 'lng',
        'telefono_contacto', 'nombre_dueno', 'email_contacto',
    ];

    public function dueno(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function zona(): BelongsTo
    {
        return $this->belongsTo(Zona::class);
    }

    public function avistamientos(): HasMany
    {
        return $this->hasMany(Avistamiento::class);
    }

    // Relación N:M real: los voluntarios que han avistado este reporte,
    // usando la tabla pivote "avistamientos" (con columnas extra: comentario, lat, lng, fecha)
    public function voluntarios(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'avistamientos', 'reporte_id', 'user_id')
            ->withPivot(['comentario', 'lat', 'lng', 'fecha'])
            ->withTimestamps();
    }

    public function notificaciones(): HasMany
    {
        return $this->hasMany(Notificacion::class);
    }
}