@extends('layouts.admin')

@section('page_title', 'Programación de Horarios y Días')

@section('content')
<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h3 style="font-size: 20px; font-weight: 700;">Horarios Semanales por Sede</h3>
        <p style="font-size: 13px; color: var(--text-muted);">Programa los días, horarios y disciplinas de baile impartidas en cada sede.</p>
    </div>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('admin.class-types.index') }}" class="btn btn-secondary">
            <span>🏷️</span> Gestionar Disciplinas (Zumba®, Strong)
        </a>
        <a href="{{ route('admin.class-schedules.create') }}" class="btn btn-primary">
            <span>➕</span> Programar Nuevo Horario
        </a>
    </div>
</div>

<!-- Filtro por Sede -->
<div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 18px 20px; margin-bottom: 24px;">
    <form action="{{ route('admin.class-schedules.index') }}" method="GET" style="display: flex; align-items: center; justify-content: space-between;">
        <div style="font-weight: 600; font-size: 14px;">Filtrar Horarios por Sede:</div>
        <select name="location_id" class="form-control" onchange="this.form.submit()" style="max-width: 300px; background: #111422; color: #fff;">
            <option value="">🏢 Todas las Sedes</option>
            @foreach($locations as $loc)
                <option value="{{ $loc->id }}" {{ $locationId == $loc->id ? 'selected' : '' }}>
                    {{ $loc->name }}
                </option>
            @endforeach
        </select>
    </form>
</div>

<div class="table-container">
    <table class="custom-table">
        <thead>
            <tr>
                <th>Sede / Local</th>
                <th>Día de la Semana</th>
                <th>Horario (Inicio - Fin)</th>
                <th>Disciplina / Clase</th>
                <th>Instructor / Coach</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($schedules as $sch)
                <tr>
                    <td style="font-weight: 700; color: #fff;">🏢 {{ $sch->location->name }}</td>
                    <td>
                        <span class="badge badge-active" style="background: rgba(255,0,127,0.15); color: var(--primary);">
                            🗓️ {{ $sch->formatted_day }}
                        </span>
                    </td>
                    <td style="font-weight: 700; color: var(--accent);">
                        ⏱️ {{ $sch->formatted_time }}
                    </td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="display: inline-block; width: 10px; height: 10px; border-radius: 50%; background: {{ $sch->classType->color_code }};"></span>
                            <span style="font-weight: 800; color: #fff;">{{ $sch->classType->name }}</span>
                        </div>
                    </td>
                    <td>{{ $sch->instructor_name ?? 'Por asignar' }}</td>
                    <td>
                        <form action="{{ route('admin.class-schedules.destroy', $sch) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar este horario programado?')">
                                🗑️ Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 40px;">
                        No existen horarios programados para el filtro seleccionado.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
