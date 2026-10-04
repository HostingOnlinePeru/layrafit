<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Raffle;
use App\Models\RaffleEligibility;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RaffleController extends Controller
{
    public function index(): View
    {
        // Recalcular elegibilidad de todas las alumnas
        $this->evaluateAllStudentsEligibility();

        $raffles = Raffle::with(['location', 'winner.user'])->latest()->paginate(15);
        $eligibleStudents = RaffleEligibility::with(['student.user', 'student.location'])
            ->where('is_eligible', true)
            ->get();

        return view('admin.raffles.index', compact('raffles', 'eligibleStudents'));
    }

    public function create(): View
    {
        return view('admin.raffles.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string'],
            'prize' => ['required', 'string', 'max:200'],
            'raffle_date' => ['required', 'date'],
        ]);

        $validated['status'] = 'active';

        Raffle::create($validated);

        return redirect()->route('admin.raffles.index')
            ->with('success', 'Sorteo creado y habilitado para participantes elegibles.');
    }

    public function draw(Raffle $raffle): RedirectResponse
    {
        $eligibleStudents = RaffleEligibility::where('is_eligible', true)->get();

        if ($eligibleStudents->isEmpty()) {
            return back()->with('error', 'No existen alumnas elegibles (6 meses de pago consecutivos) en este momento para realizar el sorteo.');
        }

        $winnerEligibility = $eligibleStudents->random();
        $winnerStudent = $winnerEligibility->student;

        $raffle->update([
            'status' => 'completed',
            'winner_student_id' => $winnerStudent->id,
        ]);

        return back()->with('success', "🎉 ¡Sorteo realizado con éxito! La ganadora es {$winnerStudent->user->full_name} (Tel: {$winnerStudent->user->phone}).");
    }

    private function evaluateAllStudentsEligibility(): void
    {
        $students = Student::all();

        foreach ($students as $student) {
            // Contar meses consecutivos con pagos aprobados
            $approvedPayments = Payment::where('student_id', $student->id)
                ->where('status', 'approved')
                ->orderBy('payment_date', 'asc')
                ->get();

            $consecutiveCount = 0;
            $lastMonth = null;

            foreach ($approvedPayments as $pay) {
                $payMonth = Carbon::parse($pay->payment_date)->startOfMonth();

                if ($lastMonth === null) {
                    $consecutiveCount = 1;
                } else {
                    $diffInMonths = $lastMonth->diffInMonths($payMonth);
                    if ($diffInMonths === 1) {
                        $consecutiveCount++;
                    } elseif ($diffInMonths > 1) {
                        $consecutiveCount = 1; // Reiniciar contador si hubo quiebre de continuidad
                    }
                }
                $lastMonth = $payMonth;
            }

            $isEligible = $consecutiveCount >= 6;

            RaffleEligibility::updateOrCreate(
                ['student_id' => $student->id],
                [
                    'consecutive_months_count' => $consecutiveCount,
                    'is_eligible' => $isEligible,
                    'last_checked_at' => now(),
                ]
            );
        }
    }
}
