@extends('layouts.admin')

@section('page_title', 'Editar Alumna')

@section('content')
<div style="max-width: 600px; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 20px; padding: 28px; backdrop-filter: blur(16px);">
    <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 20px;">Editar Alumna: {{ $student->user->full_name }}</h3>

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

    <form action="{{ route('admin.students.update', $student) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label class="form-label">Sede / Local Asignado *</label>
            <select name="location_id" class="form-control" required style="background: #111422; color: #fff;">
                @foreach($locations as $loc)
                    <option value="{{ $loc->id }}" {{ old('location_id', $student->location_id) == $loc->id ? 'selected' : '' }}>
                        {{ $loc->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Número de Teléfono (ID de Acceso) *</label>
            <input type="text" name="phone" value="{{ old('phone', $student->user->phone) }}" class="form-control" required>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
            <div class="form-group">
                <label class="form-label">Nombre *</label>
                <input type="text" name="name" value="{{ old('name', $student->user->name) }}" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">Apellido *</label>
                <input type="text" name="last_name" value="{{ old('last_name', $student->user->last_name) }}" class="form-control" required>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Correo Electrónico (Opcional)</label>
            <input type="email" name="email" value="{{ old('email', $student->user->email) }}" class="form-control">
        </div>

        <div style="display: flex; gap: 12px; margin-top: 24px;">
            <button type="submit" class="btn btn-primary" style="flex: 1;">Actualizar Datos</button>
            <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
