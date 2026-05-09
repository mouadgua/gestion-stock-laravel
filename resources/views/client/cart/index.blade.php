<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier - Boutique Virtuelle</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .cart-item:hover { transform: translateY(-2px); }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="/" class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">
                    Boutique
                </a>
                <div class="flex items-center space-x-6">
                    <a href="{{ route('products.index') }}" class="text-gray-600 hover:text-gray-900 transition">
                        Produits
                    </a>
                    <a href="{{ route('client.cart.index') }}" class="relative text-gray-600 hover:text-gray-900 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-1.638 1.638-2.517 3.71-2.293 5.708.206 1.83.987 3.327 2.293 4.126A4.904 4.904 0 0012 22a4.904 4.904 0 004.293-2.462c1.306-.799 2.087-2.296 2.293-4.126.224-1.998-.655-4.07-2.293-5.708L17 13"></path>
                        </svg>
                        @if(session('cart'))
                            <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                                {{ count(session('cart')) }}
                            </span>
                        @endif
                    </a>
                    @auth
                        <a href="{{ route('profile.edit') }}" class="text-gray-600 hover:text-gray-900 transition">
                            {{ auth()->user()->name }}
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-600 hover:text-gray-900 transition">
                                Déconnexion
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 transition">
                            Connexion
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Votre Panier</h1>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        @if(count($items) > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-2 space-y-4">
                    @foreach($items as $item)
                        <div class="cart-item bg-white rounded-xl shadow-sm border border-gray-100 p-4 transition-all duration-300">
                            <div class="flex items-center space-x-4">
                                <img src="{{ $item['product']->image ?? 'https://via.placeholder.com/100' }}" 
                                     alt="{{ $item['product']->nom_produit }}" 
                                     class="w-20 h-20 object-cover rounded-lg">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900">{{ $item['product']->nom_produit }}</h3>
                                    <p class="text-sm text-gray-500">{{ $item['product']->categorie->nom_categorie ?? 'Non catégorisé' }}</p>
                                    <p class="text-lg font-bold text-purple-600 mt-1">
                                        {{ number_format($item['product']->prix, 2) }} €
                                    </p>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <form action="{{ route('client.cart.update', $item['product']->id_produit) }}" method="POST" class="flex items-center space-x-2">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="quantity" value="{{ max(1, $item['quantity'] - 1) }}">
                                        <button type="submit" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                            </svg>
                                        </button>
                                    </form>
                                    <span class="w-8 text-center font-semibold">{{ $item['quantity'] }}</span>
                                    <form action="{{ route('client.cart.update', $item['product']->id_produit) }}" method="POST" class="flex items-center space-x-2">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="quantity" value="{{ $item['quantity'] + 1 }}">
                                        <button type="submit" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-gray-900">{{ number_format($item['subtotal'], 2) }} €</p>
                                    <form action="{{ route('client.cart.remove', $item['product']->id_produit) }}" method="POST" class="mt-2">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 text-sm transition">
                                            Supprimer
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-24">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Résumé de la commande</h2>
                        
                        <div class="space-y-4">
                            <div class="flex justify-between text-gray-600">
                                <span>Sous-total</span>
                                <span>{{ number_format($total, 2) }} €</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Livraison</span>
                                <span class="text-green-600">Gratuite</span>
                            </div>
                            <hr class="border-gray-200">
                            <div class="flex justify-between text-lg font-bold">
                                <span>Total</span>
                                <span class="text-purple-600">{{ number_format($total, 2) }} €</span>
                            </div>
                        </div>

                        <form action="{{ route('client.cart.checkout') }}" method="POST" class="mt-6">
                            @csrf
                            <button type="submit" class="btn-primary w-full py-3 px-6 rounded-lg text-white font-semibold">
                                Passer la commande
                            </button>
                        </form>

                        <div class="mt-4 text-center">
                            <a href="{{ route('products.index') }}" class="text-purple-600 hover:text-purple-700 text-sm font-medium transition">
                                Continuer les achats →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty Cart -->
            <div class="text-center py-16">
                <div class="bg-gray-100 rounded-full w-24 h-24 flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-1.638 1.638-2.517 3.71-2.293 5.708.206 1.83.987 3.327 2.293 4.126A4.904 4.904 0 0012 22a4.904 4.904 0 004.293-2.462c1.306-.799 2.087-2.296 2.293-4.126.224-1.998-.655-4.07-2.293-5.708L17 13"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Votre panier est vide</h2>
                <p class="text-gray-500 mb-8">Découvrez nos produits et ajoutez-les à votre panier.</p>
                <a href="{{ route('products.index') }}" class="btn-primary inline-block py-3 px-8 rounded-lg text-white font-semibold">
                    Voir les produits
                </a>
            </div>
        @endif
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <p class="text-center text-gray-500">© 2024 Boutique Virtuelle. Tous droits réservés.</p>
        </div>
    </footer>
</body>
</html>