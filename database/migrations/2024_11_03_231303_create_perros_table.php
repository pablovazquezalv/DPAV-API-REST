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
        Schema::create('perros', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',50);
            $table->string('color',50);
            $table->integer('edad');
            $table->enum('sexo',['Macho','Hembra']);
            $table->string('peso',50);
            $table->boolean('estatus');
            $table->enum('tamaño',['Pequeño','Mediano','Grande']);
            $table->date('fecha_nacimiento');
            $table->foreignId('id_raza')->constrained('razas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perros');
    }
};
