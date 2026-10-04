@extends('layouts.admin')

@section('page_title', 'Validación de Pagos')

@section('content')
<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
    <div>
        <h3 style="font-size: 20px; font-weight: 700;">Validación y Registro de Pagos</h3>
        <p style="font-size: 13px; color: var(--text-muted);">Revisa los comprobantes subidos por las alumnas y aprueba sus membresías.</p>
    </div>
</div>

<div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 20px; padding: 24px; margin-bottom: 24px;">
    <form action="{{ route('admin.payments.index') }}" method="GET" style="display: grid; grid-template-columns: 1fr 1fr 2fr auto auto; gap: 16px; align-items: end;">
        <div>
            <label class="form-label" style="font-size: 11px;">Estado del Pago</label>
            <select name="status" class="form-control" style="background: #111422; color: #fff;">
                <option value="">-- Todos los Estados --</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendientes</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Aprobados</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rechazados</option>
            </select>
        </div>
        <div>
            <label class="form-label" style="font-size: 11px;">Filtrar por Sede</label>
            <select name="location_id" class="form-control" style="background: #111422; color: #fff;">
                <option value="">-- Todas las Sedes --</option>
                @foreach($locations as $loc)
                    <option value="{{ $loc->id }}" {{ request('location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label" style="font-size: 11px;">N° Operación o Alumna</label>
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Ej. 123456 o Teléfono">
        </div>
        <div>
            <button type="submit" class="btn btn-primary" style="height: 42px;">Buscar</button>
        </div>
        <div>
            <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary" style="height: 42px;">Limpiar</a>
        </div>
    </form>
</div>

@if(session('success'))
    <div class="alert alert-success" style="margin-bottom: 20px;">
        <span>✅</span>
        <div>{{ session('success') }}</div>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger" style="margin-bottom: 20px;">
        <span>⚠️</span>
        <div>{{ session('error') }}</div>
    </div>
@endif

<div class="table-container">
    <table class="custom-table">
        <thead>
            <tr>
                <th>Alumno / Sede</th>
                <th>Plan Solicitado</th>
                <th>Monto & Método</th>
                <th>Operación & Fecha</th>
                <th>Comprobante</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $payment)
                <tr>
                    <td>
                        <div style="font-weight: 700; color: #fff;">{{ $payment->student->user->full_name ?? 'Alumno' }}</div>
                        <div style="font-size: 12px; color: var(--accent);">📱 {{ $payment->student->user->phone ?? '' }}</div>
                        <div style="font-size: 11px; color: var(--text-muted);">🏢 {{ $payment->location->name ?? 'N/A' }}</div>
                        <div style="margin-top: 6px;">
                            <a href="{{ route('admin.students.show', $payment->student_id) }}" class="btn btn-secondary btn-sm" style="padding: 3px 8px; font-size: 10.5px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.2); color: #ccc;">
                                <i class="fa-solid fa-user"></i> Ver Perfil
                            </a>
                        </div>
                    </td>
                    <td>
                        <span style="font-weight: 600; color: #fff;">{{ $payment->membershipPlan->name ?? 'N/A' }}</span>
                    </td>
                    <td>
                        <div style="font-weight: 800; font-size: 15px; color: var(--success);">S/ {{ number_format($payment->amount, 2) }}</div>
                        <div style="font-size: 12px; color: var(--text-muted);">💳 {{ $payment->paymentMethod->name ?? 'N/A' }}</div>
                    </td>
                    <td>
                        <div style="font-family: monospace; font-weight: 700; background: rgba(0,0,0,0.3); padding: 4px 8px; border-radius: 6px; display: inline-block;">
                            {{ $payment->operation_number }}
                        </div>
                        <div style="font-size: 11px; color: var(--text-muted); margin-top: 4px;">
                            📅 {{ $payment->payment_date->format('d/m/Y') }}
                        </div>
                    </td>
                    <td>
                        @if($payment->voucher_path)
                            <button type="button" class="btn btn-secondary btn-sm" style="padding: 4px 10px; font-size: 11px;" onclick="showVoucherModal('{{ asset('storage/' . $payment->voucher_path) }}')">
                                <i class="fa-solid fa-image"></i> Ver Váucher
                            </button>
                        @else
                            <span style="color: var(--text-muted); font-size: 11px;">Sin imagen</span>
                        @endif
                    </td>
                    <td>
                        @if($payment->status === 'pending')
                            <span class="badge badge-pending">PENDIENTE</span>
                        @elseif($payment->status === 'approved')
                            <span class="badge badge-approved">APROBADO</span>
                        @else
                            <span class="badge badge-rejected" title="{{ $payment->rejection_reason }}">RECHAZADO</span>
                        @endif
                    </td>
                    <td>
                        @if($payment->status === 'pending')
                            <div style="display: flex; gap: 8px;">
                                <form action="{{ route('admin.payments.approve', $payment) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm" style="background: var(--success); border-color: var(--success);" onclick="return confirm('¿Aprobar pago y habilitar membresía?')">
                                        <i class="fa-solid fa-check"></i>
                                    </button>
                                </form>

                                <button type="button" class="btn btn-secondary btn-sm" style="color: var(--danger); border-color: rgba(239, 68, 68, 0.3);" onclick="rejectPayment({{ $payment->id }})">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>
                        @else
                            <span style="font-size: 11px; color: var(--text-muted);">Procesado por Admin</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 40px;">
                        No se encontraron pagos con los filtros seleccionados.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top: 16px;">
    {{ $payments->links() }}
</div>

<!-- Modal de Váucher -->
<div id="voucherModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); z-index: 9999; backdrop-filter: blur(5px); justify-content: center; align-items: center; padding: 20px;">
    <div style="background: var(--bg-card); border: 1px solid var(--primary); border-radius: 12px; width: 100%; max-width: 500px; position: relative; padding: 20px; text-align: center;">
        <button type="button" onclick="document.getElementById('voucherModal').style.display = 'none'" style="position: absolute; top: 10px; right: 15px; background: transparent; border: none; color: #fff; font-size: 24px; cursor: pointer;">&times;</button>
        <h3 style="margin-bottom: 16px; font-size: 18px; font-weight: 700;">Comprobante de Pago</h3>
        <img id="voucherImage" src="" alt="Váucher" style="max-width: 100%; max-height: 70vh; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.5); object-fit: contain;">
        <div style="margin-top: 16px;">
            <a id="voucherDownloadLink" href="" download class="btn btn-primary btn-sm">
                <i class="fa-solid fa-download"></i> Descargar
            </a>
        </div>
    </div>
</div>

<!-- Script y Formulario Oculto para Rechazo -->
<form id="rejectForm" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="rejection_reason" id="rejection_reason">
</form>

<script>
    function rejectPayment(id) {
        let reason = prompt('Motivo del rechazo:');
        if (reason) {
            let form = document.getElementById('rejectForm');
            form.action = '/admin/pagos/' + id + '/rechazar';
            document.getElementById('rejection_reason').value = reason;
            form.submit();
        }
    }

    function showVoucherModal(url) {
        document.getElementById('voucherImage').src = url;
        document.getElementById('voucherDownloadLink').href = url;
        document.getElementById('voucherModal').style.display = 'flex';
    }
</script>
@endsection