<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\News;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function index(): View
    {
        $newsList = News::with('location')->latest()->paginate(15);

        return view('admin.news.index', compact('newsList'));
    }

    public function create(): View
    {
        $locations = Location::where('is_active', true)->get();

        return view('admin.news.create', compact('locations'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'description' => ['required', 'string'],
            'location_id' => ['nullable', 'exists:locations,id'],
        ]);

        $validated['is_published'] = $request->boolean('is_published', true);
        $validated['published_at'] = now();

        News::create($validated);

        return redirect()->route('admin.news.index')
            ->with('success', 'Novedad publicada correctamente.');
    }
}
