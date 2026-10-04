@extends('layouts.admin')

@section('page_title', 'Sistema de Sorteos por Pagos Consecutivos')

@section('content')
<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h3 style="font-size: 20px; font-weight: 700;">Gestión de Sorteos y Fidelidad</h3>
        <p style="font-size: 13px; color: var(--text-muted);">Participan automáticamente las alumnas que pagaron 6 meses consecutivos sin quiebres.</p>
    </div>
    <a href="{{ route('admin.raffles.create') }}" class="btn btn-primary">
        <span>➕</span> Crear Nuevo Sorteo
    </a>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
    <!-- Lista de Sorteos Creados -->
    <div>
        <h4 style="font-size: 16px; font-weight: 700; margin-bottom: 16px;">Sorteos Registrados</h4>
        <div style="display: flex; flex-direction: column; gap: 16px;">
            @forelse($raffles as $raffle)
                <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 20px; padding: 20px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                        <span class="badge {{ $raffle->status === 'completed' ? 'badge-approved' : 'badge-pending' }}">
                            {{ $raffle->status === 'completed' ? 'Sorteo Realizado' : 'Activo' }}
                        </span>
                        <span style="font-size: 12px; color: var(--text-muted);">📅 Fecha: {{ $raffle->raffle_date->format('d/m/Y') }}</span>
                    </div>

                    <h4 style="font-size: 18px; font-weight: 800; color: #fff; margin-bottom: 4px;">{{ $raffle->title }}</h4>
                    <div style="font-size: 14px; color: var(--accent); font-weight: 700; margin-bottom: 10px;">🎁 Premio: {{ $raffle->prize }}</div>
                    <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 14px;">{{ $raffle->description }}</p>

                    @if($raffle->status === 'completed')
                        <div style="background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 12px; padding: 12px; font-size: 13px;">
                            🏆 <strong>Ganadora:</strong> {{ $raffle->winner->user->full_name ?? 'N/A' }} (📱 {{ $raffle->winner->user->phone ?? '' }})
                        </div>
                    @else
                        <form action="{{ route('admin.raffles.draw', $raffle) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-sm" style="width: 100%;" onclick="return confirm('¿Ejecutar sorteo aleatorio entre las alumnas elegibles?')">
                                🎲 Realizar Sorteo Aleatorio Ahora
                            </button>
                        </form>
                    @endif
                </div>
            @empty
                <div style="text-align: center; color: var(--text-muted); padding: 40px; background: var(--bg-card); border-radius: 16px;">
                    No existen sorteos registrados.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Lista de Alumnas Elegibles -->
    <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 20px; padding: 20px; height: fit-content;">
        <h4 style="font-weight: 700; font-size: 16px; margin-bottom: 12px;">🎉 Alumnas Elegibles (6+ Meses)</h4>
        <p style="font-size: 12px; color: var(--text-muted); margin-bottom: 16px;">Total de participantes habilitadas: <strong style="color: var(--accent);">{{ $eligibleStudents->count() }}</strong></p>

        <div style="display: flex; flex-direction: column; gap: 10px; max-height: 400px; overflow-y: auto;">
            @forelse($eligibleStudents as $el)
                <div style="background: rgba(10, 13, 22, 0.6); padding: 10px 14px; border-radius: 12px; font-size: 13px;">
                    <div style="font-weight: 700; color: #fff;">{{ $el->student->user->full_name }}</div>
                    <div style="font-size: 11px; color: var(--text-muted);">📱 {{ $el->student->user->phone }} | 🏢 {{ $el->student->location->name }}</div>
                    <div style="font-size: 11px; color: var(--success); font-weight: 600; margin-top: 2px;">
                        ✓ {{ $el->consecutive_months_count }} meses consecutivos pagados
                    </div>
                </div>
            @empty
                <div style="font-size: 12px; color: var(--text-muted); text-align: center; padding: 20px;">
                    Aún no hay alumnas con 6 meses consecutivos completados.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
