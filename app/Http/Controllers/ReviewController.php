<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Store a new review for a product.
     */
    public function store(Request $request, Product $product): RedirectResponse
    {
        $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $user = $request->user();

        // Check if user already reviewed this product
        $existingReview = Review::where('product_id', $product->id_produit)
            ->where('user_id', $user->id)
            ->first();

        if ($existingReview) {
            return redirect()->back()
                ->with('error', 'Vous avez déjà laissé un avis pour ce produit.');
        }

        Review::create([
            'product_id' => $product->id_produit,
            'user_id' => $user->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_approved' => true,
        ]);

        return redirect()->back()
            ->with('success', 'Merci pour votre avis !');
    }
}