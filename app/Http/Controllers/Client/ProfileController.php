<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the client's profile dashboard
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        // Statistics
        $totalOrders = Order::where('user_id', $user->id)->count();
        $pendingOrders = Order::where('user_id', $user->id)->where('statut', 'en_attente')->count();
        $completedOrders = Order::where('user_id', $user->id)->where('statut', 'livree')->count();
        $totalSpent = Order::where('user_id', $user->id)->where('statut', 'livree')->sum('total');

        // Recent orders
        $recentOrders = Order::where('user_id', $user->id)
            ->with('items.product')
            ->latest()
            ->limit(5)
            ->get();

        // Recent reviews
        $recentReviews = Review::where('user_id', $user->id)
            ->with('product')
            ->latest()
            ->limit(3)
            ->get();

        // Wishlist count
        $wishlistCount = $user->wishlistProducts()->count();

        return view('client.profile.index', compact(
            'totalOrders',
            'pendingOrders',
            'completedOrders',
            'totalSpent',
            'recentOrders',
            'recentReviews',
            'wishlistCount'
        ));
    }

    /**
     * Display the client's order history
     */
    public function orders(Request $request): View
    {
        $user = $request->user();

        $orders = Order::where('user_id', $user->id)
            ->with('items.product')
            ->latest()
            ->paginate(10);

        return view('client.profile.orders', compact('orders'));
    }

    /**
     * Display the client's reviews
     */
    public function reviews(Request $request): View
    {
        $user = $request->user();

        $reviews = Review::where('user_id', $user->id)
            ->with('product')
            ->latest()
            ->paginate(10);

        return view('client.profile.reviews', compact('reviews'));
    }

    /**
     * Display the client's wishlist
     */
    public function wishlist(Request $request): View
    {
        $user = $request->user();

        $wishlistItems = $user->wishlistProducts()->paginate(12);

        return view('client.profile.wishlist', compact('wishlistItems'));
    }

    /**
     * Show order details with tracking
     */
    public function showOrder(Request $request, Order $order): View
    {
        $user = $request->user();

        // Ensure user can only view their own orders
        if ($order->user_id !== $user->id) {
            abort(403);
        }

        $order->load(['items.product']);

        return view('client.profile.order-details', compact('order'));
    }
}