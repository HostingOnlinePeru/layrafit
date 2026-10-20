<?php

use App\Http\Controllers\Admin\AttendanceController as AdminAttendanceController;
use App\Http\Controllers\Admin\ClassScheduleController as AdminClassScheduleController;
use App\Http\Controllers\Admin\ClassTypeController as AdminClassTypeController;
use App\Http\Controllers\Admin\DanceClassController as AdminDanceClassController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\LocationController as AdminLocationController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\PaymentMethodController as AdminPaymentMethodController;
use App\Http\Controllers\Admin\PaymentValidationController as AdminPaymentValidationController;
use App\Http\Controllers\Admin\RaffleController as AdminRaffleController;
use App\Http\Controllers\Admin\StudentController as AdminStudentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Student\CalendarController as StudentCalendarController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\PaymentController as StudentPaymentController;
use App\Http\Controllers\Student\ProfileController as StudentProfileController;
use App\Http\Controllers\Student\RoutineController as StudentRoutineController;
use App\Http\Controllers\Student\StoreController;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\MustChangePassword;
use Illuminate\Support\Facades\Route;

// RedirecciÃƒÂ³n inicial
Route::get('/', function () {
    return redirect()->route('login');
});

// Autenticación pública
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.perform');
Route::get('/registro', [AuthController::class, 'showRegister'])->name('register');
Route::post('/registro', [AuthController::class, 'register'])->name('register.perform');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Ingreso Maestro Super Admin FitClass
Route::get('/superadmin/login', [AuthController::class, 'showSuperAdminLogin'])->name('superadmin.login');

// Acceso personalizado por academia (Marca Blanca)
Route::get('/a/{slug}', [AuthController::class, 'showTenantLogin'])->name('tenant.login');
Route::get('/a/{slug}/registro', [AuthController::class, 'showTenantRegister'])->name('tenant.register');

use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;
use App\Http\Controllers\SuperAdmin\SaasPlanController as SuperAdminSaasPlanController;
use App\Http\Controllers\SuperAdmin\TenantController as SuperAdminTenantController;

// Cambio Obligatorio de Contraseña (Primer login)
Route::middleware(['auth'])->group(function () {
    Route::get('/cambiar-password', [AuthController::class, 'showChangePassword'])->name('password.change');
    Route::post('/cambiar-password', [AuthController::class, 'changePassword'])->name('password.update');
});

// Rutas de Super Admin (FitClass SaaS)
Route::middleware(['auth', MustChangePassword::class, CheckRole::class.':superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', SuperAdminDashboardController::class)->name('dashboard');
    Route::resource('tenants', SuperAdminTenantController::class);
    Route::post('tenants/{tenant}/toggle', [SuperAdminTenantController::class, 'toggleStatus'])->name('tenants.toggle');
    Route::post('tenants/{tenant}/renew', [SuperAdminTenantController::class, 'renew'])->name('tenants.renew');

    // Gestión de Membresías SaaS FitClass
    Route::resource('plans', SuperAdminSaasPlanController::class)->except(['show']);
    Route::post('plans/{plan}/toggle', [SuperAdminSaasPlanController::class, 'toggle'])->name('plans.toggle');
});

// Rutas de Administrador (Academia / Gimnasio)
Route::middleware(['auth', MustChangePassword::class, CheckRole::class.':admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', AdminDashboardController::class)->name('dashboard');

    // Locales / Sedes
    Route::resource('locations', AdminLocationController::class)->except(['destroy', 'show']);
    Route::post('locations/{location}/toggle', [AdminLocationController::class, 'toggle'])->name('locations.toggle');

    // Alumnos
    Route::resource('students', AdminStudentController::class);
    Route::post('students/{student}/toggle-status', [AdminStudentController::class, 'toggleStatus'])->name('students.toggle');

    // Pagos & ValidaciÃƒÂ³n
    Route::get('pagos', [AdminPaymentValidationController::class, 'index'])->name('payments.index');
    Route::post('pagos/{payment}/aprobar', [AdminPaymentValidationController::class, 'approve'])->name('payments.approve');
    Route::post('pagos/{payment}/rechazar', [AdminPaymentValidationController::class, 'reject'])->name('payments.reject');

    // Configuración Marca Blanca, Contacto y Métodos de Pago
    Route::get('configuracion/general', [SettingsController::class, 'edit'])->name('settings.general');
    Route::put('configuracion/general', [SettingsController::class, 'update'])->name('settings.general.update');
    Route::get('configuracion/metodos-pago', [AdminPaymentMethodController::class, 'index'])->name('settings.payment-methods');
    Route::put('configuracion/metodos-pago/{paymentMethod}', [AdminPaymentMethodController::class, 'update'])->name('settings.payment-methods.update');

    // MembresÃƒÂ­as lista (redirige a alumnos)
    Route::get('membresias', [AdminStudentController::class, 'index'])->name('memberships.index');

    // Asistencias
    Route::get('asistencia', [AdminAttendanceController::class, 'index'])->name('attendance.index');
    Route::post('asistencia', [AdminAttendanceController::class, 'store'])->name('attendance.store');

    // Disciplinas / Tipos de Clases (ZumbaÃ‚Â®, Strong Nation)
    Route::resource('class-types', AdminClassTypeController::class)->except(['destroy', 'show']);

    // ProgramaciÃƒÂ³n de Horarios Semanales
    Route::resource('class-schedules', AdminClassScheduleController::class)->only(['index', 'create', 'store', 'destroy']);

    // Rutinas y Clases de Baile
    Route::resource('dance-classes', AdminDanceClassController::class)->except(['destroy', 'show']);

    // Novedades
    Route::resource('news', AdminNewsController::class)->except(['destroy', 'show']);

    // Sorteos
    Route::resource('raffles', AdminRaffleController::class)->except(['destroy', 'show']);
    Route::post('raffles/{raffle}/sortear', [AdminRaffleController::class, 'draw'])->name('raffles.draw');
});

// Rutas de Alumno
Route::middleware(['auth', MustChangePassword::class, CheckRole::class.':student'])->prefix('alumno')->name('student.')->group(function () {
    Route::get('/dashboard', StudentDashboardController::class)->name('dashboard');
    Route::get('/rutinas', [StudentRoutineController::class, 'index'])->name('routines');
    Route::get('/calendario', [StudentCalendarController::class, '__invoke'])->name('calendar');
    Route::post('/calendario/programar', [StudentCalendarController::class, 'schedule'])->name('calendar.schedule');
    Route::get('/pagos', [StudentPaymentController::class, 'index'])->name('payments');
    Route::get('/tienda', [StoreController::class, 'index'])->name('store');
    Route::post('/pagos', [StudentPaymentController::class, 'store'])->name('payments.store');
    Route::view('/pagos/exito', 'student.payments.success')->name('payments.success');
    Route::get('/perfil', [StudentProfileController::class, 'show'])->name('profile');
    Route::put('/perfil', [StudentProfileController::class, 'update'])->name('profile.update');
});
