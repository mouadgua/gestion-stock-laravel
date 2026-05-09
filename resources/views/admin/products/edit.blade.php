@extends('layouts.app')

@section('title', 'Modifier le produit - Admin')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-8">
        <a href="{{ route('admin.products.index') }}" class="text-primary-600 hover:text-primary-700 text-sm flex items-center gap-1 mb-4">
            ← Retour à la liste
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Modifier le produit</h1>
    </div>

    <div class="bg-white rounded-2xl shadow-soft p-8">
        <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Image Upload -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Image du produit</label>
                    <div class="flex items-center gap-6">
                        <div class="w-32 h-32 rounded-xl border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden bg-gray-50">
                            @if($product->image)
                                <img id="preview" src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->nom_produit }}" class="w-full h-full object-cover">
                            @else
                                <img id="preview" src="" alt="Aperçu" class="w-full h-full object-cover hidden">
                            @endif
                        </div>
                        <div class="flex-1">
                            <input type="file" name="image" id="image" accept="image/*" onchange="previewImage(event)"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 cursor-pointer">
                            <p class="mt-2 text-xs text-gray-500">Laissez vide pour garder l'image actuelle</p>
                        </div>
                    </div>
                </div>

                <!-- Product Name -->
                <div>
                    <label for="nom_produit" class="block text-sm font-medium text-gray-700 mb-2">Nom du produit <span class="text-red-500">*</span></label>
                    <input type="text" name="nom_produit" id="nom_produit" value="{{ old('nom_produit', $product->nom_produit) }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('nom_produit') border-red-500 @enderror">
                    @error('nom_produit')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category -->
                <div>
                    <label for="categorie_id" class="block text-sm font-medium text-gray-700 mb-2">Catégorie</label>
                    <select name="categorie_id" id="categorie_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="">Sélectionner une catégorie</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('categorie_id', $product->categorie_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->nom_categorie }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" id="description" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">{{ old('description', $product->description) }}</textarea>
                </div>

                <!-- Price and Stock -->
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="prix" class="block text-sm font-medium text-gray-700 mb-2">Prix (€) <span class="text-red-500">*</span></label>
                        <input type="number" name="prix" id="prix" value="{{ old('prix', $product->prix) }}" step="0.01" min="0" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('prix') border-red-500 @enderror">
                        @error('prix')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="stock" class="block text-sm font-medium text-gray-700 mb-2">Stock <span class="text-red-500">*</span></label>
                        <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock) }}" min="0" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('stock') border-red-500 @enderror">
                        @error('stock')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="est_actif" value="1" {{ old('est_actif', $product->est_actif) ? 'checked' : '' }}
                            class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                        <span class="ml-2 text-sm text-gray-700">Produit actif (visible sur la boutique)</span>
                    </label>
                </div>
            </div>

            <div class="mt-8 flex gap-4 pt-6 border-t border-gray-100">
                <button type="submit" class="flex-1 btn-primary text-white py-3 px-6 rounded-lg font-semibold hover:shadow-lg transition">
                    Mettre à jour
                </button>
                <a href="{{ route('admin.products.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview').src = e.target.result;
            document.getElementById('preview').classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endsection