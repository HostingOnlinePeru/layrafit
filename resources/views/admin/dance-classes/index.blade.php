@extends('layouts.admin')

@section('page_title', 'Rutinas y Clases de Bail Fitness')

@section('content')
<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
    <div>
        <h3 style="font-size: 20px; font-weight: 700;">Catálogo de Clases</h3>
        <p style="font-size: 13px; color: var(--text-muted);">Administra las disciplinas y rutinas publicadas para las alumnas.</p>
    </div>
    <a href="{{ route('admin.dance-classes.create') }}" class="btn btn-primary">
        <span>➕</span> Nueva Rutina / Clase
    </a>
</div>

<div class="grid-cards" style="grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));">
    @forelse($danceClasses as $dc)
        <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 20px; padding: 22px;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <span class="badge {{ $dc->is_active ? 'badge-active' : 'badge-pending' }}">
                    {{ $dc->is_active ? 'Activa' : 'Inactiva' }} • Nivel {{ $dc->level }}
                </span>
                <span style="font-size: 12px; color: var(--text-muted);">⏱️ {{ $dc->duration_minutes }} min</span>
            </div>

            <h3 style="font-size: 18px; font-weight: 800; color: #fff; margin-bottom: 6px;">{{ $dc->name }}</h3>
            <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 12px; line-height: 1.5;">{{ $dc->description }}</p>

            <div style="font-size: 12px; color: var(--accent); margin-bottom: 14px;">
                🏢 Sede: {{ $dc->location->name ?? 'Todas las sedes' }}
            </div>

            <div style="display: flex; gap: 8px;">
                <a href="{{ route('admin.dance-classes.edit', $dc) }}" class="btn btn-primary btn-sm" style="flex: 1;">
                    <i class="fa-solid fa-pen-to-square"></i> Editar
                </a>
                
                @if($dc->video_url)
                    <a href="{{ $dc->video_url }}" target="_blank" class="btn btn-secondary btn-sm" style="flex: 1;">
                        <i class="fa-solid fa-play"></i> Ver
                    </a>
                @endif
            </div>
        </div>
    @empty
        <div style="grid-column: 1 / -1; text-align: center; color: var(--text-muted); padding: 40px;">
            No hay clases registradas aún. Haz clic en "Nueva Rutina" para agregar una.
        </div>
    @endforelse
</div>

<div style="margin-top: 16px;">
    {{ $danceClasses->links() }}
</div>
@endsection