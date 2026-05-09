@extends('layouts.app')

@section('title', 'Mes Commandes - Boutique Virtuelle')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">Mes Commandes</h1>
    <p class="text-gray-500 mt-1">Consultez l'historique de vos achats</p>
</div>

@if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    @if($orders->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Commande</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Articles</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($orders as $order)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <span class="font-semibold text-gray-900">#{{ $order->id_commande }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($order->date_commande)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $order->items->count() }} article(s)
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                {{ number_format($order->total, 2) }} €
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-xs rounded-full font-medium
                                    @if($order->statut == 'en_attente') bg-yellow-100 text-yellow-800
                                    @elseif($order->statut == 'expediee') bg-blue-100 text-blue-800
                                    @elseif($order->statut == 'livree') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    @if($order->statut == 'en_attente') En attente
                                    @elseif($order->statut == 'expediee') Expédiée
                                    @elseif($order->statut == 'livree') Livrée
                                    @else Annulée
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('client.orders.show', $order) }}" 
                                   class="text-purple-600 hover:text-purple-700 font-medium">
                                    Voir détails
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-4 px-6 pb-6">
            {{ $orders->links() }}
        </div>
    @else
        <div class="text-center py-16">
            <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">Aucune commande</h3>
            <p class="mt-2 text-gray-500">Vous n'avez pas encore passé de commande.</p>
            <div class="mt-6">
                <a href="{{ route('products.index') }}" class="btn-primary inline-block text-white px-6 py-3 rounded-lg font-semibold">
                    Découvrir les produits
                </a>
            </div>
        </div>
    @endif
</div>
@endsection