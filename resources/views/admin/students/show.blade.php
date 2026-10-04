@extends('layouts.admin')

@section('page_title', 'Perfil de Alumna — ' . $student->user->full_name)

@section('content')
<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
    <div>
        <h3 style="font-size: 22px; font-weight: 800;">{{ $student->user->full_name }}</h3>
        <p style="font-size: 13px; color: var(--text-muted);">📱 Teléfono: {{ $student->user->phone }} | 🏢 Sede: {{ $student->location->name }}</p>
    </div>

    <div style="display: flex; gap: 8px;">
        <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-secondary btn-sm">✏️ Editar Datos</a>
        <a href="{{ route('admin.students.index') }}" class="btn btn-secondary btn-sm">← Volver</a>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px;">
    <!-- Info General & Membresía -->
    <div>
        <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 20px; padding: 24px; margin-bottom: 20px;">
            <h4 style="font-weight: 700; font-size: 16px; margin-bottom: 16px; border-bottom: 1px solid var(--border-color); padding-bottom: 10px;">Estado de Membresía</h4>
            
            @if($student->activeMembership)
                <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 12px; padding: 16px; margin-bottom: 16px;">
                    <div style="font-size: 12px; color: var(--success); font-weight: 700; text-transform: uppercase;">Membresía Activa</div>
                    <div style="font-size: 18px; font-weight: 800; margin: 6px 0;">{{ $student->activeMembership->membershipPlan->name }}</div>
                    <div style="font-size: 13px; color: var(--text-muted);">Vigencia: {{ $student->activeMembership->start_date->format('d/m/Y') }} al {{ $student->activeMembership->end_date->format('d/m/Y') }}</div>
                    <div style="font-size: 13px; color: var(--accent); margin-top: 6px;">Límite Semanal: {{ $student->activeMembership->weekly_class_limit }} clases por semana</div>
                </div>
            @else
                <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 12px; padding: 16px; margin-bottom: 16px;">
                    <div style="font-size: 12px; color: var(--danger); font-weight: 700; text-transform: uppercase;">Sin Membresía Activa</div>
                    <p style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">La alumna no cuenta con un plan vigente en este momento.</p>
                </div>
            @endif

            <div style="font-size: 13px; display: flex; flex-direction: column; gap: 8px;">
                <div><strong>Fecha de Registro:</strong> {{ $student->registration_date ? $student->registration_date->format('d/m/Y') : $student->created_at->format('d/m/Y') }}</div>
                <div><strong>Correo:</strong> {{ $student->user->email ?? 'No registrado' }}</div>
                <div><strong>Estado de Acceso:</strong> {{ $student->user->is_active ? 'Permitido' : 'Suspendido' }}</div>
                <div><strong>Elegibilidad Sorteo:</strong> {{ $student->raffleEligibility && $student->raffleEligibility->is_eligible ? '✅ Elegible (6+ meses consecutivas)' : '⏳ No elegible aún' }}</div>
            </div>
        </div>
    </div>

    <!-- Historiales de Pagos y Asistencias -->
    <div>
        <!-- Historial de Pagos -->
        <div class="table-container">
            <div style="padding: 16px 20px; border-bottom: 1px solid var(--border-color); font-weight: 700;">
                💳 Historial de Pagos
            </div>
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Plan</th>
                        <th>Monto</th>
                        <th>Método</th>
                        <th>N° Operación</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($student->payments as $pay)
                        <tr>
                            <td>{{ $pay->payment_date->format('d/m/Y') }}</td>
                            <td>{{ $pay->membershipPlan->name ?? 'N/A' }}</td>
                            <td style="font-weight: 700;">S/ {{ number_format($pay->amount, 2) }}</td>
                            <td>{{ $pay->paymentMethod->name ?? 'N/A' }}</td>
                            <td><code>{{ $pay->operation_number }}</code></td>
                            <td>
                                @if($pay->status === 'approved')
                                    <span class="badge badge-approved">Aprobado</span>
                                @elseif($pay->status === 'pending')
                                    <span class="badge badge-pending">Pendiente</span>
                                @else
                                    <span class="badge badge-rejected">Rechazado</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 20px;">
                                No registra historial de pagos.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Historial de Asistencias -->
        <div class="table-container">
            <div style="padding: 16px 20px; border-bottom: 1px solid var(--border-color); font-weight: 700;">
                📋 Últimas Asistencias Registradas
            </div>
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Clase / Rutina</th>
                        <th>Sede</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($student->attendances->take(10) as $att)
                        <tr>
                            <td>{{ $att->date->format('d/m/Y') }}</td>
                            <td>{{ $att->time }}</td>
                            <td>{{ $att->danceClass->name ?? 'Clase Regular' }}</td>
                            <td>{{ $att->location->name }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 20px;">
                                No registra asistencias previas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
