<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\MembershipPlan;
use App\Models\Payment;
use App\Models\PaymentMethod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function index(): View
    {
        $student = auth()->user()->student;
        $activeMembership = $student->activeMembership;
        $plans = MembershipPlan::where('is_active', true)->get();
        $paymentMethods = PaymentMethod::where('is_active', true)->get();
        $payments = Payment::with(['membershipPlan', 'paymentMethod'])
            ->where('student_id', $student->id)
            ->latest()
            ->get();

        return view('student.payments.index', compact(
            'student',
            'activeMembership',
            'plans',
            'paymentMethods',
            'payments'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $student = auth()->user()->student;

        $validated = $request->validate([
            'membership_plan_id' => ['required', 'exists:membership_plans,id'],
            'payment_method_id' => ['required', 'exists:payment_methods,id'],
            'amount' => ['required', 'numeric', 'min:1'],
            'operation_number' => ['required', 'string', 'max:100'],
            'payment_date' => ['required', 'date'],
            'voucher' => ['required', 'image', 'max:4096'],
        ], [
            'membership_plan_id.required' => 'Selecciona el plan a adquirir.',
            'payment_method_id.required' => 'Selecciona el método de pago utilizado.',
            'amount.required' => 'Ingresa el monto pagado.',
            'operation_number.required' => 'El número de operación es obligatorio.',
            'payment_date.required' => 'Ingresa la fecha en que realizaste el pago.',
            'voucher.required' => 'Sube la imagen del comprobante o captura de pago.',
        ]);

        $voucherPath = $request->file('voucher')->store('vouchers', 'public');

        Payment::create([
            'student_id' => $student->id,
            'membership_plan_id' => $validated['membership_plan_id'],
            'payment_method_id' => $validated['payment_method_id'],
            'location_id' => $student->location_id,
            'amount' => $validated['amount'],
            'operation_number' => $validated['operation_number'],
            'payment_date' => $validated['payment_date'],
            'voucher_path' => $voucherPath,
            'status' => 'pending',
        ]);

        $plan = MembershipPlan::find($validated['membership_plan_id']);

        return redirect()->route('student.payments.success')->with('plan_name', $plan->name);
    }
}
