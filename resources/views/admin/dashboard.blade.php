@extends('layouts.app')

@section('title', 'Dashboard Admin - The Vault')

@section('content')
<div class="mb-12 gsap-fade-up">
    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 block">Administration</span>
    <h1 class="text-5xl md:text-7xl font-black text-slate-900 tracking-tighter uppercase leading-none">
        Dashboard.
    </h1>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12 gsap-fade-up">
    <div class="bg-blue-600 text-white p-8 relative overflow-hidden group">
        <div class="relative z-10">
            <p class="text-xs font-bold uppercase tracking-widest mb-2 opacity-80">Produits</p>
            <p class="text-5xl font-black tracking-tighter mb-4">{{ $totalProducts }}</p>
            <p class="text-[10px] font-bold uppercase tracking-widest bg-white/20 inline-block px-3 py-1.5">{{ $activeProducts }} actifs</p>
        </div>
        <i class="fas fa-cubes absolute -bottom-6 -right-4 text-8xl opacity-20 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500"></i>
    </div>

    <div class="bg-purple-600 text-white p-8 relative overflow-hidden group">
        <div class="relative z-10">
            <p class="text-xs font-bold uppercase tracking-widest mb-2 opacity-80">Commandes</p>
            <p class="text-5xl font-black tracking-tighter mb-4">{{ $totalOrders }}</p>
            <p class="text-[10px] font-bold uppercase tracking-widest bg-white/20 inline-block px-3 py-1.5">{{ $pendingOrders }} en attente</p>
        </div>
        <i class="fas fa-shopping-bag absolute -bottom-6 -right-4 text-8xl opacity-20 group-hover:scale-110 group-hover:-rotate-6 transition-all duration-500"></i>
    </div>

    <div class="bg-emerald-600 text-white p-8 relative overflow-hidden group">
        <div class="relative z-10">
            <p class="text-xs font-bold uppercase tracking-widest mb-2 opacity-80">Clients</p>
            <p class="text-5xl font-black tracking-tighter mb-4">{{ $totalCustomers }}</p>
            <p class="text-[10px] font-bold uppercase tracking-widest bg-white/20 inline-block px-3 py-1.5">{{ $totalAdmins }} admins</p>
        </div>
        <i class="fas fa-users absolute -bottom-6 -right-4 text-8xl opacity-20 group-hover:scale-110 transition-all duration-500"></i>
    </div>

    <div class="bg-orange-500 text-white p-8 relative overflow-hidden group">
        <div class="relative z-10">
            <p class="text-xs font-bold uppercase tracking-widest mb-2 opacity-80">Chiffre d'Affaires</p>
            <p class="text-5xl font-black tracking-tighter mb-4 whitespace-nowrap">{{ number_format($totalRevenue, 0) }}<span class="text-2xl ml-1">€</span></p>
            <p class="text-[10px] font-bold uppercase tracking-widest bg-white/20 inline-block px-3 py-1.5">{{ $deliveredOrders }} livrées</p>
        </div>
        <i class="fas fa-euro-sign absolute -bottom-6 -right-4 text-8xl opacity-20 group-hover:scale-110 transition-all duration-500"></i>
    </div>
</div>

