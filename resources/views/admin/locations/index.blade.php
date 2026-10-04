@extends('layouts.admin')

@section('page_title', 'Gestión de Sedes y Locales Multi-Local')

@section('content')
<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
    <div>
        <h3 style="font-size: 20px; font-weight: 700;">Red de Locales de Bail Fitness</h3>
        <p style="font-size: 13px; color: var(--text-muted);">Administra todas las sedes activas e inactivas de tu negocio.</p>
    </div>
    <a href="{{ route('admin.locations.create') }}" class="btn btn-primary">
        <span>➕</span> Nueva Sede
    </a>
</div>

<div class="grid-cards" style="grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));">
    @forelse($locations as $location)
        <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 20px; padding: 24px; position: relative; display: flex; flex-direction: column; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                    <span class="badge {{ $location->is_active ? 'badge-active' : 'badge-suspended' }}">
                        {{ $location->is_active ? 'Activo' : 'Inactivo' }}
                    </span>
                    <div style="font-size: 12px; color: var(--text-muted);">ID: #{{ $location->id }}</div>
                </div>

                <h3 style="font-size: 20px; font-weight: 800; margin-bottom: 6px; color: #fff;">{{ $location->name }}</h3>
                <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 14px;">📍 {{ $location->address ?? 'Sin dirección registrada' }}</p>

                <div style="background: rgba(10, 13, 22, 0.6); padding: 12px 16px; border-radius: 12px; margin-bottom: 16px; font-size: 13px; display: flex; flex-direction: column; gap: 6px;">
                    <div><strong>📞 Teléfono:</strong> {{ $location->phone ?? 'N/A' }}</div>
                    <div><strong>🕒 Horario:</strong> {{ $location->schedule ?? 'N/A' }}</div>
                </div>

                <div style="display: flex; gap: 16px; margin-bottom: 16px; font-size: 13px;">
                    <div><strong>{{ $location->students_count }}</strong> <span style="color: var(--text-muted);">Alumnos</span></div>
                    <div><strong>{{ $location->memberships_count }}</strong> <span style="color: var(--text-muted);">Membresías</span></div>
                    <div><strong>{{ $location->payments_count }}</strong> <span style="color: var(--text-muted);">Pagos</span></div>
                </div>
            </div>

            <div style="display: flex; gap: 10px; border-top: 1px solid var(--border-color); padding-top: 16px;">
                <a href="{{ route('admin.locations.edit', $location) }}" class="btn btn-secondary btn-sm" style="flex: 1;">
                    ✏️ Editar
                </a>
                <form action="{{ route('admin.locations.toggle', $location) }}" method="POST" style="flex: 1;">
                    @csrf
                    <button type="submit" class="btn {{ $location->is_active ? 'btn-danger' : 'btn-success' }} btn-sm" style="width: 100%;">
                        {{ $location->is_active ? 'Desactivar' : 'Activar' }}
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div style="grid-column: 1 / -1; text-align: center; color: var(--text-muted); padding: 40px;">
            No existen sedes creadas aún. Haz clic en "Nueva Sede" para agregar tu primer local.
        </div>
    @endforelse
</div>
@endsection
