@extends('layouts.student')

@section('title', 'Programar Asistencia — Layra Fit')

@section('content')

@php
    $canAccess = function($classTypeSlug, $classTypeName) use ($activePlans) {
        if (!$activePlans || $activePlans->isEmpty()) return false;
        
        $classTypeName = strtolower($classTypeName);
        
        foreach ($activePlans as $activePlan) {
            $planName = strtolower($activePlan->name);
            
            if (str_contains($planName, 'mensual b')) return true;
            if (str_contains($planName, 'mensual a')) {
                if (str_contains($classTypeName, 'zumba') || str_contains($classTypeName, 'funcional')) return true;
            }
            if (str_contains($planName, 'zumba')) {
                if (str_contains($classTypeName, 'zumba')) return true;
            }
        }
        return false;
    };
@endphp

<!-- SECCIÓN CALENDARIO -->
<div style="margin-bottom: 32px; margin-top: 10px;">
    
    <div style="text-align: center; margin-bottom: 24px;">
        <h3 style="font-size: 16px; font-weight: 800; color: #fff; text-transform: uppercase; letter-spacing: 1px;">
            PROGRAMA TU ASISTENCIA
        </h3>
        
        <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 12px; background: rgba(0,0,0,0.5); border-radius: 12px; padding: 8px; border: 1px solid var(--border-color);">
            <a href="{{ route('student.calendar', ['week' => $weekOffset - 1]) }}" class="btn btn-sm" style="background: transparent; color: var(--text-muted);"><i class="fa-solid fa-chevron-left"></i> Ant</a>
            <div style="font-size: 14px; font-weight: 700; color: #fff; text-transform: uppercase;">
                Semana del {{ \Carbon\Carbon::parse(array_values($weekDays)[0])->format('d M') }}
            </div>
            <a href="{{ route('student.calendar', ['week' => $weekOffset + 1]) }}" class="btn btn-sm" style="background: transparent; color: var(--text-muted);">Sig <i class="fa-solid fa-chevron-right"></i></a>
        </div>
    </div>

    <!-- Contenedor Toast Notificación -->
    <div id="toastNotification" style="display: none; position: fixed; bottom: 85px; left: 50%; transform: translateX(-50%); background: #10b981; color: #fff; font-size: 12px; font-weight: 800; padding: 10px 18px; border-radius: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.6); z-index: 9999; text-align: center; white-space: nowrap; transition: all 0.3s ease;">
        <i class="fa-solid fa-check-circle" style="margin-right: 6px;"></i> <span id="toastMessage">Guardado</span>
    </div>

    <div style="display: flex; flex-direction: column; gap: 12px;">
        @foreach($daysDb as $dayIndex => $day)
            @php
                $dateObj = array_values($weekDays)[$dayIndex];
                $dateStr = $dateObj->format('Y-m-d');
                $isToday = $dateStr === now()->format('Y-m-d');
                
                $dayClasses = $schedules->where('day_of_week', $day);
            @endphp
            
            <div style="border: 1px solid rgba(126, 34, 206, 0.8); border-radius: 12px; overflow: hidden; background: rgba(20, 5, 30, 0.95); box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);">
                <!-- Título del Día -->
                <div style="background: {{ $isToday ? 'linear-gradient(135deg, #7e22ce, #c084fc)' : 'linear-gradient(135deg, #581c87, #3b0764)' }}; text-align: center; padding: 8px; font-weight: 800; color: #fff; text-transform: uppercase; font-size: 14px; letter-spacing: 1px; border-bottom: 1px solid rgba(126, 34, 206, 0.8);">
                    {{ $day === 'miercoles' ? 'Miércoles' : ucfirst($day) }} {{ $dateObj->format('d/m') }}
                    @if($isToday) <span style="background: #fff; color: #7e22ce; padding: 2px 6px; border-radius: 6px; font-size: 10px; margin-left: 6px;">HOY</span> @endif
                </div>
                
                <div style="padding: 10px 8px; display: flex; flex-direction: row; gap: 6px; justify-content: center; align-items: stretch; width: 100%; box-sizing: border-box;">
                    @foreach($dayClasses as $schedule)
                        @php
                            $slug = $schedule->classType->slug ?? '';
                            $name = $schedule->classType->name ?? '';
                            $hasAccess = $canAccess($slug, $name);
                            
                            $classDateTime = \Carbon\Carbon::parse($dateStr . ' ' . $schedule->start_time);
                            $isPast = $classDateTime->isPast() && !$classDateTime->isCurrentHour();
                            $isProgrammed = $attendances->where('date', $dateStr)->where('class_schedule_id', $schedule->id)->count() > 0;
                        @endphp
                        
                        <div style="flex: 1; min-width: 0; position: relative;">
                            @if($isPast)
                                <!-- CLASE PASADA: BLOQUEADA SEGÚN SI ASISTIÓ O NO -->
                                <div style="height: 100%; text-align: center; padding: 8px 4px; border-radius: 10px; background: {{ $isProgrammed ? 'rgba(16, 185, 129, 0.15)' : 'rgba(255,255,255,0.03)' }}; border: 1px solid {{ $isProgrammed ? '#10b981' : 'rgba(255,255,255,0.1)' }}; display: flex; flex-direction: column; align-items: center; justify-content: space-between;">
                                    <div style="position: absolute; top: -8px; left: 50%; transform: translateX(-50%); background: {{ $isProgrammed ? '#10b981' : '#6b7280' }}; color: #fff; font-size: 8px; font-weight: 800; padding: 2px 6px; border-radius: 8px; white-space: nowrap;">
                                        {{ $isProgrammed ? 'ASISTIÓ' : 'NO ASISTIÓ' }}
                                    </div>
                                    <div style="margin-top: 4px;">
                                        <i class="fa-solid {{ $isProgrammed ? 'fa-circle-check' : 'fa-clock-rotate-left' }}" style="font-size: 16px; color: {{ $isProgrammed ? '#34d399' : '#9ca3af' }}; margin-bottom: 4px;"></i>
                                        <div style="font-weight: 900; font-size: 9px; color: {{ $isProgrammed ? '#fff' : '#9ca3af' }}; text-transform: uppercase; line-height: 1.1; margin-bottom: 4px;">{!! str_replace(' ', '<br>', strtoupper($name)) !!}</div>
                                        <div style="font-size: 9px; color: #ccc; font-weight: 800; background: rgba(0,0,0,0.5); padding: 2px 4px; border-radius: 8px;">{{ $classDateTime->format('g.i A') }}</div>
                                    </div>
                                    <!-- Selector Bloqueado -->
                                    <div style="width: 100%; margin-top: 6px;">
                                        <select disabled style="width: 100%; font-size: 9px; font-weight: 800; padding: 3px 2px; border-radius: 6px; text-align: center; border: 1px solid {{ $isProgrammed ? '#10b981' : '#4b5563' }}; background: {{ $isProgrammed ? 'rgba(16,185,129,0.2)' : 'rgba(0,0,0,0.5)' }}; color: {{ $isProgrammed ? '#34d399' : '#9ca3af' }}; cursor: not-allowed;">
                                            <option selected>{{ $isProgrammed ? 'ASISTIÓ: SÍ' : 'ASISTIÓ: NO' }}</option>
                                        </select>
                                    </div>
                                </div>

                            @elseif(!$hasAccess)
                                <!-- NO INCLUIDA EN SU MEMBRESÍA -->
                                <div style="height: 100%; text-align: center; padding: 8px 4px; border-radius: 10px; background: rgba(239, 68, 68, 0.08); border: 1px solid rgba(239, 68, 68, 0.25); display: flex; flex-direction: column; align-items: center; justify-content: space-between;">
                                    <div style="position: absolute; top: -8px; left: 50%; transform: translateX(-50%); background: #ef4444; color: #fff; font-size: 8px; font-weight: 800; padding: 2px 6px; border-radius: 8px; white-space: nowrap;">NO INCLUIDA</div>
                                    <div style="margin-top: 4px;">
                                        <i class="fa-solid fa-lock" style="font-size: 16px; color: #f87171; margin-bottom: 4px;"></i>
                                        <div style="font-weight: 900; font-size: 9px; color: #f87171; text-transform: uppercase; line-height: 1.1; margin-bottom: 4px;">{!! str_replace(' ', '<br>', strtoupper($name)) !!}</div>
                                        <div style="font-size: 9px; color: #ccc; font-weight: 800; background: rgba(0,0,0,0.5); padding: 2px 4px; border-radius: 8px;">{{ $classDateTime->format('g.i A') }}</div>
                                    </div>
                                    <div style="width: 100%; margin-top: 6px;">
                                        <select disabled style="width: 100%; font-size: 9px; font-weight: 800; padding: 3px 2px; border-radius: 6px; text-align: center; border: 1px solid rgba(239,68,68,0.3); background: rgba(0,0,0,0.5); color: #f87171; cursor: not-allowed;">
                                            <option selected>BLOQUEADO</option>
                                        </select>
                                    </div>
                                </div>

                            @else
                                <!-- CLASE DISPONIBLE: SELECTOR ACTIVO SÍ / NO -->
                                <div id="card-{{ $schedule->id }}-{{ $dateStr }}" class="class-card-selectable" style="height: 100%; text-align: center; padding: 8px 4px; border-radius: 10px; background: {{ $isProgrammed ? 'rgba(16, 185, 129, 0.22)' : 'rgba(126, 34, 206, 0.22)' }}; border: 1px solid {{ $isProgrammed ? '#10b981' : '#c084fc' }}; box-shadow: {{ $isProgrammed ? '0 0 10px rgba(16, 185, 129, 0.2)' : '0 0 10px rgba(192, 132, 252, 0.15)' }}; display: flex; flex-direction: column; align-items: center; justify-content: space-between; transition: all 0.3s ease;">
                                    
                                    <div id="badge-{{ $schedule->id }}-{{ $dateStr }}" style="position: absolute; top: -8px; left: 50%; transform: translateX(-50%); background: {{ $isProgrammed ? '#10b981' : '#c084fc' }}; color: #fff; font-size: 8px; font-weight: 800; padding: 2px 6px; border-radius: 8px; white-space: nowrap; transition: all 0.3s ease;">
                                        {{ $isProgrammed ? '¡PROGRAMADA!' : 'DISPONIBLE' }}
                                    </div>

                                    <div style="margin-top: 4px;">
                                        @if(str_contains(strtolower($name), 'zumba'))
                                            <i class="fa-solid fa-person-running" style="font-size: 16px; color: {{ $isProgrammed ? '#34d399' : '#d8b4fe' }}; margin-bottom: 4px;"></i>
                                        @elseif(str_contains(strtolower($name), 'strong'))
                                            <i class="fa-solid fa-dumbbell" style="font-size: 16px; color: {{ $isProgrammed ? '#34d399' : '#d8b4fe' }}; margin-bottom: 4px;"></i>
                                        @elseif(str_contains(strtolower($name), 'funcional'))
                                            <i class="fa-solid fa-hand-fist" style="font-size: 16px; color: {{ $isProgrammed ? '#34d399' : '#d8b4fe' }}; margin-bottom: 4px;"></i>
                                        @else
                                            <i class="fa-solid fa-star" style="font-size: 16px; color: {{ $isProgrammed ? '#34d399' : '#d8b4fe' }}; margin-bottom: 4px;"></i>
                                        @endif
                                        
                                        <div style="font-weight: 900; font-size: 9px; color: #fff; text-transform: uppercase; line-height: 1.1; margin-bottom: 4px;">{!! str_replace(' ', '<br>', strtoupper($name)) !!}</div>
                                        <div style="font-size: 9px; color: #fff; font-weight: 800; background: rgba(0,0,0,0.5); padding: 2px 4px; border-radius: 8px;">{{ $classDateTime->format('g.i A') }}</div>
                                    </div>

                                    <!-- SELECTOR INTERACTIVO DIRECTO A BD -->
                                    <div style="width: 100%; margin-top: 6px;">
                                        <select id="select-{{ $schedule->id }}-{{ $dateStr }}" onchange="guardarAsistenciaDirecta(this, {{ $schedule->id }}, '{{ $dateStr }}')" style="width: 100%; font-size: 10px; font-weight: 800; padding: 4px 2px; border-radius: 6px; text-align: center; border: 1px solid {{ $isProgrammed ? '#10b981' : '#c084fc' }}; background: {{ $isProgrammed ? '#064e3b' : '#3b0764' }}; color: #fff; cursor: pointer; outline: none; transition: all 0.3s ease;">
                                            <option value="no" {{ !$isProgrammed ? 'selected' : '' }}>❌ NO</option>
                                            <option value="si" {{ $isProgrammed ? 'selected' : '' }}>✅ SÍ</option>
                                        </select>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>

