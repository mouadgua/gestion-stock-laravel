<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class DeliveryController extends Controller
{
    /**
     * Display the deliveryman dashboard.
     */
    public function dashboard(Request $request): View
    {
        $user = $request->user();
        
        // Get deliveries assigned to this deliveryman
        $pendingDeliveries = Delivery::where('deliveryman_id', $user->id)
            ->where('status', 'pending')
            ->with('order')
            ->orderByDesc('created_at')
            ->get();

        $inTransitDeliveries = Delivery::where('deliveryman_id', $user->id)
            ->where('status', 'in_transit')
            ->with('order')
            ->orderByDesc('created_at')
            ->get();

        // Stats
        $todayDeliveries = Delivery::where('deliveryman_id', $user->id)
            ->whereDate('delivered_at', today())
            ->count();

        $weekDeliveries = Delivery::where('deliveryman_id', $user->id)
            ->whereBetween('delivered_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();

        $totalDeliveries = Delivery::where('deliveryman_id', $user->id)
            ->where('status', 'delivered')
            ->count();

        return view('delivery.dashboard', compact(
            'pendingDeliveries',
            'inTransitDeliveries',
            'todayDeliveries',
            'weekDeliveries',
            'totalDeliveries'
        ));
    }

    /**
     * Show the QR code scanner page.
     */
    public function scan(): View
    {
        return view('delivery.scan');
    }

    /**
     * Process scanned QR code.
     */
    public function processScan(Request $request): RedirectResponse
    {
        $request->validate([
            'qr_code' => 'required|string|exists:deliveries,qr_code',
        ]);

        $delivery = Delivery::where('qr_code', $request->qr_code)
            ->where('deliveryman_id', $request->user()->id)
            ->first();

        if (!$delivery) {
            return redirect()->back()
                ->with('error', 'Code QR non valide ou non assigné à votre compte.');
        }

        if ($delivery->status === 'delivered') {
            return redirect()->back()
                ->with('info', 'Cette livraison a déjà été effectuée.');
        }

        // Update delivery status
        $delivery->update([
            'status' => 'delivered',
            'delivered_at' => now(),
        ]);

        // Update order status
        $delivery->order->update([
            'statut' => 'livree',
        ]);

        return redirect()->route('delivery.dashboard')
            ->with('success', 'Livraison marquée comme effectuée avec succès !');
    }

    /**
     * Display delivery history.
     */
    public function history(): View
    {
        $user = auth()->user();
        
        $deliveries = Delivery::where('deliveryman_id', $user->id)
            ->where('status', 'delivered')
            ->with('order')
            ->orderByDesc('delivered_at')
            ->paginate(15);

        return view('delivery.history', compact('deliveries'));
    }

    /**
     * Update delivery status.
     */
    public function updateStatus(Request $request, Delivery $delivery): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:pending,in_transit,delivered',
        ]);

        if ($delivery->deliveryman_id !== $request->user()->id) {
            abort(403);
        }

        $updateData = ['status' => $request->status];
        
        if ($request->status === 'in_transit') {
            $updateData['scanned_at'] = now();
        }
        
        if ($request->status === 'delivered') {
            $updateData['delivered_at'] = now();
            $delivery->order->update(['statut' => 'livree']);
        }

        $delivery->update($updateData);

        return redirect()->back()
            ->with('success', 'Statut mis à jour avec succès.');
    }
}