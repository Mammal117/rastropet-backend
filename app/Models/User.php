<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'phone',
        'image',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    // Reportes que este usuario hizo como dueño de la mascota
    public function reportes(): HasMany
    {
        return $this->hasMany(Reporte::class);
    }

    // Avistamientos que este usuario hizo como voluntario
    public function avistamientos(): HasMany
    {
        return $this->hasMany(Avistamiento::class);
    }

    // Relación N:M inversa: reportes que este usuario ha avistado (como voluntario)
    public function reportesAvistados(): BelongsToMany
    {
        return $this->belongsToMany(Reporte::class, 'avistamientos', 'user_id', 'reporte_id')
            ->withPivot(['comentario', 'lat', 'lng', 'fecha'])
            ->withTimestamps();
    }

    // Helpers para verificar el rol sin repetir consultas por todo el código
    public function isAdmin(): bool
    {
        return $this->role?->name === 'admin';
    }

    public function isDueno(): bool
    {
        return $this->role?->name === 'dueño';
    }

    public function isVoluntario(): bool
    {
        return $this->role?->name === 'voluntario';
    }
}