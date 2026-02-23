<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
        public function up(): void
    {
        Schema::create('emergency_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Ejemplo: Robo, Incendio
            $table->string('slug')->unique(); // Para URLs o lógica interna (robo, incendio)
            $table->string('color')->default('#ef4444'); // Color para el Dashboard (Hex)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emergency_types');
    }
};
