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
        // 1. Tipos de Clase / Disciplinas de Baile
        Schema::create('class_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Ej: Zumba®, Strong Nation, Cardio Dance
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('color_code')->default('#ff007f'); // Color para distintivo en calendario
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Horarios y Días de Clases por Sede
        Schema::create('class_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->constrained('locations')->onDelete('cascade');
            $table->foreignId('class_type_id')->constrained('class_types')->onDelete('cascade');
            $table->enum('day_of_week', ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo']);
            $table->time('start_time');
            $table->time('end_time');
            $table->string('instructor_name')->nullable();
            $table->integer('max_capacity')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Relacionar attendances con class_schedule_id opcional
        Schema::table('attendances', function (Blueprint $table) {
            $table->foreignId('class_schedule_id')->nullable()->after('dance_class_id')->constrained('class_schedules')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['class_schedule_id']);
            $table->dropColumn('class_schedule_id');
        });

        Schema::dropIfExists('class_schedules');
        Schema::dropIfExists('class_types');
    }
};
