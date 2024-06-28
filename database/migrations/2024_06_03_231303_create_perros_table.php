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
            $table->string('distintivo',50)->nullable();
            $table->enum('sexo',['M','F'])->nullable();
            $table->double('peso',50)->nullable();
            $table->string('tamaño',50)->nullable();
            $table->boolean('estatus')->default(1);
            $table->string('esterilizado',50)->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('imagen',500)->nullable();
            $table->string('chip',50)->nullable();
            $table->enum('tipo',['cria','reproductor','venta']);
            $table->foreignId('id_raza')->constrained('razas');
            $table->foreignId('padre_id')->nullable()->constrained('perros');
            $table->foreignId('madre_id')->nullable()->constrained('perros');
            $table->foreignId('user_id')->constrained('users');
           
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
