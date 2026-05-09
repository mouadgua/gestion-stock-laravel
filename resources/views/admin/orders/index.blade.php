@extends('layouts.app')

@section('title', 'Gestion des Commandes - Admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Gestion des Commandes</h1>
        <p class="text-gray-500 mt-1">Suivez et traitez toutes les commandes</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 bg-purple-600 text-white rounded-lg font-medium">
            Toutes
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'en_attente']) }}" class="px-4 py-2 bg-yellow-100 text-yellow-800 rounded-lg font-medium hover:bg-yellow-200">
            En attente
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'expediee']) }}" class="px-4 py-2 bg-blue-100 text-blue-800 rounded-lg font-medium hover:bg-blue-200">
            Expédiées
        </a>
    </div>
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
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
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $order->user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $order->user->email }}</p>
                                </div>
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
                                <a href="{{ route('admin.orders.show', $order) }}" 
                                   class="text-purple-600 hover:text-purple-700 font-medium mr-3">
                                    Voir
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
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
            </svg>
            <p class="mt-2 text-gray-500">Aucune commande trouvée.</p>
        </div>
    @endif
</div>
@endsection