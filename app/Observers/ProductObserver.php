<?php

namespace App\Observers;

use App\Models\Product;
use Illuminate\Support\Str;

class ProductObserver
{
    /**
     * Handle the Product "creating" event.
     */
    public function creating(Product $product): void
    {
        if (empty($product->slug)) {
            $product->slug = Str::slug($product->nom_produit) . '-' . uniqid();
        }
    }

    /**
     * Handle the Product "updating" event.
     */
    public function updating(Product $product): void
    {
        if ($product->isDirty('nom_produit') && empty($product->slug)) {
            $product->slug = Str::slug($product->nom_produit) . '-' . uniqid();
        }
    }

    /**
     * Handle the Product "deleting" event.
     */
    public function deleting(Product $product): void
    {
        // Prevent deletion if product is in active orders
        if ($product->orderItems()->exists()) {
            // Instead of deleting, mark as inactive
            $product->est_actif = false;
            $product->save();
        }
    }
}