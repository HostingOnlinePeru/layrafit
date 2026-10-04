<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(Request $request): View
    {
        $query = Student::with(['user', 'location', 'activeMembership.membershipPlan']);

        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('phone', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->whereHas('user', fn ($q) => $q->where('is_active', true));
            } elseif ($request->status === 'suspended') {
                $query->whereHas('user', fn ($q) => $q->where('is_active', false));
            }
        }

        $students = $query->latest()->paginate(15)->withQueryString();
        $locations = Location::where('is_active', true)->get();

        return view('admin.students.index', compact('students', 'locations'));
    }

    public function create(): View
    {
        $locations = Location::where('is_active', true)->get();

        return view('admin.students.create', compact('locations'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'phone' => ['required', 'string', 'max:20', 'unique:users,phone'],
            'name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:150'],
            'location_id' => ['required', 'exists:locations,id'],
            'custom_password' => ['nullable', 'string', 'min:6'],
        ], [
            'phone.required' => 'El número de teléfono es obligatorio.',
            'phone.unique' => 'Este número de teléfono ya pertenece a una cuenta.',
            'name.required' => 'El nombre es obligatorio.',
            'last_name.required' => 'El apellido es obligatorio.',
            'location_id.required' => 'Selecciona el local asignado.',
        ]);

        $passwordToSet = $request->filled('custom_password')
            ? $request->custom_password
            : Str::random(8);

        $user = User::create([
            'phone' => $validated['phone'],
            'name' => $validated['name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'] ?? null,
            'password' => Hash::make($passwordToSet),
            'role' => 'student',
            'must_change_password' => true,
            'is_active' => true,
        ]);

        Student::create([
            'user_id' => $user->id,
            'location_id' => $validated['location_id'],
            'registration_date' => now(),
        ]);

        return redirect()->route('admin.students.index')
            ->with('success', "Alumno '{$user->full_name}' registrado exitosamente. Contraseña temporal generada: {$passwordToSet}");
    }

    public function show(Student $student): View
    {
        $student->load([
            'user',
            'location',
            'memberships.membershipPlan',
            'payments.paymentMethod',
            'attendances.danceClass',
            'raffleEligibility',
        ]);

        return view('admin.students.show', compact('student'));
    }

    public function edit(Student $student): View
    {
        $student->load(['user', 'location']);
        $locations = Location::where('is_active', true)->get();

        return view('admin.students.edit', compact('student', 'locations'));
    }

    public function update(Request $request, Student $student): RedirectResponse
    {
        $validated = $request->validate([
            'phone' => ['required', 'string', 'max:20', 'unique:users,phone,'.$student->user_id],
            'name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:150'],
            'location_id' => ['required', 'exists:locations,id'],
        ]);

        $student->user->update([
            'phone' => $validated['phone'],
            'name' => $validated['name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'] ?? null,
        ]);

        $student->update([
            'location_id' => $validated['location_id'],
        ]);

        return redirect()->route('admin.students.index')
            ->with('success', 'Datos del alumno actualizados correctamente.');
    }

    public function toggleStatus(Student $student): RedirectResponse
    {
        $user = $student->user;
        $user->update(['is_active' => ! $user->is_active]);

        $statusText = $user->is_active ? 'activado' : 'suspendido';

        return back()->with('success', "El acceso del alumno '{$user->full_name}' fue {$statusText}.");
    }
}
