@extends('layouts.app')

@section('body')
<div class="app-container">
    <!-- Sidebar Super Admin -->
    <aside class="sidebar">
        <div class="sidebar-header" style="padding: 18px 16px; border-bottom: 1px solid rgba(59, 130, 246, 0.2);">
            <div style="display: flex; align-items: center; gap: 12px;">
                <img src="/images/fitclass-logo.svg" alt="FitClass SaaS" style="height: 38px; width: auto; display: block;">
            </div>
        </div>

        <div style="padding: 12px 16px; background: rgba(59, 130, 246, 0.08); border-bottom: 1px solid rgba(59, 130, 246, 0.15); margin-bottom: 10px;">
            <div style="font-size: 11px; text-transform: uppercase; font-weight: 800; color: #38bdf8; letter-spacing: 1px;">
                <i class="fa-solid fa-crown" style="margin-right: 4px; color: #f59e0b;"></i> Módulo Super Admin
            </div>
            <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">
                FitClass Multi-Tenant
            </div>
        </div>

        <nav class="sidebar-nav">
            <a href="{{ route('superadmin.dashboard') }}" class="nav-item {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-pie nav-icon"></i> Métricas Globales
            </a>
            <a href="{{ route('superadmin.tenants.index') }}" class="nav-item {{ request()->routeIs('superadmin.tenants.*') ? 'active' : '' }}">
                <i class="fa-solid fa-building-user nav-icon"></i> Academias y Cuentas
            </a>
            <a href="{{ route('superadmin.plans.index') }}" class="nav-item {{ request()->routeIs('superadmin.plans.*') ? 'active' : '' }}">
                <i class="fa-solid fa-gem nav-icon" style="color: #f59e0b;"></i> Planes de Membresía
            </a>
            <a href="{{ route('superadmin.tenants.create') }}" class="nav-item {{ request()->routeIs('superadmin.tenants.create') ? 'active' : '' }}">
                <i class="fa-solid fa-plus-circle nav-icon"></i> Crear Nueva Cuenta
            </a>
        </nav>

        <div style="padding: 16px; margin-top: auto; border-top: 1px solid var(--border-color);">
            <a href="{{ route('superadmin.plans.index') }}" style="text-decoration: none; display: block;">
                <div style="background: rgba(0,0,0,0.3); border: 1px solid rgba(59, 130, 246, 0.3); border-radius: 12px; padding: 12px; transition: all 0.2s;">
                    <div style="font-size: 11px; font-weight: 800; color: #38bdf8; margin-bottom: 4px; display: flex; justify-content: space-between; align-items: center;">
                        <span><i class="fa-solid fa-tag"></i> Tarifas FitClass</span>
                        <i class="fa-solid fa-gear" style="font-size: 10px;"></i>
                    </div>
                    <div style="font-size: 11px; color: #fff; margin-bottom: 2px;">
                        • 6 Meses: <strong>$30 USD</strong>
                    </div>
                    <div style="font-size: 11px; color: #fff;">
                        • 1 Año: <strong>$50 USD</strong>
                    </div>
                </div>
            </a>
        </div>
    </aside>

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <header class="top-header">
            <div>
                <h3 style="font-weight: 800; font-size: 18px; color: #fff;">@yield('page_title', 'Panel Super Admin FitClass')</h3>
            </div>
            
            <div class="user-menu">
                <div style="text-align: right;">
                    <div style="font-weight: 700; font-size: 14px; color: #fff;">{{ auth()->user()->full_name }}</div>
                    <div style="font-size: 11px; color: #38bdf8; font-weight: 800;">SUPER ADMINISTRADOR</div>
                </div>
                <div class="user-avatar" style="background: linear-gradient(135deg, #06b6d4, #3b82f6); color: #fff; border: 2px solid #38bdf8;">
                    <i class="fa-solid fa-crown" style="font-size: 14px;"></i>
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
                <div class="alert alert-success" style="background: rgba(16, 185, 129, 0.2); border: 1px solid #10b981; color: #34d399; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger" style="background: rgba(239, 68, 68, 0.2); border: 1px solid #ef4444; color: #f87171; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>
@endsection
