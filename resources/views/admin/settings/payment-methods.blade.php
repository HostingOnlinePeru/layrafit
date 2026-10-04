@extends('layouts.admin')

@section('page_title', 'Configuración de Métodos de Pago')

@section('content')
<div style="margin-bottom: 24px;">
    <h3 style="font-size: 20px; font-weight: 700;">Cuentas de Cobro y Métodos de Pago</h3>
    <p style="font-size: 13px; color: var(--text-muted);">Modifica los números de cuenta, CCI, titulares y QR que verán tus alumnas al momento de reportar su pago.</p>
</div>

<div class="grid-cards" style="grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));">
    @foreach($methods as $method)
        <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 20px; padding: 24px;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
                <h4 style="font-size: 18px; font-weight: 800; color: #fff;">{{ $method->name }}</h4>
                <span class="badge {{ $method->is_active ? 'badge-active' : 'badge-suspended' }}">
                    {{ $method->is_active ? 'Activo' : 'Inactivo' }}
                </span>
            </div>

            <form action="{{ route('admin.settings.payment-methods.update', $method) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label">Número de Cuenta / Yape</label>
                    <input type="text" name="account_number" value="{{ old('account_number', $method->account_number) }}" class="form-control" placeholder="Ej. 999888777 o 193-98765432">
                </div>

                <div class="form-group">
                    <label class="form-label">Titular de la Cuenta</label>
                    <input type="text" name="titular" value="{{ old('titular', $method->titular) }}" class="form-control" placeholder="Ej. LAYRA FIT E.I.R.L.">
                </div>

                @if($method->slug !== 'yape')
                    <div class="form-group">
                        <label class="form-label">Código CCI (Interbancario)</label>
                        <input type="text" name="cci" value="{{ old('cci', $method->cci) }}" class="form-control" placeholder="Ej. 002-193-0098765432011-14">
                    </div>
                @endif

                <div class="form-group">
                    <label class="form-label">Instrucciones Adicionales</label>
                    <textarea name="extra_info" rows="2" class="form-control" placeholder="Ej. Enviar nro de operación">{{ old('extra_info', $method->extra_info) }}</textarea>
                </div>

                @if($method->slug === 'yape')
                    <div class="form-group">
                        <label class="form-label">Imagen QR (Opcional)</label>
                        <input type="file" name="qr_image" accept="image/*" class="form-control">
                        @if($method->qr_image)
                            <div style="margin-top: 8px;">
                                <img src="{{ asset('storage/'.$method->qr_image) }}" alt="QR Yape" style="max-width: 120px; border-radius: 8px;">
                            </div>
                        @endif
                    </div>
                @endif

                <div class="form-group" style="display: flex; align-items: center; gap: 8px; margin-top: 14px;">
                    <input type="checkbox" name="is_active" value="1" id="is_active_{{ $method->id }}" {{ $method->is_active ? 'checked' : '' }} style="accent-color: var(--primary);">
                    <label for="is_active_{{ $method->id }}" style="font-size: 13px; cursor: pointer;">Habilitado para cobros</label>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px;">
                    Guardar {{ $method->name }}
                </button>
            </form>
        </div>
    @endforeach
</div>
@endsection
