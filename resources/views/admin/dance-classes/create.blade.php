@extends('layouts.admin')

@section('page_title', 'Crear Nueva Rutina / Clase')

@section('content')
<div style="max-width: 600px; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 20px; padding: 28px; backdrop-filter: blur(16px);">
    <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 20px;">Registrar Rutina de Bail Fitness</h3>

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

    <form action="{{ route('admin.dance-classes.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label class="form-label">Nombre de la Rutina / Clase *</label>
            <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Ej. Bail Fitness — Nivel Inicial" required autofocus>
        </div>

        <div class="form-group">
            <label class="form-label">Descripción</label>
            <textarea name="description" rows="3" class="form-control" placeholder="Detalles de la disciplina, ritmos e intensidad">{{ old('description') }}</textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
            <div class="form-group">
                <label class="form-label">Duración (Minutos) *</label>
                <input type="number" name="duration_minutes" value="{{ old('duration_minutes', 60) }}" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label">Nivel *</label>
                <select name="level" class="form-control" required style="background: #111422; color: #fff;">
                    <option value="Inicial">Inicial</option>
                    <option value="Intermedio">Intermedio</option>
                    <option value="Cardio Dance">Cardio Dance</option>
                    <option value="Full Body">Full Body</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Sede / Local Asignado</label>
            <select name="location_id" class="form-control" style="background: #111422; color: #fff;">
                <option value="">-- Disponible en Todas las Sedes --</option>
                @foreach($locations as $loc)
                    <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">URL de Video / Demostración (Opcional)</label>
            <input type="url" name="video_url" value="{{ old('video_url') }}" class="form-control" placeholder="https://youtube.com/...">
        </div>

        <div style="display: flex; gap: 12px; margin-top: 24px;">
            <button type="submit" class="btn btn-primary" style="flex: 1;">Guardar Clase</button>
            <a href="{{ route('admin.dance-classes.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
