@extends('layouts.app')

@section('title', 'Dashboard Livreur')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Dashboard Livreur</h1>
    <p class="text-gray-500 mt-1">Gérez vos livraisons</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow-soft p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Aujourd'hui</p>
                <p class="text-3xl font-bold text-gray-900">{{ $todayDeliveries }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-blue-100 to-indigo-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-soft p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Cette semaine</p>
                <p class="text-3xl font-bold text-gray-900">{{ $weekDeliveries }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-green-100 to-emerald-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-soft p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">En attente</p>
                <p class="text-3xl font-bold text-gray-900">{{ $pendingDeliveries->count() }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-yellow-100 to-orange-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-soft p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Total livré</p>
                <p class="text-3xl font-bold text-gray-900">{{ $totalDeliveries }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-purple-100 to-pink-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="flex gap-4 mb-8">
    <a href="{{ route('delivery.scan') }}" class="flex-1 btn-primary text-white py-4 px-6 rounded-xl font-semibold text-center flex items-center justify-center gap-2">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
        </svg>
        Scanner QR Code
    </a>
    <a href="{{ route('delivery.history') }}" class="flex-1 bg-white border-2 border-gray-200 text-gray-700 py-4 px-6 rounded-xl font-semibold text-center flex items-center justify-center gap-2 hover:border-primary-500 hover:text-primary-600 transition">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Historique
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Pending Deliveries -->
    <div>
        <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-yellow-500"></span>
            Livraisons en attente
        </h2>
        @if($pendingDeliveries->count() > 0)
            <div class="space-y-4">
                @foreach($pendingDeliveries as $delivery)
                    <div class="bg-white rounded-xl shadow-soft p-5">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <p class="font-semibold text-gray-900">Commande #{{ $delivery->order->id_commande }}</p>
                                <p class="text-sm text-gray-500">{{ $delivery->order->client->name }}</p>
                            </div>
                            <span class="px-3 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700 font-medium">En attente</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <p class="text-sm text-gray-600">{{ $delivery->order->adresse_livraison ?? 'Adresse non spécifiée' }}</p>
                            <form action="{{ route('delivery.update-status', $delivery) }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="status" value="in_transit">
                                <button type="submit" class="text-sm text-primary-600 hover:text-primary-700 font-medium">
                                    Démarrer →
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-gray-50 rounded-xl p-8 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-gray-500 mt-2">Aucune livraison en attente</p>
            </div>
        @endif
    </div>

    <!-- In Transit Deliveries -->
    <div>
        <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-blue-500"></span>
            Livraisons en cours
        </h2>
        @if($inTransitDeliveries->count() > 0)
            <div class="space-y-4">
                @foreach($inTransitDeliveries as $delivery)
                    <div class="bg-white rounded-xl shadow-soft p-5">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <p class="font-semibold text-gray-900">Commande #{{ $delivery->order->id_commande }}</p>
                                <p class="text-sm text-gray-500">{{ $delivery->order->client->name }}</p>
                            </div>
                            <span class="px-3 py-1 text-xs rounded-full bg-blue-100 text-blue-700 font-medium">En cours</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <p class="text-sm text-gray-600">{{ $delivery->order->adresse_livraison ?? 'Adresse non spécifiée' }}</p>
                            <form action="{{ route('delivery.update-status', $delivery) }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="status" value="delivered">
                                <button type="submit" class="text-sm text-green-600 hover:text-green-700 font-medium">
                                    ✓ Livrer
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-gray-50 rounded-xl p-8 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <p class="text-gray-500 mt-2">Aucune livraison en cours</p>
            </div>
        @endif
    </div>
</div>
@endsection