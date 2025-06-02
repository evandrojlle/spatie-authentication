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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('status'); // Remove the old status column
            $table->enum('status', ['ativo', 'pendente', 'cancelado', 'inativo', 'excluído'])->nullable(false)->default('ativo')->after('phone'); // Add the new status column with enum values
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('status'); // Remove the old status column
            $table->tinyInteger('status')->nullable(false)->default(1); // 1 = Ativo, 0 = Inativo
        });
    }
};
