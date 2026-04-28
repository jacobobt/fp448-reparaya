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
        Schema::create('incidencias', function (Blueprint $table) {
            $table->id();
            $table->string('localizador', 20)->unique();
            $table->string('telefono_contacto', 20);
            $table->enum('franja_horaria', ['09:00-13:00', '16:00-20:00'])->default('09:00-13:00');
            $table->foreignId('cliente_id')->constrained('usuarios');
            $table->foreignId('tecnico_id')->nullable()->constrained('tecnicos');
            $table->foreignId('especialidad_id')->constrained('especialidades');
            $table->text('descripcion');
            $table->string('direccion', 255);
            $table->dateTime('fecha_servicio');
            $table->enum('tipo_urgencia', ['Estandar', 'Urgente'])->default('Estandar');
            $table->enum('estado', ['Pendiente', 'Asignada', 'Finalizada', 'Cancelada'])->default('Pendiente');
            $table->timestamp('created_at')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidencias');
    }
};
