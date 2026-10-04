<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Location;
use App\Models\Membership;
use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $locationId = $request->get('location_id');

        $studentsQuery = Student::query();
        $membershipsQuery = Membership::query();
        $paymentsQuery = Payment::query();
        $attendancesQuery = Attendance::query();

        if ($locationId) {
            $studentsQuery->where('location_id', $locationId);
            $membershipsQuery->where('location_id', $locationId);
            $paymentsQuery->where('location_id', $locationId);
            $attendancesQuery->where('location_id', $locationId);
        }

        $totalStudents = (clone $studentsQuery)->count();
        $activeStudents = (clone $studentsQuery)->whereHas('user', fn ($q) => $q->where('is_active', true))->count();
        $suspendedStudents = (clone $studentsQuery)->whereHas('user', fn ($q) => $q->where('is_active', false))->count();

        $activeMemberships = (clone $membershipsQuery)
            ->where('status', 'active')
            ->where('end_date', '>=', now()->toDateString())
            ->count();

        $expiredMemberships = (clone $membershipsQuery)
            ->where('end_date', '<', now()->toDateString())
            ->count();

        $pendingPayments = (clone $paymentsQuery)->where('status', 'pending')->count();
        $approvedPaymentsMonth = (clone $paymentsQuery)
            ->where('status', 'approved')
            ->whereMonth('payment_date', now()->month)
            ->whereYear('payment_date', now()->year);

        $monthlyRevenue = (clone $approvedPaymentsMonth)->sum('amount');
        $approvedPaymentsCount = (clone $approvedPaymentsMonth)->count();

        $todayAttendances = (clone $attendancesQuery)
            ->whereDate('date', now()->toDateString())
            ->count();

        $recentPayments = (clone $paymentsQuery)
            ->with(['student.user', 'location', 'membershipPlan', 'paymentMethod'])
            ->latest()
            ->take(5)
            ->get();

        $locations = Location::where('is_active', true)->get();

        return view('admin.dashboard', compact(
            'totalStudents',
            'activeStudents',
            'suspendedStudents',
            'activeMemberships',
            'expiredMemberships',
            'pendingPayments',
            'monthlyRevenue',
            'approvedPaymentsCount',
            'todayAttendances',
            'recentPayments',
            'locations',
            'locationId'
        ));
    }
}
