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

                {{-- Photos existantes --}}
                @if($product->images->count() > 0)
                <div>
                    <label class="block text-xs font-bold text-slate-900 uppercase tracking-widest mb-4">Photos actuelles</label>
                    <div class="flex flex-wrap gap-4" id="existing-images">
                        @foreach($product->images as $image)
                        <div class="relative w-32 aspect-[4/5] group" id="img-{{ $image->id }}">
                            <img src="{{ $image->url }}" alt="Photo {{ $loop->iteration }}"
                                 class="w-full h-full object-cover border border-slate-200 rounded">
                            <button type="button"
                                onclick="deleteImage({{ $image->id }}, {{ $product->id_produit }}, this)"
                                class="absolute top-1 right-1 w-6 h-6 bg-red-600 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity shadow">
                                <i class="fas fa-times text-[10px]"></i>
                            </button>
                        </div>
                        @endforeach
                    </div>
                    <p class="mt-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Survolez une photo et cliquez sur ✕ pour la supprimer.</p>
                </div>
                @endif

                {{-- Ajouter de nouvelles photos --}}
                <div>
                    <label class="block text-xs font-bold text-slate-900 uppercase tracking-widest mb-4">Ajouter des photos</label>
                    <div class="flex flex-col items-start gap-6">
                        <div class="flex flex-wrap gap-4 w-full" id="preview-container">
                            <div class="w-32 aspect-[4/5] bg-white border-2 border-dashed border-slate-300 flex items-center justify-center overflow-hidden shrink-0" id="placeholder-box">
                                <div class="text-center p-2">
                                    <i class="fas fa-camera text-2xl text-slate-300 mb-1"></i>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Aperçu</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex-1 w-full">
                            <input type="file" name="images[]" id="images" accept="image/jpeg,image/png,image/webp,image/gif,image/avif"
                                multiple onchange="previewMultipleImages(event)"
                                class="block w-full text-sm text-slate-500 file:mr-4 file:py-3 file:px-6 file:border-0 file:text-xs file:font-black file:uppercase file:tracking-widest file:bg-slate-900 file:text-white hover:file:bg-slate-800 cursor-pointer border border-slate-200 bg-white p-2">
                            <p class="mt-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Formats : JPEG, PNG, WEBP, GIF, AVIF (Max 5MB). Vous pouvez sélectionner plusieurs photos.</p>
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
                    <div class="flex items-center justify-between mb-3">
                        <label for="description" class="block text-xs font-bold text-slate-900 uppercase tracking-widest">Description détaillée</label>
                        <div class="flex items-center gap-2">
                            <select id="ai-lang" class="text-xs font-bold text-slate-700 border border-slate-200 bg-white px-2 py-1.5 focus:ring-0 focus:border-slate-900">
                                <option value="fr">🇫🇷 Français</option>
                                <option value="en">🇬🇧 English</option>
                                <option value="ar">🇲🇦 العربية</option>
                            </select>
                            <button type="button" onclick="generateDescription()"
                                id="ai-btn"
                                class="flex items-center gap-2 bg-violet-600 text-white text-xs font-black uppercase tracking-widest px-4 py-1.5 hover:bg-violet-700 transition-colors">
                                <i class="fas fa-wand-magic-sparkles" id="ai-icon"></i>
                                Générer avec l'IA
                            </button>
                        </div>
                    </div>
                    <textarea name="description" id="description" rows="5"
                              class="w-full px-5 py-4 bg-white border border-slate-300 focus:border-slate-900 focus:ring-0 text-slate-900 font-medium transition-colors resize-y">{{ old('description', $product->description) }}</textarea>
                    <p id="ai-error" class="text-red-500 text-xs font-bold mt-1 hidden"></p>
                </div>

                <div class="grid grid-cols-3 gap-8 border-t border-slate-200 pt-10">
                    <div>
                        <label for="prix" class="block text-xs font-bold text-slate-900 uppercase tracking-widest mb-3">Valeur (DH) <span class="text-red-500">*</span></label>
                        <input type="number" name="prix" id="prix" value="{{ old('prix', $product->prix) }}" step="0.01" min="0" required
                            class="w-full px-5 py-4 bg-white border border-slate-300 focus:border-slate-900 focus:ring-0 text-slate-900 font-black text-xl transition-colors @error('prix') border-red-500 @enderror"
                            oninput="updateDiscountPreview()">
                        @error('prix') <p class="text-red-500 text-xs font-bold mt-2">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="discount_percent" class="block text-xs font-bold text-slate-900 uppercase tracking-widest mb-3">Réduction (%)</label>
                        <input type="number" name="discount_percent" id="discount_percent" value="{{ old('discount_percent', $product->discount_percent) }}" min="0" max="100"
                            class="w-full px-5 py-4 bg-white border border-slate-300 focus:border-slate-900 focus:ring-0 text-slate-900 font-black text-xl transition-colors @error('discount_percent') border-red-500 @enderror"
                            oninput="updateDiscountPreview()">
                        <p id="discount-preview" class="mt-2 text-xs font-bold text-violet-600 {{ $product->discount_percent > 0 ? '' : 'hidden' }}">
                            @if($product->discount_percent > 0)
                                Prix final : {{ number_format($product->finalPrice, 2) }} DH
                            @endif
                        </p>
                        @error('discount_percent') <p class="text-red-500 text-xs font-bold mt-2">{{ $message }}</p> @enderror
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
function previewMultipleImages(event) {
    const files = Array.from(event.target.files);
    const container = document.getElementById('preview-container');
    const placeholder = document.getElementById('placeholder-box');

    // Remove previous previews (not the placeholder)
    container.querySelectorAll('.preview-item').forEach(el => el.remove());

    if (files.length > 0) {
        placeholder.classList.add('hidden');
        files.forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'preview-item w-32 aspect-[4/5] bg-white border border-slate-200 overflow-hidden shrink-0';
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'w-full h-full object-cover';
                div.appendChild(img);
                container.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    } else {
        placeholder.classList.remove('hidden');
    }
}

