<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Elimina la columna `telefono` agregada por error en users.
     * La tabla `users` ya cuenta con la columna `phone`, que es la
     * que usa todo el código (User::$fillable, AuthController,
     * RegisterRequest, UpdateUserRequest, UserResource, frontend).
     * La columna `telefono` quedó duplicada y sin uso (siempre NULL).
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'telefono')) {
                $table->dropColumn('telefono');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('telefono')->nullable();
        });
    }
};
