@extends('layouts.app')
@section('title', 'Ma Wishlist - The Vault')

@section('content')
<div class="mb-16 gsap-fade-up">
    <a href="{{ route('client.profile') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 uppercase tracking-widest hover:text-slate-900 transition-colors mb-6">
        <i class="fas fa-arrow-left"></i> Mon Espace
    </a>
    <div class="flex items-end justify-between border-b border-slate-900 pb-6">
        <h1 class="text-5xl md:text-7xl font-black text-slate-900 tracking-tighter uppercase leading-none">
            Favoris.
        </h1>
        <span class="hidden md:block text-sm font-bold text-slate-400 uppercase tracking-widest">{{ $wishlistItems->count() }} Objets</span>
    </div>
</div>

@if($wishlistItems->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-16 mb-12">
        @foreach($wishlistItems as $product)
            <div class="group gsap-product flex flex-col h-full">
                <a href="{{ route('products.show', $product->slug) }}" class="block relative overflow-hidden aspect-[4/5] bg-slate-100 mb-5">
                    <img src="{{ $product->image ?? 'https://via.placeholder.com/600x800' }}" 
                         alt="{{ $product->nom_produit }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-1000 ease-[cubic-bezier(0.25,1,0.5,1)]">
                    
                    <div class="absolute inset-x-0 bottom-0 p-4 opacity-0 translate-y-4 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-500 bg-gradient-to-t from-black/50 to-transparent flex justify-center pointer-events-none">
                        <span class="bg-white text-slate-900 px-6 py-3 font-bold text-xs uppercase tracking-widest">
                            Voir l'objet
                        </span>
                    </div>
                </a>
                
                <div class="flex flex-col flex-1">
                    <div class="flex justify-between items-start gap-4 mb-3">
                        <h3 class="font-extrabold text-slate-900 text-lg leading-tight uppercase group-hover:text-slate-600 transition-colors line-clamp-2">
                            <a href="{{ route('products.show', $product->slug) }}">{{ $product->nom_produit }}</a>
                        </h3>
                        <span class="font-black text-slate-900 text-lg whitespace-nowrap">{{ number_format($product->prix, 2) }} DH</span>
                    </div>
                    
                    <div class="mt-auto pt-4 border-t border-slate-100">
                        <form action="{{ route('client.wishlist.remove', $product) }}" method="POST">
                            @csrf 
                            @method('DELETE')
                            <button type="submit" class="text-xs font-bold text-slate-400 uppercase tracking-widest hover:text-red-500 transition-colors inline-flex items-center gap-2 group/btn">
                                <i class="fas fa-times group-hover/btn:rotate-90 transition-transform duration-300"></i> Retirer des favoris
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-8 gsap-fade-up">
        {{ $wishlistItems->links() }}
    </div>

@else
    <div class="border border-slate-200 border-dashed p-16 md:p-32 text-center gsap-fade-up">
        <h3 class="text-3xl font-black text-slate-900 uppercase tracking-tight mb-4">Aucun favori</h3>
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