// Existing images URLs for AI context
const existingImageUrls = @json($product->images->pluck('url')->toArray());

async function generateDescription() {
    const btn = document.getElementById('ai-btn');
    const icon = document.getElementById('ai-icon');
    const errEl = document.getElementById('ai-error');
    const lang = document.getElementById('ai-lang').value;
    const productName = document.getElementById('nom_produit')?.value || '';
    const filesInput = document.getElementById('images');
    const newFiles = filesInput ? Array.from(filesInput.files) : [];

    btn.disabled = true;
    icon.className = 'fas fa-spinner fa-spin';
    errEl.classList.add('hidden');

    let imageData = [];
    // Use newly uploaded images (base64) or existing Cloudinary URLs
    if (newFiles.length > 0) {
        imageData = await Promise.all(newFiles.slice(0, 3).map(file => new Promise((resolve) => {
            const reader = new FileReader();
            reader.onload = e => resolve(e.target.result);
            reader.readAsDataURL(file);
        })));
    } else {
        imageData = existingImageUrls.slice(0, 3);
    }

    try {
        const res = await fetch('{{ route("admin.ai.describe") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ language: lang, images: imageData, product_name: productName })
        });
        const data = await res.json();
        if (data.description) {
            document.getElementById('description').value = data.description;
        } else {
            errEl.textContent = data.error || 'Erreur inconnue.';
            errEl.classList.remove('hidden');
        }
    } catch(e) {
        errEl.textContent = 'Erreur réseau.';
        errEl.classList.remove('hidden');
    } finally {
        btn.disabled = false;
        icon.className = 'fas fa-wand-magic-sparkles';
    }
}

function deleteImage(imageId, productId, btn) {
    if (!confirm('Supprimer cette photo ?')) return;
    btn.disabled = true;
    fetch(`/admin/products/${productId}/images/${imageId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    }).then(r => {
        if (r.ok || r.redirected) {
            document.getElementById('img-' + imageId)?.remove();
        } else {
            btn.disabled = false;
            alert('Erreur lors de la suppression.');
        }
    }).catch(() => { btn.disabled = false; alert('Erreur réseau.'); });
}

function updateDiscountPreview() {
    const prix = parseFloat(document.getElementById('prix')?.value) || 0;
    const discount = parseInt(document.getElementById('discount_percent')?.value) || 0;
    const preview = document.getElementById('discount-preview');
    if (prix > 0 && discount > 0) {
        const final = (prix * (1 - discount / 100)).toFixed(2);
        preview.textContent = `Prix final : ${final} DH`;
        preview.classList.remove('hidden');
    } else {
        preview.classList.add('hidden');
    }
}

document.addEventListener("DOMContentLoaded", () => {
    gsap.fromTo(".gsap-fade-up", { y: 30, opacity: 0 }, { y: 0, opacity: 1, duration: 0.8, stagger: 0.1, ease: "power3.out" });
});
</script>
@endpush
@endsection
