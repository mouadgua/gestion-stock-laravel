<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_produit';

    protected $fillable = [
        'nom_produit',
        'description',
        'prix',
        'stock',
        'image',
        'slug',
        'est_actif',
        'categorie_id',
    ];

    protected $casts = [
        'prix' => 'decimal:2',
        'stock' => 'integer',
        'est_actif' => 'boolean',
    ];

    public function categorie(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'categorie_id');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'id_produit');
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class, 'id_produit');
    }

    public function wishedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'wishlists', 'id_produit', 'user_id');
    }

    public function isAvailable(): bool
    {
        return $this->stock > 0 && $this->est_actif;
    }
}