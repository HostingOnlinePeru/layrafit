@extends('layouts.student')

@section('title', 'Mis Pagos y Membresía — Layra Fit')

@section('content')
<!-- Header Sección -->
<div style="margin-bottom: 20px;">
    <h2 style="font-size: 22px; font-weight: 800;">Mi Membresía y Pagos</h2>
    <p style="font-size: 13px; color: var(--text-muted);">Revisa tu plan actual o adquiere una nueva membresía para entrenar.</p>
</div>

<!-- Selecci�n de Planes disponibles (Cards) -->
<div style="margin-bottom: 32px;">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px;">
        @foreach($plans as $plan)
            @php
                $isPremium = str_contains($plan->name, "Mensual B");
                $isTurnoTarde = str_contains($plan->name, "3 DÍAS");
                $isSoloZumba = str_contains($plan->name, "ZUMBA® 3") || str_contains($plan->name, "ZUMBA® 5");
                $cardIcon = $plan->type === "individual" ? "fa-ticket" : ($isPremium ? "fa-fire" : ($isSoloZumba ? "fa-fire" : "fa-star"));
            @endphp
            <div class="pricing-card" onclick="selectPlanCard({{ $plan->id }}, {{ $plan->price }})" id="plan-card-{{ $plan->id }}" style="background: linear-gradient(145deg, rgba(50, 21, 19, 0.95), rgba(30, 13, 12, 0.95)); border: 1px solid {{ $isPremium ? "var(--accent)" : "var(--border-color)" }}; border-radius: 20px; padding: 22px; position: relative; display: flex; flex-direction: column; justify-content: space-between; cursor: pointer; transition: all 0.3s ease; box-shadow: {{ $isPremium ? "0 0 20px rgba(224, 203, 194, 0.2)" : "0 10px 25px rgba(0,0,0,0.4)" }};">
                
                @if($isPremium)
                    <div style="position: absolute; top: -12px; right: 16px; display: flex; flex-direction: column; gap: 4px; align-items: flex-end;">
                        <div style="background: linear-gradient(135deg, var(--secondary), #8b342f); color: var(--accent); font-size: 10px; font-weight: 800; padding: 4px 12px; border-radius: 20px; border: 1px solid var(--primary); text-transform: uppercase; letter-spacing: 0.5px; box-shadow: 0 4px 12px rgba(81, 32, 29, 0.5);">
                            <i class="fa-solid fa-crown"></i> MÁS POPULAR
                        </div>
                        <div style="background: var(--accent); color: #111; font-size: 10px; font-weight: 800; padding: 4px 12px; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.5px;">
                            PLAN PREMIUM
                        </div>
                    </div>
                @endif

                <div>
                    <div style="display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 14px;">
                        <div style="width: 42px; height: 42px; border-radius: 12px; background: rgba(224, 203, 194, 0.12); border: 1px solid var(--border-color); display: flex; align-items: center; justify-content: center; color: var(--accent); font-size: 18px; flex-shrink: 0;">
                            <i class="fa-solid {{ $cardIcon }}"></i>
                        </div>
                        
                        <div style="display: flex; flex-direction: column; gap: 4px; align-items: flex-end;">
                            <span class="badge" style="background: rgba(224, 203, 194, 0.15); color: var(--accent); border: 1px solid var(--border-color); text-transform: uppercase;">
                                {{ $plan->type === "individual" ? "Clase Suelta" : ($isSoloZumba ? "Solo Zumba" : "Plan Mensual") }}
                            </span>
                            @if($isTurnoTarde)
                                <span class="badge" style="background: rgba(224, 203, 194, 0.05); color: var(--text-muted); border: 1px solid var(--border-color); font-size: 9px; letter-spacing: 0;">
                                    <i class="fa-solid fa-sun"></i> Solo en turno tarde
                                </span>
                            @endif
                        </div>
                    </div>

                    @php
                        // Separar nombre principal (ej. Plan Mensual A) de la cantidad (ej. 3 VECES)
                        $nameParts = explode(" ", $plan->name);
                        $lastPart = array_pop($nameParts); // "VECES" o "DÍAS" o "ZUMBA®"
                        $secondLast = array_pop($nameParts); // "3" o "5" o "Clase"
                        
                        // Reconstruir
                        $mainName = implode(" ", $nameParts);
                        if ($plan->type === "individual") {
                            $mainName = "Clase";
                            $highlightName = "ZUMBA&reg;";
                        } else {
                            $highlightName = $secondLast . " " . $lastPart;
                        }
                    @endphp
                    
                    <h4 style="font-size: 18px; font-weight: 800; color: #fff; margin-bottom: 2px; line-height: 1.1;">{!! $mainName !!}</h4>
                    <h4 style="font-size: 26px; font-weight: 900; color: #fff; margin-bottom: 12px; line-height: 1.1; text-transform: uppercase;">{!! $highlightName !!}</h4>
                    
                    <div style="margin-bottom: 18px; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 12px;">
                        @foreach(explode("|", $plan->description) as $feature)
                            <p style="font-size: 12.5px; color: var(--text-muted); line-height: 1.4; margin-bottom: 6px; display: flex; align-items: flex-start; gap: 6px;">
                                <i class="fa-solid fa-circle-check" style="color: var(--accent); margin-top: 2px; font-size: 13px;"></i> <span>{{ trim($feature) }}</span>
                            </p>
                        @endforeach
                    </div>
                </div>

                <div style="padding-top: 14px; border-top: 1px dashed var(--border-color); display: flex; align-items: flex-end; justify-content: space-between;">
                    <div>
                        <div style="font-size: 10px; text-transform: uppercase; color: var(--text-muted); font-weight: 700; letter-spacing: 0.5px;">Inversión</div>
                        <div style="font-size: 24px; font-weight: 800; color: #fff; line-height: 1.1;">
                            S/ {{ number_format($plan->price, 0) }}
                            <span style="font-size: 11px; font-weight: 500; color: var(--text-muted);">/ {{ $plan->type === "individual" ? "clase" : "mes" }}</span>
                        </div>
                    </div>
                    <button type="button" class="btn {{ $isPremium ? 'btn-accent' : 'btn-secondary' }} btn-sm" style="{{ $isPremium ? 'background: linear-gradient(135deg, #e5b079, #c78b51); color: #111; border: none;' : '' }} border-radius: 10px; font-size: 12px; gap: 4px;">
                        Elegir <i class="fa-solid fa-chevron-right" style="font-size: 10px;"></i>
                    </button>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Formulario para Reportar un Pago -->
