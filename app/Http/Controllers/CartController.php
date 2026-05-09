<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Observers\OrderObserver;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
            $product = Product::with('categorie')->find($productId);
            if ($product && $product->isAvailable()) {
                $subtotal = $product->prix * $quantity;
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
     * Process checkout and create order.
     */
    public function checkout(Request $request): RedirectResponse
    {
        $user = $request->user();
        $cart = $request->session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('client.cart.index')
                ->with('error', 'Votre panier est vide.');
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
            $order = new Order([
                'user_id' => $user->id,
                'adresse_livraison' => $user->adresse,
                'telephone_livraison' => $user->telephone,
                'statut' => 'en_attente',
            ]);

            OrderObserver::createOrderWithItems($order, $items);

            // Clear cart
            $request->session()->forget('cart');

            return redirect()->route('client.orders.show', $order)
                ->with('success', 'Commande créée avec succès!');

        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }
}