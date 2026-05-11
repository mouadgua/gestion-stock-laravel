<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_commande';

    protected $fillable = [
        'user_id',
        'date_commande',
        'total',
        'statut',
        'adresse_livraison',
        'telephone_livraison',
        'mode_paiement',
        'paypal_paiement_id',
        'paypal_payer_id',
        'statut_paiement',
    ];

    protected $casts = [
        'date_commande' => 'date',
        'total' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'id_commande', 'id_commande');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopePending($query)
    {
        return $query->where('statut', 'en_attente');
    }

    public function scopeShipped($query)
    {
        return $query->where('statut', 'expediee');
    }

    public function scopeDelivered($query)
    {
        return $query->where('statut', 'livree');
    }

    public function scopeCancelled($query)
    {
        return $query->where('statut', 'annulee');
    }
}