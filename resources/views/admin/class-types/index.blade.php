@extends('layouts.admin')

@section('page_title', 'Tipos de Clases y Disciplinas')

@section('content')
<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
    <div>
        <h3 style="font-size: 20px; font-weight: 700;">Disciplinas y Tipos de Clases</h3>
        <p style="font-size: 13px; color: var(--text-muted);">Crea y administra los tipos de clases que impartes en tus sedes (Ej: Zumba®, Strong Nation, Cardio Dance).</p>
    </div>
    <a href="{{ route('admin.class-types.create') }}" class="btn btn-primary">
        <span>➕</span> Nuevo Tipo de Clase
    </a>
</div>

<div class="grid-cards" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));">
    @forelse($classTypes as $ct)
        <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 20px; padding: 22px; position: relative;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                <span class="badge" style="background: {{ $ct->color_code }}33; color: {{ $ct->color_code }}; border: 1px solid {{ $ct->color_code }}66;">
                    {{ $ct->is_active ? 'Activo' : 'Inactivo' }}
                </span>
                <span style="font-size: 12px; color: var(--text-muted);">{{ $ct->class_schedules_count }} Horarios</span>
            </div>

            <h3 style="font-size: 22px; font-weight: 800; color: #fff; margin-bottom: 6px;">{{ $ct->name }}</h3>
            <p style="font-size: 13px; color: var(--text-muted); line-height: 1.5; margin-bottom: 16px;">{{ $ct->description }}</p>

            <div style="display: flex; justify-content: flex-end;">
                <a href="{{ route('admin.class-types.edit', $ct) }}" class="btn btn-secondary btn-sm">✏️ Editar</a>
            </div>
        </div>
    @empty
        <div style="grid-column: 1 / -1; text-align: center; color: var(--text-muted); padding: 40px;">
            No hay tipos de clases registrados.
        </div>
    @endforelse
</div>
@endsection
