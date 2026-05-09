@extends('layouts.app')

@section('title', 'Commande #' . $order->id_commande . ' - Admin')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.orders.index') }}" class="text-purple-600 hover:text-purple-700 text-sm flex items-center gap-1">
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Customer Info -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Informations Client
            </h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Nom</p>
                    <p class="font-medium text-gray-900">{{ $order->user->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Email</p>
                    <p class="font-medium text-gray-900">{{ $order->user->email }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Téléphone</p>
                    <p class="font-medium text-gray-900">{{ $order->user->telephone ?? 'Non renseigné' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Rôle</p>
                    <p class="font-medium text-gray-900 capitalize">{{ $order->user->role }}</p>
                </div>
            </div>
        </div>

        <!-- Change Status -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Changer le statut
            </h2>
            <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                @csrf
                @method('PATCH')
                <select name="statut" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent mb-3">
                    <option value="en_attente" {{ $order->statut == 'en_attente' ? 'selected' : '' }}>En attente</option>
                    <option value="expediee" {{ $order->statut == 'expediee' ? 'selected' : '' }}>Expédiée</option>
                    <option value="livree" {{ $order->statut == 'livree' ? 'selected' : '' }}>Livrée</option>
                    <option value="annulee" {{ $order->statut == 'annulee' ? 'selected' : '' }}>Annulée</option>
                </select>
                <button type="submit" class="w-full btn-primary text-white py-2 rounded-lg font-semibold">
                    Mettre à jour
                </button>
            </form>
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

    <!-- Shipping Info -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                Paiement
            </h2>
            <div class="text-gray-600">
                <p>Mode : <span class="font-medium text-gray-900">À la livraison</span></p>
                <p class="mt-2 text-sm">Statut : <span class="text-green-600 font-medium">Payé</span></p>
            </div>
        </div>
    </div>
</div>
@endsection