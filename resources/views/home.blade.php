@extends('layouts.app')

@section('title', 'Accueil - The Vault')

@section('content')

<div class="relative w-full h-[85vh] min-h-[700px] bg-slate-950 flex flex-col justify-between overflow-hidden group border-b-4 border-emerald-400">
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?q=80&w=2564&auto=format&fit=crop" 
             alt="Hero The Vault" 
             class="w-full h-full object-cover opacity-50 gsap-hero-bg scale-110">
    </div>
    
    <div class="absolute inset-0 z-10 opacity-20 pointer-events-none mix-blend-overlay" style="background-image: url('data:image/svg+xml,%3Csvg viewBox=%220 0 200 200%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter id=%22noiseFilter%22%3E%3CfeTurbulence type=%22fractalNoise%22 baseFrequency=%220.65%22 numOctaves=%223%22 stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23noiseFilter)%22/%3E%3C/svg%3E');"></div>

    <div class="relative z-20 w-full px-4 lg:px-8 pt-12 flex justify-between items-start">
        <div class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-400 text-slate-950 text-[10px] font-black uppercase tracking-widest gsap-fade-up shadow-[4px_4px_0px_0px_rgba(255,255,255,1)]">
            <span class="w-2 h-2 bg-slate-950 animate-pulse"></span> Édition Limitée 2026
        </div>
        <div class="text-right text-white/50 text-[10px] font-bold uppercase tracking-widest gsap-fade-up">
            <p>Collection Printemps</p>
            <p>2026.01</p>
        </div>
    </div>

    <div class="relative z-20 w-full px-4 lg:px-8 pb-16 pointer-events-none">
        <h1 class="text-[15vw] leading-[0.8] font-black text-white tracking-tighter uppercase mix-blend-difference overflow-hidden">
            <span class="block gsap-title-reveal">L'ART DE</span>
            <span class="block text-white gsap-title-reveal">L'EXCELLENCE</span>
        </h1>
        
        <div class="mt-12 max-w-xl pointer-events-auto gsap-fade-up">
            <form action="{{ route('products.index') }}" method="GET" class="relative group/form flex">
                <input type="text" name="search" placeholder="RECHERCHER DANS LE COFFRE..." value="{{ request('search') }}"
                    class="w-full py-5 px-6 bg-white text-slate-950 placeholder-slate-400 font-black uppercase tracking-widest text-sm focus:outline-none focus:ring-4 focus:ring-emerald-400/50 transition-all border-none shadow-[8px_8px_0px_0px_rgba(52,211,153,1)]">
                <button type="submit" class="px-8 bg-emerald-400 text-slate-950 font-black uppercase tracking-widest hover:bg-white transition-colors flex items-center justify-center">
                    <i class="fas fa-arrow-right"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<div class="w-full bg-emerald-400 border-b-4 border-slate-900 py-4 overflow-hidden flex items-center">
    <div class="gsap-marquee whitespace-nowrap flex items-center gap-8 text-slate-950 font-black uppercase tracking-widest text-xl md:text-3xl">
        <span>THE VAULT</span> <i class="fas fa-vault text-sm"></i>
        <span>SÉCURITÉ PHYSIQUE</span> <i class="fas fa-shield-alt text-sm"></i>
        <span>HORLOGERIE EXCLUSIVE</span> <i class="fas fa-clock text-sm"></i>
    </div>
</div>

