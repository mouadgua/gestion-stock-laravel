@extends('layouts.app')

@section('title', 'Produits - The Vault')

@section('content')
<div class="mb-10 gsap-stagger">
    <span class="text-sm font-bold text-slate-500 tracking-wider uppercase">Catalogue sécurisé</span>
    <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 mt-1 tracking-tight">Nos Équipements</h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
    <div class="lg:col-span-1 gsap-stagger">
        <div class="bg-white rounded-[2rem] p-6 sticky top-24 shadow-xl shadow-slate-200/50 border border-slate-100">
            <form action="{{ route('products.index') }}" method="GET">
                <div class="mb-6">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Recherche</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-search text-slate-400 group-focus-within:text-slate-900 transition-colors"></i>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom du produit..."
                            class="w-full pl-11 pr-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-900/10 transition-all font-medium text-slate-900 placeholder-slate-400">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Catégorie</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-folder text-slate-400 group-focus-within:text-slate-900 transition-colors"></i>
                        </div>
                        <select name="category" class="w-full pl-11 pr-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-900/10 transition-all font-medium text-slate-900 appearance-none">
                            <option value="">Toutes les catégories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->nom_categorie }} ({{ $category->products_count ?? 0 }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-8">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Trier par</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-sort text-slate-400 group-focus-within:text-slate-900 transition-colors"></i>
                        </div>
                        <select name="sort" class="w-full pl-11 pr-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-900/10 transition-all font-medium text-slate-900 appearance-none">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Plus récent</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Prix croissant</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Prix décroissant</option>
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nom A-Z</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white py-3.5 rounded-xl font-bold flex items-center justify-center gap-2 transition-all transform hover:-translate-y-1 hover:shadow-lg hover:shadow-slate-900/20 active:translate-y-0">
                    <i class="fas fa-filter text-sm"></i> Appliquer les filtres
                </button>
            </form>
        </div>
    </div>

    <div class="lg:col-span-3">
        @if($products->count() > 0)
            <div class="flex items-center justify-between mb-6 gsap-stagger">
                <p class="text-sm font-medium text-slate-500"><span class="font-extrabold text-slate-900">{{ $products->total() }}</span> objets dans le coffre</p>
                <div class="flex gap-2">
                    <button class="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center shadow-md"><i class="fas fa-th-large"></i></button>
                    <button class="w-10 h-10 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-slate-900 hover:bg-slate-50 transition-colors flex items-center justify-center"><i class="fas fa-list"></i></button>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @foreach($products as $product)
                    <div class="bg-white border border-slate-200 rounded-[1.5rem] p-2 hover:shadow-2xl hover:shadow-slate-200/50 hover:border-slate-300 transition-all duration-300 group gsap-product relative flex flex-col h-full">
                        <div class="relative overflow-hidden aspect-[4/3] rounded-[1rem] bg-slate-50">
                            <a href="{{ route('products.show', $product->slug) }}">
                                <img src="{{ $product->image ?? 'https://via.placeholder.com/400x300?text=' . urlencode($product->nom_produit) }}"
                                     alt="{{ $product->nom_produit }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition duration-700">
                            </a>
                            @if($product->stock <= 0)
                                <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-[2px] flex items-center justify-center">
                                    <span class="bg-red-500 text-white px-4 py-2 rounded-xl text-sm font-bold shadow-lg">Épuisé</span>
                                </div>
                            @endif
                            <div class="absolute top-3 right-3">
                                <span class="bg-white/95 backdrop-blur-md text-slate-900 font-black px-3 py-1.5 rounded-lg shadow-sm text-sm border border-slate-100/50">
                                    {{ number_format($product->prix, 2) }} DH
                                </span>
                            </div>
                        </div>
                        
                        <div class="p-4 flex flex-col flex-1">
                            <p class="text-[10px] font-black text-slate-400 mb-2 uppercase tracking-widest">{{ $product->categorie->nom_categorie ?? 'Non catégorisé' }}</p>
                            <h3 class="font-extrabold text-slate-900 mb-2 text-lg leading-tight line-clamp-2">
                                <a href="{{ route('products.show', $product->slug) }}" class="hover:text-slate-600 transition-colors">{{ $product->nom_produit }}</a>
                            </h3>
                            <p class="text-sm font-medium text-slate-500 mb-4 line-clamp-2 flex-1">{{ Str::limit($product->description, 80) }}</p>
                            
                            <div class="flex items-center justify-between mb-4">
                                @if($product->stock > 0)
                                    <span class="text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-100 px-3 py-1 rounded-lg inline-flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> {{ $product->stock }} en stock
                                    </span>
                                @else
                                    <span class="text-xs font-bold text-red-700 bg-red-50 border border-red-100 px-3 py-1 rounded-lg inline-flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Rupture
                                    </span>
                                @endif
                            </div>
                            
                            @if(auth()->check() && !auth()->user()->isAdmin())
                            <form action="{{ route('client.cart.add', $product) }}" method="POST" class="mt-auto">
                                @csrf
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="w-full {{ $product->stock > 0 ? 'bg-slate-900 hover:bg-slate-800 text-white hover:-translate-y-0.5 hover:shadow-lg shadow-slate-900/20' : 'bg-slate-100 text-slate-400 cursor-not-allowed' }} py-3 rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2"
                                        {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                    <i class="fas fa-shopping-cart"></i>
                                    {{ $product->stock > 0 ? 'Ajouter au panier' : 'Indisponible' }}
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-12 gsap-stagger">
                {{ $products->links() }}
            </div>
        @else
            <div class="bg-white rounded-[2rem] p-16 text-center border border-slate-100 shadow-xl shadow-slate-200/50 gsap-stagger">
                <div class="w-24 h-24 mx-auto mb-6 rounded-3xl bg-slate-50 border border-slate-100 flex items-center justify-center transform rotate-3">
                    <i class="fas fa-vault text-slate-300 text-4xl"></i>
                </div>
                <h3 class="text-2xl font-extrabold text-slate-900 mb-2">Coffre vide</h3>
                <p class="text-slate-500 font-medium mb-8">Aucun objet ne correspond à vos critères de recherche.</p>
                <a href="{{ route('products.index') }}" class="bg-slate-900 text-white px-8 py-3.5 rounded-xl font-bold inline-flex items-center gap-2 hover:bg-slate-800 transition-colors shadow-lg shadow-slate-900/20">
                    <i class="fas fa-undo"></i> Réinitialiser les filtres
                </a>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", () => {
        // Animation du header et de la sidebar
        gsap.fromTo(".gsap-stagger", 
            { y: 20, opacity: 0 }, 
            { y: 0, opacity: 1, duration: 0.6, stagger: 0.1, ease: "power2.out" }
        );

        // Animation des produits avec un stagger
        gsap.fromTo(".gsap-product", 
            { y: 30, opacity: 0, scale: 0.95 }, 
            { y: 0, opacity: 1, scale: 1, duration: 0.5, stagger: 0.05, ease: "back.out(1.2)", delay: 0.2 }
        );
    });
</script>
@endpush
@endsection