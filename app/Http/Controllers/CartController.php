<?php

namespace App\Http\Controllers;

use App\Mail\OrderReceipt;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Promotion;
use App\Observers\OrderObserver;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Exception;

class CartController extends Controller
{
    /**
     * Display the shopping cart.
     */
    public function index(Request $request): View
    {
        $cart = $request->session()->get('cart', []);
        $products = [];
        $total = 0;

        foreach ($cart as $productId => $quantity) {
            $product = Product::with(['categorie', 'images'])->find($productId);
            if ($product && $product->isAvailable()) {
                $subtotal = $product->finalPrice * $quantity;
                $products[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                ];
                $total += $subtotal;
            }
        }

        return view('client.cart.index', [
            'items' => $products,
            'total' => $total,
        ]);
    }

    /**
     * Add a product to the cart.
     */
    public function add(Request $request, Product $product): RedirectResponse
    {
        $quantity = (int) $request->input('quantity', 1);

        // Validate quantity
        if ($quantity < 1) {
            $quantity = 1;
        }

        // Check stock
        if (!$product->isAvailable() || $product->stock < $quantity) {
            return redirect()->back()
                ->with('error', "Le produit '{$product->nom_produit}' n'est pas disponible en quantité suffisante.");
        }

        $cart = $request->session()->get('cart', []);
        $existingQuantity = $cart[$product->id_produit] ?? 0;
        $newQuantity = $existingQuantity + $quantity;

        // Check if new total quantity is available
        if ($product->stock < $newQuantity) {
            return redirect()->back()
                ->with('error', "Quantité insuffisante pour '{$product->nom_produit}'. Stock restant: {$product->stock}");
        }

        $cart[$product->id_produit] = $newQuantity;
        $request->session()->put('cart', $cart);

        return redirect()->back()
            ->with('success', "Le produit '{$product->nom_produit}' a été ajouté au panier.");
    }

    /**
     * Update cart item quantity.
     */
    public function update(Request $request, int $productId): RedirectResponse
    {
        $quantity = (int) $request->input('quantity', 1);
        $cart = $request->session()->get('cart', []);

        if (!isset($cart[$productId])) {
            return redirect()->back()
                ->with('error', 'Produit non trouvé dans le panier.');
        }

        $product = Product::find($productId);
        if (!$product) {
            return redirect()->back()
                ->with('error', 'Produit introuvable.');
        }

        if ($quantity < 1) {
            // Remove item if quantity is 0 or less
            unset($cart[$productId]);
            $request->session()->put('cart', $cart);
            return redirect()->back()
                ->with('success', 'Produit retiré du panier.');
        }

        // Check stock
        if (!$product->isAvailable() || $product->stock < $quantity) {
            return redirect()->back()
                ->with('error', "Quantité insuffisante pour '{$product->nom_produit}'.");
        }

        $cart[$productId] = $quantity;
        $request->session()->put('cart', $cart);

        return redirect()->back()
            ->with('success', 'Panier mis à jour.');
    }

    /**
     * Remove item from cart.
     */
    public function remove(Request $request, int $productId): RedirectResponse
    {
        $cart = $request->session()->get('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            $request->session()->put('cart', $cart);
            return redirect()->back()
                ->with('success', 'Produit retiré du panier.');
        }

        return redirect()->back()
            ->with('error', 'Produit non trouvé dans le panier.');
    }

