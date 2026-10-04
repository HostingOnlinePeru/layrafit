@extends('layouts.admin')

@section('page_title', 'Crear Sorteo')

@section('content')
<div style="max-width: 600px; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 20px; padding: 28px; backdrop-filter: blur(16px);">
    <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 20px;">Registrar Nuevo Sorteo</h3>

    @if($errors->any())
        <div class="alert alert-danger">
            <span>⚠️</span>
            <div>
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        </div>
    @endif

    <form action="{{ route('admin.raffles.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label class="form-label">Título del Sorteo *</label>
            <input type="text" name="title" value="{{ old('title') }}" class="form-control" placeholder="Ej. Sorteo de Fin de Año — Kit Deportivo Bail Fitness" required autofocus>
        </div>

        <div class="form-group">
            <label class="form-label">Premio a Sortear *</label>
            <input type="text" name="prize" value="{{ old('prize') }}" class="form-control" placeholder="Ej. 1 Zapatillas Deportivas + 1 Mes Gratis" required>
        </div>

        <div class="form-group">
            <label class="form-label">Fecha Programada para el Sorteo *</label>
            <input type="date" name="raffle_date" value="{{ old('raffle_date', now()->format('Y-m-d')) }}" class="form-control" required style="background: #111422; color: #fff;">
        </div>

        <div class="form-group">
            <label class="form-label">Descripción / Reglas del Sorteo</label>
            <textarea name="description" rows="3" class="form-control" placeholder="Detalles de entrega del premio">{{ old('description') }}</textarea>
        </div>

        <div style="display: flex; gap: 12px; margin-top: 24px;">
            <button type="submit" class="btn btn-primary" style="flex: 1;">Guardar y Habilitar Sorteo</button>
            <a href="{{ route('admin.raffles.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
