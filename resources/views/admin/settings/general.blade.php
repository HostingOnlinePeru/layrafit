@extends('layouts.admin')

@section('page_title', 'Configuración de Marca Blanca y Datos')

@section('content')
<div style="max-width: 900px; margin: 0 auto; display: flex; flex-direction: column; gap: 24px;">

    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
        <div>
            <h2 style="font-size: 22px; font-weight: 800; color: #fff; margin: 0;">Identidad de Marca Blanca y Contacto</h2>
            <p style="color: var(--text-muted); font-size: 13px; margin: 4px 0 0 0;">
                Personaliza tu academia con tu propio logo, portada de login, teléfonos, Yape y cuentas bancarias.
            </p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('admin.settings.payment-methods') }}" class="btn btn-secondary" style="font-weight: 700; font-size: 13px;">
                <i class="fa-solid fa-credit-card"></i> Configurar Métodos de Pago
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger" style="background: rgba(239, 68, 68, 0.15); border: 1px solid #ef4444; color: #fca5a5; padding: 14px; border-radius: 10px; font-size: 13px;">
            <div style="font-weight: 700; margin-bottom: 6px;"><i class="fa-solid fa-triangle-exclamation"></i> Corrige los siguientes campos:</div>
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.settings.general.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Tarjeta 1: Logo y Portada (Marca Blanca) -->
        <div class="card" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 26px; margin-bottom: 24px;">
            <h4 style="font-size: 16px; font-weight: 800; color: var(--primary); text-transform: uppercase; margin-bottom: 16px; letter-spacing: 0.5px;">
                <i class="fa-solid fa-paintbrush" style="margin-right: 6px;"></i> 1. Identidad Visual (Logotipo y Portada)
            </h4>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display: block; font-size: 13px; font-weight: 700; color: #fff; margin-bottom: 6px;">
                    Nombre de tu Academia / Negocio <span style="color: #ef4444;">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name', $tenant->name) }}" required class="form-control" style="width: 100%; background: rgba(0,0,0,0.4); border: 1px solid var(--border-color); border-radius: 8px; color: #fff; padding: 12px 14px; font-size: 14px;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <!-- Campo Logotipo -->
                <div style="background: rgba(0,0,0,0.25); border: 1px dashed var(--border-color); border-radius: 12px; padding: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 700; color: #fff; margin-bottom: 6px;">
                        Tu Logotipo Oficial
                    </label>
                    <p style="font-size: 12px; color: var(--text-muted); margin-bottom: 10px;">Aparecerá en el encabezado, login y panel de tus alumnas (PNG, JPG, SVG).</p>
                    
                    <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 12px;">
                        <div style="background: rgba(0,0,0,0.5); border: 1px solid var(--border-color); border-radius: 8px; padding: 8px; width: 140px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <img src="{{ $tenant->logo_path }}" alt="Logo" style="max-height: 48px; max-width: 120px; object-fit: contain;">
                        </div>
                        <span style="font-size: 11px; color: var(--text-muted);">Logo actual</span>
                    </div>

                    <input type="file" name="logo" accept="image/*" class="form-control" style="width: 100%; background: rgba(0,0,0,0.4); border: 1px solid var(--border-color); border-radius: 6px; color: #fff; font-size: 12px; padding: 8px;">
                </div>

                <!-- Campo Portada Login -->
                <div style="background: rgba(0,0,0,0.25); border: 1px dashed var(--border-color); border-radius: 12px; padding: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 700; color: #fff; margin-bottom: 6px;">
                        Imagen de Portada (Login)
                    </label>
                    <p style="font-size: 12px; color: var(--text-muted); margin-bottom: 10px;">Foto de tu modelo, entrenadora o instalaciones en la pantalla de inicio.</p>

                    <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 12px;">
                        <div style="background: rgba(0,0,0,0.5); border: 1px solid var(--border-color); border-radius: 8px; overflow: hidden; width: 80px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <img src="{{ $tenant->cover_path }}" alt="Portada" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <span style="font-size: 11px; color: var(--text-muted);">Portada actual</span>
                    </div>

                    <input type="file" name="cover" accept="image/*" class="form-control" style="width: 100%; background: rgba(0,0,0,0.4); border: 1px solid var(--border-color); border-radius: 6px; color: #fff; font-size: 12px; padding: 8px;">
                </div>
            </div>

            <!-- Textos sobre la portada -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label style="display: block; font-size: 13px; font-weight: 700; color: #fff; margin-bottom: 6px;">
                        Nombre o Título en Portada
                    </label>
                    <input type="text" name="cover_title" value="{{ old('cover_title', $tenant->cover_title ?? $tenant->owner_name) }}" placeholder="Ej: Marely Piscoya / Tu Nombre" class="form-control" style="width: 100%; background: rgba(0,0,0,0.4); border: 1px solid var(--border-color); border-radius: 8px; color: #fff; padding: 12px 14px; font-size: 13px;">
                </div>

                <div class="form-group">
                    <label style="display: block; font-size: 13px; font-weight: 700; color: #fff; margin-bottom: 6px;">
                        Eslogan o Subtítulo en Portada
                    </label>
                    <input type="text" name="cover_subtitle" value="{{ old('cover_subtitle', $tenant->cover_subtitle ?? 'Pasión, Baile y Estilo de Vida Saludable') }}" placeholder="Ej: Pasión, Baile y Estilo de Vida Saludable" class="form-control" style="width: 100%; background: rgba(0,0,0,0.4); border: 1px solid var(--border-color); border-radius: 8px; color: #fff; padding: 12px 14px; font-size: 13px;">
                </div>
            </div>
        </div>

        <!-- Tarjeta 2: Contacto y Ubicación -->
        <div class="card" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 26px; margin-bottom: 24px;">
            <h4 style="font-size: 16px; font-weight: 800; color: var(--primary); text-transform: uppercase; margin-bottom: 16px; letter-spacing: 0.5px;">
                <i class="fa-solid fa-address-book" style="margin-right: 6px;"></i> 2. Teléfono, Correo y Dirección Física
            </h4>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div class="form-group">
                    <label style="display: block; font-size: 13px; font-weight: 700; color: #fff; margin-bottom: 6px;">
                        Teléfono / WhatsApp de Atención
                    </label>
                    <input type="text" name="phone" value="{{ old('phone', $tenant->phone ?? $tenant->owner_phone) }}" placeholder="Ej: 999888777" class="form-control" style="width: 100%; background: rgba(0,0,0,0.4); border: 1px solid var(--border-color); border-radius: 8px; color: #fff; padding: 12px 14px; font-size: 14px;">
                </div>

                <div class="form-group">
                    <label style="display: block; font-size: 13px; font-weight: 700; color: #fff; margin-bottom: 6px;">
                        Correo Electrónico de Contacto
                    </label>
                    <input type="email" name="email" value="{{ old('email', $tenant->email ?? $tenant->owner_email) }}" placeholder="contacto@tuacademia.com" class="form-control" style="width: 100%; background: rgba(0,0,0,0.4); border: 1px solid var(--border-color); border-radius: 8px; color: #fff; padding: 12px 14px; font-size: 14px;">
                </div>
            </div>

            <div class="form-group">
                <label style="display: block; font-size: 13px; font-weight: 700; color: #fff; margin-bottom: 6px;">
                    Dirección Física / Sede Principal
                </label>
                <input type="text" name="address" value="{{ old('address', $tenant->address) }}" placeholder="Ej: Av. Las Américas 450, Chiclayo / Lima" class="form-control" style="width: 100%; background: rgba(0,0,0,0.4); border: 1px solid var(--border-color); border-radius: 8px; color: #fff; padding: 12px 14px; font-size: 14px;">
            </div>
        </div>

        <!-- Tarjeta 3: Yape y Cuentas Bancarias -->
        <div class="card" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 26px; margin-bottom: 24px;">
            <h4 style="font-size: 16px; font-weight: 800; color: var(--primary); text-transform: uppercase; margin-bottom: 16px; letter-spacing: 0.5px;">
                <i class="fa-solid fa-wallet" style="margin-right: 6px;"></i> 3. Información de Cobros (Yape y Cuentas Bancarias)
            </h4>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div class="form-group">
                    <label style="display: block; font-size: 13px; font-weight: 700; color: #fff; margin-bottom: 6px;">
                        Número de Yape / Plin
                    </label>
                    <input type="text" name="yape_number" value="{{ old('yape_number', $tenant->yape_number) }}" placeholder="Ej: 999888777" class="form-control" style="width: 100%; background: rgba(0,0,0,0.4); border: 1px solid var(--border-color); border-radius: 8px; color: #fff; padding: 12px 14px; font-size: 14px;">
                </div>

                <div class="form-group">
                    <label style="display: block; font-size: 13px; font-weight: 700; color: #fff; margin-bottom: 6px;">
                        Titular de Yape / Plin
                    </label>
                    <input type="text" name="yape_holder" value="{{ old('yape_holder', $tenant->yape_holder ?? $tenant->owner_name) }}" placeholder="Ej: Marely Piscoya" class="form-control" style="width: 100%; background: rgba(0,0,0,0.4); border: 1px solid var(--border-color); border-radius: 8px; color: #fff; padding: 12px 14px; font-size: 14px;">
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 16px;">
                <label style="display: block; font-size: 13px; font-weight: 700; color: #fff; margin-bottom: 6px;">
                    Código QR de Yape (Opcional)
                </label>
                @if($tenant->yape_qr)
                    <div style="margin-bottom: 8px;">
                        <img src="{{ asset('storage/' . $tenant->yape_qr) }}" alt="QR" style="max-height: 80px; border-radius: 8px;">
                    </div>
                @endif
                <input type="file" name="yape_qr" accept="image/*" class="form-control" style="width: 100%; background: rgba(0,0,0,0.4); border: 1px solid var(--border-color); border-radius: 6px; color: #fff; font-size: 12px; padding: 8px;">
            </div>

            <div class="form-group">
                <label style="display: block; font-size: 13px; font-weight: 700; color: #fff; margin-bottom: 6px;">
                    Cuentas Bancarias (BCP, BBVA, Interbank, CCI)
                </label>
                <textarea name="bank_accounts" rows="4" placeholder="BCP Soles: 193-XXXXXXXX-X-XX&#10;CCI: 002-193-XXXXXXXXXXXX-XX&#10;Titular: Tu Nombre o Empresa" class="form-control" style="width: 100%; background: rgba(0,0,0,0.4); border: 1px solid var(--border-color); border-radius: 8px; color: #fff; padding: 12px 14px; font-size: 13px; line-height: 1.5;">{{ old('bank_accounts', $tenant->bank_accounts) }}</textarea>
                <span style="font-size: 11px; color: var(--text-muted); margin-top: 4px; display: block;">Esta información se mostrará a tus alumnas en la sección de pagos y transferencias.</span>
            </div>
        </div>

        <!-- Botón Guardar -->
        <div style="display: flex; justify-content: flex-end; gap: 12px;">
            <button type="submit" class="btn btn-primary" style="padding: 14px 32px; font-weight: 800; font-size: 15px; box-shadow: 0 4px 15px rgba(192, 132, 252, 0.3);">
                <i class="fa-solid fa-floppy-disk"></i> Guardar Configuración de Marca Blanca
            </button>
        </div>

    </form>

</div>
@endsection
