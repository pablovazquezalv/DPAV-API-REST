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
        Schema::create('cartilla_vacunas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perro_id')->constrained('perros');
            $table->string('vacuna',50);
            $table->date('fecha_aplicacion');
            $table->date('fecha_proxima');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cartilla_vacunas');
    }
};