<div class="py-24 border-b-4 border-slate-900 bg-slate-50">
    <div class="px-4 lg:px-8 mb-12 flex items-end justify-between gsap-fade-up">
        <h2 class="text-4xl md:text-6xl font-black text-slate-900 tracking-tighter uppercase leading-none">
            Dernières<br>Acquisitions.
        </h2>
        <div class="flex gap-4">
            <button class="w-12 h-12 border-2 border-slate-900 flex items-center justify-center hover:bg-slate-900 hover:text-white transition-colors" id="scroll-left">
                <i class="fas fa-arrow-left"></i>
            </button>
            <button class="w-12 h-12 bg-slate-900 text-white flex items-center justify-center hover:bg-emerald-400 hover:text-slate-950 transition-colors shadow-[4px_4px_0px_0px_rgba(52,211,153,1)]" id="scroll-right">
                <i class="fas fa-arrow-right"></i>
            </button>
        </div>
    </div>

    <div class="flex gap-6 overflow-x-auto snap-x snap-mandatory hide-scrollbar px-4 lg:px-8 pb-8" id="product-carousel">
        @forelse($recentProducts as $product)
            <div class="snap-start shrink-0 w-[85vw] sm:w-[400px] group cursor-pointer gsap-carousel-item relative">
                <div class="absolute top-4 right-4 z-20 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col gap-2">
                    @auth
                    <form action="{{ route('client.wishlist.add', $product) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-10 h-10 bg-white text-slate-900 flex items-center justify-center hover:text-rose-500 transition-colors shadow-[4px_4px_0px_0px_rgba(239,68,68,1)]">
                            <i class="fas fa-heart"></i>
                        </button>
                    </form>
                    @endauth
                </div>

                <a href="{{ route('products.show', $product->slug) }}" class="block relative overflow-hidden aspect-[4/5] bg-slate-200 border-2 border-slate-900 hover:border-emerald-400 transition-colors">
                    <img src="{{ $product->firstImage ?? 'https://via.placeholder.com/600x800?text=' . urlencode($product->nom_produit) }}"
                         alt="{{ $product->nom_produit }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-1000 ease-out">
                    
                    @if(!$product->isAvailable())
                        <div class="absolute top-4 left-4 bg-rose-500 text-white px-4 py-2 text-[10px] font-black uppercase tracking-widest shadow-[4px_4px_0px_0px_rgba(255,255,255,1)]">
                            Rupture
                        </div>
                    @endif
                </a>
                
                <div class="pt-6 border-b-2 border-transparent group-hover:border-slate-900 transition-colors pb-4 flex justify-between items-start gap-4">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">{{ $product->categorie->nom_categorie ?? 'Dossier' }}</p>
                        <h3 class="font-black text-slate-900 text-xl uppercase tracking-tight leading-tight line-clamp-1 group-hover:text-emerald-600 transition-colors">
                            {{ $product->nom_produit }}
                        </h3>
                    </div>
                    <span class="font-black text-slate-900 text-xl whitespace-nowrap">{{ number_format($product->prix, 0) }}<span class="text-sm ml-1">DH</span></span>
                </div>
            </div>
        @empty
            <div class="w-full py-20 text-center border-4 border-slate-900 border-dashed">
                <p class="text-xl font-black uppercase tracking-widest">Le coffre est actuellement vide.</p>
            </div>
        @endforelse
    </div>
</div>

