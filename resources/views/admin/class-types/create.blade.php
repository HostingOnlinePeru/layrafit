@extends('layouts.admin')

@section('page_title', 'Crear Tipo de Clase')

@section('content')
<div style="max-width: 600px; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 20px; padding: 28px; backdrop-filter: blur(16px);">
    <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 20px;">Registrar Nueva Disciplina / Tipo de Clase</h3>

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

    <form action="{{ route('admin.class-types.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label class="form-label">Nombre de la Disciplina / Clase *</label>
            <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Ej. Zumba® o Strong Nation" required autofocus>
            <span style="font-size: 11px; color: var(--text-muted); margin-top: 4px; display: block;">Nota: Puedes incluir el símbolo ® directamente en el nombre (Ej. Zumba®).</span>
        </div>

        <div class="form-group">
            <label class="form-label">Descripción</label>
            <textarea name="description" rows="3" class="form-control" placeholder="Detalles de la clase, intensidad y beneficios">{{ old('description') }}</textarea>
        </div>

        <div class="form-group">
            <label class="form-label">Color Distintivo para Calendario *</label>
            <input type="color" name="color_code" value="{{ old('color_code', '#ff007f') }}" class="form-control" style="height: 44px; cursor: pointer;">
        </div>

        <div style="display: flex; gap: 12px; margin-top: 24px;">
            <button type="submit" class="btn btn-primary" style="flex: 1;">Guardar Tipo de Clase</button>
            <a href="{{ route('admin.class-types.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
