<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\DanceClass;
use App\Models\IndividualClassCredit;
use App\Models\Location;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function index(Request $request): View
    {
        $locationId = $request->get('location_id');
        $date = $request->get('date', now()->toDateString());

        $locations = Location::where('is_active', true)->get();
        $danceClasses = DanceClass::where('is_active', true)
            ->when($locationId, fn ($q) => $q->where('location_id', $locationId))
            ->get();

        $attendances = Attendance::with(['student.user', 'location', 'danceClass'])
            ->whereDate('date', $date)
            ->when($locationId, fn ($q) => $q->where('location_id', $locationId))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $students = Student::with(['user', 'location', 'activeMembership.membershipPlan', 'individualClassCredits'])
            ->when($locationId, fn ($q) => $q->where('location_id', $locationId))
            ->get();

        return view('admin.attendance.index', compact(
            'locations',
            'danceClasses',
            'attendances',
            'students',
            'locationId',
            'date'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'location_id' => ['required', 'exists:locations,id'],
            'dance_class_id' => ['nullable', 'exists:dance_classes,id'],
            'date' => ['required', 'date'],
            'time' => ['required'],
            'notes' => ['nullable', 'string', 'max:255'],
        ], [
            'student_id.required' => 'Selecciona la alumna.',
            'location_id.required' => 'Selecciona la sede.',
            'date.required' => 'Ingresa la fecha de la asistencia.',
            'time.required' => 'Ingresa la hora de la clase.',
        ]);

        $student = Student::with(['activeMembership.membershipPlan', 'individualClassCredits'])->findOrFail($validated['student_id']);
        $dateObj = Carbon::parse($validated['date']);

        // Check if attendance already registered for this date & time for student
        $existing = Attendance::where('student_id', $student->id)
            ->whereDate('date', $validated['date'])
            ->exists();

        if ($existing) {
            return back()->with('error', "La alumna '{$student->user->full_name}' ya tiene asistencia registrada el día {$dateObj->format('d/m/Y')}.");
        }

        $activeMembership = $student->activeMembership;

        if (! $activeMembership) {
            // Verificar si tiene crédito por clase individual (Plan S/ 10)
            $availableCredit = IndividualClassCredit::where('student_id', $student->id)
                ->where('status', 'available')
                ->first();

            if (! $availableCredit) {
                return back()->with('error', "La alumna '{$student->user->full_name}' no tiene una membresía activa ni clases individuales disponibles.");
            }

            // Registrar asistencia con crédito individual
            $attendance = Attendance::create([
                'student_id' => $student->id,
                'location_id' => $validated['location_id'],
                'dance_class_id' => $validated['dance_class_id'] ?? null,
                'date' => $validated['date'],
                'time' => $validated['time'],
                'registered_by_user_id' => auth()->id(),
                'notes' => $validated['notes'] ?? 'Clase Individual (S/10)',
            ]);

            $availableCredit->update([
                'status' => 'used',
                'used_at' => now(),
                'attendance_id' => $attendance->id,
            ]);

            return back()->with('success', "Asistencia registrada correctamente utilizando 1 crédito individual para {$student->user->full_name}.");
        }

        // Para Membresías Mensuales (3 veces/semana o 5 veces/semana)
        $startOfWeek = $dateObj->copy()->startOfWeek()->toDateString();
        $endOfWeek = $dateObj->copy()->endOfWeek()->toDateString();

        $weeklyAttendancesCount = Attendance::where('student_id', $student->id)
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->count();

        $weeklyLimit = $activeMembership->weekly_class_limit ?? 3;

        if ($weeklyAttendancesCount >= $weeklyLimit) {
            return back()->with('error', "La alumna '{$student->user->full_name}' alcanzó el límite semanal de {$weeklyLimit} clases permitido por su membresía ({$weeklyAttendancesCount}/{$weeklyLimit} clases usadas esta semana).");
        }

        Attendance::create([
            'student_id' => $student->id,
            'location_id' => $validated['location_id'],
            'dance_class_id' => $validated['dance_class_id'] ?? null,
            'date' => $validated['date'],
            'time' => $validated['time'],
            'registered_by_user_id' => auth()->id(),
            'notes' => $validated['notes'] ?? null,
        ]);

        $remaining = $weeklyLimit - ($weeklyAttendancesCount + 1);

        return back()->with('success', "Asistencia registrada para {$student->user->full_name}. Clases restantes esta semana: {$remaining}.");
    }
}
