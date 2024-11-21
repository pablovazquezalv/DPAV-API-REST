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
        Schema::table('perros_usuarios', function (Blueprint $table) {
            // Eliminar la clave foránea existente
            $table->dropForeign(['perro_id']);

            // Volver a agregar la clave foránea con ON DELETE CASCADE
            $table->foreign('perro_id')
                ->references('id')->on('perros')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perros_usuarios', function (Blueprint $table) {
            // Eliminar la clave foránea con ON DELETE CASCADE
            $table->dropForeign(['perro_id']);

            // Volver a agregar la clave foránea sin ON DELETE CASCADE
            $table->foreign('perro_id')
                ->references('id')->on('perros');
        });
    }
};
