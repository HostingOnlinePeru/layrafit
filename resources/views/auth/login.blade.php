@extends('layouts.app')

@section('title', 'Iniciar Sesión — Layra Fit')

@section('body')
<!-- Video de Fondo YouTube (GrJwIRbO6uc) -->
<div class="video-bg-container">
    <div id="bg-video-player" class="video-bg-iframe"></div>
</div>
<div class="video-bg-overlay"></div>

<div class="auth-wrapper">
    <div class="auth-card">
        
        <!-- Formulario de Login (Izquierda) -->
        <div class="auth-form-side">
            <div style="text-align: center; margin-bottom: 18px;">
                <img src="{{ isset($currentTenant) ? $currentTenant->logo_path : '/images/logo.png' }}" alt="{{ $currentTenant->name ?? 'Layra Fit' }}" style="max-width: 210px; width: 100%; height: auto; max-height: 70px; object-fit: contain; margin: 0 auto; display: block; filter: drop-shadow(0 4px 10px rgba(0,0,0,0.5));">
            </div>

            <div style="font-size: 13px; color: var(--text-muted); text-align: center; margin-bottom: 20px;">
                Ingresa tus credenciales para acceder a tu cuenta
            </div>

            @if($errors->any())
                <div class="alert alert-danger" style="margin-bottom: 20px;">
                    <span>⚠️</span>
                    <div>
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">
                    <span>✅</span> {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('login.perform') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Teléfono o Correo de Acceso</label>
                    <input type="text" id="phone-input" name="phone" value="{{ old('phone') }}" class="form-control" placeholder="Ej. 912345678 o tu@correo.com" required autofocus>
                </div>

                <div class="form-group">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>

                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; font-size: 13px;">
                    <label style="display: flex; align-items: center; gap: 8px; color: var(--text-muted); cursor: pointer;">
                        <input type="checkbox" name="remember" style="accent-color: var(--primary);"> Recordarme
                    </label>
                </div>

                <button type="submit" id="btn-submit-login" class="btn btn-primary" style="width: 100%; padding: 14px; font-size: 15px; font-weight: 800; display: flex; align-items: center; justify-content: center; gap: 8px;">
                    <i class="fa-solid fa-right-to-bracket"></i> Iniciar Sesión
                </button>
            </form>

            <div id="register-link-container" style="text-align: center; margin-top: 16px; padding-top: 14px; border-top: 1px solid var(--border-color); font-size: 13px; color: var(--text-muted);">
                ¿Eres alumna nueva? 
                <a href="{{ isset($currentTenant) ? route('tenant.register', $currentTenant->slug) : route('register') }}" style="color: var(--primary); font-weight: 700;">Regístrate aquí</a>
            </div>

            <!-- Acceso Directo Super Admin FitClass -->
            <div style="text-align: center; margin-top: 16px; padding-top: 12px; border-top: 1px solid rgba(255,255,255,0.06); display: flex; flex-direction: column; align-items: center; gap: 6px;">
                <a href="{{ route('superadmin.login') }}" style="display: inline-flex; align-items: center; gap: 6px; background: rgba(56, 189, 248, 0.1); border: 1px solid rgba(56, 189, 248, 0.35); color: #38bdf8; padding: 6px 14px; border-radius: 20px; font-size: 11px; font-weight: 800; text-decoration: none; transition: all 0.2s;">
                    <i class="fa-solid fa-crown" style="color: #f59e0b;"></i> Ingreso Super Admin FitClass &rarr;
                </a>
                <div style="font-size: 10px; color: var(--text-muted); opacity: 0.7;">
                    Plataforma SaaS Multi-Academia FitClass
                </div>
            </div>
        </div>

        <!-- Imagen Derecha: Portada Personalizada / Marca Blanca -->
        <div class="auth-image-side">
            <img src="{{ isset($currentTenant) ? $currentTenant->cover_path : '/images/Guapisima-Marely-Piscoya-1.png' }}" alt="{{ $currentTenant->name ?? 'Layra Fit' }}" class="auth-image-bg">
            <div class="auth-image-overlay">
                <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 4px;">
                    <div class="auth-image-title" style="margin-bottom: 0;">
                        {{ !empty($currentTenant->cover_title) ? $currentTenant->cover_title : ($currentTenant->owner_name ?? 'Marely Piscoya') }}
                    </div>
                </div>
                <div class="auth-image-sub">
                    {{ !empty($currentTenant->cover_subtitle) ? $currentTenant->cover_subtitle : 'Pasión, Baile y Estilo de Vida Saludable' }}
                </div>
            </div>
        </div>




    </div>
</div>
@endsection

@push('scripts')
<script>
    var tag = document.createElement('script');
    tag.src = "https://www.youtube.com/iframe_api";
    var firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

    var player;
    function onYouTubeIframeAPIReady() {
        player = new YT.Player('bg-video-player', {
            videoId: 'tx1gr5Hzpyg',
            playerVars: {
                'autoplay': 1,
                'controls': 0,
                'showinfo': 0,
                'autohide': 1,
                'modestbranding': 1,
                'mute': 1,
                'loop': 1,
                'playlist': 'tx1gr5Hzpyg',

                'rel': 0,
                'playsinline': 1,
                'enablejsapi': 1
            },
            events: {
                'onReady': function(event) {
                    event.target.mute();
                    event.target.playVideo();
                },
                'onStateChange': function(event) {
                    if (event.data === YT.PlayerState.ENDED) {
                        player.playVideo();
                    }
                }
            }
        });
    }
</script>
@endpush



