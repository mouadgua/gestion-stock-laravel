@extends('layouts.app')
@section('title', 'Mes Avis - The Vault')

@section('content')
<div class="mb-16 gsap-fade-up">
    <a href="{{ route('client.profile') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 uppercase tracking-widest hover:text-slate-900 transition-colors mb-6">
        <i class="fas fa-arrow-left"></i> Mon Espace
    </a>
    <div class="flex items-end justify-between border-b border-slate-900 pb-6">
        <h1 class="text-5xl md:text-7xl font-black text-slate-900 tracking-tighter uppercase leading-none">
            Vos Avis.
        </h1>
        <span class="hidden md:block text-sm font-bold text-slate-400 uppercase tracking-widest">{{ $reviews->count() }} Évaluations</span>
    </div>
</div>

@if($reviews->count() > 0)
    <div class="flex flex-col border-b border-slate-200 mb-12">
        @foreach($reviews as $review)
            <div class="py-8 flex flex-col sm:flex-row gap-6 sm:gap-10 border-t border-slate-200 gsap-row hover:bg-slate-50/50 transition-colors px-4 -mx-4">
                <a href="{{ route('products.show', $review->product->slug ?? '') }}" class="w-24 sm:w-32 aspect-[4/5] bg-slate-100 shrink-0 block overflow-hidden">
                    <img src="{{ $review->product->image ?? 'https://via.placeholder.com/150' }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-700">
                </a>
                
                <div class="flex-1 flex flex-col justify-center">
                    <div class="flex flex-col sm:flex-row sm:items-start justify-between mb-4 gap-2">
                        <div>
                            <a href="{{ route('products.show', $review->product->slug ?? '') }}" class="font-black text-slate-900 text-xl md:text-2xl uppercase leading-tight hover:text-slate-600 transition-colors">
                                {{ $review->product->nom_produit }}
                            </a>
                            <p class="text-xs font-bold text-slate-400 tracking-widest uppercase mt-1">
                                Publié le {{ $review->created_at->format('d/m/Y') }}
                            </p>
                        </div>
                        
                        <div class="flex text-amber-400 text-sm shrink-0">
                            @for($i=1; $i<=5; $i++)
                                <i class="fas fa-star{{ $i <= $review->rating ? '' : ' text-slate-200' }}"></i>
                            @endfor
                        </div>
                    </div>

                    @if($review->comment)
                        <div class="relative pl-4 border-l-2 border-slate-900">
                            <p class="text-slate-700 font-medium leading-relaxed text-lg italic">
                                "{{ $review->comment }}"
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-8 gsap-fade-up">
        {{ $reviews->links() }}
    </div>

@else
    <div class="border border-slate-200 border-dashed p-16 md:p-32 text-center gsap-fade-up">
        <h3 class="text-3xl font-black text-slate-900 uppercase tracking-tight mb-4">Aucun avis</h3>
        <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-10">Vous n'avez pas encore partagé votre expérience.</p>
        <a href="{{ route('products.index') }}" class="inline-block bg-slate-900 text-white font-black uppercase tracking-widest px-8 py-4 hover:bg-slate-800 transition-colors">
            Explorer le catalogue
        </a>
    </div>
@endif

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", () => {
        gsap.fromTo(".gsap-fade-up", 
            { y: 30, opacity: 0 }, 
            { y: 0, opacity: 1, duration: 0.8, stagger: 0.1, ease: "power3.out" }
        );

        gsap.fromTo(".gsap-row", 
            { x: -20, opacity: 0 }, 
            { x: 0, opacity: 1, duration: 0.6, stagger: 0.1, ease: "power2.out", delay: 0.2 }
        );
    });
</script>
@endpush
@endsection