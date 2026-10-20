<?php

use App\Models\SaasPlan;

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

SaasPlan::firstOrCreate(
    ['slug' => '6_months'],
    [
        'name' => 'Plan Semestral (6 Meses)',
        'duration_months' => 6,
        'price' => 30.00,
        'currency' => 'USD',
        'badge' => 'Flexible',
        'description' => 'Ideal para academias y gimnasios que inician su digitalización con FitClass.',
        'features' => "Acceso a panel administrativo completo\nRegistro y gestión de alumnos\nHorarios y control de asistencias\nMódulo de membresías y pagos\nSoporte técnico estándar",
        'is_active' => true,
        'sort_order' => 1,
    ]
);

SaasPlan::firstOrCreate(
    ['slug' => '1_year'],
    [
        'name' => 'Plan Anual (1 Año)',
        'duration_months' => 12,
        'price' => 50.00,
        'currency' => 'USD',
        'badge' => 'Más Popular (Ahorro 30%)',
        'description' => 'La mejor opción para academias consolidadas con soporte prioritario.',
        'features' => "Todo lo incluido en el plan semestral\n12 meses completos de servicio ininterrumpido\nActualizaciones automáticas del sistema\nSoporte prioritario 24/7\nMayor ahorro garantizado",
        'is_active' => true,
        'sort_order' => 2,
    ]
);

echo "SUCCESS: " . SaasPlan::count() . " planes SaaS creados correctamente.\n";
