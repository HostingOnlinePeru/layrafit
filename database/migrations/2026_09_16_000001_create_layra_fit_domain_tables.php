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
        // 1. Locales / Sedes
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('schedule')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('extra_info')->nullable();
            $table->timestamps();
        });

        // 2. Alumnos / Estudiantes
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('location_id')->constrained('locations')->onDelete('cascade');
            $table->date('registration_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 3. Planes de Membresía
        Schema::create('membership_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->enum('type', ['weekly_limit', 'individual'])->default('weekly_limit');
            $table->integer('weekly_limit')->nullable(); // Ej: 3 o 5 veces por semana
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 4. Membresías del Alumno
        Schema::create('memberships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('membership_plan_id')->constrained('membership_plans')->onDelete('cascade');
            $table->foreignId('location_id')->constrained('locations')->onDelete('cascade');
            $table->decimal('price', 10, 2);
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['active', 'pending_payment', 'expired', 'suspended', 'cancelled'])->default('active');
            $table->integer('weekly_class_limit')->default(3);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 5. Métodos de Pago
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Yape, Banco de la Nación, BCP
            $table->string('slug')->unique(); // yape, banco_nacion, bcp
            $table->string('account_number')->nullable();
            $table->string('titular')->nullable();
            $table->string('cci')->nullable();
            $table->string('qr_image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('extra_info')->nullable();
            $table->timestamps();
        });

        // 6. Pagos
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('membership_plan_id')->constrained('membership_plans')->onDelete('cascade');
            $table->foreignId('payment_method_id')->constrained('payment_methods')->onDelete('cascade');
            $table->foreignId('location_id')->constrained('locations')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('operation_number');
            $table->date('payment_date');
            $table->string('voucher_path')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->foreignId('validated_by_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('validated_at')->nullable();
            $table->timestamps();
        });

        // 7. Clases / Rutinas de Bail Fitness
        Schema::create('dance_classes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('video_url')->nullable();
            $table->integer('duration_minutes')->default(60);
            $table->string('level')->default('Inicial'); // Inicial, Intermedio, Advanced, Full Body
            $table->foreignId('location_id')->nullable()->constrained('locations')->onDelete('set null');
            $table->dateTime('scheduled_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 8. Registro de Asistencias (Solo Administrador)
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('location_id')->constrained('locations')->onDelete('cascade');
            $table->foreignId('dance_class_id')->nullable()->constrained('dance_classes')->onDelete('set null');
            $table->date('date');
            $table->time('time');
            $table->foreignId('registered_by_user_id')->constrained('users')->onDelete('cascade');
            $table->string('notes')->nullable();
            $table->timestamps();
        });

        // 9. Créditos por Clase Individual (S/ 10)
        Schema::create('individual_class_credits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('payment_id')->constrained('payments')->onDelete('cascade');
            $table->enum('status', ['available', 'used'])->default('available');
            $table->timestamp('used_at')->nullable();
            $table->foreignId('attendance_id')->nullable()->constrained('attendances')->onDelete('set null');
            $table->timestamps();
        });

        // 10. Novedades / Noticias
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('image')->nullable();
            $table->foreignId('location_id')->nullable()->constrained('locations')->onDelete('cascade');
            $table->boolean('is_published')->default(true);
            $table->timestamp('published_at')->useCurrent();
            $table->timestamps();
        });

        // 11. Sorteos
        Schema::create('raffles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('prize');
            $table->date('raffle_date')->nullable();
            $table->foreignId('location_id')->nullable()->constrained('locations')->onDelete('cascade');
            $table->enum('status', ['draft', 'active', 'completed'])->default('draft');
            $table->foreignId('winner_student_id')->nullable()->constrained('students')->onDelete('set null');
            $table->timestamps();
        });

        // 12. Elegibilidad para Sorteo (Pagos Consecutivos)
        Schema::create('raffle_eligibilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->integer('consecutive_months_count')->default(0);
            $table->boolean('is_eligible')->default(false);
            $table->timestamp('last_checked_at')->nullable();
            $table->timestamps();
        });

        // 13. Notificaciones
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('message');
            $table->string('type')->default('info');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        // 14. Logs de Auditoría de Acciones de Administrador
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('action');
            $table->text('description');
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('raffle_eligibilities');
        Schema::dropIfExists('raffles');
        Schema::dropIfExists('news');
        Schema::dropIfExists('individual_class_credits');
        Schema::dropIfExists('attendances');
        Schema::dropIfExists('dance_classes');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('payment_methods');
        Schema::dropIfExists('memberships');
        Schema::dropIfExists('membership_plans');
        Schema::dropIfExists('students');
        Schema::dropIfExists('locations');
    }
};
