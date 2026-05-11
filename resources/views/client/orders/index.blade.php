@extends('layouts.app')
@section('title', 'Mes Commandes - The Vault')

@section('content')
<div class="mb-16 gsap-fade-up">
    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 block">Historique d'acquisitions</span>
    <div class="flex items-end justify-between border-b border-slate-900 pb-6">
        <h1 class="text-5xl md:text-7xl font-black text-slate-900 tracking-tighter uppercase leading-none">
            Commandes.
        </h1>
    </div>
</div>

@if($orders->count() > 0)
    <div class="flex flex-col border-b border-slate-200 mb-12">
        @foreach($orders as $order)
            <a href="{{ route('client.orders.show', $order) }}" class="group block py-8 border-t border-slate-200 hover:bg-slate-50 transition-colors px-4 -mx-4 gsap-row">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div>
                        <p class="font-black text-slate-900 text-2xl md:text-3xl uppercase tracking-tighter group-hover:text-slate-500 transition-colors mb-2">
                            #{{ $order->id_commande }}
                        </p>
                        <div class="flex items-center gap-4 text-xs font-bold text-slate-400 uppercase tracking-widest">
                            <span>{{ $order->created_at->format('d/m/Y') }}</span>
                            <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                            <span>{{ $order->items->count() }} Article(s)</span>
                        </div>
                    </div>
                    
                    <div class="flex flex-row md:flex-col items-center md:items-end justify-between gap-2">
                        <p class="text-2xl font-black text-slate-900 tracking-tight">{{ number_format($order->total, 2) }} DH</p>
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] font-black uppercase tracking-widest px-2 py-1
                                @if($order->mode_paiement === 'paypal') bg-blue-100 text-blue-800
                                @else bg-emerald-100 text-emerald-800 @endif">
                                @if($order->mode_paiement === 'paypal') PayPal @else COD @endif
                            </span>
                            <span class="text-[10px] font-black uppercase tracking-widest px-3 py-1 
                                @if($order->statut=='en_attente') bg-amber-100 text-amber-900 
                                @elseif($order->statut=='livree') bg-emerald-100 text-emerald-900 
                                @else bg-slate-200 text-slate-900 @endif">
                                {{ str_replace('_',' ',$order->statut) }}
                            </span>
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    <div class="mt-8 gsap-fade-up">
        {{ $orders->links() }}
    </div>
@else
    <div class="border border-slate-200 border-dashed p-16 md:p-32 text-center gsap-fade-up">
        <h3 class="text-3xl font-black text-slate-900 uppercase tracking-tight mb-4">Aucune commande</h3>
        <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-10">Votre historique est actuellement vide.</p>
        <a href="{{ route('products.index') }}" class="inline-block bg-slate-900 text-white font-black uppercase tracking-widest px-8 py-4 hover:bg-slate-800 transition-colors">
            Explorer le catalogue
        </a>
    </div>
@endif

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", () => {
        gsap.fromTo(".gsap-fade-up", { y: 30, opacity: 0 }, { y: 0, opacity: 1, duration: 0.8, stagger: 0.1, ease: "power3.out" });
        gsap.fromTo(".gsap-row", { x: -20, opacity: 0 }, { x: 0, opacity: 1, duration: 0.5, stagger: 0.08, ease: "power2.out", delay: 0.2 });
    });
</script>
@endpush
@endsection