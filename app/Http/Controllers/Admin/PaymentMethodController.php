<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PaymentMethodController extends Controller
{
    public function index(): View
    {
        $methods = PaymentMethod::all();

        return view('admin.settings.payment-methods', compact('methods'));
    }

    public function update(Request $request, PaymentMethod $paymentMethod): RedirectResponse
    {
        $validated = $request->validate([
            'account_number' => ['nullable', 'string', 'max:100'],
            'titular' => ['nullable', 'string', 'max:150'],
            'cci' => ['nullable', 'string', 'max:100'],
            'extra_info' => ['nullable', 'string'],
            'qr_image' => ['nullable', 'image', 'max:2048'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('qr_image')) {
            if ($paymentMethod->qr_image && Storage::disk('public')->exists($paymentMethod->qr_image)) {
                Storage::disk('public')->delete($paymentMethod->qr_image);
            }
            $path = $request->file('qr_image')->store('payment-qrs', 'public');
            $validated['qr_image'] = $path;
        }

        $paymentMethod->update($validated);

        return back()->with('success', "Configuración de '{$paymentMethod->name}' actualizada.");
    }
}
