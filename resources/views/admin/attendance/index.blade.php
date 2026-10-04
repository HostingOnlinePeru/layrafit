@extends('layouts.admin')

@section('page_title', 'Toma de Asistencias — Layra Fit')

@section('content')
<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h3 style="font-size: 20px; font-weight: 700;">Registro Estricto de Asistencias</h3>
        <p style="font-size: 13px; color: var(--text-muted);">Solo el Administrador puede registrar la asistencia diaria de las alumnas.</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px;">
    <!-- Formulario Toma Rápida de Asistencia -->
    <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 20px; padding: 24px; height: fit-content;">
        <h4 style="font-weight: 700; font-size: 16px; margin-bottom: 16px; border-bottom: 1px solid var(--border-color); padding-bottom: 10px;">📋 Registrar Nueva Asistencia</h4>

        @if($errors->any())
            <div class="alert alert-danger" style="font-size: 13px;">
                <span>⚠️</span>
                <div>
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        <form action="{{ route('admin.attendance.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label">Seleccionar Alumna *</label>
                <select name="student_id" class="form-control" required style="background: #111422; color: #fff;">
                    <option value="">-- Elige una alumna --</option>
                    @foreach($students as $st)
                        <option value="{{ $st->id }}">
                            {{ $st->user->full_name }} (📱 {{ $st->user->phone }}) — {{ $st->location->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Sede / Local *</label>
                <select name="location_id" class="form-control" required style="background: #111422; color: #fff;">
                    @foreach($locations as $loc)
                        <option value="{{ $loc->id }}" {{ $locationId == $loc->id ? 'selected' : '' }}>
                            {{ $loc->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Rutina / Clase de Baile (Opcional)</label>
                <select name="dance_class_id" class="form-control" style="background: #111422; color: #fff;">
                    <option value="">-- Clase Regular --</option>
                    @foreach($danceClasses as $dc)
                        <option value="{{ $dc->id }}">{{ $dc->name }} ({{ $dc->level }})</option>
                    @endforeach
                </select>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                <div class="form-group">
                    <label class="form-label">Fecha *</label>
                    <input type="date" name="date" value="{{ old('date', now()->format('Y-m-d')) }}" class="form-control" required style="background: #111422; color: #fff;">
                </div>
                <div class="form-group">
                    <label class="form-label">Hora *</label>
                    <input type="time" name="time" value="{{ old('time', now()->format('H:i')) }}" class="form-control" required style="background: #111422; color: #fff;">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Observación / Notas</label>
                <input type="text" name="notes" value="{{ old('notes') }}" class="form-control" placeholder="Ej. Asistió con pase promocional">
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px;">
                ✓ Registrar Asistencia
            </button>
        </form>
    </div>

    <!-- Lista de Asistencias del Día / Filtro -->
    <div>
        <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 16px 20px; margin-bottom: 20px;">
            <form action="{{ route('admin.attendance.index') }}" method="GET" style="display: flex; gap: 12px; align-items: flex-end;">
                <div style="flex: 1;">
                    <label class="form-label" style="font-size: 12px;">Filtrar por Fecha</label>
                    <input type="date" name="date" value="{{ $date }}" class="form-control" style="background: #111422; color: #fff;">
                </div>
                <div style="flex: 1;">
                    <label class="form-label" style="font-size: 12px;">Filtrar por Sede</label>
                    <select name="location_id" class="form-control" style="background: #111422; color: #fff;">
                        <option value="">-- Todas las Sedes --</option>
                        @foreach($locations as $loc)
                            <option value="{{ $loc->id }}" {{ $locationId == $loc->id ? 'selected' : '' }}>
                                {{ $loc->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-secondary">Filtrar</button>
            </form>
        </div>

        <div class="table-container">
            <div style="padding: 16px 20px; border-bottom: 1px solid var(--border-color); font-weight: 700; display: flex; align-items: center; justify-content: space-between;">
                <span>📋 Asistencias Registradas ({{ \Carbon\Carbon::parse($date)->format('d/m/Y') }})</span>
            </div>
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Alumna</th>
                        <th>Sede</th>
                        <th>Clase / Rutina</th>
                        <th>Hora</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $att)
                        <tr>
                            <td>
                                <div style="font-weight: 700; color: #fff;">{{ $att->student->user->full_name }}</div>
                                <div style="font-size: 12px; color: var(--accent);">📱 {{ $att->student->user->phone }}</div>
                            </td>
                            <td>{{ $att->location->name }}</td>
                            <td>{{ $att->danceClass->name ?? 'Clase Regular' }}</td>
                            <td style="font-weight: 700; color: var(--primary);">{{ $att->time }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 30px;">
                                No hay asistencias registradas para esta fecha y sede.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 14px;">
            {{ $attendances->links() }}
        </div>
    </div>
</div>
@endsection
