<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Observers\OrderObserver;
use App\Services\PayPalService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PayPalController extends Controller
{
    protected PayPalService $paypalService;

    public function __construct(PayPalService $paypalService)
    {
        $this->paypalService = $paypalService;
    }

    /**
     * Process PayPal payment after checkout.
     */
    public function processPayment(Request $request, Order $order): RedirectResponse
    {
        // Verify the order belongs to the authenticated user
        if ($order->user_id !== $request->user()->id) {
            abort(403);
        }

        // Verify the order is still pending and using PayPal
        if ($order->statut !== 'en_attente' || $order->mode_paiement !== 'paypal') {
            return redirect()->route('client.orders.show', $order)
                ->with('error', 'Cette commande ne peut pas être payée en ligne.');
        }

        // ⚠️ CONVERSION OBLIGATOIRE : PayPal ne supporte pas le MAD (Dirham)
        // Exemple: 1 Euro = 10.8 Dirhams (Tu peux ajuster ce taux ou le rendre dynamique)
        $tauxDeChange = 10.8;
        $totalEnEuro = round($order->total / $tauxDeChange, 2);

        // Create PayPal order en forçant la devise EUR ou USD
        $paypalOrder = $this->paypalService->createOrder(
            $totalEnEuro,
            'EUR', 
            "Commande The Vault #{$order->id_commande}"
        );

        if (!$paypalOrder || !isset($paypalOrder['id'])) {
            return redirect()->route('client.orders.show', $order)
                ->with('error', 'Impossible de créer le paiement PayPal. Veuillez réessayer.');
        }

        // Store PayPal order ID on our order
        $order->update([
            'paypal_paiement_id' => $paypalOrder['id'],
        ]);

        // Find approval URL to redirect user
        $approvalUrl = null;
        
        // 🛠 CORRECTION ICI : Remplacement de 'payer-action' par 'approve'
        foreach ($paypalOrder['links'] as $link) {
            if ($link['rel'] === 'approve') { 
                $approvalUrl = $link['href'];
                break;
            }
        }

        if (!$approvalUrl) {
            return redirect()->route('client.orders.show', $order)
                ->with('error', 'Erreur de liaison PayPal. Veuillez réessayer.');
        }

        // Redirect to PayPal for payment approval
        return redirect()->away($approvalUrl);
    }

    /**
     * Handle successful PayPal payment return.
     */
    public function success(Request $request): RedirectResponse
    {
        $token = $request->query('token');
        $payerId = $request->query('PayerID');

        if (!$token || !$payerId) {
            return redirect()->route('client.orders.index')
                ->with('error', 'Paramètres de paiement manquants.');
        }

        // Find the order by PayPal payment ID
        $order = Order::where('paypal_paiement_id', $token)->first();

        if (!$order) {
            return redirect()->route('client.orders.index')
                ->with('error', 'Commande introuvable pour ce paiement.');
        }

        // Capture the PayPal order
        $captureResult = $this->paypalService->captureOrder($token);

        if (!$captureResult) {
            $order->update([
                'statut_paiement' => 'echoue',
            ]);

            return redirect()->route('client.orders.show', $order)
                ->with('error', 'Le paiement a échoué. Veuillez contacter le support.');
        }

        // Check capture status
        $status = $captureResult['status'] ?? '';
        $captureId = null;

        if ($status === 'COMPLETED') {
            // Get the capture ID from the response
            if (isset($captureResult['purchase_units'][0]['payments']['captures'][0]['id'])) {
                $captureId = $captureResult['purchase_units'][0]['payments']['captures'][0]['id'];
            }

            $order->update([
                'paypal_payer_id' => $payerId,
                'paypal_paiement_id' => $captureId ?? $token,
                'statut_paiement' => 'paye',
            ]);

            return redirect()->route('client.orders.show', $order)
                ->with('success', 'Paiement PayPal effectué avec succès ! Votre commande est confirmée.');
        }

        $order->update([
            'statut_paiement' => 'echoue',
        ]);

        return redirect()->route('client.orders.show', $order)
            ->with('error', 'Le paiement n\'a pas été complété. Veuillez réessayer.');
    }

    public function cancel(Request $request): RedirectResponse
    {
        $token = $request->query('token');

        if ($token) {
            $order = Order::where('paypal_paiement_id', $token)->first();

            if ($order) {
                $order->update([
                    'statut_paiement' => 'echoue',
                ]);

                return redirect()->route('client.orders.show', $order)
                    ->with('info', 'Paiement PayPal annulé. Vous pouvez réessayer ou choisir un autre mode de paiement.');
            }
        }

        return redirect()->route('client.orders.index')
            ->with('info', 'Paiement PayPal annulé.');
    }
}