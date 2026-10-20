<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Student;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showSuperAdminLogin(): View|RedirectResponse
    {
        if (Auth::check() && Auth::user()->isSuperAdmin()) {
            return redirect()->route('superadmin.dashboard');
        }

        return view('auth.superadmin-login');
    }

    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            if (Auth::user()->isSuperAdmin()) {
                return redirect()->route('superadmin.dashboard');
            }

            return Auth::user()->isAdmin()
                ? redirect()->route('admin.dashboard')
                : redirect()->route('student.dashboard');
        }

        return view('auth.login');
    }

    public function showTenantLogin(string $slug): View|RedirectResponse
    {
        $tenant = Tenant::where('slug', $slug)->firstOrFail();
        session(['current_tenant_id' => $tenant->id]);

        if (Auth::check()) {
            if (Auth::user()->isSuperAdmin()) {
                return redirect()->route('superadmin.dashboard');
            }

            return Auth::user()->isAdmin()
                ? redirect()->route('admin.dashboard')
                : redirect()->route('student.dashboard');
        }

        return view('auth.login', ['currentTenant' => $tenant]);
    }

    public function showTenantRegister(string $slug): View
    {
        $tenant = Tenant::where('slug', $slug)->firstOrFail();
        session(['current_tenant_id' => $tenant->id]);

        $locations = Location::where('is_active', true)->get();

        return view('auth.register', ['currentTenant' => $tenant, 'locations' => $locations]);
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'phone' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'phone.required' => 'Ingresa tu número de teléfono o correo de acceso.',
            'password.required' => 'Ingresa tu contraseña.',
        ]);

        $loginField = $credentials['phone'];
        $user = User::where('phone', $loginField)
            ->orWhere('email', $loginField)
            ->first();

        if (! $user || ! $user->is_active) {
            return back()->withErrors([
                'phone' => 'Las credenciales no coinciden o la cuenta está suspendida.',
            ])->onlyInput('phone');
        }

        if (Auth::attempt(['id' => $user->id, 'password' => $credentials['password']], $request->boolean('remember'))) {
            $request->session()->regenerate();

            if ($user->must_change_password) {
                return redirect()->route('password.change');
            }

            if ($user->isSuperAdmin()) {
                return redirect()->route('superadmin.dashboard');
            }

            return $user->isAdmin()
                ? redirect()->route('admin.dashboard')
                : redirect()->route('student.dashboard');
        }

        return back()->withErrors([
            'phone' => 'El número de teléfono o la contraseña son incorrectos.',
        ])->onlyInput('phone');
    }

    public function showRegister(): View
    {
        $locations = Location::where('is_active', true)->get();

        return view('auth.register', compact('locations'));
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'phone' => ['required', 'string', 'max:20', 'unique:users,phone'],
            'name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:150'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'location_id' => ['required', 'exists:locations,id'],
        ], [
            'phone.required' => 'El número de teléfono es obligatorio.',
            'phone.unique' => 'Este número de teléfono ya está registrado en el sistema.',
            'password.required' => 'Ingresa tu contraseña.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'location_id.required' => 'Selecciona tu sede de Bail Fitness.',
        ]);

        $tenantId = session('current_tenant_id') ?? Tenant::first()?->id;

        $user = User::create([
            'tenant_id' => $tenantId,
            'phone' => $validated['phone'],
            'name' => $validated['name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => 'student',
            'must_change_password' => false,
            'is_active' => true,
        ]);

        Student::create([
            'user_id' => $user->id,
            'location_id' => $validated['location_id'],
            'registration_date' => now(),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('student.dashboard')->with('success', '¡Bienvenida a Layra Fit! Tu registro fue exitoso.');
    }

    public function showChangePassword(): View
    {
        return view('auth.change-password');
    }

    public function changePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'password.required' => 'Ingresa tu nueva contraseña.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        /** @var User $user */
        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password),
            'must_change_password' => false,
        ]);

        return redirect()->route($user->isAdmin() ? 'admin.dashboard' : 'student.dashboard')
            ->with('success', 'Contraseña actualizada correctamente.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
