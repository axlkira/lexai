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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->enum('document_type', [
                'registro_civil',
                'tarjeta_identidad',
                'cedula_ciudadania',
                'cedula_extranjeria',
                'pasaporte',
                'pep',
                'ppt'
            ])->nullable()->comment('Tipos de documentos de identidad en Colombia');
            $table->string('document_number')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
