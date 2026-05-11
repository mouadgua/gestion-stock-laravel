@extends('layouts.app')

@section('title', 'Accueil - The Vault')

@section('content')

<div class="relative w-full h-[80vh] min-h-[600px] bg-slate-900 rounded-[2.5rem] overflow-hidden flex flex-col justify-end p-8 md:p-16 mb-20 group">
    <img src="https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?q=80&w=2564&auto=format&fit=crop" 
         alt="The Vault Hero" 
         class="absolute inset-0 w-full h-full object-cover opacity-40 mix-blend-overlay gsap-parallax scale-105">
    
    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40 to-transparent"></div>

    <div class="relative z-10 w-full max-w-5xl mx-auto flex flex-col items-center text-center">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-white/20 bg-white/5 backdrop-blur-md text-white/80 text-xs font-bold uppercase tracking-widest mb-8 gsap-hero-el">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
            Nouveautés exclusives
        </div>
        
        <h1 class="text-6xl md:text-8xl lg:text-9xl font-black text-white mb-8 tracking-tighter leading-none gsap-hero-el">
            THE VAULT.
        </h1>
        
        <p class="text-lg md:text-2xl text-white/70 font-medium mb-12 max-w-2xl mx-auto gsap-hero-el">
            L'excellence à l'état pur. Découvrez notre sélection d'équipements premium.
        </p>

        <div class="w-full max-w-3xl mx-auto gsap-hero-el">
            <form action="{{ route('products.index') }}" method="GET" class="relative group/form">
                <div class="flex items-center bg-white/10 backdrop-blur-xl rounded-2xl overflow-hidden border border-white/20 focus-within:border-white transition-all duration-500 hover:bg-white/20">
                    <div class="pl-6 pr-4">
                        <i class="fas fa-search text-white/50 text-xl group-focus-within/form:text-white transition-colors"></i>
                    </div>
                    <input type="text" name="search" placeholder="Rechercher un objet..." value="{{ request('search') }}"
                        class="w-full py-5 md:py-6 text-white placeholder-white/50 bg-transparent focus:outline-none text-lg md:text-xl font-bold">
                    <button type="submit" class="px-8 py-5 md:py-6 bg-white text-slate-900 font-black uppercase tracking-widest hover:bg-slate-200 transition-colors hidden sm:block">
                        Explorer
                    </button>
                </div>
            </form>
            <div class="flex flex-wrap justify-center gap-4 mt-6 text-xs font-bold tracking-widest uppercase">
                <span class="text-white/40">Tendances :</span>
                <a href="{{ route('products.index') }}?sort=bestsellers" class="text-white hover:text-slate-300 transition-colors">Meilleures ventes</a>
                <span class="text-white/20">/</span>
                <a href="{{ route('products.index') }}?sort=newest" class="text-white hover:text-slate-300 transition-colors">Derniers ajouts</a>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-2 md:grid-cols-4 border-y border-slate-200 py-10 mb-24 gsap-fade-up">
    <div class="text-center px-4 border-r border-slate-100 last:border-0 md:last:border-r-0">
        <p class="text-4xl md:text-5xl font-black text-slate-900 tracking-tighter mb-1">{{ $recentProducts->count() }}+</p>
        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Objets en stock</p>
    </div>
    <div class="text-center px-4 border-r-0 md:border-r border-slate-100">
        <p class="text-4xl md:text-5xl font-black text-slate-900 tracking-tighter mb-1">24h</p>
        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Expédition</p>
    </div>
    <div class="text-center px-4 border-r border-slate-100 mt-8 md:mt-0 pt-8 md:pt-0 border-t md:border-t-0">
        <p class="text-4xl md:text-5xl font-black text-slate-900 tracking-tighter mb-1">100%</p>
        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Sécurisé</p>
    </div>
    <div class="text-center px-4 mt-8 md:mt-0 pt-8 md:pt-0 border-t md:border-t-0 border-slate-100">
        <p class="text-4xl md:text-5xl font-black text-slate-900 tracking-tighter mb-1">7/7</p>
        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Conciergerie</p>
    </div>
</div>

