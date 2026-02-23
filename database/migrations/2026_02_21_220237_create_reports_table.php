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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            
            // Relaciones
            $table->foreignId('emergency_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
    
            // Datos del reportante
            $table->string('reporter_name')->nullable(); 
            $table->string('phone');
    
            // Información de la emergencia
            $table->text('description');
            
            // Ubicación GPS (Uso decimales de alta precisión)
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            
            // Estado del reporte
            $table->enum('status', ['pending', 'responding', 'resolved'])->default('pending');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
