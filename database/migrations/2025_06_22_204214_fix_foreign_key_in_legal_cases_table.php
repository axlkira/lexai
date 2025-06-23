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
        Schema::table('legal_cases', function (Blueprint $table) {
            // 1. Eliminar la clave foránea incorrecta (el nombre se infiere del error)
            $table->dropForeign('legal_cases_client_id_foreign');

            // 2. Añadir la clave foránea correcta que apunta a la tabla 'clients'
            $table->foreign('client_id')
                  ->references('id')
                  ->on('clients')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('legal_cases', function (Blueprint $table) {
            // Revertir los cambios para que la migración sea reversible
            // 1. Eliminar la clave foránea que acabamos de añadir
            $table->dropForeign(['client_id']);

            // 2. Volver a añadir la clave foránea incorrecta original
            $table->foreign('client_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }
};
