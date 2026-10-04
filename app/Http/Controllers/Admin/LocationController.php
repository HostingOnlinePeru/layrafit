<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LocationController extends Controller
{
    public function index(): View
    {
        $locations = Location::withCount(['students', 'memberships', 'payments'])->get();

        return view('admin.locations.index', compact('locations'));
    }

    public function create(): View
    {
        return view('admin.locations.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'schedule' => ['nullable', 'string', 'max:255'],
            'extra_info' => ['nullable', 'string'],
        ], [
            'name.required' => 'El nombre del local es obligatorio.',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        Location::create($validated);

        return redirect()->route('admin.locations.index')
            ->with('success', 'Sede / Local registrado correctamente.');
    }

    public function edit(Location $location): View
    {
        return view('admin.locations.edit', compact('location'));
    }

    public function update(Request $request, Location $location): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'schedule' => ['nullable', 'string', 'max:255'],
            'extra_info' => ['nullable', 'string'],
        ], [
            'name.required' => 'El nombre del local es obligatorio.',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $location->update($validated);

        return redirect()->route('admin.locations.index')
            ->with('success', 'Datos de la sede actualizados correctamente.');
    }

    public function toggle(Location $location): RedirectResponse
    {
        $location->update(['is_active' => ! $location->is_active]);
        $status = $location->is_active ? 'activado' : 'desactivado';

        return back()->with('success', "El local '{$location->name}' fue {$status}.");
    }
}
