@extends('layouts.admin')

@section('page_title', 'Editar Sede')

@section('content')
<div style="max-width: 600px; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 20px; padding: 28px; backdrop-filter: blur(16px);">
    <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 20px;">Editar Local: {{ $location->name }}</h3>

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

    <form action="{{ route('admin.locations.update', $location) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label class="form-label">Nombre del Local / Sede *</label>
            <input type="text" name="name" value="{{ old('name', $location->name) }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label class="form-label">Dirección Completa</label>
            <input type="text" name="address" value="{{ old('address', $location->address) }}" class="form-control">
        </div>

        <div class="form-group">
            <label class="form-label">Teléfono de Contacto</label>
            <input type="text" name="phone" value="{{ old('phone', $location->phone) }}" class="form-control">
        </div>

        <div class="form-group">
            <label class="form-label">Horarios de Atención</label>
            <input type="text" name="schedule" value="{{ old('schedule', $location->schedule) }}" class="form-control">
        </div>

        <div class="form-group">
            <label class="form-label">Información Adicional / Descripción</label>
            <textarea name="extra_info" rows="3" class="form-control">{{ old('extra_info', $location->extra_info) }}</textarea>
        </div>

        <div class="form-group" style="display: flex; align-items: center; gap: 8px;">
            <input type="checkbox" name="is_active" value="1" id="is_active" {{ $location->is_active ? 'checked' : '' }} style="accent-color: var(--primary);">
            <label for="is_active" style="font-size: 14px; font-weight: 500; cursor: pointer;">Sede activa para nuevos registros</label>
        </div>

        <div style="display: flex; gap: 12px; margin-top: 24px;">
            <button type="submit" class="btn btn-primary" style="flex: 1;">Actualizar Sede</button>
            <a href="{{ route('admin.locations.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
