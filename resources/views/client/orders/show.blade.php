@extends('layouts.app')

@section('title', 'Commande #' . $order->id_commande . ' - Boutique Virtuelle')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('client.orders.index') }}" class="text-purple-600 hover:text-purple-700 text-sm flex items-center gap-1">
            ← Retour aux commandes
        </a>
        <div class="flex justify-between items-center mt-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Commande #{{ $order->id_commande }}</h1>
                <p class="text-gray-500 mt-1">Passée le {{ \Carbon\Carbon::parse($order->date_commande)->format('d/m/Y à H:i') }}</p>
            </div>
            <span class="px-4 py-2 text-sm rounded-full font-medium
                @if($order->statut == 'en_attente') bg-yellow-100 text-yellow-800
                @elseif($order->statut == 'expediee') bg-blue-100 text-blue-800
                @elseif($order->statut == 'livree') bg-green-100 text-green-800
                @else bg-red-100 text-red-800
                @endif">
                @if($order->statut == 'en_attente') 🕐 En attente
                @elseif($order->statut == 'expediee') 📦 Expédiée
                @elseif($order->statut == 'livree') ✅ Livrée
                @else ❌ Annulée
                @endif
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Shipping Info -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Adresse de livraison
            </h2>
            <div class="text-gray-600">
                <p class="font-medium text-gray-900">{{ $order->user->name }}</p>
                <p>{{ $order->adresse_livraison ?? $order->user->adresse ?? 'Non renseignée' }}</p>
                <p class="mt-2">📞 {{ $order->telephone_livraison ?? $order->user->telephone ?? 'Non renseigné' }}</p>
            </div>
        </div>

        <!-- Payment Info -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                Paiement
            </h2>
            <div class="text-gray-600">
                <p>Mode de paiement : <span class="font-medium text-gray-900">À la livraison</span></p>
                <p class="mt-2 text-sm">Statut : <span class="text-green-600 font-medium">Payé</span></p>
            </div>
        </div>
    </div>

    <!-- Order Items -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-lg font-bold text-gray-900">Articles commandés</h2>
        </div>
        <div class="divide-y divide-gray-100">
            @foreach($order->items as $item)
                <div class="p-6 flex items-center gap-4 hover:bg-gray-50 transition">
                    <img src="{{ $item->product->image ?? 'https://via.placeholder.com/80' }}" 
                         alt="{{ $item->product->nom_produit }}" 
                         class="w-20 h-20 rounded-lg object-cover border border-gray-200">
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900">{{ $item->product->nom_produit }}</h3>
                        <p class="text-sm text-gray-500">{{ $item->product->categorie->nom_categorie ?? '' }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-500">Quantité</p>
                        <p class="font-semibold text-gray-900">{{ $item->quantite }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">Sous-total</p>
                        <p class="font-bold text-gray-900">{{ number_format($item->sous_total, 2) }} €</p>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="p-6 bg-gray-50">
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Total de la commande</span>
                <span class="text-2xl font-bold text-gray-900">{{ number_format($order->total, 2) }} €</span>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex gap-4">
        <a href="{{ route('products.index') }}" class="flex-1 text-center btn-primary text-white py-3 rounded-lg font-semibold">
            Commander à nouveau
        </a>
        <button onclick="window.print()" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-semibold">
            📄 Imprimer
        </button>
    </div>
</div>
@endsection