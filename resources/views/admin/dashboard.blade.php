@extends('layouts.admin')

@section('page_title', 'Dashboard')

@section('content')
<!-- Filtro por Local -->
<div class="admin-filter-card">
    <div class="admin-filter-info">
        <h4 style="font-weight: 700; font-size: 15px; margin: 0 0 2px; color: var(--accent);">Vista por Sede</h4>
        <p style="font-size: 12px; color: var(--text-muted); margin: 0;">Consulta métricas por sede o consolidado general</p>
    </div>

    <form action="{{ route('admin.dashboard') }}" method="GET" style="margin: 0;">
        <select name="location_id" class="form-control" onchange="this.form.submit()" style="background: #111422; color: #fff; min-width: 220px; font-weight: 600; cursor: pointer;">
            <option value="">🏢 Todas las Sedes (Consolidado)</option>
            @foreach($locations as $loc)
                <option value="{{ $loc->id }}" {{ $locationId == $loc->id ? 'selected' : '' }}>
                    {{ $loc->name }}
                </option>
            @endforeach
        </select>
    </form>
</div>

<!-- KPI Cards Centradas -->
<div class="admin-kpi-grid">
    <div class="admin-kpi-card">
        <div class="kpi-icon-badge" style="background: rgba(224, 203, 194, 0.12); color: var(--primary);">
            <i class="fa-solid fa-users"></i>
        </div>
        <div class="kpi-title">Total Alumnas</div>
        <div class="kpi-value">{{ number_format($totalStudents) }}</div>
        <div class="kpi-sub">
            <span style="color: var(--success); font-weight: 600;">{{ $activeStudents }} Activas</span>
            <span style="color: var(--text-muted); opacity: 0.6;">·</span>
            <span style="color: var(--text-muted);">{{ $suspendedStudents }} Susp.</span>
        </div>
    </div>

    <div class="admin-kpi-card">
        <div class="kpi-icon-badge" style="background: rgba(16, 185, 129, 0.15); color: var(--success);">
            <i class="fa-solid fa-id-card"></i>
        </div>
        <div class="kpi-title">Membresías</div>
        <div class="kpi-value" style="color: var(--success);">{{ number_format($activeMemberships) }}</div>
        <div class="kpi-sub">
            <span style="color: #fbbf24;">{{ $expiredMemberships }} Vencidas</span>
        </div>
    </div>

    <div class="admin-kpi-card">
        <div class="kpi-icon-badge" style="background: rgba(245, 158, 11, 0.15); color: var(--warning);">
            <i class="fa-solid fa-clock-rotate-left"></i>
        </div>
        <div class="kpi-title">Pagos Pendientes</div>
        <div class="kpi-value" style="color: var(--warning);">{{ number_format($pendingPayments) }}</div>
        <div class="kpi-sub">
            <span style="color: var(--warning); font-weight: 600;">Por validar</span>
        </div>
    </div>

    <div class="admin-kpi-card">
        <div class="kpi-icon-badge" style="background: rgba(255, 236, 218, 0.15); color: var(--accent);">
            <i class="fa-solid fa-coins"></i>
        </div>
        <div class="kpi-title">Ingresos Mes</div>
        <div class="kpi-value" style="color: var(--accent);">S/ {{ number_format($monthlyRevenue, 2) }}</div>
        <div class="kpi-sub">
            <span style="color: var(--primary);">{{ $approvedPaymentsCount }} Aprobados</span>
        </div>
    </div>
</div>

<!-- Accesos Directos & Asistencia del Día -->
<div class="dashboard-split-grid">
    <div class="table-container" style="margin-bottom: 0;">
        <div style="padding: 16px 20px; border-bottom: 1px solid var(--border-color); display: flex; align-items: center; justify-content: space-between;">
            <h4 style="font-weight: 700; font-size: 15px; margin: 0; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-receipt" style="color: var(--primary);"></i> Últimos Pagos Reportados
            </h4>
            <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary btn-sm">Ver Todos</a>
        </div>
        <div style="overflow-x: auto; -webkit-overflow-scrolling: touch; width: 100%;">
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
    </div>

    <!-- Card de Asistencias Hoy -->
    <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 18px; padding: 22px; display: flex; flex-direction: column; justify-content: space-between; gap: 16px;">
        <div>
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
                <div class="kpi-icon-badge" style="margin-bottom: 0; background: rgba(224, 203, 194, 0.15); color: var(--accent);">
                    <i class="fa-solid fa-clipboard-user"></i>
                </div>
                <div>
                    <h4 style="font-weight: 700; font-size: 16px; margin: 0;">Asistencia de Hoy</h4>
                    <p style="font-size: 12px; color: var(--text-muted); margin: 0;">Alumnas registradas en clases hoy</p>
                </div>
            </div>
            <div style="text-align: center; padding: 18px 0; background: rgba(22, 11, 10, 0.4); border-radius: 14px; margin: 12px 0;">
                <div style="font-size: 42px; font-weight: 800; color: var(--primary); line-height: 1;">
                    {{ $todayAttendances }}
                </div>
                <div style="font-size: 13px; font-weight: 600; color: var(--text-muted); margin-top: 6px; text-transform: uppercase; letter-spacing: 0.5px;">
                    Asistencias Registradas
                </div>
            </div>
        </div>

        <a href="{{ route('admin.attendance.index') }}" class="btn btn-primary" style="width: 100%; gap: 8px;">
            <i class="fa-solid fa-clipboard-check"></i> Registrar Asistencia Rápida
        </a>
    </div>
</div>
@endsection
@endsection
