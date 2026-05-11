@extends('layouts.app')

@section('title', 'Éditer l\'objet - The Vault')

@section('content')
<div class="mb-16 gsap-fade-up">
    <a href="{{ route('admin.products.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 uppercase tracking-widest hover:text-slate-900 transition-colors mb-6">
        <i class="fas fa-arrow-left"></i> Retour au catalogue
    </a>
    <h1 class="text-5xl md:text-7xl font-black text-slate-900 tracking-tighter uppercase leading-none border-b border-slate-900 pb-6">
        Modifier<br>L'Objet.
    </h1>
</div>

<div class="max-w-4xl gsap-fade-up">
    <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" class="space-y-12">
        @csrf
        @method('PUT')

        <div class="bg-slate-50 p-8 md:p-12 border border-slate-200">
            <div class="space-y-10">
                
                <div>
                    <label class="block text-xs font-bold text-slate-900 uppercase tracking-widest mb-4">Photographie de l'objet</label>
                    <div class="flex flex-col sm:flex-row items-start gap-8">
                        <div class="w-40 aspect-[4/5] bg-white border border-slate-300 flex items-center justify-center overflow-hidden shrink-0 relative">
                            @if($product->image)
                                <img id="preview" src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->nom_produit }}" class="w-full h-full object-cover">
                            @else
                                <img id="preview" src="" alt="Aperçu" class="w-full h-full object-cover hidden">
                            @endif
                        </div>
                        <div class="flex-1 w-full">
                            <input type="file" name="image" id="image" accept="image/*" onchange="previewImage(event)"
                                class="block w-full text-sm text-slate-500 file:mr-4 file:py-3 file:px-6 file:border-0 file:text-xs file:font-black file:uppercase file:tracking-widest file:bg-slate-900 file:text-white hover:file:bg-slate-800 cursor-pointer border border-slate-200 bg-white p-2">
                            <p class="mt-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Laissez ce champ vide pour conserver l'image actuelle.</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label for="nom_produit" class="block text-xs font-bold text-slate-900 uppercase tracking-widest mb-3">Désignation <span class="text-red-500">*</span></label>
                        <input type="text" name="nom_produit" id="nom_produit" value="{{ old('nom_produit', $product->nom_produit) }}" required
                            class="w-full px-5 py-4 bg-white border border-slate-300 focus:border-slate-900 focus:ring-0 text-slate-900 font-bold transition-colors @error('nom_produit') border-red-500 @enderror">
                        @error('nom_produit') <p class="text-red-500 text-xs font-bold mt-2">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="categorie_id" class="block text-xs font-bold text-slate-900 uppercase tracking-widest mb-3">Catégorie</label>
                        <div class="relative">
                            <select name="categorie_id" id="categorie_id" class="w-full px-5 py-4 bg-white border border-slate-300 focus:border-slate-900 focus:ring-0 text-slate-900 font-bold transition-colors appearance-none cursor-pointer">
                                <option value="">SÉLECTIONNER...</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('categorie_id', $product->categorie_id) == $category->id ? 'selected' : '' }}>
                                        {{ mb_strtoupper($category->nom_categorie) }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-5 pointer-events-none text-slate-500">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-xs font-bold text-slate-900 uppercase tracking-widest mb-3">Description détaillée</label>
                    <textarea name="description" id="description" rows="5" 
                              class="w-full px-5 py-4 bg-white border border-slate-300 focus:border-slate-900 focus:ring-0 text-slate-900 font-medium transition-colors resize-y">{{ old('description', $product->description) }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-8 border-t border-slate-200 pt-10">
                    <div>
                        <label for="prix" class="block text-xs font-bold text-slate-900 uppercase tracking-widest mb-3">Valeur (DH) <span class="text-red-500">*</span></label>
                        <input type="number" name="prix" id="prix" value="{{ old('prix', $product->prix) }}" step="0.01" min="0" required
                            class="w-full px-5 py-4 bg-white border border-slate-300 focus:border-slate-900 focus:ring-0 text-slate-900 font-black text-xl transition-colors @error('prix') border-red-500 @enderror">
                        @error('prix') <p class="text-red-500 text-xs font-bold mt-2">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="stock" class="block text-xs font-bold text-slate-900 uppercase tracking-widest mb-3">Unités en stock <span class="text-red-500">*</span></label>
                        <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock) }}" min="0" required
                            class="w-full px-5 py-4 bg-white border border-slate-300 focus:border-slate-900 focus:ring-0 text-slate-900 font-black text-xl transition-colors @error('stock') border-red-500 @enderror">
                        @error('stock') <p class="text-red-500 text-xs font-bold mt-2">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="border-t border-slate-200 pt-10">
                    <label class="flex items-center gap-4 cursor-pointer group">
                        <div class="relative flex items-center">
                            <input type="checkbox" name="est_actif" value="1" {{ old('est_actif', $product->est_actif) ? 'checked' : '' }}
                                class="w-6 h-6 border-2 border-slate-300 text-slate-900 focus:ring-slate-900 focus:ring-2 transition-colors cursor-pointer">
                        </div>
                        <span class="text-sm font-bold text-slate-900 uppercase tracking-widest group-hover:text-slate-500 transition-colors">Maintenir l'objet dans le catalogue</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-4 pt-6">
            <button type="submit" class="flex-1 bg-slate-900 text-white py-5 px-8 font-black uppercase tracking-widest hover:bg-slate-800 transition-colors text-sm">
                Sauvegarder les modifications
            </button>
            <a href="{{ route('admin.products.index') }}" class="py-5 px-8 border border-slate-300 text-slate-900 text-center font-bold uppercase tracking-widest hover:bg-slate-50 transition-colors text-sm">
                Annuler
            </a>
        </div>
    </form>
</div>

@push('scripts')
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
document.addEventListener("DOMContentLoaded", () => {
    gsap.fromTo(".gsap-fade-up", { y: 30, opacity: 0 }, { y: 0, opacity: 1, duration: 0.8, stagger: 0.1, ease: "power3.out" });
});
</script>
@endpush
@endsection