@extends('layouts.app')
@section('title', 'Codes Promo - The Vault')
@section('content')

<div class="mb-12 gsap-fade-up">
    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 block">Administration</span>
    <h1 class="text-5xl md:text-7xl font-black text-slate-900 tracking-tighter uppercase leading-none border-b border-slate-900 pb-6">
        Promos.
    </h1>
</div>

@if(session('success'))
    <div class="bg-slate-900 text-white px-6 py-4 mb-8 text-xs font-bold uppercase tracking-widest flex items-center gap-3 gsap-fade-up">
        <i class="fas fa-check text-emerald-400"></i> {{ session('success') }}
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

    {{-- Create form --}}
    <div class="lg:col-span-1 gsap-fade-up">
        <div class="bg-slate-50 border border-slate-200 p-8 sticky top-24">
            <h2 class="text-lg font-black text-slate-900 uppercase tracking-tight mb-6 border-b border-slate-200 pb-4">Créer un code</h2>

            <form method="POST" action="{{ route('admin.promotions.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-widest mb-2">Code (vide = auto-généré)</label>
                    <input type="text" name="code" value="{{ old('code') }}" placeholder="Ex: SUMMER20"
                        class="w-full px-4 py-3 bg-white border border-slate-300 focus:border-slate-900 focus:ring-0 font-bold text-slate-900 uppercase tracking-widest @error('code') border-red-500 @enderror">
                    @error('code') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-widest mb-2">Type de réduction <span class="text-red-500">*</span></label>
                    <select name="discount_type" class="w-full px-4 py-3 bg-white border border-slate-300 focus:border-slate-900 focus:ring-0 font-bold text-slate-900">
                        <option value="percentage" {{ old('discount_type') === 'percentage' ? 'selected' : '' }}>Pourcentage (%)</option>
                        <option value="fixed" {{ old('discount_type') === 'fixed' ? 'selected' : '' }}>Montant fixe (DH)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-widest mb-2">Valeur <span class="text-red-500">*</span></label>
                    <input type="number" name="discount_value" value="{{ old('discount_value') }}" step="0.01" min="0.01" placeholder="20"
                        class="w-full px-4 py-3 bg-white border border-slate-300 focus:border-slate-900 focus:ring-0 font-bold text-slate-900 @error('discount_value') border-red-500 @enderror">
                    @error('discount_value') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-widest mb-2">Montant minimum commande (DH)</label>
                    <input type="number" name="min_order_amount" value="{{ old('min_order_amount', 0) }}" step="0.01" min="0"
                        class="w-full px-4 py-3 bg-white border border-slate-300 focus:border-slate-900 focus:ring-0 font-bold text-slate-900">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-widest mb-2">Utilisations max (0 = illimité)</label>
                    <input type="number" name="max_uses" value="{{ old('max_uses', 0) }}" min="0"
                        class="w-full px-4 py-3 bg-white border border-slate-300 focus:border-slate-900 focus:ring-0 font-bold text-slate-900">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-widest mb-2">Date d'expiration</label>
                    <input type="datetime-local" name="expires_at" value="{{ old('expires_at') }}"
                        class="w-full px-4 py-3 bg-white border border-slate-300 focus:border-slate-900 focus:ring-0 font-bold text-slate-900">
                </div>

                <div class="border-t border-slate-200 pt-5">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-widest mb-2">Quantité à générer</label>
                    <input type="number" name="quantity" value="{{ old('quantity', 1) }}" min="1" max="100"
                        class="w-full px-4 py-3 bg-white border border-slate-300 focus:border-slate-900 focus:ring-0 font-bold text-slate-900">
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Si > 1, les codes sont auto-générés.</p>
                </div>

                <button type="submit" class="w-full bg-slate-900 text-white py-4 font-black uppercase tracking-widest hover:bg-slate-800 transition-colors text-sm">
                    Créer le(s) code(s)
                </button>
            </form>
        </div>
    </div>

    {{-- List --}}
    <div class="lg:col-span-2 gsap-fade-up">
        @if($promotions->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left min-w-[700px]">
                    <thead>
                        <tr class="border-b-2 border-slate-900">
                            <th class="py-4 px-3 text-xs font-bold text-slate-400 uppercase tracking-widest">Code</th>
                            <th class="py-4 px-3 text-xs font-bold text-slate-400 uppercase tracking-widest">Réduction</th>
                            <th class="py-4 px-3 text-xs font-bold text-slate-400 uppercase tracking-widest text-center">Utilisations</th>
                            <th class="py-4 px-3 text-xs font-bold text-slate-400 uppercase tracking-widest">Expire</th>
                            <th class="py-4 px-3 text-xs font-bold text-slate-400 uppercase tracking-widest text-center">Statut</th>
                            <th class="py-4 px-3 text-xs font-bold text-slate-400 uppercase tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($promotions as $promo)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-4 px-3">
                                <span class="font-black text-slate-900 tracking-widest text-sm uppercase bg-slate-100 px-3 py-1 rounded">{{ $promo->code }}</span>
                            </td>
                            <td class="py-4 px-3 font-bold text-slate-700 text-sm">
                                @if($promo->discount_type === 'percentage')
                                    {{ $promo->discount_value }}%
                                @else
                                    {{ number_format($promo->discount_value, 2) }} DH
                                @endif
                                @if($promo->min_order_amount > 0)
                                    <span class="text-[10px] text-slate-400 block">Min. {{ number_format($promo->min_order_amount, 0) }} DH</span>
                                @endif
                            </td>
                            <td class="py-4 px-3 text-center font-bold text-sm">
                                {{ $promo->used_count }}
                                @if($promo->max_uses > 0)
                                    <span class="text-slate-400">/ {{ $promo->max_uses }}</span>
                                @else
                                    <span class="text-slate-400">/ ∞</span>
                                @endif
                            </td>
                            <td class="py-4 px-3 text-xs font-bold text-slate-500">
                                {{ $promo->expires_at ? $promo->expires_at->format('d/m/Y H:i') : '—' }}
                            </td>
                            <td class="py-4 px-3 text-center">
                                @if($promo->isValid())
                                    <span class="bg-emerald-100 text-emerald-800 text-[10px] font-black uppercase tracking-widest px-2 py-1 rounded">Actif</span>
                                @elseif(!$promo->is_active)
                                    <span class="bg-slate-200 text-slate-600 text-[10px] font-black uppercase tracking-widest px-2 py-1 rounded">Inactif</span>
                                @else
                                    <span class="bg-red-100 text-red-700 text-[10px] font-black uppercase tracking-widest px-2 py-1 rounded">Expiré</span>
                                @endif
                            </td>
                            <td class="py-4 px-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <form method="POST" action="{{ route('admin.promotions.toggle', $promo) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="text-xs font-bold text-slate-500 hover:text-slate-900 uppercase tracking-widest transition-colors">
                                            {{ $promo->is_active ? 'Désactiver' : 'Activer' }}
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.promotions.destroy', $promo) }}" onsubmit="return confirm('Supprimer ce code ?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs font-bold text-red-400 hover:text-red-700 uppercase tracking-widest transition-colors">
                                            Supprimer
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-8">{{ $promotions->links() }}</div>
        @else
            <div class="border border-slate-200 border-dashed p-16 text-center">
                <i class="fas fa-tag text-4xl text-slate-200 mb-4 block"></i>
                <p class="text-slate-500 font-bold uppercase tracking-widest text-sm">Aucun code promo créé.</p>
            </div>
        @endif
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
