<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassSchedule;
use App\Models\ClassType;
use App\Models\Location;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClassScheduleController extends Controller
{
    public function index(Request $request): View
    {
        $locationId = $request->get('location_id');

        $query = ClassSchedule::with(['location', 'classType']);

        if ($locationId) {
            $query->where('location_id', $locationId);
        }

        $schedules = $query->orderByRaw("CASE day_of_week 
            WHEN 'lunes' THEN 1 
            WHEN 'martes' THEN 2 
            WHEN 'miercoles' THEN 3 
            WHEN 'jueves' THEN 4 
            WHEN 'viernes' THEN 5 
            WHEN 'sabado' THEN 6 
            WHEN 'domingo' THEN 7 END")
            ->orderBy('start_time')
            ->get();

        $locations = Location::where('is_active', true)->get();

        return view('admin.class-schedules.index', compact('schedules', 'locations', 'locationId'));
    }

    public function create(): View
    {
        $locations = Location::where('is_active', true)->get();
        $classTypes = ClassType::where('is_active', true)->get();

        return view('admin.class-schedules.create', compact('locations', 'classTypes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'location_id' => ['required', 'exists:locations,id'],
            'class_type_id' => ['required', 'exists:class_types,id'],
            'day_of_week' => ['required', 'in:lunes,martes,miercoles,jueves,viernes,sabado,domingo'],
            'start_time' => ['required'],
            'end_time' => ['required', 'after:start_time'],
            'instructor_name' => ['nullable', 'string', 'max:100'],
            'max_capacity' => ['nullable', 'integer', 'min:1'],
        ], [
            'location_id.required' => 'Selecciona la sede del horario.',
            'class_type_id.required' => 'Selecciona el tipo de clase.',
            'day_of_week.required' => 'Selecciona el día de la semana.',
            'start_time.required' => 'Ingresa la hora de inicio.',
            'end_time.required' => 'Ingresa la hora de finalización.',
            'end_time.after' => 'La hora de fin debe ser posterior a la hora de inicio.',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        ClassSchedule::create($validated);

        return redirect()->route('admin.class-schedules.index')
            ->with('success', 'Horario programado correctamente.');
    }

    public function destroy(ClassSchedule $classSchedule): RedirectResponse
    {
        $classSchedule->delete();

        return back()->with('success', 'Horario eliminado con éxito.');
    }
}
