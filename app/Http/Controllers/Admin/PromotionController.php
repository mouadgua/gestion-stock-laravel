<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::orderByDesc('created_at')->paginate(20);
        return view('admin.promotions.index', compact('promotions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'              => ['nullable', 'string', 'max:50', 'unique:promotions,code'],
            'discount_type'     => ['required', 'in:percentage,fixed'],
            'discount_value'    => ['required', 'numeric', 'min:0.01'],
            'min_order_amount'  => ['nullable', 'numeric', 'min:0'],
            'max_uses'          => ['nullable', 'integer', 'min:0'],
            'expires_at'        => ['nullable', 'date', 'after:now'],
            'quantity'          => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        // Generate code(s)
        $quantity = (int) ($validated['quantity'] ?? 1);
        $generated = 0;

        for ($i = 0; $i < $quantity; $i++) {
            $code = !empty($validated['code']) && $quantity === 1
                ? strtoupper($validated['code'])
                : 'VAULT-' . strtoupper(Str::random(8));

            // Ensure unique if bulk
            while (Promotion::where('code', $code)->exists()) {
                $code = 'VAULT-' . strtoupper(Str::random(8));
            }

            Promotion::create([
                'code'             => $code,
                'discount_type'    => $validated['discount_type'],
                'discount_value'   => $validated['discount_value'],
                'min_order_amount' => $validated['min_order_amount'] ?? 0,
                'max_uses'         => $validated['max_uses'] ?? 0,
                'expires_at'       => $validated['expires_at'] ?? null,
                'is_active'        => true,
            ]);
            $generated++;
        }

        return back()->with('success', "{$generated} code(s) promo créé(s) avec succès.");
    }

    public function toggle(Promotion $promotion)
    {
        $promotion->update(['is_active' => !$promotion->is_active]);
        return back()->with('success', 'Statut du code mis à jour.');
    }

    public function destroy(Promotion $promotion)
    {
        $promotion->delete();
        return back()->with('success', 'Code promo supprimé.');
    }
}
