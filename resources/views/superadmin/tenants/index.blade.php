@extends('layouts.superadmin')

@section('page_title', 'Academias y Cuentas FitClass')

@section('content')
<div style="display: flex; flex-direction: column; gap: 20px;">

    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
        <div>
            <h2 style="font-size: 20px; font-weight: 800; color: #fff; margin: 0;">Gestión de Academias Registradas</h2>
            <p style="color: var(--text-muted); font-size: 13px; margin: 4px 0 0 0;">Cuentas independientes que usan el software FitClass.</p>
        </div>
        <a href="{{ route('superadmin.tenants.create') }}" class="btn btn-primary" style="background: linear-gradient(135deg, #06b6d4, #3b82f6); border: none; font-weight: 800; padding: 10px 18px;">
            <i class="fa-solid fa-plus-circle"></i> Registrar Nueva Cuenta
        </a>
    </div>

    <!-- Filtros de búsqueda -->
    <div class="card" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 12px; padding: 16px;">
        <form method="GET" action="{{ route('superadmin.tenants.index') }}" style="display: flex; gap: 12px; flex-wrap: wrap; align-items: center;">
            <div style="flex: 1; min-width: 220px;">
                <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Buscar por nombre, responsable, teléfono..." style="width: 100%; background: rgba(0,0,0,0.4); border: 1px solid var(--border-color); border-radius: 8px; color: #fff; padding: 10px 14px; font-size: 13px;">
            </div>

            <div style="min-width: 160px;">
                <select name="status" class="form-control" style="width: 100%; background: rgba(0,0,0,0.4); border: 1px solid var(--border-color); border-radius: 8px; color: #fff; padding: 10px 14px; font-size: 13px;">
                    <option value="">Todos los Estados</option>
                    <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Activas</option>
                    <option value="suspended" {{ $status === 'suspended' ? 'selected' : '' }}>Suspendidas</option>
                    <option value="expired" {{ $status === 'expired' ? 'selected' : '' }}>Vencidas</option>
                </select>
            </div>

            <button type="submit" class="btn btn-secondary" style="font-weight: 700; padding: 10px 16px;">
                <i class="fa-solid fa-magnifying-glass"></i> Filtrar
            </button>

            @if($search || $status)
                <a href="{{ route('superadmin.tenants.index') }}" class="btn btn-link" style="color: var(--text-muted); font-size: 13px; text-decoration: none;">
                    Limpiar
                </a>
            @endif
        </form>
    </div>

    <!-- Tabla -->
    <div class="card" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 14px; overflow: hidden;">
        <div class="table-responsive">
            <table class="table" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--border-color); color: var(--text-muted); font-size: 11px; text-transform: uppercase;">
                        <th style="padding: 14px 20px; text-align: left;">Academia / Gimnasio</th>
                        <th style="padding: 14px 20px; text-align: left;">Contacto Principal</th>
                        <th style="padding: 14px 20px; text-align: left;">Plan & Tarifa</th>
                        <th style="padding: 14px 20px; text-align: left;">Vencimiento</th>
                        <th style="padding: 14px 20px; text-align: center;">Usuarios</th>
                        <th style="padding: 14px 20px; text-align: center;">Estado</th>
                        <th style="padding: 14px 20px; text-align: right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tenants as $tenant)
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                            <td style="padding: 16px 20px;">
                                <div style="font-weight: 800; color: #fff; font-size: 14px;">{{ $tenant->name }}</div>
                                <div style="font-size: 12px; color: var(--text-muted);">ID: #{{ $tenant->id }} &bull; Slug: <code>{{ $tenant->slug }}</code></div>
                            </td>
                            <td style="padding: 16px 20px;">
                                <div style="font-weight: 600; color: #fff; font-size: 13px;">{{ $tenant->owner_name }}</div>
                                <div style="font-size: 12px; color: var(--text-muted);">
                                    <i class="fa-solid fa-phone" style="font-size: 10px;"></i> {{ $tenant->owner_phone }}
                                </div>
                                @if($tenant->owner_email)
                                    <div style="font-size: 12px; color: var(--text-muted);">
                                        <i class="fa-solid fa-envelope" style="font-size: 10px;"></i> {{ $tenant->owner_email }}
                                    </div>
                                @endif
                            </td>
                            <td style="padding: 16px 20px;">
                                <span style="background: rgba(59, 130, 246, 0.15); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.3); padding: 4px 10px; border-radius: 8px; font-size: 12px; font-weight: 700;">
                                    {{ $tenant->plan_period_label }}
                                </span>
                            </td>
                            <td style="padding: 16px 20px; font-size: 13px; color: #fff;">
                                @if($tenant->subscription_ends_at)
                                    <div>{{ $tenant->subscription_ends_at->format('d/m/Y') }}</div>
                                    <div style="font-size: 11px; color: {{ $tenant->subscription_ends_at->isPast() ? '#f87171' : '#34d399' }};">
                                        {{ $tenant->subscription_ends_at->diffForHumans() }}
                                    </div>
                                @else
                                    <span style="color: var(--text-muted);">Ilimitado</span>
                                @endif
                            </td>
                            <td style="padding: 16px 20px; text-align: center;">
                                <span style="background: rgba(255,255,255,0.06); padding: 4px 10px; border-radius: 10px; font-weight: 700; color: #fff; font-size: 12px;">
                                    <i class="fa-solid fa-users" style="color: var(--text-muted); font-size: 11px; margin-right: 4px;"></i> {{ $tenant->users_count }}
                                </span>
                            </td>
                            <td style="padding: 16px 20px; text-align: center;">
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
                            <td style="padding: 16px 20px; text-align: right;">
                                <div style="display: flex; gap: 6px; justify-content: flex-end;">
                                    <a href="{{ route('superadmin.tenants.show', $tenant) }}" class="btn btn-sm btn-secondary" title="Ver detalles y suscripciones" style="font-weight: 700;">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <form action="{{ route('superadmin.tenants.toggle', $tenant) }}" method="POST" onsubmit="return confirm('¿Deseas cambiar el estado de acceso de esta academia?');">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $tenant->status === 'active' ? 'btn-danger' : 'btn-success' }}" title="{{ $tenant->status === 'active' ? 'Suspender Acceso' : 'Activar Acceso' }}">
                                            <i class="fa-solid {{ $tenant->status === 'active' ? 'fa-ban' : 'fa-check' }}"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="padding: 30px; text-align: center; color: var(--text-muted);">
                                No se encontraron cuentas registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tenants->hasPages())
            <div style="padding: 16px 20px; border-top: 1px solid var(--border-color);">
                {{ $tenants->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
