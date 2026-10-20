<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SaasPlan;
use App\Models\Tenant;
use App\Models\TenantSubscription;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TenantController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->get('search');
        $status = $request->get('status');

        $tenants = Tenant::withCount('users')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('owner_name', 'like', "%{$search}%")
                    ->orWhere('owner_phone', 'like', "%{$search}%")
                    ->orWhere('owner_email', 'like', "%{$search}%");
            })
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('superadmin.tenants.index', compact('tenants', 'search', 'status'));
    }

    public function create(): View
    {
        $plans = SaasPlan::where('is_active', true)->orderBy('sort_order')->get();

        return view('superadmin.tenants.create', compact('plans'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'owner_name' => ['required', 'string', 'max:150'],
            'owner_phone' => ['required', 'string', 'max:30', 'unique:users,phone'],
            'owner_email' => ['nullable', 'email', 'max:150'],
            'admin_password' => ['required', 'string', 'min:6'],
            'plan_id' => ['nullable', 'exists:saas_plans,id'],
            'plan_period' => ['nullable', 'string'],
            'payment_reference' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
        ], [
            'name.required' => 'El nombre de la academia / gimnasio es obligatorio.',
            'owner_name.required' => 'El nombre del administrador es obligatorio.',
            'owner_phone.required' => 'El teléfono del administrador es obligatorio.',
            'owner_phone.unique' => 'Este número de teléfono ya está registrado como usuario en el sistema.',
            'admin_password.required' => 'Ingresa la contraseña para el administrador.',
            'admin_password.min' => 'La contraseña debe tener mínimo 6 caracteres.',
        ]);

        $slugBase = Str::slug($validated['name']);
        $slug = $slugBase;
        $counter = 1;
        while (Tenant::where('slug', $slug)->exists()) {
            $slug = "{$slugBase}-{$counter}";
            $counter++;
        }

        $plan = null;
        if (! empty($validated['plan_id'])) {
            $plan = SaasPlan::find($validated['plan_id']);
        }

        if ($plan) {
            $period = $plan->slug;
            $price = (float) $plan->price;
            $durationMonths = $plan->duration_months;
            $planLabel = $plan->name;
        } else {
            $period = $validated['plan_period'] ?? '6_months';
            $durationMonths = $period === '1_year' ? 12 : 6;
            $price = $period === '1_year' ? 50.00 : 30.00;
            $planLabel = $period === '1_year' ? '1 Año ($50)' : '6 Meses ($30)';
        }

        $startsAt = now();
        $endsAt = now()->addMonths($durationMonths);

        // 1. Crear Tenant
        $tenant = Tenant::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'owner_name' => $validated['owner_name'],
            'owner_phone' => $validated['owner_phone'],
            'owner_email' => $validated['owner_email'],
            'plan_period' => $period,
            'plan_price' => $price,
            'status' => 'active',
            'subscription_starts_at' => $startsAt,
            'subscription_ends_at' => $endsAt,
            'notes' => $validated['notes'],
        ]);

        // 2. Crear Administrador para esta cuenta
        User::create([
            'tenant_id' => $tenant->id,
            'phone' => $validated['owner_phone'],
            'name' => $validated['owner_name'],
            'email' => $validated['owner_email'],
            'password' => Hash::make($validated['admin_password']),
            'role' => 'admin',
            'must_change_password' => false,
            'is_active' => true,
        ]);

        // 3. Registrar suscripción pagada
        TenantSubscription::create([
            'tenant_id' => $tenant->id,
            'period' => $period,
            'amount' => $price,
            'currency' => 'USD',
            'payment_method' => 'Registro Super Admin',
            'payment_reference' => $validated['payment_reference'] ?? 'MEMB-'.strtoupper(Str::random(6)),
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'status' => 'paid',
            'notes' => 'Membresía inicial: '.$planLabel,
        ]);

        return redirect()->route('superadmin.tenants.index')
            ->with('success', "¡Cuenta '{$tenant->name}' creada con éxito! El administrador ya puede iniciar sesión con el teléfono {$validated['owner_phone']}.");
    }

    public function show(Tenant $tenant): View
    {
        $tenant->load(['subscriptions' => fn ($q) => $q->latest(), 'users' => fn ($q) => $q->latest()]);
        $admins = $tenant->users->where('role', 'admin');
        $studentsCount = $tenant->users->where('role', 'student')->count();
        $plans = SaasPlan::where('is_active', true)->orderBy('sort_order')->get();

        return view('superadmin.tenants.show', compact('tenant', 'admins', 'studentsCount', 'plans'));
    }

    public function edit(Tenant $tenant): View
    {
        return view('superadmin.tenants.edit', compact('tenant'));
    }

    public function update(Request $request, Tenant $tenant): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'owner_name' => ['required', 'string', 'max:150'],
            'owner_phone' => ['required', 'string', 'max:30'],
            'owner_email' => ['nullable', 'email', 'max:150'],
            'status' => ['required', 'in:active,suspended,expired'],
            'plan_period' => ['required', 'in:6_months,1_year'],
            'notes' => ['nullable', 'string'],
        ]);

        $tenant->update($validated);

        return redirect()->route('superadmin.tenants.show', $tenant)
            ->with('success', 'Cuenta actualizada correctamente.');
    }

    public function toggleStatus(Tenant $tenant): RedirectResponse
    {
        $newStatus = $tenant->status === 'active' ? 'suspended' : 'active';
        $tenant->update(['status' => $newStatus]);

        // Suspender/Activar los usuarios vinculados a este tenant
        $tenant->users()->update(['is_active' => $newStatus === 'active']);

        $msg = $newStatus === 'active' ? 'Cuenta activada correctamente.' : 'Cuenta suspendida correctamente.';

        return back()->with('success', $msg);
    }

    public function renew(Request $request, Tenant $tenant): RedirectResponse
    {
        $validated = $request->validate([
            'plan_id' => ['nullable', 'exists:saas_plans,id'],
            'plan_period' => ['nullable', 'string'],
            'amount' => ['nullable', 'numeric'],
            'payment_method' => ['nullable', 'string'],
            'payment_reference' => ['nullable', 'string'],
        ]);

        $plan = null;
        if (! empty($validated['plan_id'])) {
            $plan = SaasPlan::find($validated['plan_id']);
        }

        if ($plan) {
            $period = $plan->slug;
            $durationMonths = $plan->duration_months;
            $price = ! empty($validated['amount']) ? (float) $validated['amount'] : (float) $plan->price;
            $planLabel = $plan->name;
        } else {
            $period = $validated['plan_period'] ?? '6_months';
            $durationMonths = $period === '1_year' ? 12 : 6;
            $defaultPrice = $period === '1_year' ? 50.00 : 30.00;
            $price = ! empty($validated['amount']) ? (float) $validated['amount'] : $defaultPrice;
            $planLabel = $period === '1_year' ? '1 Año ($50)' : '6 Meses ($30)';
        }

        // Si la suscripción actual sigue vigente en el futuro, le sumamos el tiempo a partir de esa fecha, sino desde hoy
        $currentEnd = ($tenant->subscription_ends_at && $tenant->subscription_ends_at->isFuture())
            ? $tenant->subscription_ends_at->copy()
            : now();

        $newEnd = $currentEnd->copy()->addMonths($durationMonths);

        $tenant->update([
            'status' => 'active',
            'plan_period' => $period,
            'plan_price' => $price,
            'subscription_ends_at' => $newEnd,
        ]);

        TenantSubscription::create([
            'tenant_id' => $tenant->id,
            'period' => $period,
            'amount' => $price,
            'currency' => 'USD',
            'payment_method' => $validated['payment_method'] ?? 'Renovación Manual',
            'payment_reference' => $validated['payment_reference'] ?? 'RENOV-'.strtoupper(Str::random(6)),
            'starts_at' => $currentEnd,
            'ends_at' => $newEnd,
            'status' => 'paid',
            'notes' => 'Renovación de membresía FitClass: '.$planLabel,
        ]);

        return back()->with('success', 'Membresía renovada con éxito hasta el '.$newEnd->format('d/m/Y').'.');
    }
}
