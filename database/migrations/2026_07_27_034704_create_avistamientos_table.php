<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('avistamientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reporte_id')->constrained('reportes')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // voluntario que avista
            $table->text('comentario')->nullable();
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();
            $table->dateTime('fecha');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avistamientos');
    }
};