@extends('layouts.app')

@section('title', 'Registro de Alumna — Layra Fit')

@section('body')
<!-- Video de Fondo YouTube (tx1gr5Hzpyg) -->
<div class="video-bg-container">
    <div id="bg-video-player" class="video-bg-iframe"></div>
</div>
<div class="video-bg-overlay"></div>

<div class="auth-wrapper">
    <div class="auth-card" style="max-width: 980px;">
        
        <!-- Formulario de Registro (Izquierda) -->
        <div class="auth-form-side" style="padding: 32px 28px;">
            <div style="text-align: center; margin-bottom: 20px;">
                <img src="/images/logo.png" alt="Layra Fit" style="max-width: 190px; width: 100%; height: auto; margin: 0 auto 6px; display: block; filter: drop-shadow(0 4px 10px rgba(0,0,0,0.5));">
                <p style="color: var(--text-muted); font-size: 13px;">Únete a nuestras clases de Bail Fitness</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger" style="margin-bottom: 16px;">
                    <span>⚠️</span>
                    <div>
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            <form action="{{ route('register.perform') }}" method="POST">
                @csrf

                <div class="form-group" style="margin-bottom: 14px;">
                    <label class="form-label">Sede / Local al que asistirás *</label>
                    <select name="location_id" class="form-control" required style="background: #111422; color: #fff;">
                        <option value="">-- Selecciona tu local --</option>
                        @foreach($locations as $loc)
                            <option value="{{ $loc->id }}" {{ old('location_id') == $loc->id ? 'selected' : '' }}>
                                {{ $loc->name }} — {{ $loc->address }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 14px;">
                    <label class="form-label">Número de Teléfono (Será tu ID de acceso) *</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" placeholder="Ej. 987654321" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 14px;">
                    <div>
                        <label class="form-label">Nombre *</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="María" required>
                    </div>
                    <div>
                        <label class="form-label">Apellido *</label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control" placeholder="González" required>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 14px;">
                    <label class="form-label">Correo Electrónico (Opcional)</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="maria@ejemplo.com">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 16px;">
                    <div>
                        <label class="form-label">Contraseña *</label>
                        <input type="password" name="password" class="form-control" placeholder="Mínimo 6 caracteres" required>
                    </div>
                    <div>
                        <label class="form-label">Confirmar Contraseña *</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Repite contraseña" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 13px; font-size: 15px;">
                    Completar Registro
                </button>
            </form>

            <div style="text-align: center; margin-top: 16px; font-size: 14px; color: var(--text-muted);">
                ¿Ya tienes cuenta? 
                <a href="{{ route('login') }}" style="color: var(--primary); font-weight: 600;">Inicia sesión aquí</a>
            </div>
        </div>

        <!-- Imagen Derecha: Marely -->
        <div class="auth-image-side" style="min-height: 520px;">
            <img src="/images/Guapisima-Marely-Piscoya-1.png" alt="Marely - Layra Fit" class="auth-image-bg">
            <div class="auth-image-overlay">
                <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 4px;">
                    <img src="/images/zimzumba.svg" alt="ZIN Zumba" style="height: 38px; width: auto; max-width: 130px; display: block; filter: drop-shadow(0 4px 8px rgba(0,0,0,0.6));">
                    <div class="auth-image-title" style="margin-bottom: 0;">Marely Piscoya</div>
                </div>
                <div class="auth-image-sub">Transforma tu energía en cada clase</div>
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
