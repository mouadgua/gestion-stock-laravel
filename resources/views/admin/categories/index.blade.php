@extends('layouts.app')

@section('title', 'Gestion des Catégories - Admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Gestion des Catégories</h1>
        <p class="text-gray-500 mt-1">Organisez vos produits par catégories</p>
    </div>
    <button onclick="openModal()" class="btn-primary text-white px-6 py-3 rounded-lg font-semibold shadow-lg">
        + Nouvelle catégorie
    </button>
</div>

@if(session('success'))
    <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-700 px-6 py-4 rounded-lg mb-6 shadow-sm">
        {{ session('success') }}
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($categories as $category)
        <div class="bg-white rounded-2xl shadow-soft p-6 card-hover">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-primary-100 to-indigo-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
                <div class="flex gap-2">
                    <button onclick="editCategory({{ $category->id }}, '{{ $category->nom_categorie }}')" class="text-primary-600 hover:text-primary-700 p-2 rounded-lg hover:bg-primary-50 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 012.828 0L11.828 15H9a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                        </svg>
                    </button>
                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer cette catégorie ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $category->nom_categorie }}</h3>
            <p class="text-sm text-gray-500 mb-4">{{ $category->produits_count ?? 0 }} produits</p>
            <a href="{{ route('products.index') }}?category={{ $category->id }}" class="text-primary-600 hover:text-primary-700 text-sm font-medium flex items-center gap-1">
                Voir les produits
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    @empty
        <div class="col-span-full text-center py-16">
            <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">Aucune catégorie</h3>
            <p class="mt-2 text-gray-500">Commencez par créer votre première catégorie.</p>
        </div>
    @endforelse
</div>

<!-- Modal for creating/editing category -->
<div id="categoryModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4">
        <h2 id="modalTitle" class="text-2xl font-bold text-gray-900 mb-6">Nouvelle catégorie</h2>
        <form id="categoryForm" method="POST">
            @csrf
            <div class="mb-6">
                <label for="nom_categorie" class="block text-sm font-medium text-gray-700 mb-2">Nom de la catégorie</label>
                <input type="text" name="nom_categorie" id="nom_categorie" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            </div>
            <div class="flex gap-4">
                <button type="submit" class="flex-1 btn-primary text-white py-3 px-6 rounded-lg font-semibold">
                    Enregistrer
                </button>
                <button type="button" onclick="closeModal()" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium">
                    Annuler
                </button>
            </div>
        </form>
    </div>
</div>

<script>
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
    document.getElementById('modalTitle').textContent = 'Modifier la catégorie';
    document.getElementById('categoryForm').action = `/admin/categories/${id}`;
    document.getElementById('nom_categorie').value = name;
}

function closeModal() {
    document.getElementById('categoryModal').classList.add('hidden');
    document.getElementById('categoryModal').classList.remove('flex');
}

// Close modal when clicking outside
document.getElementById('categoryModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
@endsection