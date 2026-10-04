@extends('layouts.admin')

@section('page_title', 'Publicar Novedad')

@section('content')
<div style="max-width: 600px; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 20px; padding: 28px; backdrop-filter: blur(16px);">
    <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 20px;">Nueva Publicación para Alumnas</h3>

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

    <form action="{{ route('admin.news.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label class="form-label">Título del Comunicado *</label>
            <input type="text" name="title" value="{{ old('title') }}" class="form-control" placeholder="Ej. ¡Nuevo horario de Cardio Dance este Sábado!" required autofocus>
        </div>

        <div class="form-group">
            <label class="form-label">Sede / Local Destino</label>
            <select name="location_id" class="form-control" style="background: #111422; color: #fff;">
                <option value="">-- Publicar para Todas las Sedes --</option>
                @foreach($locations as $loc)
                    <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Contenido de la Novedad *</label>
            <textarea name="description" rows="5" class="form-control" placeholder="Escribe aquí los detalles del aviso..." required>{{ old('description') }}</textarea>
        </div>

        <div style="display: flex; gap: 12px; margin-top: 24px;">
            <button type="submit" class="btn btn-primary" style="flex: 1;">Publicar Novedad</button>
            <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
