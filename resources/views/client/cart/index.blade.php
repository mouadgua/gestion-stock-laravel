@extends('layouts.app')

@section('title', 'Panier - The Vault')

@section('content')
<div class="mb-16 gsap-fade-up">
    <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 uppercase tracking-widest hover:text-slate-900 transition-colors mb-6">
        <i class="fas fa-arrow-left"></i> Continuer les achats
    </a>
    <div class="flex items-end justify-between border-b border-slate-900 pb-6">
        <h1 class="text-5xl md:text-7xl font-black text-slate-900 tracking-tighter uppercase leading-none">
            Panier.
        </h1>
        <span class="hidden md:block text-sm font-bold text-slate-400 uppercase tracking-widest">{{ count($items) }} Objet(s)</span>
    </div>
</div>

@if(count($items) > 0)
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
        <div class="lg:col-span-8 border-t border-slate-200">
            @foreach($items as $item)
                <div class="py-8 flex flex-col sm:flex-row gap-6 border-b border-slate-200 gsap-row">
                    
                    <div class="w-24 sm:w-32 aspect-[4/5] bg-slate-100 shrink-0 overflow-hidden">
                        <img src="{{ $item['product']->image ?? 'https://via.placeholder.com/200x250' }}"
                             alt="{{ $item['product']->nom_produit }}"
                             class="w-full h-full object-cover">
                    </div>
                    
                    <div class="flex-1 flex flex-col justify-between">
                        <div class="flex justify-between items-start gap-4">
                            <div>
                                <h3 class="font-black text-slate-900 text-xl uppercase leading-tight mb-1">
                                    {{ $item['product']->nom_produit }}
                                </h3>
                                <p class="text-xs font-bold text-slate-400 tracking-widest uppercase">
                                    {{ $item['product']->categorie->nom_categorie ?? 'Standard' }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="font-black text-slate-900 text-lg whitespace-nowrap">{{ number_format($item['subtotal'], 2) }} DH</p>
                                <p class="text-[10px] font-bold text-slate-400 tracking-widest uppercase mt-1">{{ number_format($item['product']->prix, 2) }} DH / unité</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between mt-6">
                            
                            <div class="flex items-center border border-slate-200 bg-white">
                                <form action="{{ route('client.cart.update', $item['product']->id_produit) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="quantity" value="{{ max(1, $item['quantity'] - 1) }}">
                                    <button type="submit" class="w-10 h-10 flex items-center justify-center text-slate-500 hover:text-slate-900 hover:bg-slate-50 transition-colors">
                                        <i class="fas fa-minus text-xs"></i>
                                    </button>
                                </form>
                                
                                <span class="w-10 text-center font-black text-slate-900 text-sm">{{ $item['quantity'] }}</span>
                                
                                <form action="{{ route('client.cart.update', $item['product']->id_produit) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="quantity" value="{{ $item['quantity'] + 1 }}">
                                    <button type="submit" class="w-10 h-10 flex items-center justify-center text-slate-500 hover:text-slate-900 hover:bg-slate-50 transition-colors">
                                        <i class="fas fa-plus text-xs"></i>
                                    </button>
                                </form>
                            </div>

                            <form action="{{ route('client.cart.remove', $item['product']->id_produit) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs font-bold text-slate-400 uppercase tracking-widest hover:text-red-500 transition-colors inline-flex items-center gap-2 group/btn">
                                    Retirer <i class="fas fa-times group-hover/btn:rotate-90 transition-transform duration-300"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="lg:col-span-4 gsap-fade-up">
            <div class="bg-slate-50 p-8 border border-slate-200 sticky top-28">
                <h2 class="text-xl font-black text-slate-900 uppercase tracking-tight border-b border-slate-200 pb-4 mb-6">Récapitulatif</h2>
                
                <div class="space-y-4 mb-8">
                    <div class="flex justify-between items-center text-sm font-bold text-slate-500 uppercase tracking-widest">
                        <span>Sous-total</span>
                        <span class="text-slate-900">{{ number_format($total, 2) }} DH</span>
                    </div>
                    <div class="flex justify-between items-center text-sm font-bold text-slate-500 uppercase tracking-widest">
                        <span>Expédition</span>
                        <span class="text-slate-900">Offerte</span>
                    </div>
                </div>

                <div class="border-t border-slate-900 pt-6 mb-8">
                    <div class="flex justify-between items-end">
                        <span class="text-sm font-bold text-slate-900 uppercase tracking-widest">Total</span>
                        <span class="font-black text-4xl text-slate-900 tracking-tighter">{{ number_format($total, 2) }} <span class="text-xl">DH</span></span>
                    </div>
                </div>

                <a href="{{ route('client.cart.checkout') }}" class="w-full bg-slate-900 text-white font-black uppercase tracking-widest py-5 hover:bg-slate-800 transition-colors flex items-center justify-center gap-2 text-center">
                    Procéder au paiement <i class="fas fa-arrow-right text-xs"></i>
                </a>
                
                <div class="mt-6 text-center">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest flex items-center justify-center gap-2">
                        <i class="fas fa-shield-alt"></i> Transaction 100% chiffrée
                    </p>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="border border-slate-200 border-dashed p-16 md:p-32 text-center gsap-fade-up">
        <h3 class="text-3xl font-black text-slate-900 uppercase tracking-tight mb-4">Panier vide</h3>
        <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-10">Vous n'avez encore rien sélectionné dans The Vault.</p>
        <a href="{{ route('products.index') }}" class="inline-block bg-slate-900 text-white font-black uppercase tracking-widest px-8 py-4 hover:bg-slate-800 transition-colors">
            Explorer le catalogue
        </a>
    </div>
@endif

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", () => {
        // Apparition du Header
        gsap.fromTo(".gsap-fade-up", 
            { y: 30, opacity: 0 }, 
            { y: 0, opacity: 1, duration: 0.8, stagger: 0.1, ease: "power3.out" }
        );

        // Apparition en cascade des articles du panier
        gsap.fromTo(".gsap-row", 
            { x: -20, opacity: 0 }, 
            { x: 0, opacity: 1, duration: 0.6, stagger: 0.1, ease: "power2.out", delay: 0.2 }
        );
    });
</script>
@endpush
@endsection