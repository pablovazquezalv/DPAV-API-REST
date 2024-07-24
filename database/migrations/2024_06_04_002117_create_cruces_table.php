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
        Schema::create('cruces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perro_macho_id')->constrained('perros');
            $table->foreignId('perro_hembra_id')->constrained('perros');
            $table->date('fecha');
            $table->enum('estado',['pendiente','realizado','fallido']);
            
            $table->foreignId('cita_id')->nullable()->constrained('citas');
            $table->string('observaciones',100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cruces');
    }
};
