@extends('layouts.app')

@section('title', 'Historique des Livraisons - Livreur')

@section('content')
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Historique des Livraisons</h1>
            <p class="text-gray-500 mt-1">Consultez toutes vos livraisons effectuées</p>
        </div>
        <a href="{{ route('delivery.dashboard') }}" class="btn-primary text-white px-6 py-3 rounded-lg font-semibold">
            ← Retour au dashboard
        </a>
    </div>
</div>

@if($deliveries->count() > 0)
    <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Commande</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Client</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date livraison</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($deliveries as $delivery)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">
                                    Commande #{{ $delivery->order->id_commande }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $delivery->order->total }} DH
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">
                                    {{ $delivery->order->client->name }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $delivery->order->client->email }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $delivery->delivered_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-700 font-medium">
                                    ✓ Livrée
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4 px-6 pb-6">
            {{ $deliveries->links() }}
        </div>
    </div>
@else
    <div class="bg-white rounded-2xl shadow-soft p-16 text-center">
        <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <h3 class="mt-4 text-lg font-medium text-gray-900">Aucune livraison effectuée</h3>
        <p class="mt-2 text-gray-500">Votre historique de livraisons apparaîtra ici une fois que vous aurez commencé à livrer.</p>
        <a href="{{ route('delivery.dashboard') }}" class="mt-6 inline-block btn-primary text-white px-6 py-3 rounded-lg font-semibold">
            Retour au dashboard
        </a>
    </div>
@endif
@endsection