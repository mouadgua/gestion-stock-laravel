@extends('layouts.app')

@section('title', 'Scanner QR Code - Livreur')

@section('content')
<div class="max-w-md mx-auto">
    <div class="mb-8 text-center">
        <a href="{{ route('delivery.dashboard') }}" class="text-primary-600 hover:text-primary-700 text-sm flex items-center justify-center gap-1 mb-4">
            ← Retour au dashboard
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Scanner QR Code</h1>
        <p class="text-gray-500 mt-2">Scannez le code QR pour marquer la livraison comme effectuée</p>
    </div>

    <div class="bg-white rounded-2xl shadow-soft p-8">
        <!-- Manual Input -->
        <form action="{{ route('delivery.scan.process') }}" method="POST">
            @csrf
            <div class="mb-6">
                <label for="qr_code" class="block text-sm font-medium text-gray-700 mb-2">
                    Ou entrez le code manuellement
                </label>
                <input type="text" name="qr_code" id="qr_code" required
                    placeholder="Entrez le code QR"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent text-center text-lg font-mono">
            </div>
            <button type="submit" class="w-full btn-primary text-white py-4 rounded-lg font-semibold">
                Valider le code
            </button>
        </form>

        <!-- QR Scanner (placeholder - would need a JS library) -->
        <div class="mt-8 pt-8 border-t border-gray-100">
            <div class="text-center mb-4">
                <p class="text-sm text-gray-500 mb-4">Utilisez votre caméra pour scanner</p>
                <div class="w-48 h-48 mx-auto border-2 border-dashed border-primary-300 rounded-xl flex items-center justify-center bg-primary-50">
                    <svg class="w-16 h-16 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                </div>
                <p class="text-xs text-gray-400 mt-4">
                    Fonctionnalité de scan caméra à intégrer avec une librairie JS
                </p>
            </div>
        </div>
    </div>
</div>
@endsection