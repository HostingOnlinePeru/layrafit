<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SaasPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SaasPlanController extends Controller
{
    public function index(): View
    {
        $plans = SaasPlan::orderBy('sort_order')->latest()->get();

        return view('superadmin.plans.index', compact('plans'));
    }

    public function create(): View
    {
        return view('superadmin.plans.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'duration_months' => ['required', 'integer', 'min:1', 'max:120'],
            'price' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'max:5'],
            'badge' => ['nullable', 'string', 'max:60'],
            'description' => ['nullable', 'string', 'max:500'],
            'features' => ['nullable', 'string'],
            'is_active' => ['boolean'],
            'sort_order' => ['nullable', 'integer'],
        ], [
            'name.required' => 'Ingresa el nombre de la membresía.',
            'duration_months.required' => 'Ingresa la duración en meses.',
            'price.required' => 'Ingresa el precio en USD.',
        ]);

        $slugBase = Str::slug($validated['name']);
        $slug = $slugBase;
        $counter = 1;
        while (SaasPlan::where('slug', $slug)->exists()) {
            $slug = "{$slugBase}-{$counter}";
            $counter++;
        }

        SaasPlan::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'duration_months' => $validated['duration_months'],
            'price' => $validated['price'],
            'currency' => strtoupper($validated['currency'] ?? 'USD'),
            'badge' => $validated['badge'],
            'description' => $validated['description'],
            'features' => $validated['features'],
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return redirect()->route('superadmin.plans.index')
            ->with('success', '¡Plan de membresía creado con éxito!');
    }

    public function edit(SaasPlan $plan): View
    {
        return view('superadmin.plans.edit', compact('plan'));
    }

    public function update(Request $request, SaasPlan $plan): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'duration_months' => ['required', 'integer', 'min:1', 'max:120'],
            'price' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'max:5'],
            'badge' => ['nullable', 'string', 'max:60'],
            'description' => ['nullable', 'string', 'max:500'],
            'features' => ['nullable', 'string'],
            'is_active' => ['boolean'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $plan->update([
            'name' => $validated['name'],
            'duration_months' => $validated['duration_months'],
            'price' => $validated['price'],
            'currency' => strtoupper($validated['currency'] ?? 'USD'),
            'badge' => $validated['badge'],
            'description' => $validated['description'],
            'features' => $validated['features'],
            'is_active' => $request->boolean('is_active'),
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return redirect()->route('superadmin.plans.index')
            ->with('success', 'Plan de membresía actualizado correctamente.');
    }

    public function toggle(SaasPlan $plan): RedirectResponse
    {
        $plan->update(['is_active' => ! $plan->is_active]);

        $status = $plan->is_active ? 'activado' : 'pausado';

        return back()->with('success', "El plan '{$plan->name}' ha sido {$status}.");
    }

    public function destroy(SaasPlan $plan): RedirectResponse
    {
        $plan->delete();

        return redirect()->route('superadmin.plans.index')
            ->with('success', 'Plan de membresía eliminado.');
    }
}
