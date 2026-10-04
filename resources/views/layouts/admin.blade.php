@extends('layouts.app')

@section('body')
<div class="app-container">
    <!-- Sidebar Admin -->
    <aside class="sidebar">
        <div class="sidebar-header" style="padding: 16px;">
            <img src="/images/logo.png" alt="Layra Fit" style="max-height: 42px; width: auto; max-width: 180px; display: block; filter: drop-shadow(0 2px 6px rgba(0,0,0,0.5));">
        </div>


        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-line nav-icon"></i> Dashboard
            </a>
            <a href="{{ route('admin.students.index') }}" class="nav-item {{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
                <i class="fa-solid fa-users nav-icon"></i> Alumnos
            </a>
            <a href="{{ route('admin.locations.index') }}" class="nav-item {{ request()->routeIs('admin.locations.*') ? 'active' : '' }}">
                <i class="fa-solid fa-location-dot nav-icon"></i> Sedes / Locales
            </a>
            <a href="{{ route('admin.payments.index') }}" class="nav-item {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                <i class="fa-solid fa-credit-card nav-icon"></i> Pagos
            </a>
            <a href="{{ route('admin.memberships.index') }}" class="nav-item {{ request()->routeIs('admin.memberships.*') ? 'active' : '' }}">
                <i class="fa-solid fa-id-card nav-icon"></i> Membresías
            </a>
            <a href="{{ route('admin.attendance.index') }}" class="nav-item {{ request()->routeIs('admin.attendance.*') ? 'active' : '' }}">
                <i class="fa-solid fa-clipboard-user nav-icon"></i> Asistencias
            </a>
            <a href="{{ route('admin.class-types.index') }}" class="nav-item {{ request()->routeIs('admin.class-types.*') ? 'active' : '' }}">
                <i class="fa-solid fa-fire-flame-curved nav-icon"></i> Disciplinas (Zumba®, Strong)
            </a>
            <a href="{{ route('admin.class-schedules.index') }}" class="nav-item {{ request()->routeIs('admin.class-schedules.*') ? 'active' : '' }}">
                <i class="fa-solid fa-clock nav-icon"></i> Horarios Semanales
            </a>
            <a href="{{ route('admin.dance-classes.index') }}" class="nav-item {{ request()->routeIs('admin.dance-classes.*') ? 'active' : '' }}">
                <i class="fa-solid fa-person-running nav-icon"></i> Clases & Rutinas
            </a>
            <a href="{{ route('admin.news.index') }}" class="nav-item {{ request()->routeIs('admin.news.*') ? 'active' : '' }}">
                <i class="fa-solid fa-bullhorn nav-icon"></i> Novedades
            </a>
            <a href="{{ route('admin.raffles.index') }}" class="nav-item {{ request()->routeIs('admin.raffles.*') ? 'active' : '' }}">
                <i class="fa-solid fa-gift nav-icon"></i> Sorteos
            </a>
            <a href="{{ route('admin.settings.payment-methods') }}" class="nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <i class="fa-solid fa-gear nav-icon"></i> Configuración
            </a>
        </nav>
    </aside>

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <header class="top-header">
            <div>
                <h3 style="font-weight: 700; font-size: 18px;">@yield('page_title', 'Panel Administrativo')</h3>
            </div>
            
            <div class="user-menu">
                <div style="text-align: right;">
                    <div style="font-weight: 600; font-size: 14px;">{{ auth()->user()->full_name }}</div>
                    <div style="font-size: 12px; color: var(--accent); font-weight: 500;">Administrador</div>
                </div>
                <div class="user-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <form action="{{ route('logout') }}" method="POST" style="margin-left: 10px;">
                    @csrf
                    <button type="submit" class="btn btn-secondary btn-sm" style="gap: 6px;">
                        <i class="fa-solid fa-right-from-bracket"></i> Salir
                    </button>
                </form>
            </div>
        </header>

        <main class="content-area">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>
@endsection
