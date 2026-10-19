<?php

use App\Models\Tenant;
use App\Models\TenantSubscription;
use App\Models\User;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Hash;

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

// 1. Crear Tenant inicial Layra Fit
$tenant = Tenant::firstOrCreate(
    ['slug' => 'layrafit'],
    [
        'name' => 'Layra Fit',
        'owner_name' => 'Marely Piscoya',
        'owner_email' => 'admin@layrafit.com',
        'owner_phone' => '999888777',
        'plan_period' => '1_year',
        'plan_price' => 50.00,
        'status' => 'active',
        'subscription_starts_at' => now(),
        'subscription_ends_at' => now()->addYear(),
        'notes' => 'Cuenta inicial Layra Fit',
    ]
);

// Registrar suscripción inicial
TenantSubscription::firstOrCreate(
    ['tenant_id' => $tenant->id, 'period' => '1_year'],
    [
        'amount' => 50.00,
        'currency' => 'USD',
        'payment_method' => 'Efectivo / Transferencia',
        'payment_reference' => 'INICIAL-LAYRA',
        'starts_at' => now(),
        'ends_at' => now()->addYear(),
        'status' => 'paid',
        'notes' => 'Primer año de suscripción FitClass',
    ]
);

// Asociar usuarios existentes a Layra Fit
User::whereNull('tenant_id')
    ->where('role', '!=', 'superadmin')
    ->update(['tenant_id' => $tenant->id]);

// 2. Crear Super Admin FitClass
$super = User::updateOrCreate(
    ['phone' => '000000000'],
    [
        'name' => 'Super Admin',
        'last_name' => 'FitClass',
        'email' => 'superadmin@fitclass.com',
        'password' => Hash::make('superadmin123'),
        'role' => 'superadmin',
        'must_change_password' => false,
        'is_active' => true,
        'tenant_id' => null,
    ]
);

echo 'SUCCESS: Super Admin creado con telefono: '.$super->phone." y clave: superadmin123\n";
echo "SUCCESS: Tenant 'Layra Fit' configurado con ID: ".$tenant->id."\n";
