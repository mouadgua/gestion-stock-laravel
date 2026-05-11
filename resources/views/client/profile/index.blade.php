@extends('layouts.app')

@section('title', 'Mon Espace - The Vault')

@section('content')
<div class="mb-16 gsap-fade-up">
    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 block">Tableau de bord</span>
    <h1 class="text-5xl md:text-7xl font-black text-slate-900 tracking-tighter uppercase leading-none">Mon<br>Espace.</h1>
</div>

<div class="grid grid-cols-2 md:grid-cols-4 border-y border-slate-200 py-10 mb-20 gsap-fade-up">
    <div class="text-center px-4 border-r border-slate-100 last:border-0 md:last:border-r-0">
        <p class="text-4xl md:text-5xl font-black text-slate-900 tracking-tighter mb-1">{{ $totalOrders }}</p>
        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Commandes</p>
    </div>
    <div class="text-center px-4 border-r-0 md:border-r border-slate-100">
        <p class="text-4xl md:text-5xl font-black text-slate-900 tracking-tighter mb-1">{{ $pendingOrders }}</p>
        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">En attente</p>
    </div>
    <div class="text-center px-4 border-r border-slate-100 mt-8 md:mt-0 pt-8 md:pt-0 border-t md:border-t-0">
        <p class="text-4xl md:text-5xl font-black text-slate-900 tracking-tighter mb-1">{{ $completedOrders }}</p>
        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Livrées</p>
    </div>
    <div class="text-center px-4 mt-8 md:mt-0 pt-8 md:pt-0 border-t md:border-t-0 border-slate-100">
        <p class="text-4xl md:text-5xl font-black text-slate-900 tracking-tighter mb-1 whitespace-nowrap">{{ number_format($totalSpent, 0) }} <span class="text-2xl text-slate-400">DH</span></p>
        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Dépensé</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-16 mb-20">
    <div class="gsap-fade-up">
        <div class="flex items-end justify-between border-b border-slate-900 pb-4 mb-6">
            <h2 class="text-2xl font-black text-slate-900 uppercase tracking-tight">Acquisitions</h2>
            <a href="{{ route('client.orders.index') }}" class="text-xs font-bold text-slate-900 uppercase tracking-widest hover:text-slate-500 transition-colors">Tout voir</a>
        </div>
        
        @if($recentOrders->count() > 0)
            <div class="flex flex-col">
                @foreach($recentOrders as $order)
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between py-5 border-b border-slate-100 hover:bg-slate-50 transition-colors px-2 -mx-2">
                        <div class="mb-2 sm:mb-0">
                            <p class="font-black text-slate-900 text-lg uppercase">#{{ $order->id_commande }}</p>
                            <p class="text-xs font-bold text-slate-400 tracking-widest uppercase">{{ $order->created_at->format('d/m/Y') }}</p>
                        </div>
                        <div class="text-left sm:text-right flex flex-row sm:flex-col items-center sm:items-end justify-between">
                            <p class="font-black text-slate-900 text-lg">{{ number_format($order->total, 2) }} DH</p>
                            <span class="text-[10px] font-black uppercase tracking-widest px-2 py-1 mt-1 
                                @if($order->statut == 'en_attente') bg-amber-100 text-amber-900 
                                @elseif($order->statut == 'livree') bg-emerald-100 text-emerald-900 
                                @else bg-slate-200 text-slate-900 @endif">
                                {{ str_replace('_', ' ', $order->statut) }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else 
            <p class="text-slate-500 font-medium py-8">Aucune acquisition récente.</p> 
        @endif
    </div>

    <div class="gsap-fade-up">
        <div class="flex items-end justify-between border-b border-slate-900 pb-4 mb-6">
            <h2 class="text-2xl font-black text-slate-900 uppercase tracking-tight">Vos Avis</h2>
            <a href="{{ route('client.profile.reviews') }}" class="text-xs font-bold text-slate-900 uppercase tracking-widest hover:text-slate-500 transition-colors">Tout voir</a>
        </div>

        @if($recentReviews->count() > 0)
            <div class="flex flex-col">
                @foreach($recentReviews as $review)
                    <div class="flex items-center gap-4 py-5 border-b border-slate-100 hover:bg-slate-50 transition-colors px-2 -mx-2">
                        <img src="{{ $review->product->image ?? 'https://via.placeholder.com/80' }}" class="w-16 h-20 object-cover bg-slate-100">
                        <div class="flex-1">
                            <p class="font-black text-slate-900 text-base leading-tight mb-1">{{ $review->product->nom_produit }}</p>
                            <div class="flex text-slate-900 text-xs">
                                @for($i=1;$i<=5;$i++)
                                    <i class="fas fa-star{{ $i<=$review->rating ? '' : ' text-slate-200' }}"></i>
                                @endfor
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else 
            <p class="text-slate-500 font-medium py-8">Aucun avis publié.</p> 
        @endif
    </div>
</div>

<div class="grid grid-cols-2 md:grid-cols-4 gap-4 gsap-fade-up">
    <a href="{{ route('products.index') }}" class="group bg-slate-900 text-white p-8 flex flex-col justify-between h-40 hover:bg-slate-800 transition-colors">
        <i class="fas fa-box text-xl opacity-50 group-hover:opacity-100 transition-opacity"></i>
        <p class="font-black uppercase tracking-widest text-sm text-left">Explorer<br>le catalogue</p>
    </a>
    <a href="{{ route('client.orders.index') }}" class="group bg-white border border-slate-200 p-8 flex flex-col justify-between h-40 hover:bg-slate-50 transition-colors">
        <i class="fas fa-receipt text-xl text-slate-300 group-hover:text-slate-900 transition-colors"></i>
        <p class="font-black text-slate-900 uppercase tracking-widest text-sm text-left">Historique<br>des commandes</p>
    </a>
    <a href="{{ route('client.wishlist.index') }}" class="group bg-white border border-slate-200 p-8 flex flex-col justify-between h-40 hover:bg-slate-50 transition-colors">
        <i class="fas fa-heart text-xl text-slate-300 group-hover:text-slate-900 transition-colors"></i>
        <p class="font-black text-slate-900 uppercase tracking-widest text-sm text-left">Favoris<br>({{ $wishlistCount }})</p>
    </a>
    <a href="{{ route('client.profile.reviews') }}" class="group bg-white border border-slate-200 p-8 flex flex-col justify-between h-40 hover:bg-slate-50 transition-colors">
        <i class="fas fa-star text-xl text-slate-300 group-hover:text-slate-900 transition-colors"></i>
        <p class="font-black text-slate-900 uppercase tracking-widest text-sm text-left">Gérer<br>mes avis</p>
    </a>
</div>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", () => {
        gsap.fromTo(".gsap-fade-up", 
            { y: 40, opacity: 0 }, 
            { y: 0, opacity: 1, duration: 0.8, stagger: 0.1, ease: "power3.out" }
        );
    });
</script>
@endpush
@endsection