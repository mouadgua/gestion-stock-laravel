<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index(Request $request)
    {
        $query = Product::where('est_actif', true)
            ->where('stock', '>', 0)
            ->with(['categorie', 'images']);

        // Search by name
        if ($request->filled('search')) {
            $query->where('nom_produit', 'like', '%' . $request->search . '%');
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('categorie_id', $request->category);
        }

        // Sort by price
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('prix', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('prix', 'desc');
                    break;
                case 'newest':
                    $query->orderByDesc('created_at');
                    break;
            }
        } else {
            $query->orderByDesc('created_at');
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::withCount(['products' => function ($q) {
            $q->where('est_actif', true)->where('stock', '>', 0);
        }])->has('products')->get();

        $wishlistIds = [];
        if (Auth::check()) {
            $wishlistIds = Auth::user()->wishlistProducts()->pluck('products.id_produit')->toArray();
        }

        return view('products.index', compact('products', 'categories', 'wishlistIds'));
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        if (!$product->est_actif) {
            abort(404);
        }

        $product->load('images');

        $relatedProducts = Product::where('est_actif', true)
            ->where('id_produit', '!=', $product->id_produit)
            ->where('categorie_id', $product->categorie_id)
            ->where('stock', '>', 0)
            ->with('images')
            ->limit(4)
            ->get();

        // Get reviews for this product
        $reviews = Review::where('product_id', $product->id_produit)
            ->where('is_approved', true)
            ->with('user')
            ->orderByDesc('created_at')
            ->get();

        // Calculate average rating
        $averageRating = $reviews->avg('rating') ?? 0;

        // Check if current user has already reviewed
        $hasReviewed = false;
        $isInWishlist = false;
        if (Auth::check()) {
            $hasReviewed = Review::where('product_id', $product->id_produit)
                ->where('user_id', Auth::id())
                ->exists();
            $isInWishlist = Auth::user()->wishlistProducts()
                ->where('products.id_produit', $product->id_produit)
                ->exists();
        }

        return view('products.show', compact('product', 'relatedProducts', 'reviews', 'averageRating', 'hasReviewed', 'isInWishlist'));
    }
}