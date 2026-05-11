@extends('layouts.app')

@section('title', 'Gestion des Catégories - Admin - The Vault')

@section('content')
<div class="mb-12 gsap-fade-up">
    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 block">Administration</span>
    <div class="flex flex-col lg:flex-row lg:items-end justify-between border-b-4 border-slate-900 pb-6 gap-6">
        <h1 class="text-5xl md:text-7xl font-black text-slate-900 tracking-tighter uppercase leading-none">
            Catégories.
        </h1>
        <button onclick="openModal()" class="bg-slate-900 text-white font-black uppercase tracking-widest px-8 py-4 hover:bg-slate-800 transition-colors inline-flex items-center justify-center gap-2 text-xs shrink-0">
            <i class="fas fa-plus"></i> Nouvelle Catégorie
        </button>
    </div>
</div>

@if(session('success'))
    <div class="bg-emerald-500 text-white px-6 py-4 mb-8 text-xs font-black uppercase tracking-widest flex items-center gap-3 gsap-fade-up">
        <i class="fas fa-check-square"></i> {{ session('success') }}
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
    @forelse($categories as $category)
        <div class="bg-slate-50 border-2 border-slate-200 p-8 hover:border-slate-900 transition-colors flex flex-col h-full gsap-card group">
            
            <div class="flex justify-between items-start mb-8">
                <div class="w-14 h-14 bg-slate-900 text-white flex items-center justify-center text-2xl font-black shrink-0 group-hover:scale-110 transition-transform">
                    {{ substr($category->nom_categorie, 0, 1) }}
                </div>
                
                <div class="flex items-center gap-4 border-b-2 border-transparent group-hover:border-slate-200 pb-2 transition-all">
                    <button onclick="editCategory({{ $category->id }}, '{{ addslashes($category->nom_categorie) }}')" class="text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-purple-600 transition-colors">
                        Éditer
                    </button>
                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Détruire cette catégorie ? Attention, cela pourrait impacter les objets liés.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-red-600 transition-colors">
                            Retirer
                        </button>
                    </form>
                </div>
            </div>

            <div class="flex-1">
                <h3 class="text-2xl font-black text-slate-900 uppercase tracking-tight mb-2 leading-tight">
                    {{ $category->nom_categorie }}
                </h3>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-6">
                    <span class="text-slate-900">{{ $category->produits_count ?? 0 }}</span> objets liés
                </p>
            </div>

            <div class="mt-4 pt-6 border-t-2 border-slate-200 group-hover:border-slate-900 transition-colors">
                <a href="{{ route('products.index') }}?category={{ $category->id }}" class="inline-flex items-center justify-between w-full text-xs font-black uppercase tracking-widest text-slate-900 hover:text-slate-500 transition-colors">
                    Explorer les objets <i class="fas fa-arrow-right group-hover:translate-x-2 transition-transform"></i>
                </a>
            </div>
        </div>
    @empty
        <div class="col-span-full border-2 border-slate-200 border-dashed p-16 md:p-32 text-center gsap-fade-up">
            <i class="fas fa-folder-open text-4xl text-slate-300 mb-4"></i>
            <h3 class="text-3xl font-black text-slate-900 uppercase tracking-tight mb-2">Classement vide</h3>
            <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-6">Commencez par créer votre première catégorie.</p>
            <button onclick="openModal()" class="bg-slate-900 text-white font-black uppercase tracking-widest px-8 py-4 hover:bg-slate-800 transition-colors text-xs inline-flex items-center gap-2">
                <i class="fas fa-plus"></i> Créer une catégorie
            </button>
        </div>
    @endforelse
</div>

<div id="categoryModal" class="fixed inset-0 bg-slate-900/90 backdrop-blur-sm hidden items-center justify-center z-50 px-4">
    <div class="bg-white border-4 border-slate-900 max-w-lg w-full p-8 md:p-12 shadow-2xl relative transform transition-all">
        
        <button onclick="closeModal()" class="absolute top-6 right-6 text-slate-400 hover:text-slate-900 transition-colors">
            <i class="fas fa-times text-2xl"></i>
        </button>

        <h2 id="modalTitle" class="text-3xl font-black text-slate-900 uppercase tracking-tighter mb-8 border-b-4 border-slate-900 pb-4">
            Nouvelle catégorie
        </h2>
        
        <form id="categoryForm" method="POST" class="space-y-8">
            @csrf
            <div>
                <label for="nom_categorie" class="block text-xs font-bold text-slate-900 uppercase tracking-widest mb-3">Désignation de la catégorie</label>
                <input type="text" name="nom_categorie" id="nom_categorie" required
                    class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-300 focus:border-purple-600 focus:bg-white focus:ring-0 text-slate-900 font-bold transition-colors text-lg uppercase placeholder:normal-case placeholder:text-slate-400"
                    placeholder="Ex: Montres">
            </div>
            
            <div class="flex flex-col sm:flex-row gap-4 pt-4 border-t-2 border-slate-200">
                <button type="submit" class="flex-1 bg-slate-900 text-white py-4 font-black uppercase tracking-widest hover:bg-slate-800 transition-colors text-sm">
                    Sauvegarder
                </button>
                <button type="button" onclick="closeModal()" class="px-8 py-4 border-2 border-slate-300 text-slate-900 font-bold uppercase tracking-widest hover:bg-slate-50 transition-colors text-sm">
                    Annuler
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
// Logique Laravel conservée
function openModal() {
    document.getElementById('categoryModal').classList.remove('hidden');
    document.getElementById('categoryModal').classList.add('flex');
    document.getElementById('modalTitle').textContent = 'Nouvelle catégorie';
    document.getElementById('categoryForm').action = "{{ route('admin.categories.store') }}";
    document.getElementById('nom_categorie').value = '';
}

function editCategory(id, name) {
    document.getElementById('categoryModal').classList.remove('hidden');
    document.getElementById('categoryModal').classList.add('flex');
    document.getElementById('modalTitle').textContent = 'Modifier la classe';
    document.getElementById('categoryForm').action = `/admin/categories/${id}`;
    document.getElementById('nom_categorie').value = name;
}

function closeModal() {
    document.getElementById('categoryModal').classList.add('hidden');
    document.getElementById('categoryModal').classList.remove('flex');
}

// Fermer la modale si on clique à l'extérieur
document.getElementById('categoryModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

// Animations GSAP
document.addEventListener("DOMContentLoaded", () => {
    gsap.fromTo(".gsap-fade-up", { y: 30, opacity: 0 }, { y: 0, opacity: 1, duration: 0.8, stagger: 0.1, ease: "power3.out" });
    gsap.fromTo(".gsap-card", { y: 20, opacity: 0 }, { y: 0, opacity: 1, duration: 0.5, stagger: 0.05, ease: "power2.out", delay: 0.2 });
});
</script>
@endpush
@endsection