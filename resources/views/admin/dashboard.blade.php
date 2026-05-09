@extends('layouts.app')

@section('title', 'Dashboard Admin - Boutique Virtuelle')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">Tableau de bord</h1>
    <p class="text-gray-500 mt-1">Bienvenue dans l'espace administrateur</p>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Orders -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Commandes totales</p>
                <p class="text-2xl font-bold text-gray-900">{{ $totalOrders }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Pending Orders -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">En attente</p>
                <p class="text-2xl font-bold text-yellow-600">{{ $pendingOrders }}</p>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Total Revenue -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Chiffre d'affaires</p>
                <p class="text-2xl font-bold text-green-600">{{ number_format($totalRevenue, 2) }} €</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Low Stock Alert -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Alerte stock faible</p>
                <p class="text-2xl font-bold text-red-600">{{ $lowStockProducts }}</p>
            </div>
            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Recent Orders -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Commandes récentes</h2>
        @if($recentOrders->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-sm text-gray-500 border-b">
                            <th class="pb-3 font-medium">Commande</th>
                            <th class="pb-3 font-medium">Client</th>
                            <th class="pb-3 font-medium">Total</th>
                            <th class="pb-3 font-medium">Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                            <tr class="border-b border-gray-50 last:border-0">
                                <td class="py-3 text-sm font-medium">#{{ $order->id_commande }}</td>
                                <td class="py-3 text-sm text-gray-600">{{ $order->user->name }}</td>
                                <td class="py-3 text-sm font-semibold">{{ number_format($order->total, 2) }} €</td>
                                <td class="py-3">
                                    <span class="px-2 py-1 text-xs rounded-full
                                        @if($order->statut == 'en_attente') bg-yellow-100 text-yellow-800
                                        @elseif($order->statut == 'expediee') bg-blue-100 text-blue-800
                                        @elseif($order->statut == 'livree') bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $order->statut)) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="mt-4 inline-block text-purple-600 hover:text-purple-700 text-sm font-medium">
                Voir toutes les commandes →
            </a>
        @else
            <p class="text-gray-500 text-center py-8">Aucune commande récente.</p>
        @endif
    </div>

    <!-- Top Products -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Produits les plus vendus</h2>
        @if($topProducts->count() > 0)
            <div class="space-y-4">
                @foreach($topProducts as $product)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <img src="{{ $product->image ?? 'https://via.placeholder.com/40' }}" 
                                 alt="{{ $product->nom_produit }}" 
                                 class="w-10 h-10 rounded-lg object-cover">
                            <div>
                                <p class="font-medium text-gray-900 text-sm">{{ $product->nom_produit }}</p>
                                <p class="text-xs text-gray-500">{{ number_format($product->prix, 2) }} €</p>
                            </div>
                        </div>
                        <span class="text-sm font-semibold text-gray-600">{{ $product->order_items_count }} vendus</span>
                    </div>
                @endforeach
            </div>
            <a href="{{ route('admin.products.index') }}" class="mt-4 inline-block text-purple-600 hover:text-purple-700 text-sm font-medium">
                Voir tous les produits →
            </a>
        @else
            <p class="text-gray-500 text-center py-8">Aucun produit vendu pour le moment.</p>
        @endif
    </div>
</div>
@endsection