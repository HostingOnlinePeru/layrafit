<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ClassTypeController extends Controller
{
    public function index(): View
    {
        $classTypes = ClassType::withCount('classSchedules')->latest()->get();

        return view('admin.class-types.index', compact('classTypes'));
    }

    public function create(): View
    {
        return view('admin.class-types.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'color_code' => ['required', 'string', 'max:20'],
        ], [
            'name.required' => 'El nombre del tipo de clase es obligatorio (Ej. Zumba®, Strong Nation).',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active', true);

        ClassType::create($validated);

        return redirect()->route('admin.class-types.index')
            ->with('success', "Tipo de clase '{$validated['name']}' registrado correctamente.");
    }

    public function edit(ClassType $classType): View
    {
        return view('admin.class-types.edit', compact('classType'));
    }

    public function update(Request $request, ClassType $classType): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'color_code' => ['required', 'string', 'max:20'],
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active');

        $classType->update($validated);

        return redirect()->route('admin.class-types.index')
            ->with('success', 'Tipo de clase actualizado con éxito.');
    }
}
