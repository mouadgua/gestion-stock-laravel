@extends('layouts.app')
@section('title', 'Commande #'.$order->id_commande . ' - The Vault')

@section('content')
<div class="mb-16 gsap-fade-up">
    <a href="{{ route('client.orders.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 uppercase tracking-widest hover:text-slate-900 transition-colors mb-6">
        <i class="fas fa-arrow-left"></i> Retour aux commandes
    </a>
    
    <div class="flex flex-col md:flex-row md:items-end justify-between border-b border-slate-900 pb-6 gap-6">
        <div>
            <h1 class="text-5xl md:text-7xl font-black text-slate-900 tracking-tighter uppercase leading-none mb-2">
                Dossier<br>#{{ $order->id_commande }}
            </h1>
            <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">
                Acquisition du {{ $order->created_at->format('d/m/Y') }}
            </p>
        </div>
        
        <div class="flex flex-col md:items-end gap-4">
            <span class="px-4 py-2 font-black uppercase tracking-widest text-xs 
                @if($order->statut=='en_attente') bg-amber-100 text-amber-900 
                @elseif($order->statut=='livree') bg-emerald-100 text-emerald-900 
                @else bg-slate-200 text-slate-900 @endif">
                {{ str_replace('_', ' ', $order->statut) }}
            </span>
            <a href="{{ route('client.orders.receipt', $order) }}" class="bg-slate-900 text-white font-black uppercase tracking-widest px-6 py-3 hover:bg-slate-800 transition-colors inline-flex items-center gap-2 no-print text-xs">
                <i class="fas fa-print"></i> Éditer le reçu
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
    <div class="lg:col-span-8 gsap-fade-up">
        <h2 class="text-2xl font-black text-slate-900 uppercase tracking-tight border-b border-slate-200 pb-4 mb-6">Inventaire</h2>
        
        @if($order->items->count() > 0)
            <div class="flex flex-col">
                @foreach($order->items as $item)
                    <div class="py-6 flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-200 gap-4 gsap-row">
                        <div class="flex items-center gap-6">
                            <div class="w-20 aspect-[4/5] bg-slate-100 shrink-0 overflow-hidden">
                                @php $img = $item->product->firstImage ?? null; @endphp
                                @if($img)
                                    <img src="{{ $img }}" alt="{{ $item->product->nom_produit }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-300"><i class="fas fa-image text-2xl"></i></div>
                                @endif
                            </div>
                            <div>
                                <p class="font-black text-slate-900 text-lg uppercase leading-tight mb-1">{{ $item->product->nom_produit }}</p>
                                <p class="text-xs font-bold text-slate-400 tracking-widest uppercase">{{ $item->quantite }} unité(s) x {{ number_format($item->prix_unitaire, 2) }} DH</p>
                            </div>
                        </div>
                        <div class="text-left sm:text-right pl-26 sm:pl-0">
                            <p class="font-black text-slate-900 text-xl whitespace-nowrap">{{ number_format($item->sous_total, 2) }} DH</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="lg:col-span-4 gsap-fade-up">
        <div class="bg-slate-50 p-8 border border-slate-200 sticky top-28">
            <h2 class="text-xl font-black text-slate-900 uppercase tracking-tight border-b border-slate-200 pb-4 mb-6">Bilan financier</h2>
            
            <div class="space-y-4 mb-6">
                @php
                    $itemsTotal = $order->items->sum('sous_total');
                    $promoDiscount = $order->discount ?? 0;
                @endphp
                <div class="flex justify-between items-center text-sm font-bold text-slate-500 uppercase tracking-widest">
                    <span>Sous-total</span>
                    <span class="text-slate-900">{{ number_format($itemsTotal, 2) }} DH</span>
                </div>
                @if($promoDiscount > 0)
                    <div class="flex justify-between items-center text-sm font-bold text-emerald-600 uppercase tracking-widest">
                        <span>Promo {{ $order->promo_code }}</span>
                        <span>-{{ number_format($promoDiscount, 2) }} DH</span>
                    </div>
                @endif
                <div class="flex justify-between items-center text-sm font-bold text-slate-500 uppercase tracking-widest">
                    <span>Taxes & Frais</span>
                    <span class="text-slate-900">Inclus</span>
                </div>
            </div>

            <!-- Payment Information -->
            <div class="border-t border-slate-200 pt-6 mb-6">
                <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-4">Informations de paiement</h3>
                
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Mode de paiement</span>
                    <span class="text-sm font-bold text-slate-900 uppercase">
                        @if($order->mode_paiement === 'paypal')
                            <span class="inline-flex items-center gap-1"><i class="fab fa-paypal text-blue-600"></i> PayPal</span>
                        @else
                            <span class="inline-flex items-center gap-1"><i class="fas fa-money-bill-wave text-emerald-600"></i> Paiement à la livraison</span>
                        @endif
                    </span>
                </div>

                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Statut du paiement</span>
                    <span class="px-2 py-1 text-xs font-black uppercase tracking-widest rounded
                        @if($order->statut_paiement === 'paye') bg-emerald-100 text-emerald-800
                        @elseif($order->statut_paiement === 'echoue') bg-red-100 text-red-800
                        @elseif($order->statut_paiement === 'rembourse') bg-purple-100 text-purple-800
                        @else bg-amber-100 text-amber-800 @endif">
                        {{ str_replace('_', ' ', $order->statut_paiement ?? 'en_attente') }}
                    </span>
                </div>

                @if($order->paypal_paiement_id)
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">ID Transaction</span>
                        <span class="text-xs font-mono text-slate-600 truncate max-w-[150px]">{{ $order->paypal_paiement_id }}</span>
                    </div>
                @endif
            </div>

            <!-- PayPal Pay Button (for pending PayPal orders) -->
            @if($order->mode_paiement === 'paypal' && $order->statut_paiement === 'en_attente' && $order->statut !== 'annulee')
                <div class="mb-6">
                    <a href="{{ route('paypal.process', $order) }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black uppercase tracking-widest py-4 transition-colors flex items-center justify-center gap-2 text-sm">
                        <i class="fab fa-paypal text-lg"></i> Payer avec PayPal
                    </a>
                </div>
            @endif

            <div class="border-t-4 border-slate-900 pt-6">
                <div class="flex flex-col">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Total Réglé</span>
                    <span class="font-black text-4xl md:text-5xl text-slate-900 tracking-tighter">{{ number_format($order->total, 2) }} <span class="text-2xl">DH</span></span>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", () => {
        gsap.fromTo(".gsap-fade-up", { y: 30, opacity: 0 }, { y: 0, opacity: 1, duration: 0.8, stagger: 0.1, ease: "power3.out" });
        gsap.fromTo(".gsap-row", { x: -20, opacity: 0 }, { x: 0, opacity: 1, duration: 0.5, stagger: 0.08, ease: "power2.out", delay: 0.2 });
    });
</script>
@endpush
@endsection