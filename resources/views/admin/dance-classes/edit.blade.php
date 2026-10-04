@extends('layouts.admin')

@section('page_title', 'Editar Rutina / Clase')

@section('content')
<div style="max-width: 600px; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 20px; padding: 28px; backdrop-filter: blur(16px);">
    <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 20px;">Editar Rutina de Bail Fitness</h3>

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

    <form action="{{ route('admin.dance-classes.update', $danceClass) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label class="form-label">Nombre de la Rutina / Clase *</label>
            <input type="text" name="name" value="{{ old('name', $danceClass->name) }}" class="form-control" placeholder="Ej. Bail Fitness — Nivel Inicial" required autofocus>
        </div>

        <div class="form-group">
            <label class="form-label">Descripción</label>
            <textarea name="description" rows="3" class="form-control" placeholder="Detalles de la disciplina, ritmos e intensidad">{{ old('description', $danceClass->description) }}</textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
            <div class="form-group">
                <label class="form-label">Duración (Minutos) *</label>
                <input type="number" name="duration_minutes" value="{{ old('duration_minutes', $danceClass->duration_minutes) }}" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label">Nivel *</label>
                <select name="level" class="form-control" required style="background: #111422; color: #fff;">
                    <option value="Inicial" {{ old('level', $danceClass->level) == 'Inicial' ? 'selected' : '' }}>Inicial</option>
                    <option value="Intermedio" {{ old('level', $danceClass->level) == 'Intermedio' ? 'selected' : '' }}>Intermedio</option>
                    <option value="Cardio Dance" {{ old('level', $danceClass->level) == 'Cardio Dance' ? 'selected' : '' }}>Cardio Dance</option>
                    <option value="Full Body" {{ old('level', $danceClass->level) == 'Full Body' ? 'selected' : '' }}>Full Body</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Sede / Local Asignado</label>
            <select name="location_id" class="form-control" style="background: #111422; color: #fff;">
                <option value="">-- Disponible en Todas las Sedes --</option>
                @foreach($locations as $loc)
                    <option value="{{ $loc->id }}" {{ old('location_id', $danceClass->location_id) == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">URL de Video / Demostración (Opcional)</label>
            <input type="url" name="video_url" value="{{ old('video_url', $danceClass->video_url) }}" class="form-control" placeholder="https://youtube.com/...">
        </div>

        <div class="form-group" style="margin-top: 15px;">
            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; color: #fff;">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $danceClass->is_active) ? 'checked' : '' }} style="width: 18px; height: 18px;">
                Esta clase está activa y visible
            </label>
        </div>

        <div style="display: flex; gap: 12px; margin-top: 24px;">
            <button type="submit" class="btn btn-primary" style="flex: 1;">Actualizar Clase</button>
            <a href="{{ route('admin.dance-classes.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection