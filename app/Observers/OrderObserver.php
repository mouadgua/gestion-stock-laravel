<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Exception;

class OrderObserver
{
    /**
     * Handle the Order "creating" event.
     */
    public function creating(Order $order): void
    {
        if (empty($order->date_commande)) {
            $order->date_commande = now()->toDateString();
        }
    }

    /**
     * Handle the Order "created" event.
     * Recalculate order total based on items.
     */
    public function created(Order $order): void
    {
        $this->recalculateTotal($order);
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        if ($order->wasChanged('statut')) {
            $this->handleStatusChange($order);
        }
    }

    /**
     * Handle the Order "deleted" event.
     * Restore stock when order is deleted.
     */
    public function deleted(Order $order): void
    {
        $this->restoreStock($order);
    }

    /**
     * Recalculate order total from its items.
     */
    private function recalculateTotal(Order $order): void
    {
        $total = $order->items()->sum('sous_total');
        $order->total = $total;
        $order->saveQuietly();
    }

    /**
     * Handle order status changes.
     */
    private function handleStatusChange(Order $order): void
    {
        // If order is cancelled, restore stock
        if ($order->statut === 'annulee') {
            $this->restoreStock($order);
        }
    }

    /**
     * Restore stock for all items in the order.
     */
    private function restoreStock(Order $order): void
    {
        foreach ($order->items as $item) {
            $product = $item->product;
            if ($product) {
                $product->stock += $item->quantite;
                $product->save();
            }
        }
    }

    /**
     * Validate stock availability before order creation.
     *
     * @throws Exception
     */
    public static function validateStock(array $items): void
    {
        foreach ($items as $item) {
            $product = Product::find($item['id_produit']);
            
            if (!$product) {
                throw new Exception("Produit introuvable: {$item['id_produit']}");
            }

            if (!$product->isAvailable()) {
                throw new Exception("Produit non disponible: {$product->nom_produit}");
            }

            if ($product->stock < $item['quantite']) {
                throw new Exception("Stock insuffisant pour: {$product->nom_produit}. Stock: {$product->stock}, Demandé: {$item['quantite']}");
            }
        }
    }

    /**
     * Create order items and update stock.
     * Uses database transaction for data integrity.
     */
    public static function createOrderWithItems(Order $order, array $items): Order
    {
        DB::transaction(function () use ($order, $items) {
            // Validate stock first
            self::validateStock($items);

            // Save the order
            $order->save();

            $total = 0;

            // Create order items and reduce stock
            foreach ($items as $item) {
                $product = Product::lockForUpdate()->find($item['id_produit']);
                
                $sousTotal = $product->finalPrice * $item['quantite'];
                
                OrderItem::create([
                    'id_commande' => $order->id_commande,
                    'id_produit' => $item['id_produit'],
                    'quantite' => $item['quantite'],
                    'sous_total' => $sousTotal,
                ]);

                // Reduce stock
                $product->stock -= $item['quantite'];
                $product->save();

                $total += $sousTotal;
            }

            // Update order total — subtract promo discount if any
            $order->total = max(0, $total - ($order->discount ?? 0));
            $order->save();
        });

        return $order->fresh();
    }

    /**
     * Update order item quantity with stock adjustment.
     */
    public static function updateOrderItemQuantity(OrderItem $orderItem, int $newQuantity): void
    {
        DB::transaction(function () use ($orderItem, $newQuantity) {
            $oldQuantity = $orderItem->quantite;
            $difference = $newQuantity - $oldQuantity;

            $product = Product::lockForUpdate()->find($orderItem->id_produit);

            if ($difference > 0) {
                // Increasing quantity - check stock
                if ($product->stock < $difference) {
                    throw new Exception("Stock insuffisant pour: {$product->nom_produit}");
                }
                $product->stock -= $difference;
            } elseif ($difference < 0) {
                // Decreasing quantity - restore stock
                $product->stock += abs($difference);
            }

            $product->save();

            // Update order item
            $orderItem->quantite = $newQuantity;
            $orderItem->sous_total = $product->finalPrice * $newQuantity;
            $orderItem->save();

            // Recalculate order total
            $order = $orderItem->order;
            $order->total = $order->items()->sum('sous_total');
            $order->save();
        });
    }

    /**
     * Remove order item and restore stock.
     */
    public static function removeOrderItem(OrderItem $orderItem): void
    {
        DB::transaction(function () use ($orderItem) {
            // Restore stock
            $product = Product::lockForUpdate()->find($orderItem->id_produit);
            $product->stock += $orderItem->quantite;
            $product->save();

            // Get order before deleting item
            $order = $orderItem->order;

            // Delete the item
            $orderItem->delete();

            // Recalculate order total
            $order->total = $order->items()->sum('sous_total');
            $order->save();
        });
    }
}