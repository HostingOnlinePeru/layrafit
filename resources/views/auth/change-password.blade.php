@extends('layouts.app')

@section('title', 'Cambiar Contraseña — Layra Fit')

@section('body')
<div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; background: var(--bg-dark);">
    <div style="max-width: 420px; width: 100%; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 24px; padding: 32px 24px; backdrop-filter: blur(20px);">
        
        <div style="text-align: center; margin-bottom: 24px;">
            <div style="font-size: 36px; margin-bottom: 8px;">🔐</div>
            <h3 style="font-size: 22px; font-weight: 700;">Cambiar Contraseña Temporal</h3>
            <p style="color: var(--text-muted); font-size: 13px; margin-top: 4px;">Establece tu nueva contraseña personal antes de ingresar a la plataforma.</p>
        </div>

        @if(session('warning'))
            <div class="alert alert-danger" style="font-size: 13px;">
                <span>⚠️</span> {{ session('warning') }}
            </div>
        @endif

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

        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Nueva Contraseña *</label>
                <input type="password" name="password" class="form-control" placeholder="Mínimo 6 caracteres" required autofocus>
            </div>

            <div class="form-group">
                <label class="form-label">Confirmar Nueva Contraseña *</label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="Repite tu contraseña" required>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; margin-top: 8px;">
                Actualizar Contraseña y Continuar
            </button>
        </form>
    </div>
</div>
@endsection
