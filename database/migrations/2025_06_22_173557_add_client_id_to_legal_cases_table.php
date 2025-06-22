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
            // Verificar si la columna ya existe para evitar errores
            if (!Schema::hasColumn('legal_cases', 'client_id')) {
                // Agregar la columna client_id como clave foránea
                $table->foreignId('client_id')
                      ->nullable()
                      ->after('id')
                      ->constrained('clients')
                      ->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('legal_cases', function (Blueprint $table) {
            // Eliminar la clave foránea y la columna
            $table->dropForeign(['client_id']);
            $table->dropColumn('client_id');
        });
    }
};
