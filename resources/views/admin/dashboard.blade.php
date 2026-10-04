@extends('layouts.admin')

@section('page_title', 'Dashboard Resumen — Bail Fitness PWA')

@section('content')
<!-- Filtro por Local -->
<div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 18px 24px; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
    <div>
        <h4 style="font-weight: 700; font-size: 16px;">Vista General de la Red de Locales</h4>
        <p style="font-size: 13px; color: var(--text-muted);">Filtra la información por sede específica o consulta el total consolidado.</p>
    </div>

    <form action="{{ route('admin.dashboard') }}" method="GET" style="display: flex; align-items: center; gap: 12px;">
        <select name="location_id" class="form-control" onchange="this.form.submit()" style="min-width: 220px; background: #111422; color: #fff;">
            <option value="">🏢 Todas las Sedes (Consolidado)</option>
            @foreach($locations as $loc)
                <option value="{{ $loc->id }}" {{ $locationId == $loc->id ? 'selected' : '' }}>
                    {{ $loc->name }}
                </option>
            @endforeach
        </select>
    </form>
</div>

<!-- KPI Cards -->
<div class="grid-cards">
    <div class="card-kpi">
        <div class="kpi-title">Total Alumnos</div>
        <div class="kpi-value">{{ number_format($totalStudents) }}</div>
        <div class="kpi-sub"><i class="fa-solid fa-users" style="color: var(--primary); margin-right: 4px;"></i> {{ $activeStudents }} Activos / {{ $suspendedStudents }} Suspendidos</div>
    </div>

    <div class="card-kpi">
        <div class="kpi-title">Membresías Vigentes</div>
        <div class="kpi-value" style="color: var(--success);">{{ number_format($activeMemberships) }}</div>
        <div class="kpi-sub"><i class="fa-solid fa-id-card" style="color: var(--accent); margin-right: 4px;"></i> {{ $expiredMemberships }} Membresías Vencidas</div>
    </div>

    <div class="card-kpi">
        <div class="kpi-title">Pagos Pendientes</div>
        <div class="kpi-value" style="color: var(--warning);">{{ number_format($pendingPayments) }}</div>
        <div class="kpi-sub"><i class="fa-solid fa-credit-card" style="color: var(--warning); margin-right: 4px;"></i> Por Validar por Admin</div>
    </div>

    <div class="card-kpi">
        <div class="kpi-title">Ingresos del Mes</div>
        <div class="kpi-value" style="color: var(--accent);">S/ {{ number_format($monthlyRevenue, 2) }}</div>
        <div class="kpi-sub"><i class="fa-solid fa-chart-line" style="color: var(--accent); margin-right: 4px;"></i> {{ $approvedPaymentsCount }} Pagos Aprobados este mes</div>
    </div>
</div>

<!-- Accesos Directos & Asistencia del Día -->
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; margin-bottom: 28px;">
    <div class="table-container" style="margin-bottom: 0;">
        <div style="padding: 18px 20px; border-bottom: 1px solid var(--border-color); display: flex; align-items: center; justify-content: space-between;">
            <h4 style="font-weight: 700; font-size: 15px;">Últimos Pagos Reportados</h4>
            <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary btn-sm">Ver Todos</a>
        </div>
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Alumno</th>
                    <th>Sede</th>
                    <th>Plan</th>
                    <th>Monto</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentPayments as $payment)
                    <tr>
                        <td>
                            <div style="font-weight: 600;">{{ $payment->student->user->full_name ?? 'Alumno' }}</div>
                            <div style="font-size: 12px; color: var(--text-muted);"><i class="fa-solid fa-phone" style="font-size: 11px; margin-right: 4px;"></i> {{ $payment->student->user->phone ?? '' }}</div>
                        </td>
                        <td>{{ $payment->location->name ?? 'N/A' }}</td>
                        <td>{{ $payment->membershipPlan->name ?? 'N/A' }}</td>
                        <td style="font-weight: 700;">S/ {{ number_format($payment->amount, 2) }}</td>
                        <td>
                            @if($payment->status === 'approved')
                                <span class="badge badge-approved">Aprobado</span>
                            @elseif($payment->status === 'pending')
                                <span class="badge badge-pending">Pendiente</span>
                            @else
                                <span class="badge badge-rejected">Rechazado</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 30px;">
                            No hay pagos recientes registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Card de Asistencias Hoy -->
    <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 24px; display: flex; flex-direction: column; justify-content: space-between;">
        <div>
            <div style="font-size: 28px; color: var(--accent); margin-bottom: 10px;"><i class="fa-solid fa-clipboard-user"></i></div>
            <h4 style="font-weight: 700; font-size: 16px; margin-bottom: 6px;">Asistencia de Hoy</h4>
            <p style="font-size: 13px; color: var(--text-muted);">Total de alumnas registradas en clases el día de hoy.</p>
            <div style="font-size: 38px; font-weight: 800; color: var(--primary); margin: 16px 0;">
                {{ $todayAttendances }} <span style="font-size: 16px; font-weight: 500; color: var(--text-muted);">asistencias</span>
            </div>
        </div>

        <a href="{{ route('admin.attendance.index') }}" class="btn btn-primary" style="width: 100%; gap: 8px;">
            <i class="fa-solid fa-clipboard-check"></i> Registrar Asistencia Rápida
        </a>
    </div>
</div>
@endsection
