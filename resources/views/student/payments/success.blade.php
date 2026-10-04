@extends('layouts.student')

@section('title', 'Pago Registrado — Layra Fit')

@section('content')
<!-- Full-screen background -->
<div style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 0; background: url('{{ asset('images/bg-success.png') }}') center/cover no-repeat;">
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(10, 13, 22, 0.4);"></div>
</div>

<!-- Main Content -->
<div style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 70vh; text-align: center; padding: 20px; position: relative; z-index: 1;">
    
    <div style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, var(--secondary), var(--primary)); display: flex; align-items: center; justify-content: center; margin-bottom: 24px; box-shadow: 0 10px 25px rgba(116, 48, 44, 0.4);">
        <i class="fa-solid fa-check" style="font-size: 36px; color: var(--accent);"></i>
    </div>

    <h2 style="font-size: 26px; font-weight: 800; color: #fff; margin-bottom: 12px; text-shadow: 0 2px 10px rgba(0,0,0,0.8);">¡Bienvenida a</h2>
    <img src="{{ asset('images/logo.png') }}" alt="Layra Fit" style="max-height: 100px; width: auto; margin-bottom: 24px; filter: drop-shadow(0 2px 8px rgba(0,0,0,0.8));">
    
    @if(session('plan_name'))
        <p style="font-size: 16px; color: rgba(255,255,255,0.9); margin-bottom: 32px; text-shadow: 0 2px 8px rgba(0,0,0,0.9); font-weight: 600;">
            Has elegido el <strong style="color: var(--accent);">{{ session('plan_name') }}</strong>.<br>
            Tu reporte de pago ha sido enviado exitosamente y será validado a la brevedad.
        </p>
    @else
        <p style="font-size: 16px; color: rgba(255,255,255,0.9); margin-bottom: 32px; text-shadow: 0 2px 8px rgba(0,0,0,0.9); font-weight: 600;">
            Tu reporte de pago ha sido enviado exitosamente y será validado a la brevedad.
        </p>
    @endif

    <a href="{{ route('student.routines') }}" class="btn btn-primary" style="padding: 14px 32px; font-size: 16px; font-weight: 700; border-radius: 12px; display: inline-flex; gap: 8px; align-items: center; box-shadow: 0 4px 15px rgba(0,0,0,0.5);">
        <i class="fa-solid fa-person-running"></i> Ir a Mis Clases
    </a>
</div>
@endsection