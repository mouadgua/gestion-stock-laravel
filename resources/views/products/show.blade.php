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
        
        <!-- Rating -->
        <div class="flex items-center mb-4">
            <div class="flex items-center">
                @for($i = 1; $i <= 5; $i++)
                    <svg class="w-5 h-5 {{ $i <= $averageRating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                @endfor
            </div>
            <span class="ml-2 text-gray-600">{{ number_format($averageRating, 1) }}/5</span>
            <span class="mx-2 text-gray-400">|</span>
            <span class="text-gray-600">{{ $reviews->count() }} avis</span>
        </div>

        <p class="text-3xl font-bold text-primary-600 mb-6">{{ number_format($product->prix, 2) }} €</p>

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

<!-- Reviews Section -->
<div class="mt-16">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Avis des clients</h2>
    
    @if($reviews->count() > 0)
        <div class="space-y-6">
            @foreach($reviews as $review)
                <div class="bg-white rounded-xl shadow-soft p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-r from-primary-400 to-indigo-400 flex items-center justify-center text-white font-bold">
                                {{ substr($review->user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $review->user->name }}</p>
                                <p class="text-sm text-gray-500">{{ $review->created_at->format('d/m/Y') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                    </div>
                    @if($review->comment)
                        <p class="text-gray-600">{{ $review->comment }}</p>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-gray-50 rounded-xl p-8 text-center">
            <p class="text-gray-500">Aucun avis pour le moment. Soyez le premier à donner votre avis !</p>
        </div>
    @endif

    <!-- Add Review Form -->
    @auth
        @if(auth()->user()->isClient() && !$hasReviewed)
            <div class="mt-8 bg-white rounded-xl shadow-soft p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Laisser un avis</h3>
                <form action="{{ route('reviews.store', $product) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Note</label>
                        <div class="flex gap-2" id="ratingStars">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" onclick="setRating({{ $i }})" data-rating="{{ $i }}" class="star-btn">
                                    <svg class="w-8 h-8 text-gray-300 hover:text-yellow-400" fill="currentColor" viewBox="0 0 20 20" id="star-{{ $i }}">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                </button>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="ratingInput" value="5">
                    </div>
                    <div class="mb-4">
                        <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">Commentaire</label>
                        <textarea name="comment" id="comment" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="Partagez votre expérience avec ce produit..."></textarea>
                    </div>
                    <button type="submit" class="btn-primary text-white px-6 py-3 rounded-lg font-semibold">
                        Publier l'avis
                    </button>
                </form>
            </div>
        @endif
    @else
        <div class="mt-8 bg-gray-50 rounded-xl p-6 text-center">
            <p class="text-gray-600 mb-4">Connectez-vous pour laisser un avis</p>
            <a href="{{ route('login') }}" class="text-primary-600 hover:text-primary-700 font-medium">Se connecter</a>
        </div>
    @endauth
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