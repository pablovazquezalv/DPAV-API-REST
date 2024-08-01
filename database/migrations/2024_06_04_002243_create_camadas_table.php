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
        Schema::create('camadas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cruce_id')->constrained('cruces');
            $table->date('fecha');
            $table->integer('numero_machos')->nullable();
            $table->integer('numero_hembras')->nullable();
            $table->integer('numero_total')->nullable();
            //variables de control
            $table->integer('hijos_registrados')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('camadas');
    }
};
