@extends('layouts.student')

@section('title', 'Mis Clases — Layra Fit')

@section('content')

@php
    $canAccess = function($classTypeSlug, $classTypeName) use ($activePlans) {
        if (!$activePlans || $activePlans->isEmpty()) return false;
        
        $classTypeName = strtolower($classTypeName);
        
        foreach ($activePlans as $activePlan) {
            $planName = strtolower($activePlan->name);
            
            // Premium -> Todos
            if (str_contains($planName, 'mensual b')) return true;
            
            // Mensual A -> Zumba + Funcional
            if (str_contains($planName, 'mensual a')) {
                if (str_contains($classTypeName, 'zumba') || str_contains($classTypeName, 'funcional')) return true;
            }
            
            // Solo Zumba
            if (str_contains($planName, 'zumba')) {
                if (str_contains($classTypeName, 'zumba')) return true;
            }
        }
        
        return false;
    };
@endphp

<!-- SECCIÓN HORARIO -->
<div style="margin-bottom: 32px; margin-top: 10px;">
    <div style="text-align: center; margin-bottom: 24px;">
        <h3 style="font-size: 16px; font-weight: 800; color: #fff; text-transform: uppercase; letter-spacing: 1px; display: flex; align-items: center; justify-content: center; gap: 8px;">
            <i class="fa-solid fa-diamond" style="color: #c084fc; font-size: 10px;"></i>
            HORARIO DE CLASES
            <i class="fa-solid fa-diamond" style="color: #c084fc; font-size: 10px;"></i>
        </h3>
        <h2 style="font-size: 28px; font-weight: 900; color: #f5f5dc; text-transform: uppercase; margin-top: 4px; letter-spacing: 4px; font-family: 'Times New Roman', serif;">
            {{ now()->locale('es')->monthName }}
        </h2>
    </div>

    <!-- Calendario en Filas -->
    <div style="display: flex; flex-direction: column; gap: 12px;">
        @foreach($days as $day)
            <div style="border: 1px solid rgba(126, 34, 206, 0.8); border-radius: 12px; overflow: hidden; background: rgba(20, 5, 30, 0.95); box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);">
                <!-- Título del Día -->
                <div style="background: linear-gradient(135deg, #581c87, #3b0764); text-align: center; padding: 8px; font-weight: 800; color: #fff; text-transform: uppercase; font-size: 14px; letter-spacing: 1px; border-bottom: 1px solid rgba(126, 34, 206, 0.8);">
                    {{ $day }}
                </div>
                
                <!-- Clases del Día (Horizontales, Responsivas sin Scroll) -->
                <div style="padding: 10px 8px; display: flex; flex-direction: row; gap: 6px; justify-content: center; align-items: stretch; width: 100%; box-sizing: border-box;">
                    @foreach($schedules->where('day_of_week', $day) as $schedule)
                        @php
                            $slug = $schedule->classType->slug ?? '';
                            $name = $schedule->classType->name ?? '';
                            $hasAccess = $canAccess($slug, $name);
                        @endphp
                        
                        <div style="flex: 1; min-width: 0; text-align: center; padding: 10px 4px; border-radius: 10px; background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.1); opacity: 0.6; position: relative; display: flex; flex-direction: column; align-items: center; justify-content: center; filter: grayscale(1); {{ $hasAccess ? 'opacity: 1; filter: none; background: rgba(126, 34, 206, 0.35); border: 1px solid #c084fc; box-shadow: 0 0 10px rgba(192, 132, 252, 0.2);' : '' }}">
                            
                            @if($hasAccess)
                                <div style="position: absolute; top: -8px; left: 50%; transform: translateX(-50%); background: #c084fc; color: #fff; font-size: 9px; font-weight: 800; padding: 2px 6px; border-radius: 8px; white-space: nowrap;">TU PLAN</div>
                            @endif

                            @if(str_contains(strtolower($name), 'zumba'))
                                <i class="fa-solid fa-person-running" style="font-size: 18px; color: #d8b4fe; margin-bottom: 6px; margin-top: 4px;"></i>
                            @elseif(str_contains(strtolower($name), 'strong'))
                                <i class="fa-solid fa-dumbbell" style="font-size: 18px; color: #d8b4fe; margin-bottom: 6px; margin-top: 4px;"></i>
                            @elseif(str_contains(strtolower($name), 'funcional'))
                                <i class="fa-solid fa-hand-fist" style="font-size: 18px; color: #d8b4fe; margin-bottom: 6px; margin-top: 4px;"></i>
                            @else
                                <i class="fa-solid fa-star" style="font-size: 18px; color: #d8b4fe; margin-bottom: 6px; margin-top: 4px;"></i>
                            @endif
                            
                            <div style="font-weight: 900; font-size: 10px; color: #fff; text-transform: uppercase; line-height: 1.1; margin-bottom: 6px; letter-spacing: 0px; word-wrap: break-word; max-width: 100%;">
                                {!! str_replace(' ', '<br>', strtoupper($name)) !!}
                            </div>
                            <div style="font-size: 10px; color: #fff; font-weight: 800; background: rgba(0,0,0,0.6); display: inline-block; padding: 4px 6px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.1); white-space: nowrap;">
                                {{ \Carbon\Carbon::parse($schedule->start_time)->format('g.i A') }}
                            </div>
                        </div>
                        
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection