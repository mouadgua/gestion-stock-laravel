<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_ligne';

    protected $fillable = [
        'id_commande',
        'id_produit',
        'quantite',
        'sous_total',
    ];

    protected $casts = [
        'quantite' => 'integer',
        'sous_total' => 'decimal:2',
    ];

    public $timestamps = true;

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'id_commande', 'id_commande');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'id_produit', 'id_produit');
    }
}