@extends('layouts.app')

@section('title', 'Produits - Boutique Virtuelle')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">Nos Produits</h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
    <!-- Filters Sidebar -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-24">
            <form action="{{ route('products.index') }}" method="GET">
                <!-- Search -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Recherche</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom du produit..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                </div>

                <!-- Category Filter -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catégorie</label>
                    <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <option value="">Toutes les catégories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->nom_categorie }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Sort -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Trier par</label>
                    <select name="sort" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Plus récent</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Prix croissant</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Prix décroissant</option>
                    </select>
                </div>

                <button type="submit" class="w-full btn-primary text-white py-2 rounded-lg font-semibold">
                    Filtrer
                </button>
            </form>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="lg:col-span-3">
        @if($products->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @foreach($products as $product)
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
                            <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ Str::limit($product->description, 80) }}</p>
                            <div class="flex items-center justify-between">
                                <span class="text-lg font-bold text-purple-600">{{ number_format($product->prix, 2) }} €</span>
                                @if($product->stock > 0)
                                    <span class="text-xs text-green-600 bg-green-50 px-2 py-1 rounded">En stock ({{ $product->stock }})</span>
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

            <!-- Pagination -->
            <div class="mt-8">
                {{ $products->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <p class="text-gray-500 text-lg">Aucun produit trouvé.</p>
            </div>
        @endif
    </div>
</div>
@endsection