<div class="relative w-full h-screen min-h-[800px] bg-slate-900 border-b-4 border-slate-900 overflow-hidden" id="interactive-lookbook">
    <div class="absolute inset-0 transition-opacity duration-700 ease-in-out opacity-40 scale-105" id="lookbook-bg">
        <img src="https://images.unsplash.com/photo-1550684848-fac1c5b4e853?q=80&w=2564&auto=format&fit=crop" class="w-full h-full object-cover">
    </div>

    <div class="relative z-10 w-full h-full flex flex-col justify-center px-4 lg:px-16">
        <p class="text-emerald-400 font-black uppercase tracking-widest text-sm mb-12 gsap-fade-up">Parcourir les catégories</p>
        
        <ul class="flex flex-col gap-4 md:gap-8">
            <li class="lookbook-item group" data-bg="https://images.unsplash.com/photo-1550684848-fac1c5b4e853?q=80&w=2564&auto=format&fit=crop">
                <a href="{{ route('products.index') }}?category=equipement-tactique" class="text-5xl md:text-8xl font-black text-white/30 uppercase tracking-tighter hover:text-white transition-colors duration-300 flex items-center gap-6 group">
                    <span class="text-xl md:text-3xl opacity-0 group-hover:opacity-100 text-emerald-400 transition-opacity -translate-x-10 group-hover:translate-x-0 duration-300 font-mono">01</span>
                    ÉQUIPEMENT TACTIQUE
                    <i class="fas fa-arrow-right text-3xl md:text-6xl text-emerald-400 opacity-0 group-hover:opacity-100 transition-all group-hover:translate-x-3"></i>
                </a>
            </li>
            
            <li class="lookbook-item group" data-bg="https://images.unsplash.com/photo-1595341888016-a392ef81b7de?q=80&w=2564&auto=format&fit=crop">
                <a href="{{ route('products.index') }}?category=securite-physique" class="text-5xl md:text-8xl font-black text-white/30 uppercase tracking-tighter hover:text-white transition-colors duration-300 flex items-center gap-6 group">
                    <span class="text-xl md:text-3xl opacity-0 group-hover:opacity-100 text-emerald-400 transition-opacity -translate-x-10 group-hover:translate-x-0 duration-300 font-mono">02</span>
                    SÉCURITÉ PHYSIQUE
                    <i class="fas fa-arrow-right text-3xl md:text-6xl text-emerald-400 opacity-0 group-hover:opacity-100 transition-all group-hover:translate-x-3"></i>
                </a>
            </li>
            
            <li class="lookbook-item group" data-bg="https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?q=80&w=2564&auto=format&fit=crop">
                <a href="{{ route('products.index') }}?category=horlogerie-dexception" class="text-5xl md:text-8xl font-black text-white/30 uppercase tracking-tighter hover:text-white transition-colors duration-300 flex items-center gap-6 group">
                    <span class="text-xl md:text-3xl opacity-0 group-hover:opacity-100 text-emerald-400 transition-opacity -translate-x-10 group-hover:translate-x-0 duration-300 font-mono">03</span>
                    HORLOGERIE D'EXCEPTION
                    <i class="fas fa-arrow-right text-3xl md:text-6xl text-emerald-400 opacity-0 group-hover:opacity-100 transition-all group-hover:translate-x-3"></i>
                </a>
            </li>
        </ul>
    </div>
</div>