<div class="mb-24">
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6 gsap-fade-up">
        <div>
            <h2 class="text-5xl md:text-7xl font-black text-slate-900 tracking-tighter leading-none">NOUVELLES<br>ARRIVÉES.</h2>
        </div>
        <a href="{{ route('products.index') }}" class="inline-flex items-center gap-3 text-sm font-bold text-slate-900 uppercase tracking-widest hover:text-slate-500 transition-colors group">
            Voir la collection complète 
            <span class="w-8 h-8 rounded-full bg-slate-900 text-white flex items-center justify-center group-hover:translate-x-2 transition-transform">
                <i class="fas fa-arrow-right text-xs"></i>
            </span>
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-12">
        @forelse($recentProducts as $product)
            <div class="group cursor-pointer gsap-product">
                <a href="{{ route('products.show', $product->slug) }}" class="block relative overflow-hidden aspect-[4/5] bg-slate-100 mb-5">
                    <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/600x800?text=' . urlencode($product->nom_produit) }}"
                         alt="{{ $product->nom_produit }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-1000 ease-[cubic-bezier(0.25,1,0.5,1)]">
                    
                    @if(!$product->isAvailable())
                        <div class="absolute inset-0 bg-slate-900/20 backdrop-blur-[2px] flex items-center justify-center">
                            <span class="bg-slate-900 text-white px-6 py-3 text-xs font-bold uppercase tracking-widest">Épuisé</span>
                        </div>
                    @endif
                    
                    <div class="absolute inset-x-0 bottom-0 p-4 opacity-0 translate-y-4 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-500 bg-gradient-to-t from-black/50 to-transparent flex justify-center">
                        <span class="bg-white text-slate-900 px-6 py-3 font-bold text-xs uppercase tracking-widest hover:bg-slate-900 hover:text-white transition-colors">
                            Voir les détails
                        </span>
                    </div>
                </a>
                
                <div class="flex justify-between items-start gap-4">
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-lg leading-tight mb-1 group-hover:text-slate-600 transition-colors line-clamp-1">
                            {{ $product->nom_produit }}
                        </h3>
                        <p class="text-sm font-medium text-slate-500 line-clamp-1">{{ Str::limit($product->description, 40) }}</p>
                    </div>
                    <span class="font-black text-slate-900 text-lg whitespace-nowrap">{{ number_format($product->prix, 2) }} DH</span>
                </div>
            </div>
        @empty
            <div class="col-span-full py-32 text-center border border-slate-200 border-dashed rounded-[2rem]">
                <h3 class="text-3xl font-black text-slate-900 mb-2">Le coffre est vide.</h3>
                <p class="text-slate-500 font-medium">Revenez bientôt pour découvrir nos nouveautés.</p>
            </div>
        @endforelse
    </div>
</div>

<div class="bg-slate-900 rounded-[2.5rem] p-10 md:p-24 text-center relative overflow-hidden gsap-fade-up">
    <div class="absolute top-0 right-0 w-[800px] h-[800px] bg-white opacity-5 rounded-full blur-3xl transform translate-x-1/2 -translate-y-1/2 pointer-events-none"></div>
    
    <div class="relative z-10">
        <h2 class="text-4xl md:text-7xl font-black text-white mb-6 tracking-tighter leading-tight">
            ACCÉDEZ À<br>L'EXCELLENCE.
        </h2>
        <p class="text-lg text-white/60 font-medium mb-10 max-w-xl mx-auto">
            Rejoignez The Vault. Créez votre compte pour accéder à notre conciergerie et à nos offres privées.
        </p>
        
        @auth
            <a href="{{ route('products.index') }}" class="inline-block bg-white text-slate-900 font-black uppercase tracking-widest px-10 py-5 hover:bg-slate-200 transition-all hover:scale-105 duration-300">
                Ouvrir le coffre
            </a>
        @else
            <a href="{{ route('register') }}" class="inline-block bg-white text-slate-900 font-black uppercase tracking-widest px-10 py-5 hover:bg-slate-200 transition-all hover:scale-105 duration-300">
                Devenir Membre
            </a>
        @endauth
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", () => {
        // Animation du texte dans le Hero (Stagger)
        gsap.fromTo(".gsap-hero-el", 
            { y: 50, opacity: 0 }, 
            { y: 0, opacity: 1, duration: 1, stagger: 0.15, ease: "power4.out", delay: 0.2 }
        );

        // Effet Parallax doux sur l'image du Hero au scroll
        gsap.to(".gsap-parallax", {
            yPercent: 20,
            ease: "none",
            scrollTrigger: {
                trigger: ".gsap-parallax",
                start: "top top",
                end: "bottom top",
                scrub: true
            }
        });

        // Apparition générique au scroll pour les sections
        gsap.utils.toArray('.gsap-fade-up').forEach(section => {
            gsap.fromTo(section,
                { y: 60, opacity: 0 },
                {
                    scrollTrigger: {
                        trigger: section,
                        start: "top 85%",
                    },
                    y: 0,
                    opacity: 1,
                    duration: 1,
                    ease: "power3.out"
                }
            );
        });

        // Stagger sur la grille de produits (Lookbook)
        gsap.fromTo(".gsap-product",
            { y: 50, opacity: 0 },
            {
                scrollTrigger: {
                    trigger: ".gsap-product",
                    start: "top 80%",
                },
                y: 0,
                opacity: 1,
                duration: 0.8,
                stagger: 0.1,
                ease: "power3.out"
            }
        );
    });
</script>
@endpush
@endsection