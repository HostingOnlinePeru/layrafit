<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ClassSchedule;
use App\Models\DanceClass;
use Illuminate\View\View;

class RoutineController extends Controller
{
    public function index(): View
    {
        $student = auth()->user()->student;

        $routines = DanceClass::where('is_active', true)
            ->where(function ($q) use ($student) {
                $q->whereNull('location_id')
                    ->orWhere('location_id', $student->location_id);
            })
            ->latest()
            ->get();

        $schedules = ClassSchedule::with('classType')
            ->where('is_active', true)
            ->orderBy('start_time')
            ->get();

        $days = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes'];

        $activeMemberships = $student->memberships()->where('status', 'active')->where('end_date', '>=', now())->with('membershipPlan')->get();
        $activePlans = $activeMemberships->pluck('membershipPlan')->filter();

        return view('student.routines', compact('routines', 'schedules', 'days', 'activePlans'));
    }
}
