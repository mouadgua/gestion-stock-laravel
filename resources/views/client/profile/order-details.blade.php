@extends('layouts.app')
@section('title', 'Commande #'.$order->id_commande . ' - The Vault')

@section('content')
<div class="mb-16 gsap-fade-up">
    <a href="{{ route('client.orders.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 uppercase tracking-widest hover:text-slate-900 transition-colors mb-8">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
    <h1 class="text-5xl md:text-7xl font-black text-slate-900 tracking-tighter uppercase leading-none mb-4">
        Commande<br>#{{ $order->id_commande }}
    </h1>
    <div class="flex items-center gap-4">
        <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">
            Date : {{ $order->created_at->format('d/m/Y - H:i') }}
        </p>
        <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
        <span class="text-[10px] font-black uppercase tracking-widest px-3 py-1 
            @if($order->statut=='en_attente') bg-amber-100 text-amber-900 
            @elseif($order->statut=='livree') bg-emerald-100 text-emerald-900 
            @else bg-slate-200 text-slate-900 @endif">
            {{ str_replace('_',' ',$order->statut) }}
        </span>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-12 gsap-fade-up">
    <div class="lg:col-span-2">
        <h2 class="text-2xl font-black text-slate-900 uppercase tracking-tight border-b border-slate-900 pb-4 mb-6">Contenu de l'acquisition</h2>
        
        @if($order->items->count() > 0)
            <div class="flex flex-col border-b border-slate-200">
                @foreach($order->items as $item)
                    <div class="py-6 flex flex-col sm:flex-row sm:items-center justify-between border-t border-slate-200 gap-4">
                        <div class="flex items-center gap-6">
                            <div class="w-20 h-24 bg-slate-100 shrink-0">
                                <img src="{{ $item->product->image ?? 'https://via.placeholder.com/150' }}" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <p class="font-black text-slate-900 text-lg uppercase leading-tight mb-1">{{ $item->product->nom_produit }}</p>
                                <p class="text-sm font-bold text-slate-400 tracking-widest uppercase">QTE: {{ $item->quantite }} <span class="mx-2">|</span> {{ number_format($item->prix_unitaire, 2) }} DH</p>
                            </div>
                        </div>
                        <div class="text-left sm:text-right mt-2 sm:mt-0 pl-26 sm:pl-0">
                            <p class="font-black text-slate-900 text-xl">{{ number_format($item->prix_unitaire * $item->quantite, 2) }} DH</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div>
        <div class="bg-slate-50 p-8 border border-slate-200 sticky top-28">
            <h2 class="text-xl font-black text-slate-900 uppercase tracking-tight border-b border-slate-200 pb-4 mb-6">Récapitulatif</h2>
            
            <div class="space-y-4 mb-8">
                <div class="flex justify-between items-center text-sm font-bold text-slate-500 uppercase tracking-widest">
                    <span>Sous-total</span>
                    <span class="text-slate-900">{{ number_format($order->total, 2) }} DH</span>
                </div>
                <div class="flex justify-between items-center text-sm font-bold text-slate-500 uppercase tracking-widest">
                    <span>Livraison</span>
                    <span class="text-slate-900">Inclus</span>
                </div>
            </div>

            <div class="border-t border-slate-900 pt-6">
                <div class="flex justify-between items-end">
                    <span class="text-sm font-bold text-slate-900 uppercase tracking-widest">Total</span>
                    <span class="font-black text-4xl text-slate-900 tracking-tighter">{{ number_format($order->total, 2) }} <span class="text-xl">DH</span></span>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", () => {
        gsap.fromTo(".gsap-fade-up", 
            { y: 30, opacity: 0 }, 
            { y: 0, opacity: 1, duration: 0.8, stagger: 0.15, ease: "power3.out" }
        );
    });
</script>
@endpush
@endsection