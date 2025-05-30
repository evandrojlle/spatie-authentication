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
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('social_name', 200)->nullable(false);
            $table->string('fantasy_name', 200)->nullable(false);
            $table->string('cnpj', 20)->unique()->nullable(false);
            $table->string('email', 200)->nullable(false);
            $table->string('phone', 12)->nullable(false);
            $table->tinyInteger('status')->nullable(false)->default(1); // 1 = Ativo, 0 = Inativo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
