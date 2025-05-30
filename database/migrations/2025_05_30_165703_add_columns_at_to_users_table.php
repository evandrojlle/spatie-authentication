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
            $table->string('cpf', 15)->nullable(false)->after('name')->unique();
            $table->string('phone', 20)->nullable(false)->after('cpf');
            $table->tinyInteger('status')->nullable(false)->default(1); // 1 = Ativo, 0 = Inativo
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['cpf', 'phone', 'status']); // Remove as colunas adicionadas
        });
    }
};
