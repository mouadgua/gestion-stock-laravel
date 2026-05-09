@extends('layouts.app')

@section('title', 'Accueil - Boutique Virtuelle')

@section('content')
<!-- Hero Section -->
<div class="relative bg-gradient-to-r from-purple-600 to-indigo-600 rounded-2xl p-12 mb-12 text-white">
    <div class="max-w-2xl">
        <h1 class="text-5xl font-bold mb-4">Bienvenue sur notre Boutique</h1>
        <p class="text-xl opacity-90 mb-8">Découvrez nos produits exclusifs et profitez de nos offres exceptionnelles.</p>
        <a href="{{ route('products.index') }}" class="inline-block bg-white text-purple-600 font-semibold px-8 py-3 rounded-lg hover:bg-gray-100 transition">
            Voir les produits
        </a>
    </div>
</div>

<!-- Categories Section -->
@if($categories->count() > 0)
<div class="mb-12">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Catégories</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach($categories as $category)
        <a href="{{ route('products.index', ['category' => $category->id]) }}" 
           class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition text-center">
            <h3 class="font-semibold text-gray-900">{{ $category->nom_categorie }}</h3>
            <p class="text-sm text-gray-500 mt-1">{{ $category->products_count }} produits</p>
        </a>
        @endforeach
    </div>
</div>
@endif

<!-- Featured Products -->
<div>
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Produits en vedette</h2>
    @if($featuredProducts->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($featuredProducts as $product)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
            <a href="{{ route('products.show', $product->slug) }}">
                <img src="{{ $product->image ?? 'https://via.placeholder.com/300x200?text=' . urlencode($product->nom_produit) }}" 
                     alt="{{ $product->nom_produit }}" 
                     class="w-full h-48 object-cover">
            </a>
            <div class="p-4">
                <p class="text-sm text-gray-500 mb-1">{{ $product->categorie->nom_categorie ?? 'Non catégorisé' }}</p>
                <h3 class="font-semibold text-gray-900 mb-2 truncate">
                    <a href="{{ route('products.show', $product->slug) }}">{{ $product->nom_produit }}</a>
                </h3>
                <div class="flex items-center justify-between">
                    <span class="text-lg font-bold text-purple-600">{{ number_format($product->prix, 2) }} €</span>
                    @if($product->stock > 0)
                        <span class="text-xs text-green-600 bg-green-50 px-2 py-1 rounded">En stock</span>
                    @else
                        <span class="text-xs text-red-600 bg-red-50 px-2 py-1 rounded">Rupture</span>
                    @endif
                </div>
                <form action="{{ route('client.cart.add', $product) }}" method="POST" class="mt-3">
                    @csrf
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="w-full btn-primary text-white py-2 rounded-lg text-sm font-semibold" 
                            {{ $product->stock <= 0 ? 'disabled' : '' }}>
                        {{ $product->stock > 0 ? 'Ajouter au panier' : 'Indisponible' }}
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-12">
        <p class="text-gray-500">Aucun produit disponible pour le moment.</p>
    </div>
    @endif
</div>
@endsection