    /**
     * Apply a promo code to the cart.
     */
    public function applyPromo(Request $request): RedirectResponse
    {
        $request->validate(['promo_code' => ['required', 'string']]);

        $code = strtoupper(trim($request->promo_code));
        $promo = Promotion::where('code', $code)->first();

        if (!$promo || !$promo->isValid()) {
            return back()->withErrors(['promo_code' => 'Code promo invalide ou expiré.']);
        }

        $cart = $request->session()->get('cart', []);
        $total = 0;
        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);
            if ($product) {
                $total += $product->finalPrice * $quantity;
            }
        }

        $discount = $promo->calculateDiscount($total);
        if ($discount <= 0) {
            return back()->withErrors(['promo_code' => "Montant minimum non atteint ({$promo->min_order_amount} DH requis)."]);
        }

        $request->session()->put('cart_promo', [
            'code' => $promo->code,
            'discount' => $discount,
            'type' => $promo->discount_type,
            'value' => $promo->discount_value,
        ]);

        return back()->with('success', "Code promo appliqué : -{$discount} DH");
    }

    /**
     * Remove applied promo code from cart.
     */
    public function removePromo(Request $request): RedirectResponse
    {
        $request->session()->forget('cart_promo');
        return back()->with('success', 'Code promo retiré.');
    }

    /**
     * Show the checkout page with payment selection.
     */
    public function checkoutPage(Request $request): View|RedirectResponse
    {
        $cart = $request->session()->get('cart', []);
        $products = [];
        $total = 0;

        foreach ($cart as $productId => $quantity) {
            $product = Product::with(['categorie', 'images'])->find($productId);
            if ($product && $product->isAvailable()) {
                $subtotal = $product->finalPrice * $quantity;
                $products[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                ];
                $total += $subtotal;
            }
        }

        if (empty($products)) {
            return redirect()->route('client.cart.index')
                ->with('error', 'Votre panier est vide.');
        }

        $promo = $request->session()->get('cart_promo');
        $discount = $promo['discount'] ?? 0;
        $finalTotal = max(0, $total - $discount);

        return view('client.cart.checkout', [
            'items'      => $products,
            'total'      => $total,
            'discount'   => $discount,
            'finalTotal' => $finalTotal,
            'promo'      => $promo,
        ]);
    }

    /**
     * Process checkout and create order.
     */
    public function checkout(Request $request): RedirectResponse
    {
        $user = $request->user();
        $cart = $request->session()->get('cart', []);
        $modePaiement = $request->input('mode_paiement', 'cod');
        $cartPromo = $request->session()->get('cart_promo');

        if (empty($cart)) {
            return redirect()->route('client.cart.index')
                ->with('error', 'Votre panier est vide.');
        }

        // Validate payment mode
        if (!in_array($modePaiement, ['paypal', 'cod'])) {
            return redirect()->route('client.cart.index')
                ->with('error', 'Mode de paiement invalide.');
        }

        // Validate user has address
        if (empty($user->adresse) || empty($user->telephone)) {
            return redirect()->route('profile.edit')
                ->with('error', 'Veuillez compléter votre adresse et téléphone avant de commander.');
        }

        // Prepare items for order
        $items = [];
        foreach ($cart as $productId => $quantity) {
            $items[] = [
                'id_produit' => $productId,
                'quantite' => $quantity,
            ];
        }

        try {
            // Validate stock before creating order
            OrderObserver::validateStock($items);

            // Create order with transaction
            $discount = $cartPromo['discount'] ?? 0;
            $order = new Order([
                'user_id'             => $user->id,
                'adresse_livraison'   => $user->adresse,
                'telephone_livraison' => $user->telephone,
                'statut'              => 'en_attente',
                'mode_paiement'       => $modePaiement,
                'statut_paiement'     => 'en_attente',
                'promo_code'          => $cartPromo['code'] ?? null,
                'discount'            => $discount,
            ]);

            OrderObserver::createOrderWithItems($order, $items);

            // Clear cart and promo
            $request->session()->forget('cart');
            $request->session()->forget('cart_promo');

            // Increment promo usage
            if ($cartPromo) {
                Promotion::where('code', $cartPromo['code'])->increment('used_count');
            }

            // Send receipt email
            try {
                Mail::to($user->email)->send(new OrderReceipt($order));
            } catch (\Exception $mailException) {
                Log::warning('Receipt email failed: ' . $mailException->getMessage());
            }

            // If PayPal, redirect to payment processing
            if ($modePaiement === 'paypal') {
                return redirect()->route('paypal.process', $order)
                    ->with('info', 'Redirection vers PayPal pour le paiement...');
            }

            // If COD, order is created successfully
            return redirect()->route('client.orders.show', $order)
                ->with('success', 'Commande créée avec succès! Un reçu a été envoyé par email.');

        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }
}