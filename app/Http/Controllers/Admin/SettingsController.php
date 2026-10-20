<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function edit(): View
    {
        $user = auth()->user();
        $tenant = $user->tenant ?? Tenant::first();

        return view('admin.settings.general', compact('tenant'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $tenant = $user->tenant ?? Tenant::first();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:150'],
            'address' => ['nullable', 'string', 'max:255'],
            'cover_title' => ['nullable', 'string', 'max:120'],
            'cover_subtitle' => ['nullable', 'string', 'max:255'],
            'yape_number' => ['nullable', 'string', 'max:50'],
            'yape_holder' => ['nullable', 'string', 'max:150'],
            'bank_accounts' => ['nullable', 'string'],
            'primary_color' => ['nullable', 'string', 'max:30'],
            'logo' => ['nullable', 'image', 'max:4096'],
            'cover' => ['nullable', 'image', 'max:6144'],
            'yape_qr' => ['nullable', 'image', 'max:4096'],
        ], [
            'name.required' => 'El nombre de tu academia / gimnasio es obligatorio.',
            'logo.image' => 'El logotipo debe ser una imagen válida.',
            'cover.image' => 'La portada debe ser una imagen válida.',
            'yape_qr.image' => 'El QR debe ser una imagen válida.',
        ]);

        // Subir Logo
        if ($request->hasFile('logo')) {
            if ($tenant->logo_url && Storage::disk('public')->exists($tenant->logo_url)) {
                Storage::disk('public')->delete($tenant->logo_url);
            }
            $validated['logo_url'] = $request->file('logo')->store('tenants/logos', 'public');
        }

        // Subir Portada Login
        if ($request->hasFile('cover')) {
            if ($tenant->cover_url && Storage::disk('public')->exists($tenant->cover_url)) {
                Storage::disk('public')->delete($tenant->cover_url);
            }
            $validated['cover_url'] = $request->file('cover')->store('tenants/covers', 'public');
        }

        // Subir QR de Yape
        if ($request->hasFile('yape_qr')) {
            if ($tenant->yape_qr && Storage::disk('public')->exists($tenant->yape_qr)) {
                Storage::disk('public')->delete($tenant->yape_qr);
            }
            $validated['yape_qr'] = $request->file('yape_qr')->store('tenants/qrs', 'public');
        }

        unset($validated['logo'], $validated['cover']);

        $tenant->update($validated);

        return back()->with('success', '¡Identidad de marca blanca, datos de contacto y cuentas actualizadas con éxito!');
    }
}
