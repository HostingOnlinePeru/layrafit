@extends('layouts.admin')

@section('page_title', 'Programar Nuevo Horario')

@section('content')
<div style="max-width: 600px; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 20px; padding: 28px; backdrop-filter: blur(16px);">
    <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 20px;">Programar Clase Semanal por Sede</h3>

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

    <form action="{{ route('admin.class-schedules.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label class="form-label">Sede / Local *</label>
            <select name="location_id" class="form-control" required style="background: #111422; color: #fff;">
                <option value="">-- Selecciona el local --</option>
                @foreach($locations as $loc)
                    <option value="{{ $loc->id }}" {{ old('location_id') == $loc->id ? 'selected' : '' }}>
                        {{ $loc->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Disciplina / Tipo de Clase *</label>
            <select name="class_type_id" class="form-control" required style="background: #111422; color: #fff;">
                <option value="">-- Selecciona la disciplina (Zumba®, Strong, etc.) --</option>
                @foreach($classTypes as $ct)
                    <option value="{{ $ct->id }}" {{ old('class_type_id') == $ct->id ? 'selected' : '' }}>
                        {{ $ct->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Día de la Semana *</label>
            <select name="day_of_week" class="form-control" required style="background: #111422; color: #fff;">
                <option value="lunes">Lunes</option>
                <option value="martes">Martes</option>
                <option value="miercoles">Miércoles</option>
                <option value="jueves">Jueves</option>
                <option value="viernes">Viernes</option>
                <option value="sabado">Sábado</option>
                <option value="domingo">Domingo</option>
            </select>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
            <div class="form-group">
                <label class="form-label">Hora Inicio *</label>
                <input type="time" name="start_time" value="{{ old('start_time', '19:00') }}" class="form-control" required style="background: #111422; color: #fff;">
            </div>

            <div class="form-group">
                <label class="form-label">Hora Fin *</label>
                <input type="time" name="end_time" value="{{ old('end_time', '20:00') }}" class="form-control" required style="background: #111422; color: #fff;">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Nombre del Instructor / Coach (Opcional)</label>
            <input type="text" name="instructor_name" value="{{ old('instructor_name') }}" class="form-control" placeholder="Ej. Coach Sofía">
        </div>

        <div style="display: flex; gap: 12px; margin-top: 24px;">
            <button type="submit" class="btn btn-primary" style="flex: 1;">Guardar Horario</button>
            <a href="{{ route('admin.class-schedules.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
