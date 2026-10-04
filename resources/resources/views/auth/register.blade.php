@extends('layouts.app')

@section('title', 'Registro de Alumna — Layra Fit')

@section('body')
<div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 24px 16px; background: radial-gradient(circle at top left, rgba(255, 0, 127, 0.15), transparent 50%), radial-gradient(circle at bottom right, rgba(121, 40, 202, 0.15), transparent 50%), var(--bg-dark);">
    <div style="max-width: 460px; width: 100%; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 24px; padding: 32px 24px; backdrop-filter: blur(20px); box-shadow: 0 20px 50px rgba(0,0,0,0.5);">
        
        <div style="text-align: center; margin-bottom: 24px;">
            <div class="brand-logo" style="width: 48px; height: 48px; font-size: 20px; margin: 0 auto 10px;">LF</div>
            <h2 class="brand-name" style="font-size: 24px; margin-bottom: 4px;">Crear Cuenta de Alumna</h2>
            <p style="color: var(--text-muted); font-size: 13px;">Únete a nuestras clases de Bail Fitness</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <span>⚠️</span>
                <div>
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        <form action="{{ route('register.perform') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label">Sede / Local al que asistirás *</label>
                <select name="location_id" class="form-control" required style="background: #111422; color: #fff;">
                    <option value="">-- Selecciona tu local --</option>
                    @foreach($locations as $loc)
                        <option value="{{ $loc->id }}" {{ old('location_id') == $loc->id ? 'selected' : '' }}>
                            {{ $loc->name }} — {{ $loc->address }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Número de Teléfono (Será tu ID de acceso) *</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" placeholder="Ej. 987654321" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div class="form-group">
                    <label class="form-label">Nombre *</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="María" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Apellido *</label>
                    <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control" placeholder="González" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Correo Electrónico (Opcional)</label>
                <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="maria@ejemplo.com">
            </div>

            <div class="form-group">
                <label class="form-label">Contraseña *</label>
                <input type="password" name="password" class="form-control" placeholder="Mínimo 6 caracteres" required>
            </div>

            <div class="form-group">
                <label class="form-label">Confirmar Contraseña *</label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="Repite tu contraseña" required>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 14px; font-size: 15px; margin-top: 10px;">
                Completar Registro
            </button>
        </form>

        <div style="text-align: center; margin-top: 20px; font-size: 14px; color: var(--text-muted);">
            ¿Ya tienes cuenta? 
            <a href="{{ route('login') }}" style="color: var(--primary); font-weight: 600;">Inicia sesión aquí</a>
        </div>
    </div>
</div>
@endsection
