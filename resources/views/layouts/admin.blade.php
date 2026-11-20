@extends('layouts.app')

@section('body')
<div class="app-container">
    <!-- Sidebar Admin -->
    <aside class="sidebar" id="adminSidebar">
        <div class="sidebar-header" style="padding: 16px; display: flex; align-items: center; justify-content: space-between;">
            <img src="{{ isset($currentTenant) ? $currentTenant->logo_path : '/images/logo.png' }}" alt="{{ $currentTenant->name ?? 'Layra Fit' }}" style="max-height: 40px; width: auto; max-width: 170px; display: block; filter: drop-shadow(0 2px 6px rgba(0,0,0,0.5));">
            <button type="button" id="btnCloseSidebar" class="sidebar-close-btn" aria-label="Cerrar menú" style="background: none; border: none; color: var(--accent); font-size: 20px; cursor: pointer; display: none; padding: 4px 8px;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-gauge-high nav-icon"></i> Dashboard
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
            <a href="{{ route('admin.settings.general') }}" class="nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <i class="fa-solid fa-gear nav-icon"></i> Mi Marca y Ajustes
            </a>
        </nav>
    </aside>

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <header class="top-header">
            <div class="header-left">
                <!-- Mobile Logo -->
                <div class="mobile-only-logo">
                    <img src="{{ isset($currentTenant) ? $currentTenant->logo_path : '/images/logo.png' }}" alt="{{ $currentTenant->name ?? 'Layra Fit' }}" style="max-height: 34px; width: auto; max-width: 140px; display: block;">
                </div>
                <!-- Desktop Title & Link -->
                <div class="desktop-only-title">
                    <h3 style="font-weight: 700; font-size: 18px; margin: 0;">@yield('page_title', 'Panel Administrativo')</h3>
                    @if(isset($currentTenant))
                        <a href="{{ route('tenant.login', $currentTenant->slug) }}" target="_blank" class="btn btn-secondary btn-sm" style="font-size: 11px; font-weight: 700; padding: 4px 10px; gap: 6px; border-color: rgba(192, 132, 252, 0.4); text-decoration: none;">
                            <i class="fa-solid fa-arrow-up-right-from-square" style="color: var(--primary);"></i> Ver Mi Portal Alumnas
                        </a>
                    @endif
                </div>
            </div>
            
            <div class="user-menu">
                <div class="desktop-user-info" style="text-align: right;">
                    <div style="font-weight: 600; font-size: 14px;">{{ auth()->user()->full_name }}</div>
                    <div style="font-size: 12px; color: var(--accent); font-weight: 500;">Administrador</div>
                </div>
                <div class="user-avatar" title="{{ auth()->user()->full_name }}">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <form action="{{ route('logout') }}" method="POST" style="margin-left: 8px;">
                    @csrf
                    <button type="submit" class="btn btn-secondary btn-sm" style="gap: 5px; padding: 6px 12px; font-size: 12px;" title="Cerrar Sesión">
                        <i class="fa-solid fa-right-from-bracket"></i> <span class="desktop-logout-text">Salir</span>
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

    <!-- Bottom Navigation Bar Admin Mobile -->
    <nav class="bottom-nav admin-bottom-nav">
        <a href="{{ route('admin.dashboard') }}" class="bottom-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-gauge-high bottom-nav-icon"></i>
            <span>Inicio</span>
        </a>
        <a href="{{ route('admin.students.index') }}" class="bottom-nav-item {{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
            <i class="fa-solid fa-users bottom-nav-icon"></i>
            <span>Alumnos</span>
        </a>
        <a href="{{ route('admin.attendance.index') }}" class="bottom-nav-item {{ request()->routeIs('admin.attendance.*') ? 'active' : '' }}">
            <i class="fa-solid fa-clipboard-user bottom-nav-icon"></i>
            <span>Asistencias</span>
        </a>
        <a href="{{ route('admin.payments.index') }}" class="bottom-nav-item {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
            <i class="fa-solid fa-credit-card bottom-nav-icon"></i>
            <span>Pagos</span>
        </a>
        <button type="button" class="bottom-nav-item" id="btnAdminMenuToggle" style="background: none; border: none; cursor: pointer; padding: 0;">
            <i class="fa-solid fa-bars bottom-nav-icon"></i>
            <span>Menú</span>
        </button>
    </nav>

    <!-- Mobile Sidebar Backdrop -->
    <div id="adminSidebarBackdrop" class="admin-sidebar-backdrop"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('btnAdminMenuToggle');
    const closeBtn = document.getElementById('btnCloseSidebar');
    const sidebar = document.getElementById('adminSidebar');
    const backdrop = document.getElementById('adminSidebarBackdrop');

    function openSidebar() {
        if (sidebar) sidebar.classList.add('mobile-open');
        if (backdrop) backdrop.classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        if (sidebar) sidebar.classList.remove('mobile-open');
        if (backdrop) backdrop.classList.remove('show');
        document.body.style.overflow = '';
    }

    if (toggleBtn) toggleBtn.addEventListener('click', openSidebar);
    if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
    if (backdrop) backdrop.addEventListener('click', closeSidebar);
});
</script>
@endsection
