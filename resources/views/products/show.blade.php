@extends('layouts.app')

@section('title', $product->nom_produit . ' - The Vault')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-20">
    <div class="gsap-hero">
        <div class="bg-white border border-slate-200 rounded-[2.5rem] p-3 shadow-2xl shadow-slate-200/60 sticky top-24">
            <div class="rounded-[2rem] overflow-hidden bg-slate-50 aspect-square md:aspect-auto md:h-[600px] relative">
                <img src="{{ $product->image ?? 'https://via.placeholder.com/800x800?text=' . urlencode($product->nom_produit) }}"
                     alt="{{ $product->nom_produit }}"
                     class="w-full h-full object-cover hover:scale-105 transition duration-700 ease-in-out">
            </div>
        </div>
    </div>

    <div class="flex flex-col justify-center">
        <div class="gsap-info">
            <span class="text-xs font-black text-slate-400 tracking-widest uppercase inline-flex items-center gap-2 mb-4">
                <i class="fas fa-folder-open text-slate-300"></i> {{ $product->categorie->nom_categorie ?? 'Non catégorisé' }}
            </span>
            <h1 class="text-4xl lg:text-5xl font-extrabold text-slate-900 mb-6 tracking-tight leading-tight">{{ $product->nom_produit }}</h1>

            <div class="flex flex-wrap items-center gap-4 mb-8">
                <div class="flex items-center gap-1 text-amber-400 text-lg">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star{{ $i <= round($averageRating) ? '' : '-half-alt' }} {{ $i <= round($averageRating) ? '' : 'text-slate-200' }}"></i>
                    @endfor
                </div>
                <div class="flex items-center gap-3 text-sm font-medium text-slate-500">
                    <span class="font-extrabold text-slate-900 text-lg">{{ number_format($averageRating, 1) }}</span>
                    <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                    <a href="#reviews" class="hover:text-slate-900 underline decoration-slate-300 underline-offset-4">{{ $reviews->count() }} avis</a>
                    <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                    <span><i class="fas fa-shopping-bag mr-1.5 text-slate-400"></i>{{ $product->orders_count ?? 0 }} acquis</span>
                </div>
            </div>

            <div class="flex items-end gap-5 mb-10 pb-10 border-b border-slate-100">
                <span class="text-5xl font-black text-slate-900 tracking-tight">{{ number_format($product->prix, 2) }} <span class="text-2xl text-slate-400 font-bold">DH</span></span>
                @if($product->stock > 0)
                    <span class="text-sm font-bold text-emerald-700 bg-emerald-50 border border-emerald-100 px-4 py-2 rounded-xl inline-flex items-center gap-2 mb-1">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> En stock ({{ $product->stock }})
                    </span>
                @else
                    <span class="text-sm font-bold text-red-700 bg-red-50 border border-red-100 px-4 py-2 rounded-xl inline-flex items-center gap-2 mb-1">
                        <i class="fas fa-times-circle"></i> Épuisé
                    </span>
                @endif
            </div>
        </div>

        <div class="gsap-info">
            <h3 class="font-extrabold text-slate-900 mb-4 text-lg">À propos de cet objet</h3>
            <p class="text-slate-600 font-medium leading-relaxed mb-10 text-lg">
                {{ $product->description ?? 'Aucune description disponible pour le moment.' }}
            </p>

            <div class="space-y-4">
                @if($product->stock > 0 && auth()->check())
                    <form action="{{ route('client.cart.add', $product) }}" method="POST" class="flex flex-col sm:flex-row gap-4">
                        @csrf
                        <div class="flex items-center bg-slate-50 border border-slate-200 rounded-2xl p-1 shrink-0 w-full sm:w-auto">
                            <button type="button" onclick="decrementQty()" class="w-12 h-12 flex items-center justify-center text-slate-500 hover:text-slate-900 hover:bg-white rounded-xl transition-colors">
                                <i class="fas fa-minus text-sm"></i>
                            </button>
                            <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $product->stock }}"
                                   class="w-12 text-center bg-transparent border-0 focus:ring-0 font-extrabold text-slate-900 p-0 appearance-none">
                            <button type="button" onclick="incrementQty()" class="w-12 h-12 flex items-center justify-center text-slate-500 hover:text-slate-900 hover:bg-white rounded-xl transition-colors">
                                <i class="fas fa-plus text-sm"></i>
                            </button>
                        </div>
                        <button type="submit" class="flex-1 bg-slate-900 text-white py-4 px-8 rounded-2xl font-bold flex items-center justify-center gap-3 hover:bg-slate-800 transition-all hover:shadow-xl hover:shadow-slate-900/20 transform hover:-translate-y-1">
                            <i class="fas fa-shopping-cart"></i> Ajouter au coffre
                        </button>
                    </form>
                @endif

                @auth
                    <form action="{{ route('client.wishlist.add', $product) }}" method="POST" class="text-center sm:text-left mt-4">
                        @csrf
                        <button type="submit" class="text-sm font-bold text-slate-500 hover:text-slate-900 transition-colors inline-flex items-center gap-2 py-2 group">
                            <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center group-hover:bg-red-50 group-hover:text-red-500 transition-colors">
                                <i class="fas fa-heart text-xs"></i>
                            </div>
                            Ajouter aux favoris
                        </button>
                    </form>
                @endauth
            </div>
        </div>
    </div>
