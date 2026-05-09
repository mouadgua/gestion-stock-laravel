<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index(): View
    {
        // Statistics
        $totalOrders = Order::count();
        $pendingOrders = Order::where('statut', 'en_attente')->count();
        $shippedOrders = Order::where('statut', 'expediee')->count();
        $deliveredOrders = Order::where('statut', 'livree')->count();
        $totalRevenue = Order::where('statut', '!=', 'annulee')->sum('total');
        $totalCustomers = User::where('role', 'client')->count();
        $lowStockProducts = Product::where('stock', '<', 10)->where('est_actif', true)->count();

        // Recent orders
        $recentOrders = Order::with('user', 'items.product')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // Top selling products
        $topProducts = Product::withCount('orderItems')
            ->orderByDesc('order_items_count')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalOrders',
            'pendingOrders',
            'shippedOrders',
            'deliveredOrders',
            'totalRevenue',
            'totalCustomers',
            'lowStockProducts',
            'recentOrders',
            'topProducts'
        ));
    }
}