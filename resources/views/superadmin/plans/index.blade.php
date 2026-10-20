@extends('layouts.superadmin')

@section('page_title', 'Planes de Membresía SaaS FitClass')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">

    <!-- Encabezado con Botón de Creación -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div>
            <h2 style="font-size: 22px; font-weight: 800; color: #fff; margin: 0;">Planes de Membresía FitClass</h2>
            <p style="color: var(--text-muted); font-size: 13px; margin: 4px 0 0 0;">
                Configura los precios, periodos y características de los planes que ofreces a las academias y gimnasios.
            </p>
        </div>
        <a href="{{ route('superadmin.plans.create') }}" class="btn btn-primary" style="background: linear-gradient(135deg, #06b6d4, #3b82f6); border: none; font-weight: 800; padding: 12px 20px; box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);">
            <i class="fa-solid fa-plus-circle"></i> Crear Nueva Membresía
        </a>
    </div>

    <!-- Cards de Planes (Pricing Grid) -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 20px;">
        @forelse($plans as $plan)
            <div class="card" style="background: var(--bg-card); border: 2px solid {{ $plan->is_active ? ($plan->duration_months >= 12 ? '#10b981' : '#3b82f6') : '#475569' }}; border-radius: 16px; padding: 24px; position: relative; display: flex; flex-direction: column; justify-content: space-between; box-shadow: 0 10px 25px rgba(0,0,0,0.4); opacity: {{ $plan->is_active ? '1' : '0.65' }};">
                
                <!-- Badge Superior -->
                @if($plan->badge)
                    <div style="position: absolute; top: -12px; right: 20px; background: {{ $plan->duration_months >= 12 ? 'linear-gradient(135deg, #10b981, #059669)' : 'linear-gradient(135deg, #06b6d4, #3b82f6)' }}; color: #fff; font-size: 11px; font-weight: 800; padding: 4px 12px; border-radius: 12px; text-transform: uppercase; letter-spacing: 0.5px; box-shadow: 0 2px 8px rgba(0,0,0,0.3);">
                        <i class="fa-solid fa-star" style="font-size: 9px; margin-right: 3px;"></i> {{ $plan->badge }}
                    </div>
                @endif

                <div>
                    <!-- Estado & Nombre -->
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                        <span style="font-size: 11px; font-weight: 800; padding: 3px 8px; border-radius: 6px; {{ $plan->is_active ? 'background: rgba(16,185,129,0.15); color: #34d399; border: 1px solid rgba(16,185,129,0.3);' : 'background: rgba(239,68,68,0.15); color: #f87171; border: 1px solid rgba(239,68,68,0.3);' }}">
                            {{ $plan->is_active ? 'ACTIVO' : 'PAUSADO' }}
                        </span>
                        <span style="font-size: 12px; color: var(--text-muted); font-weight: 600;">
                            <i class="fa-solid fa-calendar-days" style="margin-right: 4px;"></i> {{ $plan->duration_months }} Meses
                        </span>
                    </div>

                    <h3 style="font-size: 20px; font-weight: 900; color: #fff; margin: 8px 0 4px 0;">
                        {{ $plan->name }}
                    </h3>

                    @if($plan->description)
                        <p style="color: var(--text-muted); font-size: 13px; margin-bottom: 16px; min-height: 38px;">
                            {{ $plan->description }}
                        </p>
                    @endif

                    <!-- Precio -->
                    <div style="background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 16px; margin-bottom: 20px; text-align: center;">
                        <div style="font-size: 36px; font-weight: 900; color: #fff; line-height: 1;">
                            <span style="font-size: 20px; color: #38bdf8; vertical-align: top;">$</span>{{ number_format($plan->price, 0) }}
                            <span style="font-size: 14px; font-weight: 600; color: var(--text-muted);">{{ $plan->currency }}</span>
                        </div>
                        <div style="font-size: 12px; color: #38bdf8; font-weight: 700; margin-top: 6px;">
                            Por {{ $plan->duration_months }} meses de servicio (~${{ number_format($plan->price / max(1, $plan->duration_months), 1) }}/mes)
                        </div>
                    </div>

                    <!-- Lista de Beneficios -->
                    <div style="margin-bottom: 20px;">
                        <div style="font-size: 12px; font-weight: 800; color: #fff; text-transform: uppercase; margin-bottom: 10px; letter-spacing: 0.5px;">
                            Beneficios Incluidos:
                        </div>
                        <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 8px;">
                            @foreach($plan->features_list as $feature)
                                <li style="font-size: 13px; color: #ddd; display: flex; align-items: flex-start; gap: 8px;">
                                    <i class="fa-solid fa-circle-check" style="color: #10b981; margin-top: 3px; font-size: 13px;"></i>
                                    <span>{{ $feature }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div style="border-top: 1px solid var(--border-color); padding-top: 16px; display: flex; gap: 8px; justify-content: space-between; align-items: center;">
                    <a href="{{ route('superadmin.plans.edit', $plan) }}" class="btn btn-secondary btn-sm" style="font-weight: 700; flex: 1; text-align: center;">
                        <i class="fa-solid fa-pen-to-square"></i> Editar Plan
                    </a>

                    <form action="{{ route('superadmin.plans.toggle', $plan) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm {{ $plan->is_active ? 'btn-danger' : 'btn-success' }}" title="{{ $plan->is_active ? 'Pausar este plan' : 'Activar este plan' }}" style="font-weight: 700;">
                            <i class="fa-solid {{ $plan->is_active ? 'fa-pause' : 'fa-play' }}"></i>
                        </button>
                    </form>

                    <form action="{{ route('superadmin.plans.destroy', $plan) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este plan de membresía?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-secondary" title="Eliminar plan" style="color: #f87171; border-color: rgba(239,68,68,0.3);">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </div>

            </div>
        @empty
            <div style="grid-column: 1 / -1; padding: 40px; text-align: center; background: var(--bg-card); border-radius: 16px; border: 1px dashed var(--border-color);">
                <i class="fa-solid fa-id-card" style="font-size: 40px; color: var(--text-muted); margin-bottom: 12px;"></i>
                <h3 style="font-size: 18px; color: #fff; font-weight: 800;">No hay membresías creadas todavía</h3>
                <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 16px;">Crea tus planes de 6 meses ($30), 1 año ($50) o cualquier otra opción personalizada.</p>
                <a href="{{ route('superadmin.plans.create') }}" class="btn btn-primary" style="font-weight: 800;">
                    <i class="fa-solid fa-plus-circle"></i> Crear Primera Membresía
                </a>
            </div>
        @endforelse
    </div>

</div>
@endsection
