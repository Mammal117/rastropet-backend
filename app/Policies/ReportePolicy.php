<?php

namespace App\Policies;

use App\Models\Reporte;
use App\Models\User;

class ReportePolicy
{
    public function update(User $user, Reporte $reporte): bool
    {
        return $user->isAdmin() || $user->id === $reporte->user_id;
    }

    public function delete(User $user, Reporte $reporte): bool
    {
        return $this->update($user, $reporte);
    }
}