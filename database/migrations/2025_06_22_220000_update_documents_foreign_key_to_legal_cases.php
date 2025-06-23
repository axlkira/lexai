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
        Schema::table('documents', function (Blueprint $table) {
            // 1. Drop the incorrect foreign key constraint by its name
            $table->dropForeign('documents_case_id_foreign');

            // 2. Add the correct foreign key constraint
            $table->foreign('case_id')
                  ->references('id')
                  ->on('legal_cases') // Correct table name
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            // 1. Drop the correct foreign key constraint
            // Laravel's default naming convention is table_column_foreign
            $table->dropForeign('documents_case_id_foreign');

            // 2. Re-add the original, incorrect foreign key constraint for rollback
            $table->foreign('case_id', 'documents_case_id_foreign')
                  ->references('id')
                  ->on('cases') // Incorrect table name
                  ->onDelete('cascade');
        });
    }
};
