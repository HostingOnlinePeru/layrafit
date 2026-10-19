@extends('layouts.superadmin')

@section('page_title', 'Métricas Globales FitClass')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">

    <!-- Banner Bienvenida FitClass -->
    <div style="background: linear-gradient(135deg, rgba(15, 23, 42, 0.95), rgba(30, 58, 138, 0.85)); border: 1px solid rgba(59, 130, 246, 0.4); border-radius: 16px; padding: 24px; position: relative; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
            <div>
                <span style="background: rgba(56, 189, 248, 0.2); color: #38bdf8; font-size: 11px; font-weight: 800; padding: 4px 10px; border-radius: 20px; border: 1px solid rgba(56, 189, 248, 0.4); letter-spacing: 1px;">
                    PLATAFORMA SAAS FITCLASS
                </span>
                <h1 style="font-size: 24px; font-weight: 900; color: #fff; margin-top: 8px; margin-bottom: 4px;">
                    Control Maestro de Cuentas y Academias
                </h1>
                <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 0;">
                    Monitorea academias de baile, gimnasios, licencias de 6 meses ($30) y 1 año ($50).
                </p>
            </div>
            <div>
                <a href="{{ route('superadmin.tenants.create') }}" class="btn btn-primary" style="background: linear-gradient(135deg, #06b6d4, #3b82f6); border: none; font-weight: 800; padding: 12px 20px; box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);">
                    <i class="fa-solid fa-plus-circle"></i> Crear Nueva Cuenta
                </a>
            </div>
        </div>
    </div>

    <!-- Cards Estadísticas -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
        <!-- Total Cuentas -->
        <div class="card" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 14px; padding: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                <span style="font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Total Cuentas</span>
                <div style="width: 38px; height: 38px; border-radius: 10px; background: rgba(59, 130, 246, 0.15); color: #3b82f6; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                    <i class="fa-solid fa-building"></i>
                </div>
            </div>
            <div style="font-size: 28px; font-weight: 900; color: #fff;">{{ $totalTenants }}</div>
            <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">
                {{ $activeTenants }} cuentas activas
            </div>
        </div>

        <!-- Ingresos Membresías -->
        <div class="card" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 14px; padding: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                <span style="font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Ingresos SaaS</span>
                <div style="width: 38px; height: 38px; border-radius: 10px; background: rgba(16, 185, 129, 0.15); color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                    <i class="fa-solid fa-dollar-sign"></i>
                </div>
            </div>
            <div style="font-size: 28px; font-weight: 900; color: #34d399;">${{ number_format($totalSubscriptions, 2) }} <span style="font-size: 14px; font-weight: 600; color: var(--text-muted);">USD</span></div>
            <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">
                Planes cobrados ($30 / $50)
            </div>
        </div>

        <!-- Total Usuarios en el Ecosistema -->
        <div class="card" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 14px; padding: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                <span style="font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Usuarios del Ecosistema</span>
                <div style="width: 38px; height: 38px; border-radius: 10px; background: rgba(168, 85, 247, 0.15); color: #a855f7; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
            <div style="font-size: 28px; font-weight: 900; color: #fff;">{{ $totalUsers }}</div>
            <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">
                Alumnos y administradores
            </div>
        </div>
    </div>

    <!-- Lista de Cuentas / Academias Recientes -->
    <div class="card" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 14px; overflow: hidden;">
        <div style="padding: 18px 20px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 16px; font-weight: 800; color: #fff; margin: 0;">
                <i class="fa-solid fa-building-user" style="color: #38bdf8; margin-right: 8px;"></i> Cuentas y Academias Registradas
            </h3>
            <a href="{{ route('superadmin.tenants.index') }}" style="font-size: 13px; color: #38bdf8; font-weight: 600; text-decoration: none;">
                Ver todas ({{ $totalTenants }}) <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <div class="table-responsive">
            <table class="table" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--border-color); color: var(--text-muted); font-size: 11px; text-transform: uppercase;">
                        <th style="padding: 12px 20px; text-align: left;">Academia / Gimnasio</th>
                        <th style="padding: 12px 20px; text-align: left;">Responsable</th>
                        <th style="padding: 12px 20px; text-align: left;">Plan FitClass</th>
                        <th style="padding: 12px 20px; text-align: left;">Vencimiento</th>
                        <th style="padding: 12px 20px; text-align: center;">Estado</th>
                        <th style="padding: 12px 20px; text-align: right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentTenants as $tenant)
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                            <td style="padding: 14px 20px;">
                                <div style="font-weight: 800; color: #fff; font-size: 14px;">{{ $tenant->name }}</div>
                                <div style="font-size: 12px; color: var(--text-muted);">Slug: <code>{{ $tenant->slug }}</code></div>
                            </td>
                            <td style="padding: 14px 20px;">
                                <div style="font-weight: 600; color: #fff; font-size: 13px;">{{ $tenant->owner_name ?? '—' }}</div>
                                <div style="font-size: 12px; color: var(--text-muted);">
                                    <i class="fa-solid fa-phone" style="font-size: 10px;"></i> {{ $tenant->owner_phone }}
                                </div>
                            </td>
                            <td style="padding: 14px 20px;">
                                <span style="background: rgba(59, 130, 246, 0.15); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.3); padding: 4px 10px; border-radius: 8px; font-size: 12px; font-weight: 700;">
                                    {{ $tenant->plan_period_label }}
                                </span>
                            </td>
                            <td style="padding: 14px 20px; font-size: 13px; color: #fff;">
                                @if($tenant->subscription_ends_at)
                                    <div>{{ $tenant->subscription_ends_at->format('d/m/Y') }}</div>
                                    <div style="font-size: 11px; color: {{ $tenant->subscription_ends_at->isPast() ? '#f87171' : '#34d399' }};">
                                        {{ $tenant->subscription_ends_at->diffForHumans() }}
                                    </div>
                                @else
                                    <span style="color: var(--text-muted);">Sin límite</span>
                                @endif
                            </td>
                            <td style="padding: 14px 20px; text-align: center;">
                                @if($tenant->status === 'active')
                                    <span style="background: rgba(16, 185, 129, 0.2); color: #34d399; border: 1px solid #10b981; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 800;">
                                        ACTIVA
                                    </span>
                                @elseif($tenant->status === 'suspended')
                                    <span style="background: rgba(239, 68, 68, 0.2); color: #f87171; border: 1px solid #ef4444; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 800;">
                                        SUSPENDIDA
                                    </span>
                                @else
                                    <span style="background: rgba(245, 158, 11, 0.2); color: #fbbf24; border: 1px solid #f59e0b; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 800;">
                                        VENCIDA
                                    </span>
                                @endif
                            </td>
                            <td style="padding: 14px 20px; text-align: right;">
                                <a href="{{ route('superadmin.tenants.show', $tenant) }}" class="btn btn-sm btn-secondary" style="font-weight: 700;">
                                    <i class="fa-solid fa-eye"></i> Gestionar
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding: 30px; text-align: center; color: var(--text-muted);">
                                No hay cuentas registradas aún.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
