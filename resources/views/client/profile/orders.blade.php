@extends('layouts.app')
@section('title', 'Mes Commandes - The Vault')

@section('content')
<div class="mb-16 gsap-fade-up">
    <a href="{{ route('client.profile') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 uppercase tracking-widest hover:text-slate-900 transition-colors mb-6">
        <i class="fas fa-arrow-left"></i> Mon Espace
    </a>
    <div class="flex items-end justify-between border-b border-slate-900 pb-6">
        <h1 class="text-5xl md:text-7xl font-black text-slate-900 tracking-tighter uppercase leading-none">
            Historique.
        </h1>
        <span class="hidden md:block text-sm font-bold text-slate-400 uppercase tracking-widest">{{ $orders->count() }} Archives</span>
    </div>
</div>

@if($orders->count() > 0)
    <div class="flex flex-col border-b border-slate-200 gsap-stagger-container mb-12">
        <div class="hidden md:grid grid-cols-12 gap-4 py-4 border-b border-slate-200 text-xs font-bold text-slate-400 uppercase tracking-widest px-4">
            <div class="col-span-3">Numéro</div>
            <div class="col-span-3">Date</div>
            <div class="col-span-2">Articles</div>
            <div class="col-span-2">Statut</div>
            <div class="col-span-2 text-right">Total</div>
        </div>

        @foreach($orders as $order)
            <a href="{{ route('client.orders.show', $order) }}" class="group block py-6 px-4 border-t border-slate-200 hover:bg-slate-50 transition-colors gsap-row">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 md:items-center">
                    
                    <div class="col-span-3">
                        <p class="font-black text-slate-900 text-xl uppercase group-hover:text-slate-600 transition-colors">#{{ $order->id_commande }}</p>
                    </div>
                    
                    <div class="col-span-3 flex items-center">
                        <p class="text-sm font-bold text-slate-500 tracking-widest uppercase">{{ $order->created_at->format('d/m/Y') }}</p>
                    </div>

                    <div class="col-span-2 flex items-center">
                        @if($order->items->count() > 0)
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ $order->items->count() }} Article(s)</p>
                        @else
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">-</p>
                        @endif
                    </div>
                    
                    <div class="col-span-2 flex items-center">
                        <span class="text-[10px] font-black uppercase tracking-widest px-3 py-1.5 inline-block
                            @if($order->statut=='en_attente') bg-amber-100 text-amber-900 
                            @elseif($order->statut=='livree') bg-emerald-100 text-emerald-900 
                            @else bg-slate-200 text-slate-900 @endif">
                            {{ str_replace('_',' ',$order->statut) }}
                        </span>
                    </div>
                    
                    <div class="col-span-2 md:text-right mt-2 md:mt-0 flex justify-between md:block items-center">
                        <span class="md:hidden text-xs font-bold text-slate-400 uppercase tracking-widest">Total</span>
                        <p class="text-2xl font-black text-slate-900">{{ number_format($order->total, 2) }} DH</p>
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
        <h3 class="text-3xl font-black text-slate-900 uppercase tracking-tight mb-4">Aucune archive</h3>
        <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-10">Votre historique est actuellement vide.</p>
        <a href="{{ route('products.index') }}" class="inline-block bg-slate-900 text-white font-black uppercase tracking-widest px-8 py-4 hover:bg-slate-800 transition-colors">
            Explorer le catalogue
        </a>
    </div>
@endif

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", () => {
        // Apparition du Header et pagination
        gsap.fromTo(".gsap-fade-up", 
            { y: 30, opacity: 0 }, 
            { y: 0, opacity: 1, duration: 0.8, stagger: 0.1, ease: "power3.out" }
        );

        // Apparition en cascade des lignes du tableau
        gsap.fromTo(".gsap-row", 
            { x: -20, opacity: 0 }, 
            { x: 0, opacity: 1, duration: 0.5, stagger: 0.08, ease: "power2.out", delay: 0.2 }
        );
    });
</script>
@endpush
@endsection