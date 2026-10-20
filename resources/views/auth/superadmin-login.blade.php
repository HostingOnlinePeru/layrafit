@extends('layouts.app')

@section('title', 'Ingreso Super Admin — FitClass SaaS')

@section('body')
<div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; background: radial-gradient(circle at 50% 20%, #1e1b4b 0%, #090d16 60%, #020617 100%); padding: 20px; position: relative;">

    <!-- Background Ambient Glows -->
    <div style="position: absolute; top: 10%; left: 15%; width: 350px; height: 350px; background: rgba(56, 189, 248, 0.12); border-radius: 50%; filter: blur(80px); pointer-events: none;"></div>
    <div style="position: absolute; bottom: 10%; right: 15%; width: 350px; height: 350px; background: rgba(139, 92, 246, 0.15); border-radius: 50%; filter: blur(80px); pointer-events: none;"></div>

    <div class="auth-card" style="max-width: 960px; width: 100%; border: 1px solid rgba(56, 189, 248, 0.3); box-shadow: 0 20px 50px rgba(0,0,0,0.7), 0 0 30px rgba(56, 189, 248, 0.15); border-radius: 24px; overflow: hidden; background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(20px);">
        
        <!-- Formulario Izquierda -->
        <div class="auth-form-side" style="padding: 40px 36px; display: flex; flex-direction: column; justify-content: space-between;">
            <div>
                <!-- FitClass SaaS Logo -->
                <div style="margin-bottom: 28px;">
                    <img src="/images/fitclass-logo.svg" alt="FitClass SaaS" style="height: 46px; width: auto; display: block;">
                </div>

                <div style="margin-bottom: 24px;">
                    <span style="background: rgba(245, 158, 11, 0.15); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.4); padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 800; letter-spacing: 0.5px;">
                        <i class="fa-solid fa-crown" style="margin-right: 4px;"></i> ACCESO MAESTRO SUPER ADMIN
                    </span>
                    <h2 style="font-size: 22px; font-weight: 900; color: #fff; margin-top: 10px; margin-bottom: 4px;">
                        Control Maestro del SaaS
                    </h2>
                    <p style="color: var(--text-muted); font-size: 13px; margin: 0;">
                        Ingresa tus credenciales maestras para gestionar academias y membresías.
                    </p>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger" style="background: rgba(239, 68, 68, 0.15); border: 1px solid #ef4444; color: #fca5a5; padding: 12px 16px; border-radius: 10px; font-size: 13px; margin-bottom: 20px;">
                        <div style="font-weight: 700;"><i class="fa-solid fa-circle-exclamation"></i> Error al autenticar:</div>
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('login.perform') }}" method="POST">
                    @csrf

                    <div class="form-group" style="margin-bottom: 18px;">
                        <label style="display: block; font-size: 12px; font-weight: 800; color: #fff; text-transform: uppercase; margin-bottom: 6px; letter-spacing: 0.5px;">
                            Teléfono o ID Maestro
                        </label>
                        <div style="position: relative;">
                            <input type="text" name="phone" value="{{ old('phone', '000000000') }}" required autofocus class="form-control" placeholder="000000000" style="width: 100%; background: rgba(0,0,0,0.5); border: 1px solid rgba(56, 189, 248, 0.3); border-radius: 10px; color: #fff; padding: 12px 14px 12px 40px; font-size: 14px; font-weight: 600;">
                            <i class="fa-solid fa-shield-halved" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #38bdf8; font-size: 15px;"></i>
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 20px;">
                        <label style="display: block; font-size: 12px; font-weight: 800; color: #fff; text-transform: uppercase; margin-bottom: 6px; letter-spacing: 0.5px;">
                            Contraseña Maestra
                        </label>
                        <div style="position: relative;">
                            <input type="password" name="password" required class="form-control" placeholder="••••••••••••" style="width: 100%; background: rgba(0,0,0,0.5); border: 1px solid rgba(56, 189, 248, 0.3); border-radius: 10px; color: #fff; padding: 12px 14px 12px 40px; font-size: 14px;">
                            <i class="fa-solid fa-key" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #38bdf8; font-size: 14px;"></i>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
                        <label style="display: flex; align-items: center; gap: 8px; color: var(--text-muted); font-size: 13px; cursor: pointer;">
                            <input type="checkbox" name="remember" checked style="accent-color: #38bdf8;"> Mantener sesión iniciada
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; background: linear-gradient(135deg, #06b6d4, #3b82f6); border: none; font-weight: 900; font-size: 15px; padding: 14px; border-radius: 10px; box-shadow: 0 4px 20px rgba(59, 130, 246, 0.4); text-transform: uppercase; letter-spacing: 0.5px;">
                        <i class="fa-solid fa-lock-open" style="margin-right: 6px;"></i> Ingresar al Panel Super Admin
                    </button>
                </form>
            </div>

            <!-- Accesos a Portales de Alumnos / Academias -->
            <div style="margin-top: 28px; padding-top: 18px; border-top: 1px solid rgba(255,255,255,0.08); text-align: center;">
                <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 10px;">
                    ¿Eres profesor o alumna?
                </div>
                <div style="display: flex; gap: 10px; justify-content: center;">
                    <a href="{{ route('login') }}" class="btn btn-secondary btn-sm" style="font-size: 12px; font-weight: 700; padding: 8px 14px;">
                        <i class="fa-solid fa-chalkboard-user"></i> Portal de Academias y Alumnas
                    </a>
                </div>
            </div>
        </div>

        <!-- Portada Derecha: Ilustración FitClass Cloud -->
        <div class="auth-image-side" style="position: relative; background: #0b1120; overflow: hidden; display: flex; align-items: center; justify-content: center;">
            <img src="/images/fitclass-admin-cover.svg" alt="FitClass SaaS Cloud" style="width: 100%; height: 100%; object-fit: cover;">
        </div>

    </div>

</div>
@endsection
