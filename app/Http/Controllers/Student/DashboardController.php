<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\DanceClass;
use App\Models\News;
use App\Models\RaffleEligibility;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $user = auth()->user();
        $student = $user->student;
        $activeMembership = $student->activeMembership;

        // Calcular clases usadas esta semana
        $startOfWeek = now()->startOfWeek()->toDateString();
        $endOfWeek = now()->endOfWeek()->toDateString();

        $weeklyUsedClasses = Attendance::where('student_id', $student->id)
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->count();

        $weeklyLimit = $activeMembership ? $activeMembership->weekly_class_limit : 0;
        $weeklyRemainingClasses = max(0, $weeklyLimit - $weeklyUsedClasses);

        // Novedades para el local o generales
        $latestNews = News::where('is_published', true)
            ->where(function ($q) use ($student) {
                $q->whereNull('location_id')
                    ->orWhere('location_id', $student->location_id);
            })
            ->latest()
            ->take(3)
            ->get();

        // Clases / Rutinas disponibles
        $upcomingClasses = DanceClass::where('is_active', true)
            ->where(function ($q) use ($student) {
                $q->whereNull('location_id')
                    ->orWhere('location_id', $student->location_id);
            })
            ->latest()
            ->take(4)
            ->get();

        // Sorteo elegibilidad
        $raffleEligibility = RaffleEligibility::where('student_id', $student->id)->first();

        return view('student.dashboard', compact(
            'user',
            'student',
            'activeMembership',
            'weeklyUsedClasses',
            'weeklyLimit',
            'weeklyRemainingClasses',
            'latestNews',
            'upcomingClasses',
            'raffleEligibility'
        ));
    }
}
