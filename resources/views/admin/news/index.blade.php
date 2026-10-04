@extends('layouts.admin')

@section('page_title', 'Publicación de Novedades')

@section('content')
<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
    <div>
        <h3 style="font-size: 20px; font-weight: 700;">Novedades y Comunicados</h3>
        <p style="font-size: 13px; color: var(--text-muted);">Publica avisos, noticias y eventos para las alumnas.</p>
    </div>
    <a href="{{ route('admin.news.create') }}" class="btn btn-primary">
        <span>➕</span> Publicar Novedad
    </a>
</div>

<div style="display: flex; flex-direction: column; gap: 16px;">
    @forelse($newsList as $news)
        <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 20px; padding: 20px;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                <span style="font-size: 12px; color: var(--primary); font-weight: 700;">📅 {{ $news->published_at->format('d/m/Y H:i') }}</span>
                <span style="font-size: 12px; color: var(--accent);">🏢 {{ $news->location->name ?? 'Todas las Sedes' }}</span>
            </div>
            <h4 style="font-size: 18px; font-weight: 800; color: #fff; margin-bottom: 6px;">{{ $news->title }}</h4>
            <p style="font-size: 13px; color: var(--text-muted); line-height: 1.5;">{{ $news->description }}</p>
        </div>
    @empty
        <div style="text-align: center; color: var(--text-muted); padding: 40px;">
            No hay novedades publicadas aún.
        </div>
    @endforelse
</div>

<div style="margin-top: 16px;">
    {{ $newsList->links() }}
</div>
@endsection
