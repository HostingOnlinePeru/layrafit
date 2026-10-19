<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\TenantSubscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $totalTenants = Tenant::count();
        $activeTenants = Tenant::where('status', 'active')->count();
        $totalSubscriptions = TenantSubscription::where('status', 'paid')->sum('amount');
        $totalUsers = User::where('role', '!=', 'superadmin')->count();

        $recentTenants = Tenant::withCount('users')
            ->latest()
            ->take(5)
            ->get();

        $recentSubscriptions = TenantSubscription::with('tenant')
            ->latest()
            ->take(5)
            ->get();

        return view('superadmin.dashboard', compact(
            'totalTenants',
            'activeTenants',
            'totalSubscriptions',
            'totalUsers',
            'recentTenants',
            'recentSubscriptions'
        ));
    }
}
