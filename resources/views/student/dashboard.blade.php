@extends('layouts.student')

@section('title', 'Inicio — Layra Fit')

@section('content')
<!-- Saludo Personalizado -->
<div style="margin-bottom: 20px;">
    <h2 style="font-size: 24px; font-weight: 800;">Hola, {{ $user->name }} 👋</h2>
    <p style="font-size: 13px; color: var(--text-muted);">¡Bienvenida a tu panel de Bail Fitness!</p>
</div>

<!-- Banner de Membresía Actual / Botón Empezar Mis Rutinas -->
<div style="background: linear-gradient(135deg, rgba(81, 32, 29, 0.65), rgba(42, 19, 17, 0.85)); border: 1px solid var(--border-color); border-radius: 20px; padding: 22px; margin-bottom: 24px; backdrop-filter: blur(16px); box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35);">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
        <span style="font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: var(--accent);">MI PLAN ACTUAL</span>
        @if($activeMembership)
            <span class="badge badge-active">Activa</span>
        @else
            <span class="badge badge-expired">Sin Membresía</span>
        @endif
    </div>

    @if($activeMembership)
        <h3 style="font-size: 24px; font-weight: 800; color: #fff; margin-bottom: 6px;">{{ $activeMembership->membershipPlan->name }}</h3>
        <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 14px;">
            <i class="fa-solid fa-calendar-check" style="color: var(--accent); margin-right: 4px;"></i> Vence el: <strong style="color: #fff;">{{ $activeMembership->end_date->format('d/m/Y') }}</strong>
        </p>

        <!-- Indicador de Clases Semanales Usadas -->
        <div style="background: rgba(10, 13, 22, 0.6); padding: 14px; border-radius: 14px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 12px; color: var(--text-muted);">Clases esta semana:</div>
                <div style="font-size: 18px; font-weight: 800; color: #fff;">
                    {{ $weeklyUsedClasses }} / {{ $weeklyLimit }} <span style="font-size: 12px; font-weight: 500; color: var(--text-muted);">usadas</span>
                </div>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 12px; color: var(--text-muted);">Disponibles:</div>
                <div style="font-size: 20px; font-weight: 800; color: var(--accent);">{{ $weeklyRemainingClasses }}</div>
            </div>
        </div>
    @else
        <div style="margin: 10px 0 16px;">
            <h4 style="font-size: 17px; font-weight: 800; color: #fff;">Activa tu membresía para comenzar tus rutinas</h4>
            <p style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">¡Disfruta de nuestras clases de Zumba®, Strong Nation, Funcional y más...!</p>
        </div>
        <a href="{{ route('student.payments') }}" class="btn btn-primary" style="width: 100%; padding: 14px; font-size: 15px; gap: 8px;">
            <i class="fa-solid fa-fire"></i> EMPEZAR MIS RUTINAS
        </a>
    @endif
</div>

