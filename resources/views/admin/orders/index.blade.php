@extends('layouts.app')

@section('title', 'Gestion des Commandes - Admin - The Vault')

@section('content')
<div class="mb-12 gsap-fade-up">
    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 block">Administration</span>
    <div class="flex flex-col lg:flex-row lg:items-end justify-between border-b-4 border-slate-900 pb-6 gap-6">
        <h1 class="text-5xl md:text-7xl font-black text-slate-900 tracking-tighter uppercase leading-none">
            Dossiers.
        </h1>
    </div>
</div>

@if(session('success'))
    <div class="bg-emerald-500 text-white px-6 py-4 mb-8 text-xs font-black uppercase tracking-widest flex items-center gap-3 gsap-fade-up">
        <i class="fas fa-check-square"></i> {{ session('success') }}
    </div>
@endif

<div class="bg-slate-50 p-8 border-2 border-slate-200 mb-12 gsap-fade-up">
    <form action="{{ route('admin.orders.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6">
        
        <div class="md:col-span-2">
            <label class="block text-xs font-bold text-slate-900 uppercase tracking-widest mb-3">Recherche client ou N°</label>
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ex: #1234, Nom, ou Email..."
                       class="w-full px-5 py-4 pl-12 bg-white border-2 border-slate-300 focus:border-purple-600 focus:ring-0 text-slate-900 font-bold transition-colors placeholder:font-medium placeholder:text-slate-400">
                <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-400"></i>
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-900 uppercase tracking-widest mb-3">Statut du dossier</label>
            <div class="relative">
                <select name="status" class="w-full px-5 py-4 bg-white border-2 border-slate-300 focus:border-purple-600 focus:ring-0 text-slate-900 font-bold transition-colors appearance-none cursor-pointer">
                    <option value="">TOUS LES STATUTS</option>
                    <option value="en_attente" {{ request('status') == 'en_attente' ? 'selected' : '' }}>EN ATTENTE</option>
                    <option value="expediee" {{ request('status') == 'expediee' ? 'selected' : '' }}>EXPÉDIÉE</option>
                    <option value="livree" {{ request('status') == 'livree' ? 'selected' : '' }}>LIVRÉE</option>
                    <option value="annulee" {{ request('status') == 'annulee' ? 'selected' : '' }}>ANNULÉE</option>
                </select>
                <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-xs text-slate-500 pointer-events-none"></i>
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-900 uppercase tracking-widest mb-3">Date de création</label>
            <input type="date" name="date" value="{{ request('date') }}"
                   class="w-full px-5 py-4 bg-white border-2 border-slate-300 focus:border-purple-600 focus:ring-0 text-slate-900 font-bold transition-colors font-mono text-sm uppercase">
        </div>

        <div class="md:col-span-4 flex flex-col sm:flex-row justify-end gap-4 pt-6 border-t-2 border-slate-200 mt-2">
            @if(request()->hasAny(['search', 'status', 'date']) && (request('search') || request('status') || request('date')))
                <a href="{{ route('admin.orders.index') }}" class="bg-white text-slate-900 border-2 border-slate-900 font-black uppercase tracking-widest px-8 py-4 hover:bg-slate-100 transition-colors text-xs text-center inline-flex items-center justify-center gap-2">
                    <i class="fas fa-times"></i> Réinitialiser
                </a>
            @endif
            <button type="submit" class="bg-purple-600 text-white font-black uppercase tracking-widest px-8 py-4 hover:bg-purple-700 transition-colors text-xs inline-flex items-center justify-center gap-2">
                <i class="fas fa-filter"></i> Appliquer les filtres
            </button>
        </div>
    </form>
</div>

@if($orders->count() > 0)
    <div class="overflow-x-auto mb-8 gsap-fade-up">
        <table class="w-full text-left min-w-[1000px] border-collapse">
            <thead>
                <tr class="bg-slate-50 border-y-2 border-slate-900">
                    <th class="py-5 px-6 text-xs font-bold text-slate-400 uppercase tracking-widest">Réf.</th>
                    <th class="py-5 px-6 text-xs font-bold text-slate-400 uppercase tracking-widest">Client</th>
                    <th class="py-5 px-6 text-xs font-bold text-slate-400 uppercase tracking-widest">Date</th>
                    <th class="py-5 px-6 text-xs font-bold text-slate-400 uppercase tracking-widest">Volume</th>
                    <th class="py-5 px-6 text-xs font-bold text-slate-400 uppercase tracking-widest">Montant</th>
                    <th class="py-5 px-6 text-xs font-bold text-slate-400 uppercase tracking-widest">Statut</th>
                    <th class="py-5 px-6 text-xs font-bold text-slate-400 uppercase tracking-widest text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 border-b-2 border-slate-900">
                @foreach($orders as $order)
                    <tr class="hover:bg-slate-50 transition-colors gsap-row group">
                        <td class="py-5 px-6">
                            <span class="font-mono text-sm font-black text-slate-900">#{{ $order->id_commande }}</span>
                        </td>
                        <td class="py-5 px-6">
                            <p class="font-black text-slate-900 text-sm uppercase">{{ $order->user->name }}</p>
                            <p class="text-xs font-bold text-slate-400 tracking-widest">{{ $order->user->email }}</p>
                        </td>
                        <td class="py-5 px-6 font-mono text-xs font-bold text-slate-500">
                            {{ $order->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="py-5 px-6 text-sm font-black text-slate-900">
                            {{ $order->items->count() }} <span class="text-[10px] text-slate-400 uppercase tracking-widest">art.</span>
                        </td>
                        <td class="py-5 px-6 font-black text-slate-900 text-base whitespace-nowrap">
                            {{ number_format($order->total, 2) }} DH
                        </td>
                        <td class="py-5 px-6">
                            <span class="px-3 py-1.5 text-[10px] font-black uppercase tracking-widest border-2
                                @if($order->statut == 'en_attente') border-amber-200 bg-amber-50 text-amber-700
                                @elseif($order->statut == 'expediee') border-purple-200 bg-purple-50 text-purple-700
                                @elseif($order->statut == 'livree') border-emerald-200 bg-emerald-50 text-emerald-700
                                @else border-red-200 bg-red-50 text-red-700
                                @endif">
                                {{ str_replace('_', ' ', $order->statut) }}
                            </span>
                        </td>
                        <td class="py-5 px-6 text-right">
                            <a href="{{ route('admin.orders.show', $order) }}" 
                               class="text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-purple-600 transition-colors group-hover:underline underline-offset-4">
                                Inspecter <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="gsap-fade-up">
        {{ $orders->links() }}
    </div>
@else
    <div class="border-2 border-slate-200 border-dashed p-16 md:p-32 text-center gsap-fade-up">
        <i class="fas fa-inbox text-4xl text-slate-300 mb-4"></i>
        <h3 class="text-3xl font-black text-slate-900 uppercase tracking-tight mb-2">Aucun dossier trouvé</h3>
        <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-6">La recherche ou la file d'attente est vide.</p>
        @if(request()->hasAny(['search', 'status', 'date']) && (request('search') || request('status') || request('date')))
            <a href="{{ route('admin.orders.index') }}" class="bg-slate-900 text-white font-black uppercase tracking-widest px-8 py-4 hover:bg-slate-800 transition-colors text-xs inline-flex items-center gap-2">
                <i class="fas fa-undo"></i> Réinitialiser les filtres
            </a>
        @endif
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