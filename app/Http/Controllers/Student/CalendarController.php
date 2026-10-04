<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\ClassSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CalendarController extends Controller
{
    public function __invoke(Request $request): View
    {
        $student = auth()->user()->student;

        // Validar semana (0 = actual, 1 = proxima, etc.)
        $weekOffset = (int) $request->get('week', 0);

        // Obtener el Lunes de la semana seleccionada
        $startOfWeek = Carbon::now()->startOfWeek()->addWeeks($weekOffset);

        // Dias de la semana (Lunes a Viernes)
        $weekDays = [];
        for ($i = 0; $i < 5; $i++) {
            $date = $startOfWeek->copy()->addDays($i);
            $weekDays[strtolower($date->locale('es')->dayName)] = $date;
        }

        $days = ['lunes', 'martes', 'miércoles', 'jueves', 'viernes']; // As in ClassSchedule day_of_week
        // Ojo: en la DB los schedules estan como 'miercoles' sin tilde.
        $daysDb = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes'];

        // Obtener planes activos para validar acceso a clases
        $activeMemberships = $student->memberships()->where('status', 'active')->where('end_date', '>=', now())->with('membershipPlan')->get();
        $activePlans = $activeMemberships->pluck('membershipPlan')->filter();

        // Horarios
        $schedules = ClassSchedule::with('classType')->where('is_active', true)->orderBy('start_time')->get();

        // Asistencias de esta semana
        $attendances = Attendance::where('student_id', $student->id)
            ->whereBetween('date', [$startOfWeek->format('Y-m-d'), $startOfWeek->copy()->addDays(4)->format('Y-m-d')])
            ->get();

        return view('student.calendar', compact('student', 'weekDays', 'daysDb', 'schedules', 'activePlans', 'attendances', 'weekOffset'));
    }

    public function schedule(Request $request)
    {
        $request->validate([
            'class_schedule_id' => 'required|exists:class_schedules,id',
            'date' => 'required|date',
            'attend' => 'nullable|string|in:si,no,1,0',
        ]);

        $student = auth()->user()->student;
        $schedule = ClassSchedule::findOrFail($request->class_schedule_id);
        $date = Carbon::parse($request->date);

        // Validar que no sea fecha pasada
        $classDateTime = Carbon::parse($date->format('Y-m-d').' '.$schedule->start_time);

        if ($classDateTime->isPast() && ! $classDateTime->isCurrentHour()) {
            if ($request->ajax()) {
                return response()->json(['error' => 'No puedes modificar una clase que ya pasó.'], 422);
            }

            return back()->with('error', 'No puedes modificar una clase que ya pasó.');
        }

        $attend = in_array(strtolower($request->get('attend', 'si')), ['si', '1', 'true']);

        if ($attend) {
            Attendance::firstOrCreate(
                [
                    'student_id' => $student->id,
                    'date' => $date->format('Y-m-d'),
                    'class_schedule_id' => $schedule->id,
                ],
                [
                    'location_id' => $student->location_id ?? 1,
                    'time' => $schedule->start_time,
                    'registered_by_user_id' => auth()->id(),
                    'notes' => 'Confirmado por alumna',
                ]
            );

            if ($request->ajax()) {
                return response()->json(['success' => true, 'attended' => true, 'message' => 'Asistencia registrada con éxito']);
            }

            return back()->with('success', 'Asistencia registrada con éxito.');
        } else {
            Attendance::where('student_id', $student->id)
                ->where('date', $date->format('Y-m-d'))
                ->where('class_schedule_id', $schedule->id)
                ->delete();

            if ($request->ajax()) {
                return response()->json(['success' => true, 'attended' => false, 'message' => 'Asistencia cancelada']);
            }

            return back()->with('success', 'Asistencia cancelada.');
        }
    }
}
