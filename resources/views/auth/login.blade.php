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
            <div style="text-align: center; margin-bottom: 24px;">
                <img src="/images/logo.png" alt="Layra Fit" style="max-width: 210px; width: 100%; height: auto; margin: 0 auto; display: block; filter: drop-shadow(0 4px 10px rgba(0,0,0,0.5));">
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
                    <label class="form-label">Número de Teléfono (ID de Acceso)</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" placeholder="Ej. 999888777" required autofocus>
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

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 14px; font-size: 15px;">
                    Ingresar a Layra Fit
                </button>
            </form>

            <div style="text-align: center; margin-top: 24px; padding-top: 20px; border-top: 1px solid var(--border-color); font-size: 14px; color: var(--text-muted);">
                ¿Eres alumna nueva? 
                <a href="{{ route('register') }}" style="color: var(--primary); font-weight: 600;">Regístrate aquí</a>
            </div>
        </div>

        <!-- Imagen Derecha: Marely -->
        <div class="auth-image-side">
            <img src="/images/Guapisima-Marely-Piscoya-1.png" alt="Marely - Layra Fit" class="auth-image-bg">
            <div class="auth-image-overlay">
                <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 4px;">
                    <img src="/images/zimzumba.svg" alt="ZIN Zumba" style="height: 38px; width: auto; max-width: 130px; display: block; filter: drop-shadow(0 4px 8px rgba(0,0,0,0.6));">
                    <div class="auth-image-title" style="margin-bottom: 0;">Marely Piscoya</div>
                </div>
                <div class="auth-image-sub">Pasión, Baile y Estilo de Vida Saludable</div>
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



