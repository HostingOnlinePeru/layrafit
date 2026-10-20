@extends('layouts.superadmin')

@section('page_title', 'Editar Membresía — ' . $plan->name)

@section('content')
<div style="max-width: 760px; margin: 0 auto; display: flex; flex-direction: column; gap: 20px;">

    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2 style="font-size: 20px; font-weight: 800; color: #fff; margin: 0;">Editar Plan: {{ $plan->name }}</h2>
            <p style="color: var(--text-muted); font-size: 13px; margin: 4px 0 0 0;">Modifica el precio, duración, beneficios o estado del plan.</p>
        </div>
        <a href="{{ route('superadmin.plans.index') }}" class="btn btn-secondary" style="font-weight: 700;">
            <i class="fa-solid fa-arrow-left"></i> Volver a Planes
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger" style="background: rgba(239, 68, 68, 0.15); border: 1px solid #ef4444; color: #fca5a5; padding: 14px; border-radius: 10px; font-size: 13px;">
            <div style="font-weight: 700; margin-bottom: 6px;"><i class="fa-solid fa-triangle-exclamation"></i> Por favor corrige los errores:</div>
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 28px;">
        <form method="POST" action="{{ route('superadmin.plans.update', $plan) }}">
            @csrf
            @method('PUT')

            <!-- Nombre de la membresía -->
            <div class="form-group" style="margin-bottom: 18px;">
                <label style="display: block; font-size: 13px; font-weight: 700; color: #fff; margin-bottom: 6px;">
                    Nombre del Plan <span style="color: #ef4444;">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name', $plan->name) }}" required class="form-control" style="width: 100%; background: rgba(0,0,0,0.4); border: 1px solid var(--border-color); border-radius: 8px; color: #fff; padding: 12px 14px; font-size: 14px;">
            </div>

            <!-- Duración y Precio -->
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; margin-bottom: 18px;">
                <div class="form-group">
                    <label style="display: block; font-size: 13px; font-weight: 700; color: #fff; margin-bottom: 6px;">
                        Duración (Meses) <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="number" name="duration_months" value="{{ old('duration_months', $plan->duration_months) }}" min="1" max="120" required class="form-control" style="width: 100%; background: rgba(0,0,0,0.4); border: 1px solid var(--border-color); border-radius: 8px; color: #fff; padding: 12px 14px; font-size: 14px;">
                </div>

                <div class="form-group">
                    <label style="display: block; font-size: 13px; font-weight: 700; color: #fff; margin-bottom: 6px;">
                        Precio Total <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="number" step="0.01" name="price" value="{{ old('price', $plan->price) }}" required class="form-control" style="width: 100%; background: rgba(0,0,0,0.4); border: 1px solid var(--border-color); border-radius: 8px; color: #fff; padding: 12px 14px; font-size: 14px;">
                </div>

                <div class="form-group">
                    <label style="display: block; font-size: 13px; font-weight: 700; color: #fff; margin-bottom: 6px;">
                        Moneda <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" name="currency" value="{{ old('currency', $plan->currency) }}" required class="form-control" style="width: 100%; background: rgba(0,0,0,0.4); border: 1px solid var(--border-color); border-radius: 8px; color: #fff; padding: 12px 14px; font-size: 14px; text-transform: uppercase;">
                </div>
            </div>

            <!-- Insignia y Orden -->
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 16px; margin-bottom: 18px;">
                <div class="form-group">
                    <label style="display: block; font-size: 13px; font-weight: 700; color: #fff; margin-bottom: 6px;">
                        Insignia / Etiqueta Destacada (Opcional)
                    </label>
                    <input type="text" name="badge" value="{{ old('badge', $plan->badge) }}" placeholder="Ej: Más Popular, Mejor Ahorro..." class="form-control" style="width: 100%; background: rgba(0,0,0,0.4); border: 1px solid var(--border-color); border-radius: 8px; color: #fff; padding: 12px 14px; font-size: 14px;">
                </div>

                <div class="form-group">
                    <label style="display: block; font-size: 13px; font-weight: 700; color: #fff; margin-bottom: 6px;">
                        Orden de Muestra
                    </label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $plan->sort_order) }}" class="form-control" style="width: 100%; background: rgba(0,0,0,0.4); border: 1px solid var(--border-color); border-radius: 8px; color: #fff; padding: 12px 14px; font-size: 14px;">
                </div>
            </div>

            <!-- Descripción -->
            <div class="form-group" style="margin-bottom: 18px;">
                <label style="display: block; font-size: 13px; font-weight: 700; color: #fff; margin-bottom: 6px;">
                    Descripción Corta
                </label>
                <textarea name="description" rows="2" class="form-control" style="width: 100%; background: rgba(0,0,0,0.4); border: 1px solid var(--border-color); border-radius: 8px; color: #fff; padding: 10px 14px; font-size: 13px;">{{ old('description', $plan->description) }}</textarea>
            </div>

            <!-- Beneficios / Características -->
            <div class="form-group" style="margin-bottom: 24px;">
                <label style="display: block; font-size: 13px; font-weight: 700; color: #fff; margin-bottom: 6px;">
                    Características y Beneficios (Uno por cada línea)
                </label>
                <textarea name="features" rows="5" class="form-control" style="width: 100%; background: rgba(0,0,0,0.4); border: 1px solid var(--border-color); border-radius: 8px; color: #fff; padding: 12px 14px; font-size: 13px; line-height: 1.5;">{{ old('features', $plan->features) }}</textarea>
                <span style="font-size: 11px; color: var(--text-muted); margin-top: 4px; display: block;">Escribe cada beneficio en un renglón separado con Enter.</span>
            </div>

            <!-- Estado -->
            <div style="margin-bottom: 24px; background: rgba(0,0,0,0.2); padding: 14px; border-radius: 10px; border: 1px solid var(--border-color);">
                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; color: #fff; font-size: 14px; font-weight: 700;">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $plan->is_active) ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: #3b82f6;">
                    <span>Plan activo y disponible para contratación</span>
                </label>
            </div>

            <!-- Botones -->
            <div style="display: flex; gap: 12px; justify-content: flex-end; border-top: 1px solid var(--border-color); padding-top: 20px;">
                <a href="{{ route('superadmin.plans.index') }}" class="btn btn-secondary" style="font-weight: 700; padding: 12px 20px;">
                    Cancelar
                </a>
                <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, #06b6d4, #3b82f6); border: none; font-weight: 800; padding: 12px 28px; box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);">
                    <i class="fa-solid fa-save"></i> Guardar Cambios
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