<div class="py-24 bg-white px-4 lg:px-8 border-b-4 border-slate-900">
    <div class="mb-16 text-center gsap-fade-up">
        <h2 class="text-4xl md:text-6xl font-black text-slate-900 tracking-tighter uppercase leading-none mb-4">
            Le Choix de<br>L'Élite.
        </h2>
        <p class="text-slate-500 font-bold uppercase tracking-widest text-xs border-t border-slate-200 pt-4 inline-block">Ce que nos membres disent de The Vault</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-7xl mx-auto">
        <div class="border-4 border-slate-900 p-8 flex flex-col justify-between gsap-review hover:-translate-y-2 hover:shadow-[12px_12px_0px_0px_rgba(52,211,153,1)] transition-all duration-300 bg-white">
            <div>
                <i class="fas fa-quote-left text-4xl text-emerald-400 mb-6"></i>
                <p class="text-xl font-bold text-slate-900 leading-tight uppercase mb-12">
                    "Une sélection pointue, introuvable ailleurs au Maroc. Le service de conciergerie tactique est irréprochable."
                </p>
            </div>
            <div class="flex items-center gap-4 border-t-4 border-slate-900 pt-6 mt-auto">
                <div class="w-14 h-14 bg-slate-900 text-white flex items-center justify-center font-black text-2xl">Y.</div>
                <div>
                    <p class="font-black text-slate-900 uppercase text-lg">YOUSSEF E.</p>
                    <p class="text-[10px] text-slate-400 font-bold tracking-widest uppercase">Membre vérifié #A891</p>
                </div>
            </div>
        </div>

        <div class="border-4 border-slate-900 p-8 flex flex-col justify-between gsap-review hover:-translate-y-2 hover:shadow-[12px_12px_0px_0px_rgba(52,211,153,1)] transition-all duration-300 bg-slate-950 text-white shadow-[8px_8px_0px_0px_rgba(255,255,255,1)]">
            <div>
                <i class="fas fa-quote-left text-4xl text-emerald-400 mb-6"></i>
                <p class="text-xl font-bold leading-tight uppercase mb-12">
                    "L'authentification de ma montre a été gérée de manière experte. The Vault porte bien son nom, mon investissement est en sécurité."
                </p>
            </div>
            <div class="flex items-center gap-4 border-t-4 border-white pt-6 mt-auto">
                <div class="w-14 h-14 bg-emerald-400 text-slate-950 flex items-center justify-center font-black text-2xl">A.</div>
                <div>
                    <p class="font-black uppercase text-lg">AMINE B.</p>
                    <p class="text-[10px] text-slate-400 font-bold tracking-widest uppercase">Membre Premium #C112</p>
                </div>
            </div>
        </div>

        <div class="border-4 border-slate-900 p-8 flex flex-col justify-between gsap-review hover:-translate-y-2 hover:shadow-[12px_12px_0px_0px_rgba(52,211,153,1)] transition-all duration-300 bg-white">
            <div>
                <i class="fas fa-quote-left text-4xl text-emerald-400 mb-6"></i>
                <p class="text-xl font-bold text-slate-900 leading-tight uppercase mb-12">
                    "Le packaging pour l'équipement sécurisé est discret et haut de gamme. Une expérience d'achat unique au Maroc."
                </p>
            </div>
            <div class="flex items-center gap-4 border-t-4 border-slate-900 pt-6 mt-auto">
                <div class="w-14 h-14 bg-slate-900 text-white flex items-center justify-center font-black text-2xl">S.</div>
                <div>
                    <p class="font-black text-slate-900 uppercase text-lg">SARAH K.</p>
                    <p class="text-[10px] text-slate-400 font-bold tracking-widest uppercase">Acheteuse vérifiée #B733</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bg-emerald-400 p-10 md:p-24 text-center relative overflow-hidden group">
    <div class="absolute inset-0 flex items-center justify-center opacity-10 pointer-events-none overflow-hidden">
        <span class="text-[30vw] font-black text-slate-900 leading-none whitespace-nowrap gsap-marquee-cta">THE VAULT THE VAULT THE VAULT</span>
    </div>
    
    <div class="relative z-10 gsap-fade-up">
        <h2 class="text-5xl md:text-8xl font-black text-slate-950 mb-8 tracking-tighter uppercase leading-none">
            REJOIGNEZ<br>LE CERCLE.
        </h2>
        
        @auth
            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-4 bg-slate-950 text-emerald-400 font-black uppercase tracking-widest px-12 py-6 hover:bg-white hover:text-slate-950 transition-colors duration-300 text-sm md:text-base shadow-[8px_8px_0px_0px_rgba(255,255,255,1)]">
                Ouvrir le coffre <i class="fas fa-arrow-right"></i>
            </a>
        @else
            <a href="{{ route('register') }}" class="inline-flex items-center gap-4 bg-slate-950 text-emerald-400 font-black uppercase tracking-widest px-12 py-6 hover:bg-white hover:text-slate-950 transition-colors duration-300 text-sm md:text-base shadow-[8px_8px_0px_0px_rgba(255,255,255,1)]">
                Devenir Membre <i class="fas fa-arrow-right"></i>
            </a>
        @endauth
    </div>
</div>

