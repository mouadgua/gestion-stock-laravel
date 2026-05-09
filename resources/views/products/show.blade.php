@extends('layouts.app')

@section('title', $product->nom_produit . ' - Boutique Virtuelle')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
    <!-- Product Image -->
    <div>
        <img src="{{ $product->image ?? 'https://via.placeholder.com/600x400?text=' . urlencode($product->nom_produit) }}" 
             alt="{{ $product->nom_produit }}" 
             class="w-full rounded-xl shadow-md">
    </div>

    <!-- Product Info -->
    <div>
        <p class="text-sm text-purple-600 font-medium mb-2">{{ $product->categorie->nom_categorie ?? 'Non catégorisé' }}</p>
        <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $product->nom_produit }}</h1>
        <p class="text-3xl font-bold text-purple-600 mb-6">{{ number_format($product->prix, 2) }} €</p>

        <div class="mb-6">
            @if($product->stock > 0)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    En stock - {{ $product->stock }} disponibles
                </span>
            @else
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                    Rupture de stock
                </span>
            @endif
        </div>

        <div class="prose max-w-none mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Description</h3>
            <p class="text-gray-600">{{ $product->description ?? 'Aucune description disponible.' }}</p>
        </div>

        @if($product->stock > 0)
            <form action="{{ route('client.cart.add', $product) }}" method="POST" class="flex gap-4">
                @csrf
                <div class="flex items-center border border-gray-300 rounded-lg">
                    <button type="button" onclick="decrementQty()" class="px-4 py-2 text-gray-600 hover:bg-gray-100">-</button>
                    <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $product->stock }}" 
                           class="w-16 text-center border-0 focus:ring-0">
                    <button type="button" onclick="incrementQty()" class="px-4 py-2 text-gray-600 hover:bg-gray-100">+</button>
                </div>
                <button type="submit" class="flex-1 btn-primary text-white py-3 px-6 rounded-lg font-semibold">
                    Ajouter au panier
                </button>
            </form>
        @endif

        <!-- Wishlist Button -->
        @auth
            <form action="{{ route('client.wishlist.add', $product) }}" method="POST" class="mt-4">
                @csrf
                <button type="submit" class="text-gray-500 hover:text-red-500 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    Ajouter à la liste de souhaits
                </button>
            </form>
        @endauth
    </div>
</div>

<!-- Related Products -->
@if($relatedProducts->count() > 0)
    <div>
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Produits similaires</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($relatedProducts as $related)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
                    <a href="{{ route('products.show', $related->slug) }}">
                        <img src="{{ $related->image ?? 'https://via.placeholder.com/300x200?text=' . urlencode($related->nom_produit) }}" 
                             alt="{{ $related->nom_produit }}" 
                             class="w-full h-40 object-cover">
                    </a>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 mb-2 truncate">{{ $related->nom_produit }}</h3>
                        <span class="text-lg font-bold text-purple-600">{{ number_format($related->prix, 2) }} €</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

<script>
function decrementQty() {
    const input = document.getElementById('quantity');
    if (input.value > 1) input.value = parseInt(input.value) - 1;
}
function incrementQty() {
    const input = document.getElementById('quantity');
    if (input.value < {{ $product->stock }}) input.value = parseInt(input.value) + 1;
}
</script>
@endsection