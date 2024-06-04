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
        Schema::create('ciclos_reproductivos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perro_id')->constrained('perros');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->enum('estado', ['En curso', 'Finalizado']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ciclos_reproductivos');
    }
};
