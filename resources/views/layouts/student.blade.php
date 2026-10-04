@extends('layouts.app')

@section('body')
<div style="min-height: 100vh; display: flex; flex-direction: column; background: var(--bg-dark);">
    <!-- Header Alumno Mobile -->
    <header style="height: 65px; background: rgba(15, 18, 28, 0.9); backdrop-filter: blur(16px); border-bottom: 1px solid var(--border-color); display: flex; align-items: center; justify-content: space-between; padding: 0 20px; sticky: top; top: 0; z-index: 900;">
        <div style="display: flex; align-items: center; gap: 10px;">
            <img src="/images/logo.png" alt="Layra Fit" style="max-height: 34px; width: auto; max-width: 140px; display: block;">
        </div>


        <div style="display: flex; align-items: center; gap: 10px;">
            <div class="user-avatar" style="width: 34px; height: 34px; font-size: 13px;">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-secondary btn-sm" style="padding: 4px 10px; font-size: 11px; gap: 4px;">
                    <i class="fa-solid fa-right-from-bracket"></i> Salir
                </button>
            </form>
        </div>
    </header>

    <!-- Main Content -->
    <main style="flex: 1; padding: 20px 16px 85px; max-width: 600px; width: 100%; margin: 0 auto;">
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

    <!-- Bottom Navigation Bar Alumno -->
    <nav class="bottom-nav">
        <a href="{{ route('student.dashboard') }}" class="bottom-nav-item {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-house bottom-nav-icon"></i>
            <span>Inicio</span>
        </a>
        <a href="{{ route('student.routines') }}" class="bottom-nav-item {{ request()->routeIs('student.routines*') ? 'active' : '' }}">
            <i class="fa-solid fa-person-running bottom-nav-icon"></i>
            <span>Mis Clases</span>
        </a>
        <a href="{{ route('student.calendar') }}" class="bottom-nav-item {{ request()->routeIs('student.calendar*') ? 'active' : '' }}">
            <i class="fa-solid fa-calendar-days bottom-nav-icon"></i>
            <span>Calendario</span>
        </a>
        <a href="{{ route('student.store') }}" class="bottom-nav-item {{ request()->routeIs('student.store*') ? 'active' : '' }}">
            <i class="fa-solid fa-bag-shopping bottom-nav-icon"></i>
            <span>Tienda</span>
        </a>
        <a href="{{ route('student.profile') }}" class="bottom-nav-item {{ request()->routeIs('student.profile*') ? 'active' : '' }}">
            <i class="fa-solid fa-user bottom-nav-icon"></i>
            <span>Perfil</span>
        </a>
    </nav>
</div>
@endsection