<div id="payment-form-section" style="display: none; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 20px; padding: 22px; margin-bottom: 28px;">
    <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 6px;">Reportar Nuevo Pago</h3>
    <p style="font-size: 12px; color: var(--text-muted); margin-bottom: 18px;">Efectúa la transferencia o yape a nuestras cuentas configuradas y registra los datos aquí.</p>

    <!-- Cuentas Configuradas -->
    <div style="background: rgba(10, 13, 22, 0.8); border: 1px solid var(--border-color); border-radius: 14px; padding: 16px; margin-bottom: 20px;">
        <div style="font-weight: 700; font-size: 13px; margin-bottom: 10px; color: var(--primary); display: flex; align-items: center; gap: 6px;">
            <i class="fa-solid fa-mobile-screen-button"></i> Cuentas Oficiales para Transferencias:
        </div>
        
        <div style="display: flex; flex-direction: column; gap: 12px; font-size: 12px;">
            @if(isset($currentTenant) && ($currentTenant->yape_number || $currentTenant->bank_accounts))
                @if($currentTenant->yape_number)
                    <div style="border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 8px;">
                        <strong style="color: #fff; font-size: 13px;">Yape / Plin:</strong>
                        <div>N° Celular: <strong style="color: var(--accent);">{{ $currentTenant->yape_number }}</strong></div>
                        @if($currentTenant->yape_holder)
                            <div>Titular: {{ $currentTenant->yape_holder }}</div>
                        @endif
                        @if($currentTenant->yape_qr)
                            <div style="margin-top: 6px;">
                                <img src="{{ asset('storage/' . $currentTenant->yape_qr) }}" alt="QR" style="max-height: 80px; border-radius: 6px;">
                            </div>
                        @endif
                    </div>
                @endif
                @if($currentTenant->bank_accounts)
                    <div style="border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 8px;">
                        <strong style="color: #fff; font-size: 13px;">Cuentas Bancarias:</strong>
                        <div style="white-space: pre-line; color: #ddd; margin-top: 4px;">{{ $currentTenant->bank_accounts }}</div>
                    </div>
                @endif
            @else
                @foreach($paymentMethods as $pm)
                    <div style="border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 8px;">
                        <strong style="color: #fff; font-size: 13px;">{{ $pm->name }}:</strong>
                        @if($pm->account_number)
                            <div>N° Cuenta/Celular: <strong style="color: var(--accent);">{{ $pm->account_number }}</strong></div>
                        @endif
                        @if($pm->cci)
                            <div>CCI: <code>{{ $pm->cci }}</code></div>
                        @endif
                        @if($pm->titular)
                            <div>Titular: {{ $pm->titular }}</div>
                        @endif
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger" style="font-size: 13px;">
            <span>⚠️</span>
            <div>
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        </div>
    @endif

    <form action="{{ route('student.payments.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label class="form-label">1. Selecciona la Modalidad a Pagar *</label>
            <select name="membership_plan_id" class="form-control" required style="background: #111422; color: #fff;">
                <option value="">-- Elige el plan --</option>
                @foreach($plans as $p)
                    <option value="{{ $p->id }}" {{ old('membership_plan_id') == $p->id ? 'selected' : '' }}>
                        {{ $p->name }} — S/ {{ number_format($p->price, 2) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">2. Método de Pago Utilizado *</label>
            <select name="payment_method_id" class="form-control" required style="background: #111422; color: #fff;">
                <option value="">-- Selecciona el medio --</option>
                @foreach($paymentMethods as $pm)
                    <option value="{{ $pm->id }}" {{ old('payment_method_id') == $pm->id ? 'selected' : '' }}>
                        {{ $pm->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <input type="hidden" name="amount" value="{{ old('amount') }}">
        <div class="form-group">
            <label class="form-label">Fecha de Pago *</label>
            <input type="date" name="payment_date" value="{{ old('payment_date', now()->format('Y-m-d')) }}" class="form-control" required style="background: #111422; color: #fff;">
        </div>

        <div class="form-group">
            <label class="form-label">Número de Operación *</label>
            <input type="text" name="operation_number" value="{{ old('operation_number') }}" class="form-control" placeholder="Ej. 0987654" required>
        </div>

        <div class="form-group">
            <label class="form-label">Imagen de Comprobante / Voucher *</label>
            <input type="file" name="voucher" accept="image/*" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 14px; font-size: 15px; margin-top: 8px; gap: 8px;">
            <i class="fa-solid fa-paper-plane"></i> Enviar Reporte de Pago
        </button>
    </form>
</div>

@endsection

@push('scripts')
<script>
    function selectPlanCard(planId, price) {
        const selectElem = document.querySelector('select[name="membership_plan_id"]');
        const amountInput = document.querySelector('input[name="amount"]');
        
        if (selectElem) {
            selectElem.value = planId;
            selectElem.style.borderColor = 'var(--primary)';
        }
        if (amountInput) {
            amountInput.value = price.toFixed(2);
            amountInput.style.borderColor = 'var(--primary)';
        }
        
        const formSection = document.getElementById('payment-form-section');
        if (formSection) {
            formSection.style.display = 'block';
            formSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
        
        document.querySelectorAll('.pricing-card').forEach(card => {
            card.style.borderColor = 'var(--border-color)';
            card.style.background = 'linear-gradient(145deg, rgba(50, 21, 19, 0.95), rgba(30, 13, 12, 0.95))';
        });

        const activeCard = document.getElementById('plan-card-' + planId);
        if (activeCard) {
            activeCard.style.borderColor = 'var(--accent)';
            activeCard.style.background = 'linear-gradient(145deg, rgba(81, 32, 29, 0.95), rgba(42, 19, 17, 0.95))';
        }

        const formElem = document.querySelector('form[action="{{ route('student.payments.store') }}"]');
        if (formElem) {
            formElem.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }
</script>
@endpush









