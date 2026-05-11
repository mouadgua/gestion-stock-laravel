@extends('layouts.app')

@section('title', 'Dossier #' . $order->id_commande . ' - Admin - The Vault')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-12 gsap-fade-up">
        <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 uppercase tracking-widest hover:text-purple-600 transition-colors mb-6">
            <i class="fas fa-arrow-left"></i> Retour aux dossiers
        </a>
        <div class="flex flex-col md:flex-row md:items-end justify-between border-b-4 border-slate-900 pb-6 gap-6">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Dossier d'acquisition</p>
                <h1 class="text-5xl md:text-7xl font-mono font-black text-slate-900 tracking-tighter uppercase leading-none">
                    #{{ $order->id_commande }}
                </h1>
                <p class="font-mono text-sm font-bold text-slate-500 mt-4"><i class="far fa-clock mr-1"></i> {{ \Carbon\Carbon::parse($order->date_commande)->format('d/m/Y - H:i') }}</p>
            </div>
            
            <div class="px-6 py-3 border-2 text-xs font-black uppercase tracking-widest text-center
                @if($order->statut == 'en_attente') border-amber-500 text-amber-600
                @elseif($order->statut == 'expediee') border-purple-600 text-purple-600
                @elseif($order->statut == 'livree') border-emerald-500 text-emerald-600
                @else border-red-500 text-red-600
                @endif">
                Statut actuel : {{ str_replace('_', ' ', $order->statut) }}
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        
        <div class="lg:col-span-2 bg-slate-50 p-8 border-2 border-slate-200 relative gsap-fade-up">
            <div class="absolute top-0 right-0 bg-slate-900 text-white px-4 py-1 text-[10px] font-black uppercase tracking-widest">
                Profil Client
            </div>
            <div class="grid grid-cols-2 gap-8 mt-4">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Identité</p>
                    <p class="font-black text-slate-900 uppercase text-lg">{{ $order->user->name }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Contact</p>
                    <p class="font-bold text-slate-700">{{ $order->user->email }}</p>
                    <p class="font-bold text-slate-700 mt-1">{{ $order->user->telephone ?? 'N/A' }}</p>
                </div>
                <div class="col-span-2">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Destination</p>
                    <p class="font-bold text-slate-700">{{ $order->adresse_livraison ?? $order->user->adresse ?? 'Non spécifiée' }}</p>
                    <p class="font-bold text-slate-700 mt-1">📞 {{ $order->telephone_livraison ?? $order->user->telephone ?? 'Non renseigné' }}</p>
                </div>
            </div>
        </div>

        <div class="bg-purple-600 text-white p-8 border-2 border-purple-900 relative gsap-fade-up">
            <h2 class="text-sm font-black uppercase tracking-widest mb-6 border-b border-purple-400 pb-2">Mise à jour requise</h2>
            
            <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="flex flex-col h-full">
                @csrf
                @method('PATCH')
                <div class="relative flex-1">
                    <select name="statut" class="w-full px-4 py-4 bg-purple-800 border-none text-white font-black uppercase tracking-widest focus:ring-4 focus:ring-purple-400 appearance-none cursor-pointer">
                        <option value="en_attente" {{ $order->statut == 'en_attente' ? 'selected' : '' }}>EN ATTENTE</option>
                        <option value="expediee" {{ $order->statut == 'expediee' ? 'selected' : '' }}>EXPÉDIÉE</option>
                        <option value="livree" {{ $order->statut == 'livree' ? 'selected' : '' }}>LIVRÉE</option>
                        <option value="annulee" {{ $order->statut == 'annulee' ? 'selected' : '' }}>ANNULÉE</option>
                    </select>
                    <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-purple-400 pointer-events-none"></i>
                </div>
                <button type="submit" class="w-full bg-white text-purple-900 py-4 font-black uppercase tracking-widest mt-4 hover:bg-slate-100 transition-colors">
                    Appliquer le statut
                </button>
            </form>
        </div>
    </div>

    <div class="border-2 border-slate-900 bg-white gsap-fade-up">
        <div class="p-6 border-b-2 border-slate-900 bg-slate-900 text-white">
            <h2 class="text-sm font-black uppercase tracking-widest">Inventaire du dossier</h2>
        </div>
        
        <div class="divide-y-2 divide-slate-100">
            @foreach($order->items as $item)
                <div class="p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-6 hover:bg-slate-50 transition-colors">
                    <div class="flex items-center gap-6">
                        <div class="w-24 aspect-[4/5] bg-slate-100 overflow-hidden shrink-0 border border-slate-200">
                            <img src="{{ $item->product->image ?? 'https://via.placeholder.com/150' }}" 
                                 alt="{{ $item->product->nom_produit }}" 
                                 class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h3 class="font-black text-slate-900 uppercase text-lg">{{ $item->product->nom_produit }}</h3>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">{{ $item->product->categorie->nom_categorie ?? 'Standard' }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-12 sm:text-right">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Qté</p>
                            <p class="font-mono font-black text-slate-900 text-xl">{{ $item->quantite }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Sous-total</p>
                            <p class="font-black text-slate-900 text-xl whitespace-nowrap">{{ number_format($item->sous_total, 2) }} DH</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="p-8 bg-slate-50 border-t-4 border-slate-900 flex justify-between items-end">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Mode de règlement</p>
                <p class="font-black text-slate-900 uppercase">
                    @if($order->mode_paiement === 'paypal')
                        <span class="inline-flex items-center gap-1"><i class="fab fa-paypal text-blue-600"></i> PayPal</span>
                    @else
                        <span class="inline-flex items-center gap-1"><i class="fas fa-money-bill-wave text-emerald-600"></i> Paiement à la livraison</span>
                    @endif
                </p>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-2">
                    Statut paiement: 
                    <span class="px-1 py-0.5
                        @if($order->statut_paiement === 'paye') text-emerald-600
                        @elseif($order->statut_paiement === 'echoue') text-red-600
                        @else text-amber-600 @endif">
                        {{ str_replace('_', ' ', $order->statut_paiement ?? 'en_attente') }}
                    </span>
                </p>
                @if($order->paypal_paiement_id)
                    <p class="text-[10px] font-mono text-slate-500 mt-1">ID: {{ $order->paypal_paiement_id }}</p>
                @endif
            </div>
            <div class="text-right">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-widest block mb-1">Total Net</span>
                <span class="text-4xl md:text-5xl font-black text-slate-900 tracking-tighter leading-none">{{ number_format($order->total, 2) }} <span class="text-2xl">DH</span></span>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", () => {
        gsap.fromTo(".gsap-fade-up", { y: 30, opacity: 0 }, { y: 0, opacity: 1, duration: 0.8, stagger: 0.1, ease: "power3.out" });
    });
</script>
@endpush
@endsection