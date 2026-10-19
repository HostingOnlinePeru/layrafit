@extends('layouts.superadmin')

@section('page_title', 'Editar Cuenta — ' . $tenant->name)

@section('content')
<div style="max-width: 800px; margin: 0 auto; display: flex; flex-direction: column; gap: 20px;">

    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2 style="font-size: 20px; font-weight: 800; color: #fff; margin: 0;">Editar Datos de la Cuenta</h2>
            <p style="color: var(--text-muted); font-size: 13px; margin: 4px 0 0 0;">Actualiza el nombre, titular o estado de la academia.</p>
        </div>
        <a href="{{ route('superadmin.tenants.show', $tenant) }}" class="btn btn-secondary" style="font-weight: 700;">
            <i class="fa-solid fa-arrow-left"></i> Volver a Detalles
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
        <form method="POST" action="{{ route('superadmin.tenants.update', $tenant) }}">
            @csrf
            @method('PUT')

            <div class="form-group" style="margin-bottom: 18px;">
                <label style="display: block; font-size: 13px; font-weight: 700; color: #fff; margin-bottom: 6px;">
                    Nombre de la Academia / Negocio <span style="color: #ef4444;">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name', $tenant->name) }}" required class="form-control" style="width: 100%; background: rgba(0,0,0,0.4); border: 1px solid var(--border-color); border-radius: 8px; color: #fff; padding: 12px 14px; font-size: 14px;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 18px;">
                <div class="form-group">
                    <label style="display: block; font-size: 13px; font-weight: 700; color: #fff; margin-bottom: 6px;">
                        Titular o Responsable <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" name="owner_name" value="{{ old('owner_name', $tenant->owner_name) }}" required class="form-control" style="width: 100%; background: rgba(0,0,0,0.4); border: 1px solid var(--border-color); border-radius: 8px; color: #fff; padding: 12px 14px; font-size: 14px;">
                </div>

                <div class="form-group">
                    <label style="display: block; font-size: 13px; font-weight: 700; color: #fff; margin-bottom: 6px;">
                        Teléfono de Contacto <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" name="owner_phone" value="{{ old('owner_phone', $tenant->owner_phone) }}" required class="form-control" style="width: 100%; background: rgba(0,0,0,0.4); border: 1px solid var(--border-color); border-radius: 8px; color: #fff; padding: 12px 14px; font-size: 14px;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 18px;">
                <div class="form-group">
                    <label style="display: block; font-size: 13px; font-weight: 700; color: #fff; margin-bottom: 6px;">
                        Correo Electrónico
                    </label>
                    <input type="email" name="owner_email" value="{{ old('owner_email', $tenant->owner_email) }}" class="form-control" style="width: 100%; background: rgba(0,0,0,0.4); border: 1px solid var(--border-color); border-radius: 8px; color: #fff; padding: 12px 14px; font-size: 14px;">
                </div>

                <div class="form-group">
                    <label style="display: block; font-size: 13px; font-weight: 700; color: #fff; margin-bottom: 6px;">
                        Estado de la Cuenta <span style="color: #ef4444;">*</span>
                    </label>
                    <select name="status" class="form-control" style="width: 100%; background: rgba(0,0,0,0.4); border: 1px solid var(--border-color); border-radius: 8px; color: #fff; padding: 12px 14px; font-size: 14px;">
                        <option value="active" {{ old('status', $tenant->status) === 'active' ? 'selected' : '' }}>Activa (Acceso Total)</option>
                        <option value="suspended" {{ old('status', $tenant->status) === 'suspended' ? 'selected' : '' }}>Suspendida (Bloqueada)</option>
                        <option value="expired" {{ old('status', $tenant->status) === 'expired' ? 'selected' : '' }}>Vencida (Expirada)</option>
                    </select>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 18px;">
                <label style="display: block; font-size: 13px; font-weight: 700; color: #fff; margin-bottom: 6px;">
                    Plan de Membresía Asignado <span style="color: #ef4444;">*</span>
                </label>
                <select name="plan_period" class="form-control" style="width: 100%; background: rgba(0,0,0,0.4); border: 1px solid var(--border-color); border-radius: 8px; color: #fff; padding: 12px 14px; font-size: 14px;">
                    <option value="6_months" {{ old('plan_period', $tenant->plan_period) === '6_months' ? 'selected' : '' }}>6 Meses ($30 USD)</option>
                    <option value="1_year" {{ old('plan_period', $tenant->plan_period) === '1_year' ? 'selected' : '' }}>1 Año ($50 USD)</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 24px;">
                <label style="display: block; font-size: 13px; font-weight: 700; color: #fff; margin-bottom: 6px;">
                    Notas y Observaciones
                </label>
                <textarea name="notes" rows="3" class="form-control" style="width: 100%; background: rgba(0,0,0,0.4); border: 1px solid var(--border-color); border-radius: 8px; color: #fff; padding: 10px 14px; font-size: 13px;">{{ old('notes', $tenant->notes) }}</textarea>
            </div>

            <div style="display: flex; gap: 12px; justify-content: flex-end; border-top: 1px solid var(--border-color); padding-top: 20px;">
                <a href="{{ route('superadmin.tenants.show', $tenant) }}" class="btn btn-secondary" style="font-weight: 700; padding: 12px 20px;">
                    Cancelar
                </a>
                <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, #06b6d4, #3b82f6); border: none; font-weight: 800; padding: 12px 28px;">
                    <i class="fa-solid fa-save"></i> Guardar Cambios
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