<script>
let toastTimer = null;

function mostrarToast(mensaje, esExito = true) {
    let toast = document.getElementById('toastNotification');
    let msg = document.getElementById('toastMessage');
    
    if (toastTimer) clearTimeout(toastTimer);
    
    msg.innerText = mensaje;
    toast.style.background = esExito ? '#10b981' : '#ef4444';
    toast.style.display = 'block';
    toast.style.opacity = '1';
    
    toastTimer = setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.style.display = 'none', 300);
    }, 2500);
}

function guardarAsistenciaDirecta(selectElem, scheduleId, dateStr) {
    let valor = selectElem.value;
    let card = document.getElementById('card-' + scheduleId + '-' + dateStr);
    let badge = document.getElementById('badge-' + scheduleId + '-' + dateStr);
    
    // Deshabilitar temporalmente mientras guarda
    selectElem.disabled = true;
    selectElem.style.opacity = '0.6';
    
    fetch("{{ route('student.calendar.schedule') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            class_schedule_id: scheduleId,
            date: dateStr,
            attend: valor
        })
    })
    .then(res => res.json())
    .then(data => {
        selectElem.disabled = false;
        selectElem.style.opacity = '1';
        
        if (data.success) {
            if (valor === 'si') {
                // Modo asistiré (verde)
                card.style.background = 'rgba(16, 185, 129, 0.22)';
                card.style.borderColor = '#10b981';
                card.style.boxShadow = '0 0 10px rgba(16, 185, 129, 0.2)';
                badge.style.background = '#10b981';
                badge.innerText = '¡PROGRAMADA!';
                selectElem.style.background = '#064e3b';
                selectElem.style.borderColor = '#10b981';
                mostrarToast('¡Asistencia confirmada: SÍ!');
            } else {
                // Modo no asistiré (morado normal)
                card.style.background = 'rgba(126, 34, 206, 0.22)';
                card.style.borderColor = '#c084fc';
                card.style.boxShadow = '0 0 10px rgba(192, 132, 252, 0.15)';
                badge.style.background = '#c084fc';
                badge.innerText = 'DISPONIBLE';
                selectElem.style.background = '#3b0764';
                selectElem.style.borderColor = '#c084fc';
                mostrarToast('Asistencia desmarcada: NO');
            }
        } else {
            alert(data.error || 'No se pudo guardar la asistencia');
            // Revertir selector
            selectElem.value = (valor === 'si' ? 'no' : 'si');
        }
    })
    .catch(err => {
        selectElem.disabled = false;
        selectElem.style.opacity = '1';
        alert('Error de conexión con el servidor');
        selectElem.value = (valor === 'si' ? 'no' : 'si');
    });
}
</script>
@endsection