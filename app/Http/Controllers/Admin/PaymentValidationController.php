<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IndividualClassCredit;
use App\Models\Location;
use App\Models\Membership;
use App\Models\Payment;
use App\Models\RaffleEligibility;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PaymentValidationController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['student.user', 'location', 'membershipPlan', 'paymentMethod']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // Por defecto mostrar pendientes primero
            $query->orderByRaw("CASE WHEN status = 'pending' THEN 1 ELSE 2 END");
        }

        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('operation_number', 'like', "%{$search}%")
                    ->orWhereHas('student.user', function ($sq) use ($search) {
                        $sq->where('phone', 'like', "%{$search}%")
                            ->orWhere('name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    });
            });
        }

        $payments = $query->latest()->paginate(15)->withQueryString();
        $locations = Location::where('is_active', true)->get();

        return view('admin.payments.index', compact('payments', 'locations'));
    }

    public function approve(Payment $payment): RedirectResponse
    {
        if ($payment->status === 'approved') {
            return back()->with('error', 'Este pago ya fue aprobado anteriormente.');
        }

        $payment->update([
            'status' => 'approved',
            'validated_by_user_id' => auth()->id(),
            'validated_at' => now(),
        ]);

        $plan = $payment->membershipPlan;
        $student = $payment->student;

        // Actualizar elegibilidad para sorteos
        $raffle = RaffleEligibility::firstOrCreate(
            ['student_id' => $student->id],
            ['consecutive_months_count' => 0, 'is_eligible' => false, 'last_checked_at' => now()]
        );

        $raffle->consecutive_months_count += 1;
        if ($raffle->consecutive_months_count >= 6) {
            $raffle->is_eligible = true;
        }
        $raffle->save();

        if ($plan->type === 'individual') {
            // Plan S/ 10 Clase Individual: generar 1 crÃ©dito disponible
            IndividualClassCredit::create([
                'student_id' => $student->id,
                'payment_id' => $payment->id,
                'status' => 'available',
            ]);
        } else {
            // Plan Mensual (S/ 120 o S/ 140): calcular 30 dÃ­as de vigencia
            $startDate = now()->toDateString();
            $endDate = now()->addDays(30)->toDateString();

            Membership::create([
                'student_id' => $student->id,
                'membership_plan_id' => $plan->id,
                'location_id' => $payment->location_id,
                'price' => $payment->amount,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => 'active',
                'weekly_class_limit' => $plan->weekly_limit ?? 3,
            ]);
        }

        return back()->with('success', "Pago NÂ° {$payment->operation_number} aprobado exitosamente. MembresÃ­a habilitada.");
    }

    public function reject(Request $request, Payment $payment): RedirectResponse
    {
        $request->validate([
            'rejection_reason' => ['required', 'string', 'max:255'],
        ], [
            'rejection_reason.required' => 'Ingresa el motivo del rechazo del pago.',
        ]);

        $payment->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'validated_by_user_id' => auth()->id(),
            'validated_at' => now(),
        ]);

        return back()->with('success', "Pago NÂ° {$payment->operation_number} fue rechazado.");
    }
}
