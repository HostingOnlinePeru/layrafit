<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DanceClass;
use App\Models\Location;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DanceClassController extends Controller
{
    public function index(): View
    {
        $danceClasses = DanceClass::with('location')->latest()->paginate(15);

        return view('admin.dance-classes.index', compact('danceClasses'));
    }

    public function create(): View
    {
        $locations = Location::where('is_active', true)->get();

        return view('admin.dance-classes.create', compact('locations'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'duration_minutes' => ['required', 'integer', 'min:15', 'max:180'],
            'level' => ['required', 'string'],
            'location_id' => ['nullable', 'exists:locations,id'],
            'video_url' => ['nullable', 'url', 'max:255'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        DanceClass::create($validated);

        return redirect()->route('admin.dance-classes.index')
            ->with('success', 'Rutina / Clase de Bail Fitness creada con Ã©xito.');
    }

    public function edit(DanceClass $danceClass): View
    {
        $locations = Location::where('is_active', true)->get();

        return view('admin.dance-classes.edit', compact('danceClass', 'locations'));
    }

    public function update(Request $request, DanceClass $danceClass): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'duration_minutes' => ['required', 'integer', 'min:15', 'max:180'],
            'level' => ['required', 'string'],
            'location_id' => ['nullable', 'exists:locations,id'],
            'video_url' => ['nullable', 'url', 'max:255'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $danceClass->update($validated);

        return redirect()->route('admin.dance-classes.index')
            ->with('success', 'Rutina / Clase actualizada con éxito.');
    }
}
