<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalProducts = Product::count();
        $activeProducts = Product::where('est_actif', true)->count();
        $lowStockProducts = Product::where('stock', '<', 10)->where('est_actif', true)->count();
        $outOfStockProducts = Product::where('stock', 0)->count();
        $totalOrders = Order::count();
        $pendingOrders = Order::where('statut', 'en_attente')->count();
        $deliveredOrders = Order::where('statut', 'livree')->count();
        $totalRevenue = Order::where('statut', '!=', 'annulee')->sum('total');
        $totalCustomers = User::where('role', '!=', 'admin')->count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalReviews = Review::count();
        $totalLogs = ActivityLog::count();
        $todayLogs = ActivityLog::whereDate('created_at', today())->count();
        $uniqueUsers = ActivityLog::distinct('user_id')->count('user_id');

        $recentOrders = Order::with('user', 'items.product')->latest()->limit(5)->get();
        $topProducts = Product::withCount('orderItems')->orderByDesc('order_items_count')->limit(5)->get();
        $recentUsers = User::where('role', '!=', 'admin')->latest()->limit(8)->get();
        $recentReviews = Review::with('user', 'product')->latest()->limit(5)->get();
        $recentLogs = ActivityLog::with('user')->latest()->limit(10)->get();
        $allUsers = User::latest()->paginate(15, ['*'], 'users_page');
        $allLogs = ActivityLog::with('user')->latest()->paginate(15, ['*'], 'logs_page');
        $recentActions = ActivityLog::distinct('action')->pluck('action');

        return view('admin.dashboard', compact(
            'totalProducts', 'activeProducts', 'lowStockProducts', 'outOfStockProducts',
            'totalOrders', 'pendingOrders', 'deliveredOrders', 'totalRevenue',
            'totalCustomers', 'totalAdmins', 'totalReviews', 'totalLogs', 'todayLogs', 'uniqueUsers',
            'recentOrders', 'topProducts', 'recentUsers', 'recentReviews', 'recentLogs',
            'allUsers', 'allLogs', 'recentActions'
        ));
    }
}