<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $orders = Order::where('user_id', $user->id)
            ->with('items.product.categorie')
            ->orderByDesc('created_at')
            ->paginate(10);
        return view('client.orders.index', compact('orders'));
    }

    public function show(Request $request, Order $order): View
    {
        $user = $request->user();
        if ($order->user_id !== $user->id) abort(403);
        $order->load('items.product.categorie');
        return view('client.orders.show', compact('order'));
    }

    public function receipt(Request $request, Order $order): View
    {
        $user = $request->user();
        if ($order->user_id !== $user->id) abort(403);
        $order->load('items.product');
        return view('client.orders.receipt', compact('order'));
    }
}