<div class="grid grid-cols-2 md:grid-cols-4 border-y-4 border-slate-900 py-8 mb-16 gap-y-8 gsap-fade-up bg-white">
    <div class="flex items-center gap-4 px-4 border-r border-slate-200">
        <div class="w-14 h-14 bg-red-50 text-red-600 flex items-center justify-center text-2xl shrink-0"><i class="fas fa-exclamation-triangle"></i></div>
        <div>
            <p class="text-3xl font-black text-slate-900 leading-none">{{ $lowStockProducts }}</p>
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-1">Stock faible</p>
        </div>
    </div>
    <div class="flex items-center gap-4 px-4 border-r-0 md:border-r border-slate-200">
        <div class="w-14 h-14 bg-amber-50 text-amber-500 flex items-center justify-center text-2xl shrink-0"><i class="fas fa-stop-circle"></i></div>
        <div>
            <p class="text-3xl font-black text-slate-900 leading-none">{{ $outOfStockProducts }}</p>
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-1">Ruptures</p>
        </div>
    </div>
    <div class="flex items-center gap-4 px-4 border-r border-slate-200 mt-8 md:mt-0 pt-8 md:pt-0 border-t md:border-t-0">
        <div class="w-14 h-14 bg-indigo-50 text-indigo-600 flex items-center justify-center text-2xl shrink-0"><i class="fas fa-star"></i></div>
        <div>
            <p class="text-3xl font-black text-slate-900 leading-none">{{ $totalReviews }}</p>
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-1">Avis totaux</p>
        </div>
    </div>
    <div class="flex items-center gap-4 px-4 mt-8 md:mt-0 pt-8 md:pt-0 border-t md:border-t-0 border-slate-200">
        <div class="w-14 h-14 bg-cyan-50 text-cyan-600 flex items-center justify-center text-2xl shrink-0"><i class="fas fa-history"></i></div>
        <div>
            <p class="text-3xl font-black text-slate-900 leading-none">{{ $totalLogs }}</p>
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-1">Activités</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-12">
    <div class="gsap-fade-up">
        <h2 class="text-2xl font-black text-slate-900 uppercase tracking-tight border-b-4 border-purple-600 pb-4 mb-6 flex items-center gap-3">
            <i class="fas fa-truck text-purple-600"></i> Acquisitions
        </h2>
        
        @if($recentOrders->count() > 0)
            <div class="flex flex-col">
                @foreach($recentOrders as $order)
                    <div class="flex items-center justify-between py-4 border-b border-slate-100 hover:bg-slate-50 transition-colors px-2 -mx-2">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-slate-900 text-white font-black flex items-center justify-center text-xs shrink-0">
                                {{ substr($order->user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-black text-slate-900 text-sm uppercase">#{{ $order->id_commande }}</p>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ $order->user->name }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-black text-slate-900 text-base">{{ number_format($order->total, 2) }} DH</p>
                            <span class="text-[9px] font-black uppercase tracking-widest px-2 py-1 mt-1 inline-block
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
            <p class="text-slate-500 text-sm font-bold uppercase tracking-widest py-8">Aucune commande</p>
        @endif
    </div>

    <div class="gsap-fade-up">
        <h2 class="text-2xl font-black text-slate-900 uppercase tracking-tight border-b-4 border-blue-600 pb-4 mb-6 flex items-center gap-3">
            <i class="fas fa-fire text-blue-600"></i> Top Objets
        </h2>
        
        @if($topProducts->count() > 0)
            <div class="flex flex-col">
                @foreach($topProducts as $product)
                    <div class="flex items-center justify-between py-4 border-b border-slate-100 hover:bg-slate-50 transition-colors px-2 -mx-2">
                        <div class="flex items-center gap-4">
                            <img src="{{ $product->image ?? 'https://via.placeholder.com/40' }}" class="w-10 h-12 object-cover bg-slate-100 shrink-0">
                            <div>
                                <p class="font-black text-slate-900 text-sm uppercase leading-tight line-clamp-1">{{ $product->nom_produit }}</p>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ number_format($product->prix, 2) }} DH</p>
                            </div>
                        </div>
                        <span class="bg-blue-100 text-blue-700 text-[10px] font-black uppercase tracking-widest px-3 py-1.5 shrink-0">
                            {{ $product->order_items_count }} vendus
                        </span>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-slate-500 text-sm font-bold uppercase tracking-widest py-8">Aucune vente</p>
        @endif
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-12">
    <div class="gsap-fade-up">
        <h2 class="text-2xl font-black text-slate-900 uppercase tracking-tight border-b-4 border-emerald-600 pb-4 mb-6 flex items-center gap-3">
            <i class="fas fa-users text-emerald-600"></i> Nouveaux Membres
        </h2>
        <div class="flex flex-col">
            @foreach($recentUsers as $user)
                <div class="flex items-center justify-between py-4 border-b border-slate-100 hover:bg-slate-50 transition-colors px-2 -mx-2">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-emerald-100 text-emerald-700 font-black flex items-center justify-center text-xs shrink-0">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-black text-slate-900 text-sm uppercase">{{ $user->name }}</p>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ $user->email }}</p>
                        </div>
                    </div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $user->created_at->format('d/m/Y') }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <div class="gsap-fade-up">
        <h2 class="text-2xl font-black text-slate-900 uppercase tracking-tight border-b-4 border-cyan-600 pb-4 mb-6 flex items-center gap-3">
            <i class="fas fa-clock text-cyan-600"></i> Flux d'activités
        </h2>
        <div class="flex flex-col">
            @foreach($recentLogs as $log)
                <div class="flex items-center justify-between py-4 border-b border-slate-100 hover:bg-slate-50 transition-colors px-2 -mx-2">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-slate-100 text-slate-600 font-black flex items-center justify-center text-xs shrink-0">
                            {{ substr($log->user->name ?? 'S', 0, 1) }}
                        </div>
                        <div>
                            <p class="font-black text-slate-900 text-sm uppercase">{{ $log->user->name ?? 'Système' }}</p>
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-widest line-clamp-1">{{ $log->description ?? $log->action }}</p>
                        </div>
                    </div>
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest shrink-0">{{ $log->created_at->diffForHumans() }}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="mb-16 gsap-fade-up">
    <h2 class="text-2xl font-black text-slate-900 uppercase tracking-tight border-b-4 border-yellow-400 pb-4 mb-6 flex items-center gap-3">
        <i class="fas fa-star text-yellow-500"></i> Dernières Évaluations
    </h2>
    
    @if($recentReviews->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($recentReviews as $review)
                <div class="bg-slate-50 p-6 border border-slate-200 relative">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-slate-900 text-white font-black flex items-center justify-center text-xs shrink-0">
                                {{ substr($review->user->name, 0, 1) }}
                            </div>
                            <p class="font-black text-slate-900 text-sm uppercase">{{ $review->user->name }}</p>
                        </div>
                        <div class="flex text-yellow-400 text-xs">
                            @for($i=1; $i<=5; $i++)
                                <i class="fas fa-star{{ $i <= $review->rating ? '' : ' text-slate-200' }}"></i>
                            @endfor
                        </div>
                    </div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-3 border-b border-slate-200 pb-2 truncate">
                        {{ $review->product->nom_produit }}
                    </p>
                    @if($review->comment)
                        <p class="text-sm font-medium text-slate-700 italic leading-relaxed line-clamp-3">
                            "{{ $review->comment }}"
                        </p>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <p class="text-slate-500 text-sm font-bold uppercase tracking-widest py-8">Aucun avis</p>
    @endif
</div>

<div class="grid grid-cols-2 md:grid-cols-4 gap-4 gsap-fade-up">
    <a href="{{ route('admin.products.index') }}" class="bg-blue-600 text-white p-6 hover:bg-blue-700 transition-colors flex flex-col items-center justify-center gap-3 h-32 group">
        <i class="fas fa-cubes text-3xl opacity-70 group-hover:scale-110 transition-transform"></i>
        <span class="text-xs font-black uppercase tracking-widest">Produits</span>
    </a>
    <a href="{{ route('admin.orders.index') }}" class="bg-purple-600 text-white p-6 hover:bg-purple-700 transition-colors flex flex-col items-center justify-center gap-3 h-32 group">
        <i class="fas fa-truck text-3xl opacity-70 group-hover:scale-110 transition-transform"></i>
        <span class="text-xs font-black uppercase tracking-widest">Commandes</span>
    </a>
    <a href="{{ route('admin.users.index') }}" class="bg-emerald-600 text-white p-6 hover:bg-emerald-700 transition-colors flex flex-col items-center justify-center gap-3 h-32 group">
        <i class="fas fa-users text-3xl opacity-70 group-hover:scale-110 transition-transform"></i>
        <span class="text-xs font-black uppercase tracking-widest">Utilisateurs</span>
    </a>
    <a href="{{ route('admin.activity-logs.export') }}" class="bg-slate-900 text-white p-6 hover:bg-slate-800 transition-colors flex flex-col items-center justify-center gap-3 h-32 group">
        <i class="fas fa-download text-3xl opacity-70 group-hover:scale-110 transition-transform"></i>
        <span class="text-xs font-black uppercase tracking-widest">Export CSV</span>
    </a>
</div>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", () => {
        gsap.fromTo(".gsap-fade-up", 
            { y: 30, opacity: 0 }, 
            { y: 0, opacity: 1, duration: 0.8, stagger: 0.1, ease: "power3.out" }
        );
    });
</script>
@endpush
@endsection