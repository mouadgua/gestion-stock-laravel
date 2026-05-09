@extends('layouts.app')

@section('title', 'Inscription - Boutique Virtuelle')

@section('content')
<div class="max-w-md mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">Inscription</h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nom complet</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('email') border-red-500 @enderror">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Telephone -->
            <div class="mb-4">
                <label for="telephone" class="block text-sm font-medium text-gray-700 mb-2">Téléphone</label>
                <input type="text" name="telephone" id="telephone" value="{{ old('telephone') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('telephone') border-red-500 @enderror">
                @error('telephone')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Adresse -->
            <div class="mb-4">
                <label for="adresse" class="block text-sm font-medium text-gray-700 mb-2">Adresse</label>
                <textarea name="adresse" id="adresse" rows="2"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('adresse') border-red-500 @enderror">{{ old('adresse') }}</textarea>
                @error('adresse')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Mot de passe</label>
                <input type="password" name="password" id="password" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('password') border-red-500 @enderror">
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Role Selection -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">Type de compte</label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="relative flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-primary-500 transition">
                        <input type="radio" name="role" value="acheteur" {{ old('role', 'acheteur') == 'acheteur' ? 'checked' : '' }}
                            class="w-4 h-4 text-primary-600 border-gray-300 focus:ring-primary-500">
                        <div class="ml-3">
                            <span class="block font-medium text-gray-900">Acheteur</span>
                            <span class="block text-sm text-gray-500">Acheter des produits</span>
                        </div>
                    </label>
                    <label class="relative flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-primary-500 transition">
                        <input type="radio" name="role" value="livreur" {{ old('role') == 'livreur' ? 'checked' : '' }}
                            class="w-4 h-4 text-primary-600 border-gray-300 focus:ring-primary-500">
                        <div class="ml-3">
                            <span class="block font-medium text-gray-900">Livreur</span>
                            <span class="block text-sm text-gray-500">Livrer des commandes</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Confirm Password -->
            <div class="mb-6">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirmer le mot de passe</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            </div>

            <button type="submit" class="w-full btn-primary text-white py-3 rounded-lg font-semibold">
                S'inscrire
            </button>
        </form>

        <p class="text-center text-gray-500 mt-6">
            Déjà un compte ? 
            <a href="{{ route('login') }}" class="text-purple-600 hover:text-purple-700 font-medium">Se connecter</a>
        </p>
    </div>
</div>
@endsection