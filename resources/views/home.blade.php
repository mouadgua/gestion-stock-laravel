@extends('layouts.app')

@section('title', 'Accueil - Boutique Virtuelle')

@section('content')
<!-- Hero Section -->
<div class="relative overflow-hidden rounded-3xl gradient-primary mb-12">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-0 w-96 h-96 bg-white rounded-full -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-64 h-64 bg-white rounded-full translate-x-1/3 translate-y-1/3"></div>
    </div>
    <div class="relative px-8 py-20 text-center">
        <h1 class="text-5xl md:text-6xl font-bold text-white mb-6">
            Bienvenue sur notre Boutique
        </h1>
        <p class="text-xl text-white/90 mb-10 max-w-2xl mx-auto">
            Découvrez nos produits de qualité aux meilleurs prix. Livraison rapide et service client exceptionnel.
        </p>

        <!-- Search Bar -->
        <div class="max-w-2xl mx-auto">
            <form action="{{ route('products.index') }}" method="GET" class="relative">
                <div class="flex items-center bg-white rounded-full shadow-2xl overflow-hidden">
                    <div class="flex-1 flex items-center px-6">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" name="search" placeholder="Rechercher un produit..." 
                            class="w-full px-4 py-4 text-gray-700 bg-transparent focus:outline-none text-lg"
                            value="{{ request('search') }}">
                    </div>
                    <button type="submit" class="px-8 py-4 bg-gradient-to-r from-primary-500 to-indigo-500 text-white font-semibold hover:from-primary-600 hover:to-indigo-600 transition">
                        Rechercher
                    </button>
                </div>
            </form>
            <!-- Quick filters -->
            <div class="flex justify-center gap-3 mt-4">
                <span class="text-white/70 text-sm">Populaire:</span>
                <a href="{{ route('products.index') }}?sort=bestsellers" class="text-white/90 text-sm hover:text-white underline">Meilleures ventes</a>
                <a href="{{ route('products.index') }}?sort=newest" class="text-white/90 text-sm hover:text-white underline">Nouveautés</a>
                <a href="{{ route('products.index') }}?sort=promotions" class="text-white/90 text-sm hover:text-white underline">Promotions</a>
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
    <div class="bg-white rounded-2xl shadow-soft p-8 text-center card-hover">
        <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-gradient-to-r from-green-100 to-emerald-100 flex items-center justify-center">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Qualité Garantie</h3>
        <p class="text-gray-500">Tous nos produits sont vérifiés et certifiés</p>
    </div>
    <div class="bg-white rounded-2xl shadow-soft p-8 text-center card-hover">
        <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-gradient-to-r from-blue-100 to-indigo-100 flex items-center justify-center">
            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Livraison Rapide</h3>
        <p class="text-gray-500">Expédition en 24-48h ouvrées</p>
    </div>
    <div class="bg-white rounded-2xl shadow-soft p-8 text-center card-hover">
        <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-gradient-to-r from-purple-100 to-pink-100 flex items-center justify-center">
            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Paiement Sécurisé</h3>
        <p class="text-gray-500">Transactions cryptées et protégées</p>
    </div>
</div>

<!-- Recent Products -->
<div class="mb-16">
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-bold text-gray-900">Produits Récents</h2>
        <a href="{{ route('products.index') }}" class="text-primary-600 hover:text-primary-700 font-medium flex items-center gap-2">
            Voir tout
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @forelse($recentProducts as $product)
            <div class="bg-white rounded-2xl shadow-soft overflow-hidden card-hover group">
                <div class="relative overflow-hidden">
                    <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/300x200?text=Produit' }}" 
                         alt="{{ $product->nom_produit }}" 
                         class="w-full h-48 object-cover group-hover:scale-105 transition duration-500">
                    @if(!$product->isAvailable())
                        <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                            <span class="bg-red-500 text-white px-4 py-2 rounded-full text-sm font-medium">Rupture de stock</span>
                        </div>
                    @endif
                </div>
                <div class="p-5">
                    <h3 class="font-bold text-gray-900 mb-2 line-clamp-1">{{ $product->nom_produit }}</h3>
                    <p class="text-sm text-gray-500 mb-3 line-clamp-2">{{ Str::limit($product->description, 60) }}</p>
                    <div class="flex justify-between items-center">
                        <span class="text-xl font-bold text-primary-600">{{ number_format($product->prix, 2) }} €</span>
                        <a href="{{ route('products.show', $product->slug) }}" class="px-4 py-2 btn-primary text-white rounded-lg text-sm font-medium">
                            Voir
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-16">
                <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">Aucun produit disponible</h3>
                <p class="mt-2 text-gray-500">Revenez bientôt pour découvrir nos nouveautés !</p>
            </div>
        @endforelse
    </div>
</div>

<!-- CTA Section -->
<div class="bg-gradient-to-r from-gray-900 to-gray-800 rounded-3xl p-12 text-center">
    <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
        Prêt à commencer à vendre ?
    </h2>
    <p class="text-gray-300 mb-8 max-w-xl mx-auto">
        Rejoignez notre plateforme et vendez vos produits à des milliers de clients.
    </p>
    @auth
        @if(auth()->user()->isClient())
            <a href="{{ route('seller.products.index') }}" class="inline-block btn-primary text-white px-8 py-4 rounded-lg font-semibold">
                Devenir vendeur
            </a>
        @endif
    @else
        <a href="{{ route('register') }}" class="inline-block btn-primary text-white px-8 py-4 rounded-lg font-semibold">
            S'inscrire maintenant
        </a>
    @endauth
</div>
@endsection