</div>

<div id="reviews" class="mb-20 pt-10 border-t border-slate-200/60 gsap-section">
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-10 gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Avis des membres</h2>
            <p class="text-slate-500 font-medium mt-2">Découvrez ce que les autres pensent de cet équipement.</p>
        </div>
        <div class="flex items-center gap-3 bg-white border border-slate-200 py-3 px-6 rounded-2xl shadow-sm">
            <span class="text-3xl font-black text-slate-900">{{ number_format($averageRating, 1) }}</span>
            <div class="flex flex-col">
                <div class="flex text-amber-400 text-sm">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star{{ $i <= round($averageRating) ? '' : ($i - 0.5 <= round($averageRating) ? '-half-alt' : '') }} {{ $i <= round($averageRating) ? '' : 'text-slate-200' }}"></i>
                    @endfor
                </div>
                <span class="text-xs font-bold text-slate-400 mt-1 uppercase tracking-wider">{{ $reviews->count() }} Évaluations</span>
            </div>
        </div>
    </div>

    @if($reviews->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($reviews as $review)
                <div class="bg-white border border-slate-100 shadow-lg shadow-slate-200/30 rounded-3xl p-8 gsap-review hover:-translate-y-1 transition-transform duration-300">
                    <div class="flex items-start justify-between mb-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-slate-900 flex items-center justify-center text-white font-black shadow-md">
                                {{ substr($review->user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-extrabold text-slate-900">{{ $review->user->name }}</p>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-0.5">
                                    {{ $review->created_at->format('d/m/Y') }}
                                </p>
                            </div>
                        </div>
                        <div class="flex text-amber-400 text-sm">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star{{ $i <= $review->rating ? '' : ' text-slate-200' }}"></i>
                            @endfor
                        </div>
                    </div>
                    @if($review->comment)
                        <p class="text-slate-600 font-medium leading-relaxed">{{ $review->comment }}</p>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-slate-50 border border-slate-100 rounded-[2rem] p-12 text-center">
            <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-white shadow-sm flex items-center justify-center">
                <i class="fas fa-comment-slash text-slate-300 text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-900 mb-2">Aucun avis</h3>
            <p class="text-slate-500 font-medium">Soyez le premier à donner votre avis sur cet objet.</p>
        </div>
    @endif

    @auth
        @if(auth()->user()->isClient() && !$hasReviewed)
            <div class="bg-white border border-slate-200 shadow-xl shadow-slate-200/40 rounded-[2rem] p-8 md:p-10 mt-12 gsap-section">
                <h3 class="text-2xl font-extrabold text-slate-900 mb-8">Laisser une évaluation</h3>
                <form action="{{ route('client.reviews.store', $product) }}" method="POST">
                    @csrf
                    <div class="mb-8">
                        <label class="block text-sm font-bold text-slate-700 mb-4">Note globale</label>
                        <div class="flex gap-2" id="ratingStars">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" onclick="setRating({{ $i }})" class="focus:outline-none transition-transform hover:scale-110">
                                    <i class="fas fa-star text-4xl text-slate-200 hover:text-amber-400 transition-colors cursor-pointer" id="star-{{ $i }}"></i>
                                </button>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="ratingInput" value="0"> </div>
                    <div class="mb-8">
                        <label for="comment" class="block text-sm font-bold text-slate-700 mb-3">Votre expérience (Optionnel)</label>
                        <textarea name="comment" id="comment" rows="4"
                                  class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-900/10 transition-all font-medium text-slate-900 placeholder-slate-400 resize-none"
                                  placeholder="Qu'avez-vous pensé de cet équipement ?"></textarea>
                    </div>
                    <button type="submit" class="bg-slate-900 text-white px-8 py-4 rounded-xl font-bold flex items-center justify-center gap-2 hover:bg-slate-800 transition-all shadow-lg shadow-slate-900/20 hover:-translate-y-1">
                        Publier mon avis <i class="fas fa-arrow-right text-sm ml-1"></i>
                    </button>
                </form>
            </div>
        @elseif($hasReviewed)
            <div class="bg-emerald-50 border border-emerald-100 rounded-[2rem] p-8 mt-12 text-center flex flex-col items-center justify-center gsap-section">
                <div class="w-14 h-14 mb-4 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 text-xl">
                    <i class="fas fa-check"></i>
                </div>
                <h3 class="font-bold text-emerald-900 text-lg mb-1">Évaluation enregistrée</h3>
                <p class="text-emerald-700 font-medium">Merci d'avoir partagé votre avis avec The Vault.</p>
            </div>
        @endif
    @else
        <div class="bg-slate-50 border border-slate-100 rounded-[2rem] p-10 mt-12 text-center flex flex-col items-center gsap-section">
            <p class="text-slate-600 font-bold mb-6 text-lg">Connectez-vous pour rejoindre la discussion</p>
            <a href="{{ route('login') }}" class="bg-white border border-slate-200 text-slate-900 px-8 py-3.5 rounded-xl font-bold hover:bg-slate-50 transition-colors shadow-sm flex items-center gap-2">
                <i class="fas fa-user-circle"></i> S'identifier
            </a>
        </div>
    @endauth
</div>

@if($relatedProducts->count() > 0)
    <div class="gsap-section">
        <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight mb-8">Vous pourriez aussi aimer</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($relatedProducts as $related)
                <div class="bg-white border border-slate-200 rounded-[1.5rem] p-2 hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300 group flex flex-col h-full">
                    <a href="{{ route('products.show', $related->slug) }}" class="block relative overflow-hidden aspect-[4/3] rounded-[1rem] bg-slate-50 mb-4">
                        <img src="{{ $related->image ?? 'https://via.placeholder.com/300x200?text=' . urlencode($related->nom_produit) }}"
                             alt="{{ $related->nom_produit }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-700">
                    </a>
                    <div class="px-2 pb-2 flex flex-col flex-1">
                        <h3 class="font-extrabold text-slate-900 mb-1 text-base leading-tight line-clamp-2 hover:text-slate-600 transition-colors">
                            <a href="{{ route('products.show', $related->slug) }}">{{ $related->nom_produit }}</a>
                        </h3>
                        <span class="mt-auto text-lg font-black text-slate-900">{{ number_format($related->prix, 2) }} DH</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

@push('scripts')
<script>
    // Logique Laravel conservée
    function decrementQty() {
        const input = document.getElementById('quantity');
        if (input.value > 1) input.value = parseInt(input.value) - 1;
    }
    function incrementQty() {
        const input = document.getElementById('quantity');
        if (input.value < {{ $product->stock ?? 1 }}) input.value = parseInt(input.value) + 1;
    }
    function setRating(rating) {
        document.getElementById('ratingInput').value = rating;
        for (let i = 1; i <= 5; i++) {
            const star = document.getElementById('star-' + i);
            if (i <= rating) {
                star.className = 'fas fa-star text-4xl text-amber-400 cursor-pointer transition-colors';
            } else {
                star.className = 'fas fa-star text-4xl text-slate-200 hover:text-amber-400 cursor-pointer transition-colors';
            }
        }
    }

    // Animations GSAP
    document.addEventListener("DOMContentLoaded", () => {
        // Apparition de l'image (Slide from left)
        gsap.fromTo(".gsap-hero", 
            { x: -50, opacity: 0 }, 
            { x: 0, opacity: 1, duration: 0.8, ease: "power3.out" }
        );
        
        // Apparition des infos (Slide from bottom stagger)
        gsap.fromTo(".gsap-info", 
            { y: 30, opacity: 0 }, 
            { y: 0, opacity: 1, duration: 0.8, stagger: 0.15, ease: "power3.out", delay: 0.2 }
        );

        // Apparition des sections au scroll (si on scroll bas)
        gsap.utils.toArray('.gsap-section').forEach(section => {
            gsap.fromTo(section,
                { y: 50, opacity: 0 },
                {
                    scrollTrigger: {
                        trigger: section,
                        start: "top 85%",
                    },
                    y: 0,
                    opacity: 1,
                    duration: 0.8,
                    ease: "power2.out"
                }
            );
        });

        // Stagger des commentaires
        gsap.fromTo(".gsap-review",
            { y: 30, opacity: 0 },
            {
                scrollTrigger: {
                    trigger: "#reviews",
                    start: "top 75%",
                },
                y: 0,
                opacity: 1,
                duration: 0.6,
                stagger: 0.1,
                ease: "back.out(1.2)"
            }
        );
    });
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
<script>gsap.registerPlugin(ScrollTrigger);</script>
@endpush
@endsection