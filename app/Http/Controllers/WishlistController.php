<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WishlistController extends Controller
{
    /**
     * Display the user's wishlist.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $wishlistItems = $user->wishlistProducts()->with('categorie')->get();

        return view('client.wishlist.index', compact('wishlistItems'));
    }

    /**
     * Add a product to the wishlist.
     */
    public function add(Request $request, Product $product): RedirectResponse
    {
        $user = $request->user();

        // Check if already in wishlist
        if ($user->wishlistProducts()->where('id_produit', $product->id_produit)->exists()) {
            return redirect()->back()
                ->with('info', 'Ce produit est déjà dans votre liste de souhaits.');
        }

        $user->wishlistProducts()->attach($product->id_produit);

        return redirect()->back()
            ->with('success', 'Produit ajouté à votre liste de souhaits.');
    }

    /**
     * Remove a product from the wishlist.
     */
    public function remove(Request $request, Product $product): RedirectResponse
    {
        $user = $request->user();

        $user->wishlistProducts()->detach($product->id_produit);

        return redirect()->back()
            ->with('success', 'Produit retiré de votre liste de souhaits.');
    }
}