@extends('layouts.superadmin')

@section('page_title', 'Detalles de la Cuenta — ' . $tenant->name)

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">

    <!-- Encabezado con Botones -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
        <div>
            <div style="display: flex; align-items: center; gap: 10px;">
                <h2 style="font-size: 22px; font-weight: 800; color: #fff; margin: 0;">{{ $tenant->name }}</h2>
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
            </div>
            <p style="color: var(--text-muted); font-size: 13px; margin: 4px 0 0 0;">
                Identificador: <code>{{ $tenant->slug }}</code> &bull; Creada: {{ $tenant->created_at->format('d/m/Y') }}
            </p>
        </div>

        <div style="display: flex; gap: 10px;">
            <a href="{{ route('superadmin.tenants.edit', $tenant) }}" class="btn btn-secondary" style="font-weight: 700;">
                <i class="fa-solid fa-pen-to-square"></i> Editar Datos
            </a>

            <form action="{{ route('superadmin.tenants.toggle', $tenant) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas cambiar el estado de acceso?');">
                @csrf
                <button type="submit" class="btn {{ $tenant->status === 'active' ? 'btn-danger' : 'btn-success' }}" style="font-weight: 700;">
                    <i class="fa-solid {{ $tenant->status === 'active' ? 'fa-ban' : 'fa-check' }}"></i>
                    {{ $tenant->status === 'active' ? 'Suspender Cuenta' : 'Activar Cuenta' }}
                </button>
            </form>

            <a href="{{ route('superadmin.tenants.index') }}" class="btn btn-secondary" style="font-weight: 700;">
                <i class="fa-solid fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <!-- Banner de Enlaces Oficiales de la Academia (Marca Blanca) -->
    <div style="background: rgba(15, 23, 42, 0.7); border: 1px solid rgba(56, 189, 248, 0.3); border-radius: 14px; padding: 16px 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
        <div>
            <div style="font-size: 11px; font-weight: 800; color: #38bdf8; text-transform: uppercase; letter-spacing: 0.5px;">
                <i class="fa-solid fa-link"></i> Enlace Oficial de Acceso para esta Academia (Marca Blanca):
            </div>
            <div style="font-size: 14px; font-weight: 700; color: #fff; margin-top: 4px;">
                <a href="{{ route('tenant.login', $tenant->slug) }}" target="_blank" style="color: #38bdf8; text-decoration: underline;">
                    {{ url('/a/' . $tenant->slug) }}
                </a>
            </div>
            <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">
                Alumnos y profesores acceden con el logo y portada de esta academia. Registro directo: <code>{{ url('/a/' . $tenant->slug . '/registro') }}</code>
            </div>
        </div>
        <div>
            <a href="{{ route('tenant.login', $tenant->slug) }}" target="_blank" class="btn btn-primary btn-sm" style="font-weight: 800; background: linear-gradient(135deg, #06b6d4, #3b82f6); border: none;">
                <i class="fa-solid fa-arrow-up-right-from-square"></i> Abrir Portal de esta Academia
            </a>
        </div>
    </div>

    <!-- Grid de Información y Estado de Membresía -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 20px;">
        
        <!-- Tarjeta: Estado de Membresía FitClass -->
        <div class="card" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 22px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">
                <h4 style="font-size: 15px; font-weight: 800; color: #38bdf8; text-transform: uppercase; margin: 0;">
                    <i class="fa-solid fa-crown" style="color: #f59e0b; margin-right: 6px;"></i> Licencia FitClass SaaS
                </h4>
                <span style="font-size: 12px; font-weight: 800; color: #fff; background: rgba(59, 130, 246, 0.2); padding: 4px 10px; border-radius: 8px;">
                    {{ $tenant->plan_period_label }}
                </span>
            </div>

            <div style="display: flex; flex-direction: column; gap: 12px; margin-bottom: 20px;">
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: var(--text-muted); font-size: 13px;">Precio del Plan:</span>
                    <strong style="color: #34d399; font-size: 15px;">${{ number_format($tenant->plan_price, 2) }} USD</strong>
                </div>

                <div style="display: flex; justify-content: space-between;">
                    <span style="color: var(--text-muted); font-size: 13px;">Fecha Inicio:</span>
                    <strong style="color: #fff; font-size: 13px;">{{ $tenant->subscription_starts_at ? $tenant->subscription_starts_at->format('d/m/Y') : '—' }}</strong>
                </div>

                <div style="display: flex; justify-content: space-between;">
                    <span style="color: var(--text-muted); font-size: 13px;">Fecha Vencimiento:</span>
                    <strong style="color: #fff; font-size: 13px;">
                        {{ $tenant->subscription_ends_at ? $tenant->subscription_ends_at->format('d/m/Y') : 'Sin Vencimiento' }}
                    </strong>
                </div>

                <div style="display: flex; justify-content: space-between;">
                    <span style="color: var(--text-muted); font-size: 13px;">Días Restantes:</span>
                    @if($tenant->subscription_ends_at)
                        <strong style="color: {{ $tenant->subscription_ends_at->isPast() ? '#f87171' : '#34d399' }}; font-size: 13px;">
                            {{ $tenant->subscription_ends_at->diffForHumans() }}
                        </strong>
                    @else
                        <strong style="color: #34d399; font-size: 13px;">Ilimitado</strong>
                    @endif
                </div>
            </div>

            <!-- Formulario de Renovación In-Situ -->
            <div style="background: rgba(0,0,0,0.3); border: 1px solid var(--border-color); border-radius: 12px; padding: 16px;">
                <h5 style="font-size: 13px; font-weight: 800; color: #fff; margin-bottom: 12px;">
                    <i class="fa-solid fa-arrows-rotate" style="color: #38bdf8;"></i> Renovar o Extender Membresía
                </h5>

                <form method="POST" action="{{ route('superadmin.tenants.renew', $tenant) }}">
                    @csrf
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 12px;">
                        <div>
                            <label style="font-size: 11px; color: var(--text-muted); display: block; margin-bottom: 4px;">Plan / Membresía</label>
                            <select name="plan_id" class="form-control" style="width: 100%; background: rgba(0,0,0,0.5); border: 1px solid var(--border-color); border-radius: 6px; color: #fff; font-size: 12px; padding: 8px;">
                                @forelse($plans as $p)
                                    <option value="{{ $p->id }}" {{ $tenant->plan_period === $p->slug ? 'selected' : '' }}>
                                        {{ $p->name }} (${{ number_format($p->price, 0) }})
                                    </option>
                                @empty
                                    <option value="">6 Meses ($30)</option>
                                    <option value="">1 Año ($50)</option>
                                @endforelse
                            </select>
                        </div>
                        <div>
                            <label style="font-size: 11px; color: var(--text-muted); display: block; margin-bottom: 4px;">Monto USD (Opcional)</label>
                            <input type="number" step="0.01" name="amount" placeholder="30 o 50" class="form-control" style="width: 100%; background: rgba(0,0,0,0.5); border: 1px solid var(--border-color); border-radius: 6px; color: #fff; font-size: 12px; padding: 8px;">
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 12px;">
                        <div>
                            <label style="font-size: 11px; color: var(--text-muted); display: block; margin-bottom: 4px;">Método de Pago</label>
                            <input type="text" name="payment_method" placeholder="Ej: Transferencia / Yape / PayPal" class="form-control" style="width: 100%; background: rgba(0,0,0,0.5); border: 1px solid var(--border-color); border-radius: 6px; color: #fff; font-size: 12px; padding: 8px;">
                        </div>
                        <div>
                            <label style="font-size: 11px; color: var(--text-muted); display: block; margin-bottom: 4px;">N° Operación</label>
                            <input type="text" name="payment_reference" placeholder="Ej: OP-987654" class="form-control" style="width: 100%; background: rgba(0,0,0,0.5); border: 1px solid var(--border-color); border-radius: 6px; color: #fff; font-size: 12px; padding: 8px;">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm" style="width: 100%; background: linear-gradient(135deg, #10b981, #059669); border: none; font-weight: 800; padding: 10px;">
                        <i class="fa-solid fa-check"></i> Aplicar Renovación de Membresía
                    </button>
                </form>
            </div>
        </div>

        <!-- Tarjeta: Datos de Contacto y Administrador -->
        <div class="card" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 22px;">
            <div style="margin-bottom: 16px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">
                <h4 style="font-size: 15px; font-weight: 800; color: #38bdf8; text-transform: uppercase; margin: 0;">
                    <i class="fa-solid fa-address-card" style="margin-right: 6px;"></i> Contacto y Administradores
                </h4>
            </div>

            <div style="display: flex; flex-direction: column; gap: 12px; margin-bottom: 20px;">
                <div>
                    <div style="font-size: 12px; color: var(--text-muted);">Titular Registrado:</div>
                    <div style="font-size: 14px; font-weight: 700; color: #fff;">{{ $tenant->owner_name ?? '—' }}</div>
                </div>

                <div>
                    <div style="font-size: 12px; color: var(--text-muted);">Teléfono Principal (Login):</div>
                    <div style="font-size: 14px; font-weight: 700; color: #fff;">
                        <i class="fa-solid fa-phone" style="color: #38bdf8; font-size: 12px;"></i> {{ $tenant->owner_phone }}
                    </div>
                </div>

                <div>
                    <div style="font-size: 12px; color: var(--text-muted);">Correo Electrónico:</div>
                    <div style="font-size: 14px; font-weight: 700; color: #fff;">{{ $tenant->owner_email ?? '—' }}</div>
                </div>

                <div>
                    <div style="font-size: 12px; color: var(--text-muted);">Alumnos Registrados en esta Academia:</div>
                    <div style="font-size: 16px; font-weight: 900; color: #a855f7;">
                        {{ $studentsCount }} Alumnos
                    </div>
                </div>

                @if($tenant->notes)
                    <div style="background: rgba(255,255,255,0.03); border-radius: 8px; padding: 10px; border: 1px solid var(--border-color); margin-top: 6px;">
                        <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); margin-bottom: 4px;">NOTAS:</div>
                        <div style="font-size: 12px; color: #ddd; line-height: 1.4;">{{ $tenant->notes }}</div>
                    </div>
                @endif
            </div>

            <div style="border-top: 1px solid var(--border-color); padding-top: 14px;">
                <div style="font-size: 13px; font-weight: 800; color: #fff; margin-bottom: 8px;">
                    Usuarios Administradores Vinculados:
                </div>
                @forelse($admins as $adminUser)
                    <div style="display: flex; justify-content: space-between; align-items: center; background: rgba(0,0,0,0.3); border-radius: 8px; padding: 8px 12px; margin-bottom: 6px;">
                        <div>
                            <div style="font-size: 13px; font-weight: 700; color: #fff;">{{ $adminUser->full_name }}</div>
                            <div style="font-size: 11px; color: var(--text-muted);">Tel: {{ $adminUser->phone }}</div>
                        </div>
                        <span style="font-size: 10px; font-weight: 800; background: rgba(16, 185, 129, 0.2); color: #34d399; padding: 2px 6px; border-radius: 6px;">ADMIN</span>
                    </div>
                @empty
                    <div style="font-size: 12px; color: var(--text-muted);">No hay administradores adicionales.</div>
                @endforelse
            </div>
        </div>

    </div>

    <!-- Historial de Pagos de Membresía SaaS -->
    <div class="card" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 14px; overflow: hidden;">
        <div style="padding: 16px 20px; border-bottom: 1px solid var(--border-color);">
            <h4 style="font-size: 15px; font-weight: 800; color: #fff; margin: 0;">
                <i class="fa-solid fa-receipt" style="color: #10b981; margin-right: 6px;"></i> Historial de Suscripciones y Pagos SaaS
            </h4>
        </div>

        <div class="table-responsive">
            <table class="table" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--border-color); color: var(--text-muted); font-size: 11px; text-transform: uppercase;">
                        <th style="padding: 12px 20px; text-align: left;">Fecha</th>
                        <th style="padding: 12px 20px; text-align: left;">Periodo</th>
                        <th style="padding: 12px 20px; text-align: left;">Vigencia</th>
                        <th style="padding: 12px 20px; text-align: left;">Monto</th>
                        <th style="padding: 12px 20px; text-align: left;">Referencia / Método</th>
                        <th style="padding: 12px 20px; text-align: center;">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tenant->subscriptions as $sub)
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                            <td style="padding: 14px 20px; font-size: 13px; color: #fff;">
                                {{ $sub->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td style="padding: 14px 20px; font-size: 13px; color: #fff;">
                                {{ $sub->period === '1_year' ? '1 Año' : '6 Meses' }}
                            </td>
                            <td style="padding: 14px 20px; font-size: 12px; color: var(--text-muted);">
                                {{ $sub->starts_at->format('d/m/Y') }} al {{ $sub->ends_at->format('d/m/Y') }}
                            </td>
                            <td style="padding: 14px 20px; font-size: 14px; font-weight: 800; color: #34d399;">
                                ${{ number_format($sub->amount, 2) }} {{ $sub->currency }}
                            </td>
                            <td style="padding: 14px 20px; font-size: 12px; color: #fff;">
                                <div>{{ $sub->payment_reference ?? '—' }}</div>
                                <div style="font-size: 11px; color: var(--text-muted);">{{ $sub->payment_method ?? '—' }}</div>
                            </td>
                            <td style="padding: 14px 20px; text-align: center;">
                                <span style="background: rgba(16, 185, 129, 0.2); color: #34d399; border: 1px solid #10b981; padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: 800;">
                                    PAGADO
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding: 24px; text-align: center; color: var(--text-muted);">
                                No hay pagos registrados en esta cuenta.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
