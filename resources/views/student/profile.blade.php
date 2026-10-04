@extends('layouts.student')

@section('title', 'Mi Perfil — Layra Fit')

@section('content')
<div style="margin-bottom: 20px;">
    <h2 style="font-size: 22px; font-weight: 800;">Mi Perfil de Alumna</h2>
    <p style="font-size: 13px; color: var(--text-muted);">Consulta tus datos de acceso y tu participación en sorteos.</p>
</div>

<!-- Card de Sorteo y Elegibilidad -->
<div style="background: linear-gradient(135deg, rgba(81, 32, 29, 0.4), rgba(42, 19, 17, 0.8)); border: 1px solid var(--border-color); border-radius: 20px; padding: 20px; margin-bottom: 16px;">
    <div style="display: flex; align-items: center; gap: 14px;">
        <div style="font-size: 30px; color: var(--accent);"><i class="fa-solid fa-gift"></i></div>
        <div>
            <h4 style="font-size: 16px; font-weight: 800; color: #fff;">Sorteo por Pagos Consecutivos</h4>
            @if($student->raffleEligibility && $student->raffleEligibility->is_eligible)
                <span class="badge badge-active" style="margin-top: 4px; gap: 4px;">
                    <i class="fa-solid fa-trophy"></i> ¡Eres Elegible para Sorteo! (6+ meses)
                </span>
            @else
                <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">
                    Meses consecutivos acumulados: <strong style="color: var(--accent);">{{ $student->raffleEligibility->consecutive_months_count ?? 0 }} / 6 meses</strong>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Botón de Pagos e Historial -->
<a href="{{ route('student.payments') }}" style="display: flex; align-items: center; justify-content: space-between; background: var(--bg-card); border: 1px solid var(--primary); border-radius: 20px; padding: 18px 20px; margin-bottom: 24px; text-decoration: none; color: #fff; box-shadow: 0 4px 15px rgba(239, 68, 68, 0.15);">
    <div style="display: flex; align-items: center; gap: 14px;">
        <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(239, 68, 68, 0.2); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 20px;">
            <i class="fa-solid fa-file-invoice-dollar"></i>
        </div>
        <div>
            <h4 style="font-size: 16px; font-weight: 800; margin: 0;">Mi Membresía y Pagos</h4>
            <div style="font-size: 12px; color: var(--text-muted); margin-top: 2px;">Reporta tus pagos y renueva tu plan</div>
        </div>
    </div>
    <i class="fa-solid fa-chevron-right" style="color: var(--primary);"></i>
</a>

<!-- Formulario de Datos Personales y Clave -->
<div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 20px; padding: 22px;">
    <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px;">Actualizar Mis Datos</h3>

    @if($errors->any())
        <div class="alert alert-danger" style="font-size: 13px;">
            <span>⚠️</span>
            <div>
                <ul style="margin: 0; padding-left: 16px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success" style="font-size: 13px;">
            <span>✅</span>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    <form method="POST" action="{{ route('student.profile.update') }}">
        @csrf
        
        <div class="form-group">
            <label class="form-label">Teléfono Móvil</label>
            <input type="text" class="form-control" value="{{ $user->phone }}" disabled style="background: rgba(0,0,0,0.2); color: var(--text-muted);">
            <div style="font-size: 11px; color: var(--text-muted); margin-top: 4px;">Tu teléfono es tu usuario. Si necesitas cambiarlo comunícate con soporte.</div>
        </div>

        <div class="form-group" style="margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--border-color);">
            <h4 style="font-size: 14px; font-weight: 700; margin-bottom: 14px; color: var(--primary);">Cambiar Contraseña</h4>
            
            <label class="form-label">Contraseña Actual</label>
            <input type="password" name="current_password" class="form-control" placeholder="••••••••">
        </div>

        <div class="form-group">
            <label class="form-label">Nueva Contraseña</label>
            <input type="password" name="new_password" class="form-control" placeholder="Mínimo 6 caracteres">
        </div>

        <div class="form-group">
            <label class="form-label">Confirmar Nueva Contraseña</label>
            <input type="password" name="new_password_confirmation" class="form-control" placeholder="Repite tu contraseña">
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px;">Guardar Cambios</button>
    </form>
</div>
@endsection