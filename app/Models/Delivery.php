<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'deliveryman_id',
        'status',
        'qr_code',
        'scanned_at',
        'delivered_at',
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id_commande');
    }

    public function deliveryman()
    {
        return $this->belongsTo(User::class, 'deliveryman_id');
    }

    public function reviews()
    {
        return $this->hasMany(DeliveryReview::class);
    }
}