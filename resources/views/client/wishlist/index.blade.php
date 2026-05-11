@extends('layouts.app')

@section('title', 'Ma Liste de Souhaits - The Vault')

@section('content')
<div class="mb-16 gsap-fade-up">
    <a href="{{ route('client.profile') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 uppercase tracking-widest hover:text-slate-900 transition-colors mb-6">
        <i class="fas fa-arrow-left"></i> Mon Espace
    </a>
    <div class="flex items-end justify-between border-b border-slate-900 pb-6">
        <h1 class="text-5xl md:text-7xl font-black text-slate-900 tracking-tighter uppercase leading-none">
            Favoris.
        </h1>
        <span class="hidden md:block text-sm font-bold text-slate-400 uppercase tracking-widest">{{ $wishlistItems->count() }} Objet(s)</span>
    </div>
</div>

@if($wishlistItems->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-6 gap-y-16 mb-12">
        @foreach($wishlistItems as $product)
            <div class="group gsap-product flex flex-col h-full relative">
                
                <a href="{{ route('products.show', $product->slug) }}" class="block relative overflow-hidden aspect-[4/5] bg-slate-100 mb-5">
                    <img src="{{ $product->image ?? 'https://via.placeholder.com/600x800?text=' . urlencode($product->nom_produit) }}"
                         alt="{{ $product->nom_produit }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-1000 ease-[cubic-bezier(0.25,1,0.5,1)]">
                    
                    @if($product->stock <= 0)
                        <div class="absolute inset-0 bg-slate-900/20 backdrop-blur-[2px] flex items-center justify-center">
                            <span class="bg-slate-900 text-white px-6 py-3 text-xs font-bold uppercase tracking-widest">Épuisé</span>
                        </div>
                    @endif
                </a>
                
                <div class="flex flex-col flex-1">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">{{ $product->categorie->nom_categorie ?? 'Non catégorisé' }}</p>
                    
                    <div class="flex justify-between items-start gap-4 mb-2">
                        <h3 class="font-black text-slate-900 text-lg uppercase leading-tight hover:text-slate-600 transition-colors line-clamp-1">
                            <a href="{{ route('products.show', $product->slug) }}">{{ $product->nom_produit }}</a>
                        </h3>
                        <span class="font-black text-slate-900 text-lg whitespace-nowrap">{{ number_format($product->prix, 2) }} DH</span>
                    </div>

                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center text-amber-400 text-xs">
                            @php $avg = $product->reviews->avg('rating') ?? 0; @endphp
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star{{ $i <= round($avg) ? '' : ($i - 0.5 <= round($avg) ? '-half-alt' : '') }} {{ $i <= round($avg) ? '' : 'text-slate-200' }}"></i>
                            @endfor
                            <span class="text-slate-400 ml-2 font-bold">({{ $product->reviews->count() }})</span>
                        </div>
                        
                        @if($product->stock > 0)
                            <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">En stock ({{ $product->stock }})</span>
                        @else
                            <span class="text-[10px] font-black text-red-500 uppercase tracking-widest">Rupture</span>
                        @endif
                    </div>

                    <div class="mt-auto flex flex-col gap-3">
                        <form action="{{ route('client.cart.add', $product) }}" method="POST">
                            @csrf
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="w-full py-4 text-xs font-black uppercase tracking-widest transition-colors flex items-center justify-center gap-2
                                {{ $product->stock > 0 ? 'bg-slate-900 text-white hover:bg-slate-800' : 'bg-slate-100 text-slate-400 cursor-not-allowed' }}"
                                {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                {{ $product->stock > 0 ? 'Ajouter au panier' : 'Indisponible' }}
                            </button>
                        </form>
                        
                        <form action="{{ route('client.wishlist.remove', $product) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full py-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest hover:text-red-500 transition-colors inline-flex items-center justify-center gap-2 group/btn">
                                <i class="fas fa-times group-hover/btn:rotate-90 transition-transform duration-300"></i> Retirer des favoris
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="border border-slate-200 border-dashed p-16 md:p-32 text-center gsap-fade-up">
        <h3 class="text-3xl font-black text-slate-900 uppercase tracking-tight mb-4">Liste vide</h3>
        <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-10">Vous n'avez pas encore sélectionné d'objets.</p>
        <a href="{{ route('products.index') }}" class="inline-block bg-slate-900 text-white font-black uppercase tracking-widest px-8 py-4 hover:bg-slate-800 transition-colors">
            Explorer le catalogue
        </a>
    </div>
@endif

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", () => {
        gsap.fromTo(".gsap-fade-up", 
            { y: 30, opacity: 0 }, 
            { y: 0, opacity: 1, duration: 0.8, stagger: 0.1, ease: "power3.out" }
        );

        gsap.fromTo(".gsap-product", 
            { y: 40, opacity: 0 }, 
            { y: 0, opacity: 1, duration: 0.8, stagger: 0.1, ease: "power3.out", delay: 0.2 }
        );
    });
</script>
@endpush
@endsection