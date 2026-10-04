@extends('layouts.admin')

@section('page_title', 'Editar Tipo de Clase')

@section('content')
<div style="max-width: 600px; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 20px; padding: 28px; backdrop-filter: blur(16px);">
    <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 20px;">Editar Disciplina: {{ $classType->name }}</h3>

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

    <form action="{{ route('admin.class-types.update', $classType) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label class="form-label">Nombre de la Disciplina / Clase *</label>
            <input type="text" name="name" value="{{ old('name', $classType->name) }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label class="form-label">Descripción</label>
            <textarea name="description" rows="3" class="form-control">{{ old('description', $classType->description) }}</textarea>
        </div>

        <div class="form-group">
            <label class="form-label">Color Distintivo *</label>
            <input type="color" name="color_code" value="{{ old('color_code', $classType->color_code) }}" class="form-control" style="height: 44px; cursor: pointer;">
        </div>

        <div class="form-group" style="display: flex; align-items: center; gap: 8px;">
            <input type="checkbox" name="is_active" value="1" id="is_active" {{ $classType->is_active ? 'checked' : '' }} style="accent-color: var(--primary);">
            <label for="is_active" style="font-size: 14px; font-weight: 500; cursor: pointer;">Tipo de clase activo</label>
        </div>

        <div style="display: flex; gap: 12px; margin-top: 24px;">
            <button type="submit" class="btn btn-primary" style="flex: 1;">Actualizar Disciplina</button>
            <a href="{{ route('admin.class-types.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
