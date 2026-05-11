@extends('layouts.app')

@section('title', 'Gestion du Catalogue - The Vault')

@section('content')
<div class="mb-16 gsap-fade-up">
    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 block">Administration</span>
    <div class="flex flex-col md:flex-row md:items-end justify-between border-b border-slate-900 pb-6 gap-6">
        <h1 class="text-5xl md:text-7xl font-black text-slate-900 tracking-tighter uppercase leading-none">
            Catalogue.
        </h1>
        <a href="{{ route('admin.products.create') }}" class="bg-slate-900 text-white font-black uppercase tracking-widest px-8 py-4 hover:bg-slate-800 transition-colors inline-flex items-center justify-center gap-2 text-xs shrink-0">
            <i class="fas fa-plus"></i> Ajouter un objet
        </a>
    </div>
</div>

@if(session('success'))
    <div class="bg-slate-900 text-white px-6 py-4 mb-8 text-xs font-bold uppercase tracking-widest flex items-center gap-3 gsap-fade-up">
        <i class="fas fa-check text-emerald-400"></i> {{ session('success') }}
    </div>
@endif

@if($products->count() > 0)
    <div class="flex flex-col border-b border-slate-200 mb-12 overflow-x-auto gsap-fade-up">
        <table class="w-full text-left min-w-[800px]">
            <thead>
                <tr class="border-b-2 border-slate-900">
                    <th class="py-4 px-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Objet</th>
                    <th class="py-4 px-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Catégorie</th>
                    <th class="py-4 px-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Prix</th>
                    <th class="py-4 px-4 text-xs font-bold text-slate-400 uppercase tracking-widest text-center">Stock</th>
                    <th class="py-4 px-4 text-xs font-bold text-slate-400 uppercase tracking-widest text-center">Statut</th>
                    <th class="py-4 px-4 text-xs font-bold text-slate-400 uppercase tracking-widest text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @foreach($products as $product)
                    <tr class="hover:bg-slate-50 transition-colors gsap-row group">
                        <td class="py-4 px-4">
                            <div class="flex items-center gap-4">
                                <div class="w-12 aspect-[4/5] bg-slate-100 overflow-hidden shrink-0">
                                    <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/40' }}" 
                                         alt="{{ $product->nom_produit }}" 
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                </div>
                                <span class="font-black text-slate-900 uppercase text-sm leading-tight">{{ Str::limit($product->nom_produit, 40) }}</span>
                            </div>
                        </td>
                        
                        <td class="py-4 px-4 text-xs font-bold text-slate-500 uppercase tracking-widest">
                            {{ $product->categorie->nom_categorie ?? '-' }}
                        </td>
                        
                        <td class="py-4 px-4 font-black text-slate-900">
                            {{ number_format($product->prix, 2) }} DH
                        </td>
                        
                        <td class="py-4 px-4 text-center">
                            @if($product->stock > 10)
                                <span class="text-xs font-black text-slate-900">{{ $product->stock }}</span>
                            @elseif($product->stock > 0)
                                <span class="text-xs font-black text-amber-500">{{ $product->stock }}</span>
                            @else
                                <span class="text-[10px] font-black uppercase tracking-widest px-2 py-1 bg-red-100 text-red-700">Rupture</span>
                            @endif
                        </td>
                        
                        <td class="py-4 px-4 text-center">
                            @if($product->est_actif)
                                <span class="text-[10px] font-black uppercase tracking-widest"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block mr-1"></span> Actif</span>
                            @else
                                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400"><span class="w-1.5 h-1.5 rounded-full bg-slate-300 inline-block mr-1"></span> Inactif</span>
                            @endif
                        </td>
                        
                        <td class="py-4 px-4 text-right">
                            <div class="flex items-center justify-end gap-4">
                                <a href="{{ route('admin.products.edit', $product) }}" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest hover:text-slate-900 transition-colors">
                                    Éditer
                                </a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Confirmez-vous la suppression de cet objet ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest hover:text-red-500 transition-colors">
                                        Retirer
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="mt-8 gsap-fade-up">
        {{ $products->links() }}
    </div>
@else
    <div class="border border-slate-200 border-dashed p-16 md:p-32 text-center gsap-fade-up">
        <h3 class="text-3xl font-black text-slate-900 uppercase tracking-tight mb-4">Catalogue vide</h3>
        <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-10">Commencez par ajouter votre premier objet au coffre.</p>
        <a href="{{ route('admin.products.create') }}" class="inline-block bg-slate-900 text-white font-black uppercase tracking-widest px-8 py-4 hover:bg-slate-800 transition-colors text-xs">
            Ajouter un objet
        </a>
    </div>
@endif

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", () => {
        gsap.fromTo(".gsap-fade-up", { y: 30, opacity: 0 }, { y: 0, opacity: 1, duration: 0.8, stagger: 0.1, ease: "power3.out" });
        gsap.fromTo(".gsap-row", { x: -10, opacity: 0 }, { x: 0, opacity: 1, duration: 0.4, stagger: 0.05, ease: "power2.out", delay: 0.2 });
    });
</script>
@endpush
@endsection