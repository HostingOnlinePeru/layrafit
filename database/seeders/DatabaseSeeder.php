<?php

namespace Database\Seeders;

use App\Models\ClassSchedule;
use App\Models\ClassType;
use App\Models\DanceClass;
use App\Models\Location;
use App\Models\MembershipPlan;
use App\Models\News;
use App\Models\PaymentMethod;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Locales iniciales
        $localCentro = Location::create([
            'name' => 'Local Centro',
            'address' => 'Av. Principal 123, Centro',
            'phone' => '987654321',
            'schedule' => 'Lun - SÃ¡b: 7:00 AM - 9:00 PM',
            'is_active' => true,
            'extra_info' => 'Sede principal con amplio salÃ³n de baile acondicionado.',
        ]);

        $localNorte = Location::create([
            'name' => 'Local Norte',
            'address' => 'Calle Las Flores 456, Urb. Norte',
            'phone' => '987654322',
            'schedule' => 'Lun - Vie: 8:00 AM - 8:00 PM',
            'is_active' => true,
            'extra_info' => 'Sede norte frente al parque comercial.',
        ]);

        $localSur = Location::create([
            'name' => 'Local Sur',
            'address' => 'Av. Los Olivos 789, Sur',
            'phone' => '987654323',
            'schedule' => 'Lun - SÃ¡b: 6:30 AM - 8:30 PM',
            'is_active' => true,
            'extra_info' => 'Sede sur climatizada y moderna.',
        ]);

        // 2. Administrador Principal
        User::create([
            'phone' => '999888777',
            'name' => 'Administrador',
            'last_name' => 'Layra Fit',
            'email' => 'admin@layrafit.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'must_change_password' => false,
            'is_active' => true,
        ]);

        // 3. Tipos de Clase / Disciplinas
        $zumbaType = ClassType::create([
            'name' => 'ZumbaÂ®',
            'slug' => 'zumba',
            'description' => 'Programa de acondicionamiento fÃ­sico que combina ritmos latinos e internacionales con pasos de baile fÃ¡ciles de seguir.',
            'color_code' => '#ff007f',
            'is_active' => true,
        ]);

        $strongType = ClassType::create([
            'name' => 'Strong Nation',
            'slug' => 'strong-nation',
            'description' => 'Entrenamiento de alta intensidad con mÃºsica sincronizada que combina el peso corporal, acondicionamiento muscular y ejercicios pliomÃ©tricos.',
            'color_code' => '#7928ca',
            'is_active' => true,
        ]);

        $cardioDanceType = ClassType::create([
            'name' => 'Cardio Dance',
            'slug' => 'cardio-dance',
            'description' => 'CoreografÃ­as divertidas diseÃ±adas para acelerar el metabolismo y quemar grasa bailando.',
            'color_code' => '#00dfd8',
            'is_active' => true,
        ]);

        $fullBodyType = ClassType::create([
            'name' => 'Full Body Dance',
            'slug' => 'full-body-dance',
            'description' => 'Entrenamiento integral de cuerpo completo al ritmo de la mejor mÃºsica.',
            'color_code' => '#10b981',
            'is_active' => true,
        ]);

        // 4. Horarios Semanales de Clases por Sede
        ClassSchedule::create([
            'location_id' => $localCentro->id,
            'class_type_id' => $zumbaType->id,
            'day_of_week' => 'lunes',
            'start_time' => '19:00:00',
            'end_time' => '20:00:00',
            'instructor_name' => 'Coach SofÃ­a',
            'max_capacity' => 25,
            'is_active' => true,
        ]);

        ClassSchedule::create([
            'location_id' => $localCentro->id,
            'class_type_id' => $strongType->id,
            'day_of_week' => 'miercoles',
            'start_time' => '08:00:00',
            'end_time' => '09:00:00',
            'instructor_name' => 'Coach Elena',
            'max_capacity' => 20,
            'is_active' => true,
        ]);

        ClassSchedule::create([
            'location_id' => $localCentro->id,
            'class_type_id' => $zumbaType->id,
            'day_of_week' => 'viernes',
            'start_time' => '19:00:00',
            'end_time' => '20:00:00',
            'instructor_name' => 'Coach SofÃ­a',
            'max_capacity' => 25,
            'is_active' => true,
        ]);

        ClassSchedule::create([
            'location_id' => $localNorte->id,
            'class_type_id' => $cardioDanceType->id,
            'day_of_week' => 'martes',
            'start_time' => '18:30:00',
            'end_time' => '19:30:00',
            'instructor_name' => 'Coach Valeria',
            'max_capacity' => 30,
            'is_active' => true,
        ]);

        // 5. Planes de MembresÃ­a Iniciales
        MembershipPlan::create([
            'name' => '3 Veces por Semana',
            'price' => 120.00,
            'type' => 'weekly_limit',
            'weekly_limit' => 3,
            'description' => 'Derecho a asistir hasta 3 clases por semana durante un mes.',
            'is_active' => true,
        ]);

        MembershipPlan::create([
            'name' => '5 Veces por Semana',
            'price' => 140.00,
            'type' => 'weekly_limit',
            'weekly_limit' => 5,
            'description' => 'Derecho a asistir hasta 5 clases por semana durante un mes.',
            'is_active' => true,
        ]);

        MembershipPlan::create([
            'name' => 'Clase Individual',
            'price' => 10.00,
            'type' => 'individual',
            'weekly_limit' => null,
            'description' => 'Acceso a 1 clase individual de Bail Fitness.',
            'is_active' => true,
        ]);

        // 6. MÃ©todos de Pago
        PaymentMethod::create([
            'name' => 'Yape',
            'slug' => 'yape',
            'account_number' => '999888777',
            'titular' => 'LAYRA FIT BAIL FITNESS',
            'is_active' => true,
            'extra_info' => 'Adjunta tu captura con el nÃºmero de operaciÃ³n visible.',
        ]);

        PaymentMethod::create([
            'name' => 'Banco de la NaciÃ³n',
            'slug' => 'banco_nacion',
            'account_number' => '04-012-345678',
            'cci' => '018-012-004012345678-90',
            'titular' => 'LAYRA FIT E.I.R.L.',
            'is_active' => true,
            'extra_info' => 'Transferencias interbancarias o depÃ³sitos en agente BN.',
        ]);

        PaymentMethod::create([
            'name' => 'Banco de CrÃ©dito del PerÃº (BCP)',
            'slug' => 'bcp',
            'account_number' => '193-98765432-0-11',
            'cci' => '002-193-0098765432011-14',
            'titular' => 'LAYRA FIT E.I.R.L.',
            'is_active' => true,
            'extra_info' => 'Transferencias por Yape/Banca MÃ³vil BCP.',
        ]);

        // 7. Alumna de Prueba Inicial
        $studentUser = User::create([
            'phone' => '912345678',
            'name' => 'MarÃ­a',
            'last_name' => 'GonzÃ¡lez',
            'email' => 'maria@example.com',
            'password' => Hash::make('123456'),
            'role' => 'student',
            'must_change_password' => false,
            'is_active' => true,
        ]);

        Student::create([
            'user_id' => $studentUser->id,
            'location_id' => $localCentro->id,
            'registration_date' => now(),
        ]);

        // 8. Clases de Prueba
        DanceClass::create([
            'name' => 'ZumbaÂ® & Bail Fitness',
            'description' => 'Ritmos latinos y movimientos aerÃ³bicos para quemar grasa con la mejor energÃ­a.',
            'duration_minutes' => 60,
            'level' => 'Inicial - Intermedio',
            'location_id' => $localCentro->id,
            'is_active' => true,
        ]);

        DanceClass::create([
            'name' => 'Strong Nation',
            'description' => 'CoreografÃ­as de alta energÃ­a e intensidad muscular.',
            'duration_minutes' => 60,
            'level' => 'Intermedio - Avanzado',
            'location_id' => $localCentro->id,
            'is_active' => true,
        ]);

        // 9. Novedad de Ejemplo
        News::create([
            'title' => 'Â¡Nuevas clases de ZumbaÂ® y Strong Nation!',
            'description' => 'Consulta el nuevo horario semanal de tu sede y reserva tus dÃ­as de entrenamiento.',
            'location_id' => null,
            'is_published' => true,
            'published_at' => now(),
        ]);
    }
}
