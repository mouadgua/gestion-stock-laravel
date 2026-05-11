<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\CloudinaryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index(): View
    {
        $products = Product::with('categorie', 'images')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create(): View
    {
        $categories = Category::all();

        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request, CloudinaryService $cloudinary): RedirectResponse
    {
        $validated = $request->validate([
            'nom_produit' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'prix' => ['required', 'numeric', 'min:0'],
            'discount_percent' => ['nullable', 'integer', 'min:0', 'max:100'],
            'stock' => ['required', 'integer', 'min:0'],
            'categorie_id' => ['nullable', 'exists:categories,id'],
            'est_actif' => ['boolean'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpeg,png,webp,gif,avif', 'max:5120'],
        ]);

        $validated['slug'] = Str::slug($validated['nom_produit']) . '-' . uniqid();
        $validated['est_actif'] = $request->has('est_actif');
        $validated['discount_percent'] = $validated['discount_percent'] ?? 0;

        // Create product
        $product = Product::create($validated);

        // Upload images to Cloudinary
        if ($request->hasFile('images')) {
            $uploadedImages = $cloudinary->uploadMultiple($request->file('images'), 'products');
            foreach ($uploadedImages as $index => $img) {
                ProductImage::create([
                    'product_id' => $product->id_produit,
                    'url' => $img['url'],
                    'public_id' => $img['public_id'],
                    'ordre' => $index,
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Produit créé avec succès.');
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product): View
    {
        $categories = Category::all();
        $product->load('images');

        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product, CloudinaryService $cloudinary): RedirectResponse
    {
        $validated = $request->validate([
            'nom_produit' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'prix' => ['required', 'numeric', 'min:0'],
            'discount_percent' => ['nullable', 'integer', 'min:0', 'max:100'],
            'stock' => ['required', 'integer', 'min:0'],
            'categorie_id' => ['nullable', 'exists:categories,id'],
            'est_actif' => ['boolean'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpeg,png,webp,gif,avif', 'max:5120'],
        ]);

        $validated['est_actif'] = $request->has('est_actif');
        $validated['discount_percent'] = $validated['discount_percent'] ?? 0;

        $product->update($validated);

        // Upload new images to Cloudinary
        if ($request->hasFile('images')) {
            $uploadedImages = $cloudinary->uploadMultiple($request->file('images'), 'products');
            // Get current max order
            $maxOrder = $product->images()->max('ordre') ?? -1;
            foreach ($uploadedImages as $index => $img) {
                ProductImage::create([
                    'product_id' => $product->id_produit,
                    'url' => $img['url'],
                    'public_id' => $img['public_id'],
                    'ordre' => $maxOrder + 1 + $index,
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Produit mis à jour avec succès.');
    }

    /**
     * Remove a single image from a product.
     */
    public function destroyImage(Request $request, Product $product, ProductImage $image, CloudinaryService $cloudinary)
    {
        if ($image->public_id) {
            $cloudinary->delete($image->public_id);
        }
        $image->delete();

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('admin.products.edit', $product)
            ->with('success', 'Image supprimée avec succès.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product, CloudinaryService $cloudinary): RedirectResponse
    {
        // Delete images from Cloudinary
        foreach ($product->images as $image) {
            if ($image->public_id) {
                $cloudinary->delete($image->public_id);
            }
        }
        $product->images()->delete();

        // Instead of deleting, mark as inactive if it has orders
        if ($product->orderItems()->exists()) {
            $product->update(['est_actif' => false]);
            return redirect()->route('admin.products.index')
                ->with('success', 'Produit désactivé (contient des commandes).');
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produit supprimé avec succès.');
    }
}