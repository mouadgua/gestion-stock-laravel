@extends('layouts.app')

@section('title', 'Ma Liste de Souhaits - Boutique Virtuelle')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">Ma Liste de Souhaits</h1>
    <p class="text-gray-500 mt-1">Retrouvez tous vos produits favoris</p>
</div>

@if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
        {{ session('success') }}
    </div>
@endif

@if($wishlistItems->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($wishlistItems as $item)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
                <div class="relative">
                    <a href="{{ route('products.show', $item->product->slug) }}">
                        <img src="{{ $item->product->image ?? 'https://via.placeholder.com/300x200?text=' . urlencode($item->product->nom_produit) }}" 
                             alt="{{ $item->product->nom_produit }}" 
                             class="w-full h-48 object-cover">
                    </a>
                    <form action="{{ route('client.wishlist.remove', $item->product) }}" method="POST" class="absolute top-2 right-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-white rounded-full p-2 shadow-md hover:text-red-500 transition" title="Retirer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </form>
                </div>
                <div class="p-4">
                    <p class="text-sm text-gray-500 mb-1">{{ $item->product->categorie->nom_categorie ?? '' }}</p>
                    <h3 class="font-semibold text-gray-900 mb-2">
                        <a href="{{ route('products.show', $item->product->slug) }}">{{ $item->product->nom_produit }}</a>
                    </h3>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-lg font-bold text-purple-600">{{ number_format($item->product->prix, 2) }} €</span>
                        @if($item->product->stock > 0)
                            <span class="text-xs text-green-600 bg-green-50 px-2 py-1 rounded">En stock ({{ $item->product->stock }})</span>
                        @else
                            <span class="text-xs text-red-600 bg-red-50 px-2 py-1 rounded">Rupture</span>
                        @endif
                    </div>
                    <form action="{{ route('client.cart.add', $item->product) }}" method="POST">
                        @csrf
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="w-full btn-primary text-white py-2 rounded-lg text-sm font-semibold" 
                                {{ $item->product->stock <= 0 ? 'disabled' : '' }}>
                            {{ $item->product->stock > 0 ? 'Ajouter au panier' : 'Indisponible' }}
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="text-center py-16 bg-white rounded-xl shadow-sm border border-gray-100">
        <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
        </svg>
        <h3 class="mt-4 text-lg font-medium text-gray-900">Votre liste est vide</h3>
        <p class="mt-2 text-gray-500">Ajoutez des produits à votre liste de souhaits en cliquant sur le cœur.</p>
        <div class="mt-6">
            <a href="{{ route('products.index') }}" class="btn-primary inline-block text-white px-6 py-3 rounded-lg font-semibold">
                Découvrir les produits
            </a>
        </div>
    </div>
@endif
@endsection