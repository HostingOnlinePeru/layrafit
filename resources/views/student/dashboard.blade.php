@extends('layouts.student')

@section('title', 'Inicio — Layra Fit')

@section('content')
<!-- Saludo Personalizado -->
<div style="margin-bottom: 20px;">
    <h2 style="font-size: 24px; font-weight: 800;">Hola, {{ $user->name }} 👋</h2>
    <p style="font-size: 13px; color: var(--text-muted);">¡Bienvenida a tu panel de Bail Fitness!</p>
</div>

<!-- Banner de Membresía Actual / Botón Empezar Mis Rutinas -->
<div style="background: linear-gradient(135deg, rgba(81, 32, 29, 0.65), rgba(42, 19, 17, 0.85)); border: 1px solid var(--border-color); border-radius: 20px; padding: 22px; margin-bottom: 24px; backdrop-filter: blur(16px); box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35);">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
        <span style="font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: var(--accent);">MI PLAN ACTUAL</span>
        @if($activeMembership)
            <span class="badge badge-active">Activa</span>
        @else
            <span class="badge badge-expired">Sin Membresía</span>
        @endif
    </div>

    @if($activeMembership)
        <h3 style="font-size: 24px; font-weight: 800; color: #fff; margin-bottom: 6px;">{{ $activeMembership->membershipPlan->name }}</h3>
        <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 14px;">
            <i class="fa-solid fa-calendar-check" style="color: var(--accent); margin-right: 4px;"></i> Vence el: <strong style="color: #fff;">{{ $activeMembership->end_date->format('d/m/Y') }}</strong>
        </p>

        <!-- Indicador de Clases Semanales Usadas -->
        <div style="background: rgba(10, 13, 22, 0.6); padding: 14px; border-radius: 14px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 12px; color: var(--text-muted);">Clases esta semana:</div>
                <div style="font-size: 18px; font-weight: 800; color: #fff;">
                    {{ $weeklyUsedClasses }} / {{ $weeklyLimit }} <span style="font-size: 12px; font-weight: 500; color: var(--text-muted);">usadas</span>
                </div>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 12px; color: var(--text-muted);">Disponibles:</div>
                <div style="font-size: 20px; font-weight: 800; color: var(--accent);">{{ $weeklyRemainingClasses }}</div>
            </div>
        </div>
    @else
        <div style="margin: 10px 0 16px;">
            <h4 style="font-size: 17px; font-weight: 800; color: #fff;">Activa tu membresía para comenzar tus rutinas</h4>
            <p style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">¡Disfruta de nuestras clases de Zumba®, Strong Nation, Funcional y más...!</p>
        </div>
        <a href="{{ route('student.payments') }}" class="btn btn-primary" style="width: 100%; padding: 14px; font-size: 15px; gap: 8px;">
            <i class="fa-solid fa-fire"></i> EMPEZAR MIS RUTINAS
        </a>
    @endif
</div>

<!-- Novedades Recientes -->
<div style="margin-bottom: 28px;">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px;">
        <h3 style="font-size: 17px; font-weight: 800; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-bullhorn" style="color: var(--primary);"></i> Novedades y Avisos
        </h3>
    </div>

    <div style="display: flex; flex-direction: column; gap: 12px;">
        @forelse($latestNews as $news)
            <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 16px;">
                <div style="font-size: 11px; color: var(--primary); font-weight: 700; margin-bottom: 4px;">
                    {{ $news->published_at->format('d/m/Y') }}
                </div>
                <h4 style="font-size: 15px; font-weight: 700; color: #fff; margin-bottom: 6px;">{{ $news->title }}</h4>
                <p style="font-size: 13px; color: var(--text-muted); line-height: 1.5;">{{ $news->description }}</p>
            </div>
        @empty
            <div style="text-align: center; color: var(--text-muted); padding: 20px; font-size: 13px;">
                No hay novedades recientes publicadas.
            </div>
        @endforelse
    </div>
</div>

<!-- Accesos Rápidos -->
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
    <a href="{{ route('student.routines') }}" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 18px; display: flex; flex-direction: column; gap: 8px;">
        <div style="font-size: 24px; color: var(--accent);"><i class="fa-solid fa-person-running"></i></div>
        <div style="font-size: 15px; font-weight: 700; color: #fff;">Ver Rutinas</div>
        <div style="font-size: 12px; color: var(--text-muted);">Clases de baile disponibles</div>
    </a>

    <a href="{{ route('student.calendar') }}" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 18px; display: flex; flex-direction: column; gap: 8px;">
        <div style="font-size: 24px; color: var(--accent);"><i class="fa-solid fa-calendar-days"></i></div>
        <div style="font-size: 15px; font-weight: 700; color: #fff;">Mi Calendario</div>
        <div style="font-size: 12px; color: var(--text-muted);">Mis asistencias tomadas</div>
    </a>
</div>
@endsection
