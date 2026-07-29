<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reporte_id')->constrained('reportes')->cascadeOnDelete();
            $table->enum('tipo', ['email', 'sms', 'whatsapp']);
            $table->string('destinatario'); // correo o número de teléfono
            $table->enum('estado', ['enviado', 'fallido'])->default('enviado');
            $table->text('mensaje')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
    }
};