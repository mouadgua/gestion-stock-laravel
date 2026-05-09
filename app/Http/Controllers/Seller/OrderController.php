<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Display a listing of orders containing seller's products.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        // Get orders that contain products (in a real app, filter by seller_id)
        $orders = Order::whereHas('items', function($query) use ($user) {
            // In a real multi-vendor system, filter by seller
            // For now, show all orders to demonstrate functionality
            $query->whereHas('product');
        })
        ->with('user', 'items.product')
        ->orderByDesc('created_at')
        ->paginate(15);

        return view('seller.orders.index', compact('orders'));
    }

    /**
     * Display the specified order.
     */
    public function show(Request $request, Order $order): View
    {
        $order->load('user', 'items.product.categorie');

        return view('seller.orders.show', compact('order'));
    }
}