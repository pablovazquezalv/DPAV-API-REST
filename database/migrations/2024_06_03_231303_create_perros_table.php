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
            $table->string('raza',50);
            $table->string('color',50);
            $table->string('edad',50);
            $table->string('sexo',50);
            $table->string('peso',50);
            $table->string('tamaño',50);
            $table->string('altura',50);
            $table->enum('estatus',['activo','inactivo']);
            $table->string('esterilizado',50);
            $table->date('fecha_nacimiento');
            $table->string('imagen');
            $table->string('chip',50);
            $table->enum('tipo',['cria','reproductor','venta']);
            $table->foreignId('id_raza')->constrained('razas');
            $table->foreignId(('user_id'))->constrained('users');
            $table->foreignId('padre_id')->constrained('perros');
            $table->foreignId('madre_id')->constrained('perros');
           
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