<!-- Novedades y Avisos (Slider) -->
<div style="margin-bottom: 28px;">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px;">
        <h3 style="font-size: 17px; font-weight: 800; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-bullhorn" style="color: var(--primary);"></i> Novedades y Avisos
        </h3>
        <span style="font-size: 11px; color: var(--text-muted); font-weight: 600;">Desliza para ver más</span>
    </div>

    @if($latestNews->isNotEmpty())
        <div class="news-slider-container" id="newsSlider">
            <div class="news-slider-wrapper" id="newsSliderWrapper">
                @foreach($latestNews as $index => $news)
                    <div class="news-slide" data-index="{{ $index }}">
                        @if($news->image)
                            <img src="{{ $news->image }}" alt="{{ $news->title }}" class="news-slide-bg" loading="lazy">
                        @else
                            <div class="news-slide-bg" style="background: radial-gradient(circle at center, #2e100e 0%, #160b0a 100%);"></div>
                        @endif
                        <div class="news-slide-overlay"></div>
                        <div class="news-slide-content">
                            @php
                                $tagClass = 'tag-promo';
                                $tagIcon = 'fa-tag';
                                $tagText = 'Aviso';
                                $titleLower = mb_strtolower($news->title);
                                if (str_contains($titleLower, 'sorteo')) {
                                    $tagClass = 'tag-sorteo';
                                    $tagIcon = 'fa-gift';
                                    $tagText = 'Sorteo 23 Dic';
                                } elseif (str_contains($titleLower, 'cena')) {
                                    $tagClass = 'tag-cena';
                                    $tagIcon = 'fa-champagne-glasses';
                                    $tagText = 'Evento 23 Dic';
                                } elseif (str_contains($titleLower, 'polo') || str_contains($titleLower, 'descuento') || str_contains($titleLower, 'dcto')) {
                                    $tagClass = 'tag-promo';
                                    $tagIcon = 'fa-percent';
                                    $tagText = '15% Descuento';
                                }
                            @endphp
                            <div style="display: flex; align-items: center; justify-content: space-between;">
                                <span class="news-slide-tag {{ $tagClass }}">
                                    <i class="fa-solid {{ $tagIcon }}"></i> {{ $tagText }}
                                </span>
                                <span style="font-size: 11px; color: rgba(255,255,255,0.7); font-weight: 600;">
                                    {{ $news->published_at ? $news->published_at->format('d/m/Y') : '' }}
                                </span>
                            </div>
                            <h4 class="news-slide-title">{{ $news->title }}</h4>
                            <p class="news-slide-desc">{{ $news->description }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Flechas de navegación -->
            @if($latestNews->count() > 1)
                <button type="button" class="news-slider-arrow news-slider-prev" id="newsPrevBtn" aria-label="Anterior">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <button type="button" class="news-slider-arrow news-slider-next" id="newsNextBtn" aria-label="Siguiente">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>

                <!-- Indicadores Dots -->
                <div class="news-slider-dots" id="newsSliderDots">
                    @foreach($latestNews as $index => $news)
                        <button type="button" class="news-slider-dot {{ $index === 0 ? 'active' : '' }}" data-slide="{{ $index }}" aria-label="Slide {{ $index + 1 }}"></button>
                    @endforeach
                </div>
            @endif
        </div>
    @else
        <div style="text-align: center; color: var(--text-muted); padding: 20px; font-size: 13px; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px;">
            No hay novedades recientes publicadas.
        </div>
    @endif
</div>

<!-- Accesos Rápidos -->
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
    <a href="{{ route('student.routines') }}" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 18px; display: flex; flex-direction: column; gap: 8px;">
        <div style="font-size: 24px; color: var(--accent);"><i class="fa-solid fa-person-running"></i></div>
        <div style="font-size: 15px; font-weight: 700; color: #fff;">Ver Rutinas</div>
        <div style="font-size: 12px; color: var(--text-muted);">Clases de baile disponibles</div>
    </a>

    <a href="{{ route('student.calendar') }}" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 18px; display: flex; flex-direction: column; gap: 8px;">
        <div style="font-size: 24px; color: var(--accent);"><i class="fa-solid fa-calendar-days"></i></div>
        <div style="font-size: 15px; font-weight: 700; color: #fff;">Mi Calendario</div>
        <div style="font-size: 12px; color: var(--text-muted);">Mis asistencias tomadas</div>
    </a>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const wrapper = document.getElementById('newsSliderWrapper');
    const container = document.getElementById('newsSlider');
    if (!wrapper || !container) return;

    const slides = wrapper.querySelectorAll('.news-slide');
    const dots = document.querySelectorAll('.news-slider-dot');
    const prevBtn = document.getElementById('newsPrevBtn');
    const nextBtn = document.getElementById('newsNextBtn');
    const totalSlides = slides.length;
    if (totalSlides <= 1) return;

    let currentIndex = 0;
    let autoPlayTimer = null;
    let touchStartX = 0;
    let touchEndX = 0;

    function goToSlide(index) {
        if (index < 0) {
            currentIndex = totalSlides - 1;
        } else if (index >= totalSlides) {
            currentIndex = 0;
        } else {
            currentIndex = index;
        }
        wrapper.style.transform = `translateX(-${currentIndex * 100}%)`;
        dots.forEach((dot, idx) => {
            if (idx === currentIndex) {
                dot.classList.add('active');
            } else {
                dot.classList.remove('active');
            }
        });
    }

    function nextSlide() {
        goToSlide(currentIndex + 1);
    }

    function prevSlide() {
        goToSlide(currentIndex - 1);
    }

    function startAutoPlay() {
        stopAutoPlay();
        autoPlayTimer = setInterval(nextSlide, 5000);
    }

    function stopAutoPlay() {
        if (autoPlayTimer) {
            clearInterval(autoPlayTimer);
            autoPlayTimer = null;
        }
    }

    if (nextBtn) nextBtn.addEventListener('click', () => { nextSlide(); startAutoPlay(); });
    if (prevBtn) prevBtn.addEventListener('click', () => { prevSlide(); startAutoPlay(); });

    dots.forEach(dot => {
        dot.addEventListener('click', () => {
            const idx = parseInt(dot.getAttribute('data-slide'), 10);
            goToSlide(idx);
            startAutoPlay();
        });
    });

    // Touch swipe support for mobile
    container.addEventListener('touchstart', e => {
        touchStartX = e.changedTouches[0].screenX;
        stopAutoPlay();
    }, { passive: true });

    container.addEventListener('touchend', e => {
        touchEndX = e.changedTouches[0].screenX;
        const diff = touchStartX - touchEndX;
        if (Math.abs(diff) > 40) {
            if (diff > 0) {
                nextSlide();
            } else {
                prevSlide();
            }
        }
        startAutoPlay();
    }, { passive: true });

    container.addEventListener('mouseenter', stopAutoPlay);
    container.addEventListener('mouseleave', startAutoPlay);

    startAutoPlay();
});
</script>
@endpush
