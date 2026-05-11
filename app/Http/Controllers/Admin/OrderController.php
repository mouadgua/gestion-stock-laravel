<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     */
    public function index(Request $request): View
    {
        $query = Order::with('user', 'items.product');

        // Filter by search (order ID, user name, user email)
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('id_commande', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($status = $request->input('status')) {
            $query->where('statut', $status);
        }

        // Filter by date
        if ($date = $request->input('date')) {
            $query->whereDate('date_commande', $date);
        }

        $orders = $query->orderByDesc('created_at')->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order): View
    {
        $order->load('user', 'items.product.categorie');

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update the status of the specified order.
     */
    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'statut' => ['required', 'in:en_attente,expediee,livree,annulee'],
        ]);

        $order->update($validated);

        return redirect()->back()
            ->with('success', 'Statut de la commande mis à jour.');
    }
}