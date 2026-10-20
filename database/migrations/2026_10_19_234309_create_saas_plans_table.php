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
        Schema::create('saas_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Ej: Plan Semestral, Plan Anual
            $table->string('slug')->unique(); // 6_months, 1_year
            $table->integer('duration_months')->default(6); // 6, 12, etc.
            $table->decimal('price', 8, 2); // 30.00, 50.00
            $table->string('currency', 5)->default('USD');
            $table->text('description')->nullable();
            $table->string('badge')->nullable(); // Ej: "Más Popular", "Mejor Ahorro"
            $table->text('features')->nullable(); // Beneficios separados por línea
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saas_plans');
    }
};