<style>
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", () => {
        // Enregistrement du plugin ScrollTrigger
        gsap.registerPlugin(ScrollTrigger);

        // 1. Hero Animations
        // Révélation du titre lettre par lettre
        gsap.fromTo(".gsap-title-reveal", 
            { y: "100%", opacity: 0 }, 
            { y: "0%", opacity: 1, duration: 1.2, stagger: 0.2, ease: "power4.out", delay: 0.2 }
        );

        // Parallax sur l'image de fond du hero
        gsap.to(".gsap-hero-bg", {
            yPercent: 30,
            ease: "none",
            scrollTrigger: {
                trigger: "#global-nav", // Se déclenche quand on commence à scroller
                start: "top top",
                end: "bottom top",
                scrub: true
            }
        });

        // 2. Marquee Infini (GSAP pour une fluidité parfaite)
        // Dupliquer le contenu pour un scroll sans fin
        const marqueeContent = document.querySelector('.gsap-marquee').innerHTML;
        document.querySelector('.gsap-marquee').innerHTML = marqueeContent + marqueeContent;
        
        gsap.to(".gsap-marquee", {
            xPercent: -50, // Décale de la moitié (puisqu'on l'a dupliqué)
            ease: "none",
            duration: 25, // Vitesse
            repeat: -1
        });

        // 3. Carousel Scroll Logic (Boutons Gauche/Droite)
        const carousel = document.getElementById('product-carousel');
        const btnLeft = document.getElementById('scroll-left');
        const btnRight = document.getElementById('scroll-right');

        if (carousel && btnLeft && btnRight) {
            const scrollAmount = 400; // Largeur d'une carte environ
            btnLeft.addEventListener('click', () => {
                carousel.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
            });
            btnRight.addEventListener('click', () => {
                carousel.scrollBy({ left: scrollAmount, behavior: 'smooth' });
            });
        }

        // Animation d'entrée des cartes du carrousel au scroll
        gsap.fromTo(".gsap-carousel-item",
            { x: 100, opacity: 0 },
            {
                scrollTrigger: {
                    trigger: "#product-carousel",
                    start: "top 80%",
                },
                x: 0,
                opacity: 1,
                duration: 0.8,
                stagger: 0.1,
                ease: "power3.out"
            }
        );

        // 4. Lookbook Interactif (Hover Reveal)
        const lookbookItems = document.querySelectorAll('.lookbook-item');
        const lookbookBg = document.querySelector('#lookbook-bg img');

        lookbookItems.forEach(item => {
            item.addEventListener('mouseenter', function() {
                const newBg = this.getAttribute('data-bg');
                
                // GSAP pour un fondu enchaîné élégant
                gsap.to(lookbookBg, { 
                    opacity: 0, 
                    duration: 0.3, 
                    onComplete: () => {
                        lookbookBg.src = newBg;
                        gsap.to(lookbookBg, { opacity: 0.4, duration: 0.5 }); // Revenir à l'opacité par défaut
                    }
                });
            });
        });

        // 5. Apparition Brutaliste des Avis Clients
        gsap.fromTo(".gsap-review",
            { y: 50, opacity: 0, rotation: -2 },
            {
                scrollTrigger: {
                    trigger: ".gsap-review",
                    start: "top 85%",
                },
                y: 0,
                opacity: 1,
                rotation: 0,
                duration: 0.8,
                stagger: 0.2,
                ease: "back.out(1.5)"
            }
        );

        // 6. Marquee discret dans le CTA final
        gsap.to(".gsap-marquee-cta", {
            xPercent: -50,
            ease: "none",
            duration: 15,
            repeat: -1
        });

        // Utils générique pour les éléments qui montent au scroll
        gsap.utils.toArray('.gsap-fade-up').forEach(el => {
            gsap.fromTo(el,
                { y: 40, opacity: 0 },
                {
                    scrollTrigger: {
                        trigger: el,
                        start: "top 90%"
                    },
                    y: 0,
                    opacity: 1,
                    duration: 0.8,
                    ease: "power3.out"
                }
            );
        });
    });
</script>
@endpush
@endsection