@extends('layouts.superadmin')

@section('page_title', 'Crear Nueva Cuenta — FitClass SaaS')

@section('content')
<div style="max-width: 800px; margin: 0 auto; display: flex; flex-direction: column; gap: 20px;">

    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2 style="font-size: 20px; font-weight: 800; color: #fff; margin: 0;">Registrar Nueva Academia o Gimnasio</h2>
            <p style="color: var(--text-muted); font-size: 13px; margin: 4px 0 0 0;">Crea una cuenta independiente con su propio administrador y membresía FitClass.</p>
        </div>
        <a href="{{ route('superadmin.tenants.index') }}" class="btn btn-secondary" style="font-weight: 700;">
            <i class="fa-solid fa-arrow-left"></i> Volver a la lista
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger" style="background: rgba(239, 68, 68, 0.15); border: 1px solid #ef4444; color: #fca5a5; padding: 14px; border-radius: 10px; font-size: 13px;">
            <div style="font-weight: 700; margin-bottom: 6px;"><i class="fa-solid fa-triangle-exclamation"></i> Por favor corrige los siguientes errores:</div>
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 28px;">
        <form method="POST" action="{{ route('superadmin.tenants.store') }}">
            @csrf

            <!-- Sección 1: Datos de la Academia -->
            <div style="margin-bottom: 24px; border-bottom: 1px solid var(--border-color); padding-bottom: 16px;">
                <h4 style="font-size: 15px; font-weight: 800; color: #38bdf8; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 14px;">
                    <i class="fa-solid fa-dumbbell" style="margin-right: 6px;"></i> 1. Información de la Academia o Negocio
                </h4>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 700; color: #fff; margin-bottom: 6px;">
                        Nombre de la Academia / Gimnasio <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Ej: Ritmo & Fitness Studio, Zumba Pro Center..." class="form-control" style="width: 100%; background: rgba(0,0,0,0.4); border: 1px solid var(--border-color); border-radius: 8px; color: #fff; padding: 12px 14px; font-size: 14px;">
                </div>
            </div>

            <!-- Sección 2: Administrador de la Cuenta -->
            <div style="margin-bottom: 24px; border-bottom: 1px solid var(--border-color); padding-bottom: 16px;">
                <h4 style="font-size: 15px; font-weight: 800; color: #38bdf8; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 14px;">
                    <i class="fa-solid fa-user-shield" style="margin-right: 6px;"></i> 2. Usuario Administrador de la Cuenta
                </h4>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div class="form-group">
                        <label style="display: block; font-size: 13px; font-weight: 700; color: #fff; margin-bottom: 6px;">
                            Nombre del Administrador <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="text" name="owner_name" value="{{ old('owner_name') }}" required placeholder="Ej: Carlos Mendoza" class="form-control" style="width: 100%; background: rgba(0,0,0,0.4); border: 1px solid var(--border-color); border-radius: 8px; color: #fff; padding: 12px 14px; font-size: 14px;">
                    </div>

                    <div class="form-group">
                        <label style="display: block; font-size: 13px; font-weight: 700; color: #fff; margin-bottom: 6px;">
                            Teléfono de Acceso (Usuario Login) <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="text" name="owner_phone" value="{{ old('owner_phone') }}" required placeholder="Ej: 987654321" class="form-control" style="width: 100%; background: rgba(0,0,0,0.4); border: 1px solid var(--border-color); border-radius: 8px; color: #fff; padding: 12px 14px; font-size: 14px;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="form-group">
                        <label style="display: block; font-size: 13px; font-weight: 700; color: #fff; margin-bottom: 6px;">
                            Contraseña de Acceso <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="password" name="admin_password" required placeholder="Mínimo 6 caracteres" class="form-control" style="width: 100%; background: rgba(0,0,0,0.4); border: 1px solid var(--border-color); border-radius: 8px; color: #fff; padding: 12px 14px; font-size: 14px;">
                    </div>

                    <div class="form-group">
                        <label style="display: block; font-size: 13px; font-weight: 700; color: #fff; margin-bottom: 6px;">
                            Correo Electrónico (Opcional)
                        </label>
                        <input type="email" name="owner_email" value="{{ old('owner_email') }}" placeholder="admin@academia.com" class="form-control" style="width: 100%; background: rgba(0,0,0,0.4); border: 1px solid var(--border-color); border-radius: 8px; color: #fff; padding: 12px 14px; font-size: 14px;">
                    </div>
                </div>
            </div>

            <!-- Sección 3: Membresía SaaS FitClass -->
            <div style="margin-bottom: 24px;">
                <h4 style="font-size: 15px; font-weight: 800; color: #38bdf8; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 14px;">
                    <i class="fa-solid fa-crown" style="margin-right: 6px; color: #f59e0b;"></i> 3. Plan de Membresía FitClass
                </h4>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; margin-bottom: 16px;">
                    @forelse($plans as $plan)
                        <label style="cursor: pointer; border: 2px solid {{ $plan->duration_months >= 12 ? 'rgba(16, 185, 129, 0.5)' : 'rgba(59, 130, 246, 0.4)' }}; border-radius: 12px; padding: 16px; background: {{ $plan->duration_months >= 12 ? 'rgba(6, 78, 59, 0.25)' : 'rgba(15, 23, 42, 0.6)' }}; display: flex; align-items: flex-start; gap: 12px; position: relative;">
                            @if($plan->badge)
                                <div style="position: absolute; top: -10px; right: 12px; background: {{ $plan->duration_months >= 12 ? '#10b981' : '#3b82f6' }}; color: #fff; font-size: 10px; font-weight: 800; padding: 2px 8px; border-radius: 10px;">
                                    {{ $plan->badge }}
                                </div>
                            @endif
                            <input type="radio" name="plan_id" value="{{ $plan->id }}" {{ $loop->first ? 'checked' : '' }} style="margin-top: 4px;">
                            <div>
                                <div style="font-size: 14px; font-weight: 800; color: #fff;">{{ $plan->name }}</div>
                                <div style="font-size: 22px; font-weight: 900; color: {{ $plan->duration_months >= 12 ? '#34d399' : '#38bdf8' }}; margin: 4px 0;">
                                    ${{ number_format($plan->price, 0) }} <span style="font-size: 12px; font-weight: 600; color: var(--text-muted);">{{ $plan->currency }}</span>
                                </div>
                                <div style="font-size: 11px; color: var(--text-muted);">
                                    {{ $plan->description ?? "Duración: {$plan->duration_months} meses" }}
                                </div>
                            </div>
                        </label>
                    @empty
                        <!-- Fallback si no hay planes en BD -->
                        <label style="cursor: pointer; border: 2px solid rgba(59, 130, 246, 0.3); border-radius: 12px; padding: 16px; background: rgba(15, 23, 42, 0.6); display: flex; align-items: flex-start; gap: 12px;">
                            <input type="radio" name="plan_period" value="6_months" checked style="margin-top: 4px;">
                            <div>
                                <div style="font-size: 14px; font-weight: 800; color: #fff;">Plan Semestral (6 Meses)</div>
                                <div style="font-size: 22px; font-weight: 900; color: #38bdf8; margin: 4px 0;">$30 <span style="font-size: 12px; font-weight: 600; color: var(--text-muted);">USD</span></div>
                            </div>
                        </label>
                        <label style="cursor: pointer; border: 2px solid rgba(16, 185, 129, 0.4); border-radius: 12px; padding: 16px; background: rgba(6, 78, 59, 0.2); display: flex; align-items: flex-start; gap: 12px;">
                            <input type="radio" name="plan_period" value="1_year" style="margin-top: 4px;">
                            <div>
                                <div style="font-size: 14px; font-weight: 800; color: #fff;">Plan Anual (1 Año)</div>
                                <div style="font-size: 22px; font-weight: 900; color: #34d399; margin: 4px 0;">$50 <span style="font-size: 12px; font-weight: 600; color: var(--text-muted);">USD</span></div>
                            </div>
                        </label>
                    @endforelse
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 700; color: #fff; margin-bottom: 6px;">
                        Referencia o Comprobante de Pago (Opcional)
                    </label>
                    <input type="text" name="payment_reference" value="{{ old('payment_reference') }}" placeholder="Ej: Operación #123456 / Yape / PayPal / Transferencia..." class="form-control" style="width: 100%; background: rgba(0,0,0,0.4); border: 1px solid var(--border-color); border-radius: 8px; color: #fff; padding: 12px 14px; font-size: 14px;">
                </div>

                <div class="form-group">
                    <label style="display: block; font-size: 13px; font-weight: 700; color: #fff; margin-bottom: 6px;">
                        Notas Internas del Cliente
                    </label>
                    <textarea name="notes" rows="2" placeholder="Observaciones adicionales sobre esta cuenta..." class="form-control" style="width: 100%; background: rgba(0,0,0,0.4); border: 1px solid var(--border-color); border-radius: 8px; color: #fff; padding: 10px 14px; font-size: 13px;">{{ old('notes') }}</textarea>
                </div>
            </div>

            <!-- Botones -->
            <div style="display: flex; gap: 12px; justify-content: flex-end; border-top: 1px solid var(--border-color); padding-top: 20px;">
                <a href="{{ route('superadmin.tenants.index') }}" class="btn btn-secondary" style="font-weight: 700; padding: 12px 20px;">
                    Cancelar
                </a>
                <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, #06b6d4, #3b82f6); border: none; font-weight: 800; padding: 12px 28px; box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);">
                    <i class="fa-solid fa-check-circle"></i> Registrar y Activar Cuenta
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
