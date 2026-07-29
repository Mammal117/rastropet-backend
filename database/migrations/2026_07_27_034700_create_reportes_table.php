<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reportes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('zona_id')->constrained('zonas')->cascadeOnDelete();
            $table->string('numero_reporte')->unique();
            $table->string('mascota');
            $table->enum('especie', ['Perro', 'Gato', 'Ave', 'Otro']);
            $table->enum('estado', ['Perdido', 'Encontrado'])->default('Perdido');
            $table->date('fecha_perdida');
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reportes');
    }
};