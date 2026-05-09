@extends('layouts.app')

@section('title', 'Gestion des Produits - Admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Gestion des Produits</h1>
        <p class="text-gray-500 mt-1">Gérez le catalogue de produits</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="btn-primary text-white px-6 py-3 rounded-lg font-semibold shadow-lg">
        + Ajouter un produit
    </a>
</div>

@if(session('success'))
    <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-700 px-6 py-4 rounded-lg mb-6 shadow-sm">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-2xl shadow-soft overflow-hidden">
    @if($products->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Produit</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Catégorie</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Prix</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Stock</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($products as $product)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/40' }}" 
                                         alt="{{ $product->nom_produit }}" 
                                         class="w-10 h-10 rounded-lg object-cover shadow-sm">
                                    <span class="ml-3 font-medium text-gray-900">{{ Str::limit($product->nom_produit, 40) }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $product->categorie->nom_categorie ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ number_format($product->prix, 2) }} €</td>
                            <td class="px-6 py-4">
                                @if($product->stock > 10)
                                    <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-700 font-medium">{{ $product->stock }}</span>
                                @elseif($product->stock > 0)
                                    <span class="px-3 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700 font-medium">{{ $product->stock }}</span>
                                @else
                                    <span class="px-3 py-1 text-xs rounded-full bg-red-100 text-red-700 font-medium">Rupture</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($product->est_actif)
                                    <span class="px-3 py-1 text-xs rounded-full bg-blue-100 text-blue-700 font-medium">Actif</span>
                                @else
                                    <span class="px-3 py-1 text-xs rounded-full bg-gray-100 text-gray-700 font-medium">Inactif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('admin.products.edit', $product) }}" class="text-primary-600 hover:text-primary-700 font-medium mr-3">
                                    Modifier
                                </a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer ce produit ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-700 font-medium">
                                        Supprimer
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-4 px-6 pb-6">
            {{ $products->links() }}
        </div>
    @else
        <div class="text-center py-16">
            <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">Aucun produit</h3>
            <p class="mt-2 text-gray-500">Commencez par ajouter votre premier produit.</p>
            <a href="{{ route('admin.products.create') }}" class="mt-6 inline-block btn-primary text-white px-6 py-3 rounded-lg font-semibold">
                Ajouter un produit
            </a>
        </div>
    @endif
</div>
@endsection