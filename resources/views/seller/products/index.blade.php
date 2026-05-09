@extends('layouts.app')

@section('title', 'Espace Vendeur - Boutique Virtuelle')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Espace Vendeur</h1>
        <p class="text-gray-500 mt-1">Gérez vos produits à vendre</p>
    </div>
    <a href="{{ route('seller.products.create') }}" class="btn-primary text-white px-6 py-3 rounded-lg font-semibold">
        + Ajouter un produit
    </a>
</div>

@if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    @if($products->count() > 0)
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produit</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catégorie</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($products as $product)
                    <tr>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <img src="{{ $product->image ?? 'https://via.placeholder.com/40' }}" 
                                     alt="{{ $product->nom_produit }}" 
                                     class="w-10 h-10 rounded-lg object-cover">
                                <span class="ml-3 font-medium text-gray-900">{{ $product->nom_produit }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $product->categorie->nom_categorie ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ number_format($product->prix, 2) }} €</td>
                        <td class="px-6 py-4">
                            @if($product->stock > 10)
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">{{ $product->stock }}</span>
                            @elseif($product->stock > 0)
                                <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">{{ $product->stock }}</span>
                            @else
                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Rupture</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <a href="{{ route('seller.products.edit', $product) }}" class="text-purple-600 hover:text-purple-700 mr-3">
                                Modifier
                            </a>
                            <form action="{{ route('seller.products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer ce produit ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-700">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4 px-6 pb-6">
            {{ $products->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
            <p class="mt-2 text-gray-500">Aucun produit pour le moment.</p>
            <a href="{{ route('seller.products.create') }}" class="mt-4 inline-block btn-primary text-white px-6 py-2 rounded-lg">
                Ajouter votre premier produit
            </a>
        </div>
    @endif
</div>
@endsection