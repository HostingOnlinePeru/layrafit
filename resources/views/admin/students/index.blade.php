@extends('layouts.admin')

@section('page_title', 'Gestión de Alumnas')

@section('content')
<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h3 style="font-size: 20px; font-weight: 700;">Directorio de Alumnas</h3>
        <p style="font-size: 13px; color: var(--text-muted);">Administra la lista de alumnas registradas, sus sedes y estados de cuenta.</p>
    </div>
    <a href="{{ route('admin.students.create') }}" class="btn btn-primary">
        <span>➕</span> Registrar Alumna Manualmente
    </a>
</div>

<!-- Filtros de Búsqueda -->
<div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 18px 20px; margin-bottom: 24px;">
    <form action="{{ route('admin.students.index') }}" method="GET" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; align-items: end;">
        <div>
            <label class="form-label" style="font-size: 12px;">Buscar por Teléfono o Nombre</label>
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Ej. 912345678 o María">
        </div>

        <div>
            <label class="form-label" style="font-size: 12px;">Filtrar por Sede</label>
            <select name="location_id" class="form-control" style="background: #111422; color: #fff;">
                <option value="">-- Todas las Sedes --</option>
                @foreach($locations as $loc)
                    <option value="{{ $loc->id }}" {{ request('location_id') == $loc->id ? 'selected' : '' }}>
                        {{ $loc->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="form-label" style="font-size: 12px;">Filtrar por Estado</label>
            <select name="status" class="form-control" style="background: #111422; color: #fff;">
                <option value="">-- Todos los Estados --</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Activas</option>
                <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspendidas</option>
            </select>
        </div>

        <div style="display: flex; gap: 8px;">
            <button type="submit" class="btn btn-secondary" style="flex: 1;">Filtrar</button>
            <a href="{{ route('admin.students.index') }}" class="btn btn-secondary" style="padding: 10px 14px;">Limpiar</a>
        </div>
    </form>
</div>

<!-- Tabla de Alumnas -->
<div class="table-container">
    <table class="custom-table">
        <thead>
            <tr>
                <th>Teléfono / Alumna</th>
                <th>Sede Asignada</th>
                <th>Membresía Actual</th>
                <th>Fecha Registro</th>
                <th>Acceso</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $student)
                <tr>
                    <td>
                        <div style="font-weight: 700; color: #fff;">{{ $student->user->full_name }}</div>
                        <div style="font-size: 13px; color: var(--accent); font-weight: 600;">📱 {{ $student->user->phone }}</div>
                        @if($student->user->email)
                            <div style="font-size: 12px; color: var(--text-muted);">✉️ {{ $student->user->email }}</div>
                        @endif
                    </td>
                    <td>
                        <span style="font-weight: 600; color: #fff;">🏢 {{ $student->location->name }}</span>
                    </td>
                    <td>
                        @if($student->activeMembership)
                            <span class="badge badge-active">⭐ {{ $student->activeMembership->membershipPlan->name }}</span>
                            <div style="font-size: 11px; color: var(--text-muted); margin-top: 4px;">
                                Vence: {{ $student->activeMembership->end_date->format('d/m/Y') }}
                            </div>
                        @else
                            <span class="badge badge-expired">Sin Membresía Activa</span>
                        @endif
                    </td>
                    <td>{{ $student->registration_date ? $student->registration_date->format('d/m/Y') : $student->created_at->format('d/m/Y') }}</td>
                    <td>
                        @if($student->user->is_active)
                            <span class="badge badge-approved">Activo</span>
                        @else
                            <span class="badge badge-suspended">Suspendido</span>
                        @endif
                    </td>
                    <td>
                        <div style="display: flex; gap: 6px;">
                            <a href="{{ route('admin.students.show', $student) }}" class="btn btn-secondary btn-sm">👁️ Ver</a>
                            <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-secondary btn-sm">✏️ Edit</a>
                            <form action="{{ route('admin.students.toggle', $student) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn {{ $student->user->is_active ? 'btn-danger' : 'btn-success' }} btn-sm">
                                    {{ $student->user->is_active ? 'Suspender' : 'Activar' }}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 40px;">
                        No se encontraron alumnas registradas con los criterios seleccionados.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top: 16px;">
    {{ $students->links() }}
</div>
@endsection
