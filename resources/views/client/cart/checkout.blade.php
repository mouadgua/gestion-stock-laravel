@extends('layouts.app')

@section('title', 'Finaliser la commande - The Vault')

@section('content')
<div class="mb-16 gsap-fade-up">
    <a href="{{ route('client.cart.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 uppercase tracking-widest hover:text-slate-900 transition-colors mb-6">
        <i class="fas fa-arrow-left"></i> Retour au panier
    </a>
    <div class="flex items-end justify-between border-b border-slate-900 pb-6">
        <h1 class="text-5xl md:text-7xl font-black text-slate-900 tracking-tighter uppercase leading-none">
            Finaliser.
        </h1>
        <span class="hidden md:block text-sm font-bold text-slate-400 uppercase tracking-widest">{{ count($items) }} Objet(s)</span>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
    <!-- Order Summary -->
    <div class="lg:col-span-7 gsap-fade-up">
        <h2 class="text-xl font-black text-slate-900 uppercase tracking-tight border-b border-slate-200 pb-4 mb-8">Récapitulatif de la commande</h2>
        
        @foreach($items as $item)
            <div class="flex gap-4 py-6 border-b border-slate-100 last:border-b-0">
                <div class="w-20 h-24 bg-slate-100 shrink-0 overflow-hidden">
                    <img src="{{ $item['product']->image ?? 'https://via.placeholder.com/200x250' }}"
                         alt="{{ $item['product']->nom_produit }}"
                         class="w-full h-full object-cover">
                </div>
                <div class="flex-1 flex justify-between">
                    <div>
                        <h3 class="font-black text-slate-900 uppercase text-sm">{{ $item['product']->nom_produit }}</h3>
                        <p class="text-xs font-bold text-slate-400 tracking-widest uppercase mt-1">
                            {{ $item['product']->categorie->nom_categorie ?? 'Standard' }}
                        </p>
                        <p class="text-xs font-bold text-slate-500 mt-2">x{{ $item['quantity'] }}</p>
                    </div>
                    <p class="font-black text-slate-900 whitespace-nowrap">{{ number_format($item['subtotal'], 2) }} DH</p>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Payment Selection -->
    <div class="lg:col-span-5 gsap-fade-up">
        <div class="bg-slate-50 p-8 border border-slate-200 sticky top-28">
            <h2 class="text-xl font-black text-slate-900 uppercase tracking-tight border-b border-slate-200 pb-4 mb-6">Mode de paiement</h2>

            <div class="space-y-4 mb-8">
                <div class="flex justify-between items-center text-sm font-bold text-slate-500 uppercase tracking-widest">
                    <span>Sous-total</span>
                    <span class="text-slate-900">{{ number_format($total, 2) }} DH</span>
                </div>
                <div class="flex justify-between items-center text-sm font-bold text-slate-500 uppercase tracking-widest">
                    <span>Expédition</span>
                    <span class="text-slate-900">Offerte</span>
                </div>
            </div>

            <div class="border-t border-slate-900 pt-6 mb-8">
                <div class="flex justify-between items-end">
                    <span class="text-sm font-bold text-slate-900 uppercase tracking-widest">Total</span>
                    <span class="font-black text-4xl text-slate-900 tracking-tighter">{{ number_format($total, 2) }} <span class="text-xl">DH</span></span>
                </div>
            </div>

            <form action="{{ route('client.cart.checkout.process') }}" method="POST" id="checkout-form">
                @csrf
                
                <!-- Payment Method Selection -->
                <div class="space-y-4 mb-8">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-4">Choisissez votre mode de paiement</p>

                    <!-- PayPal Option -->
                    <label class="block">
                        <input type="radio" name="mode_paiement" value="paypal" class="peer hidden" checked>
                        <div class="flex items-center gap-4 p-4 border-2 border-slate-200 bg-white cursor-pointer transition-all duration-200 peer-checked:border-slate-900 peer-checked:bg-slate-900/5 hover:border-slate-400">
                            <div class="w-12 h-8 bg-blue-500 rounded flex items-center justify-center shrink-0">
                                <i class="fab fa-paypal text-white text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-black text-slate-900 uppercase text-sm">PayPal</p>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Paiement en ligne sécurisé</p>
                            </div>
                            <div class="w-6 h-6 rounded-full border-2 border-slate-300 flex items-center justify-center peer-checked:border-slate-900 transition-all">
                                <div class="w-3 h-3 rounded-full bg-slate-900 opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                            </div>
                        </div>
                    </label>

                    <!-- Cash on Delivery Option -->
                    <label class="block">
                        <input type="radio" name="mode_paiement" value="cod" class="peer hidden">
                        <div class="flex items-center gap-4 p-4 border-2 border-slate-200 bg-white cursor-pointer transition-all duration-200 peer-checked:border-slate-900 peer-checked:bg-slate-900/5 hover:border-slate-400">
                            <div class="w-12 h-8 bg-emerald-500 rounded flex items-center justify-center shrink-0">
                                <i class="fas fa-money-bill-wave text-white text-lg"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-black text-slate-900 uppercase text-sm">Paiement à la livraison</p>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Payez en espèces à la réception</p>
                            </div>
                            <div class="w-6 h-6 rounded-full border-2 border-slate-300 flex items-center justify-center transition-all">
                                <div class="w-3 h-3 rounded-full bg-slate-900 opacity-0 transition-opacity"></div>
                            </div>
                        </div>
                    </label>
                </div>

                <!-- PayPal Info Box (shown when PayPal is selected) -->
                <div id="paypal-info" class="bg-blue-50 border border-blue-200 p-4 mb-6 text-xs font-bold text-blue-800 tracking-wider flex items-start gap-3">
                    <i class="fab fa-paypal text-lg mt-0.5"></i>
                    <div>
                        <p class="uppercase">Paiement sécurisé via PayPal</p>
                        <p class="font-medium text-blue-600 normal-case mt-1">Vous serez redirigé vers PayPal pour finaliser votre paiement après avoir confirmé la commande.</p>
                    </div>
                </div>

                <!-- COD Info Box (shown when COD is selected) -->
                <div id="cod-info" class="bg-emerald-50 border border-emerald-200 p-4 mb-6 text-xs font-bold text-emerald-800 tracking-wider flex items-start gap-3 hidden">
                    <i class="fas fa-truck text-lg mt-0.5"></i>
                    <div>
                        <p class="uppercase">Paiement à la livraison</p>
                        <p class="font-medium text-emerald-600 normal-case mt-1">Vous paierez en espèces directement lors de la réception de votre commande.</p>
                    </div>
                </div>

                <button type="submit" class="w-full bg-slate-900 text-white font-black uppercase tracking-widest py-5 hover:bg-slate-800 transition-colors flex items-center justify-center gap-2">
                    Confirmer la commande <i class="fas fa-arrow-right text-xs"></i>
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest flex items-center justify-center gap-2">
                    <i class="fas fa-shield-alt"></i> Transaction 100% chiffrée
                </p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", () => {
        // GSAP animations
        gsap.fromTo(".gsap-fade-up", 
            { y: 30, opacity: 0 }, 
            { y: 0, opacity: 1, duration: 0.8, stagger: 0.1, ease: "power3.out" }
        );

        // Toggle payment method info
        const paypalRadio = document.querySelector('input[value="paypal"]');
        const codRadio = document.querySelector('input[value="cod"]');
        const paypalInfo = document.getElementById('paypal-info');
        const codInfo = document.getElementById('cod-info');

        function updatePaymentInfo() {
            if (paypalRadio.checked) {
                paypalInfo.classList.remove('hidden');
                codInfo.classList.add('hidden');
            } else {
                paypalInfo.classList.add('hidden');
                codInfo.classList.remove('hidden');
            }
        }

        paypalRadio.addEventListener('change', updatePaymentInfo);
        codRadio.addEventListener('change', updatePaymentInfo);
        
        // Initialize
        updatePaymentInfo();
    });
</script>
@endpush
@endsection