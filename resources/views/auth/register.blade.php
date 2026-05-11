@extends('layouts.app')

@section('title', 'Inscription - The Vault')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12 bg-slate-50">
    <div class="w-full max-w-2xl relative z-10">
        <div class="absolute inset-0 -z-10 bg-gradient-to-r from-slate-300 to-slate-200 blur-3xl opacity-50 rounded-full transform translate-y-10 scale-105"></div>

        <div class="bg-white rounded-[2rem] p-8 md:p-12 shadow-2xl shadow-slate-200/50 border border-slate-100">
            <div class="text-center mb-10 gsap-stagger">
                <div class="w-16 h-16 mx-auto mb-5 rounded-2xl bg-gradient-to-tr from-slate-900 to-slate-700 flex items-center justify-center shadow-lg shadow-slate-900/20 transform transition-transform duration-300 hover:scale-105">
                    <i class="fas fa-user-shield text-white text-2xl"></i>
                </div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Ouvrir un compte</h1>
                <p class="text-slate-500 mt-2 font-medium">Rejoignez The Vault dès aujourd'hui</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="gsap-stagger">
                        <label for="name" class="block text-sm font-bold text-slate-700 mb-2">Nom complet</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-user text-slate-400 group-focus-within:text-slate-900 transition-colors"></i>
                            </div>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required
                                   class="w-full pl-11 pr-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-900/10 transition-all duration-300 text-slate-900 font-medium placeholder-slate-400"
                                   placeholder="John Doe">
                        </div>
                        @error('name') <p class="text-red-500 text-sm mt-2 font-medium flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p> @enderror
                    </div>

                    <div class="gsap-stagger">
                        <label for="telephone" class="block text-sm font-bold text-slate-700 mb-2">Téléphone</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-phone text-slate-400 group-focus-within:text-slate-900 transition-colors"></i>
                            </div>
                            <input id="telephone" type="text" name="telephone" value="{{ old('telephone') }}"
                                   class="w-full pl-11 pr-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-900/10 transition-all duration-300 text-slate-900 font-medium placeholder-slate-400"
                                   placeholder="06 XX XX XX XX">
                        </div>
                        @error('telephone') <p class="text-red-500 text-sm mt-2 font-medium flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="gsap-stagger">
                    <label for="email" class="block text-sm font-bold text-slate-700 mb-2">Email</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-slate-400 group-focus-within:text-slate-900 transition-colors"></i>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required
                               class="w-full pl-11 pr-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-900/10 transition-all duration-300 text-slate-900 font-medium placeholder-slate-400"
                               placeholder="votre@email.com">
                    </div>
                    @error('email') <p class="text-red-500 text-sm mt-2 font-medium flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p> @enderror
                </div>

                <div class="gsap-stagger">
                    <label for="adresse" class="block text-sm font-bold text-slate-700 mb-2">Adresse</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-map-marker-alt text-slate-400 group-focus-within:text-slate-900 transition-colors"></i>
                        </div>
                        <input id="adresse" type="text" name="adresse" value="{{ old('adresse') }}"
                               class="w-full pl-11 pr-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-900/10 transition-all duration-300 text-slate-900 font-medium placeholder-slate-400"
                               placeholder="Votre adresse complète">
                    </div>
                    @error('adresse') <p class="text-red-500 text-sm mt-2 font-medium flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 border-t border-slate-100 pt-5 mt-2">
                    <div class="gsap-stagger">
                        <label for="password" class="block text-sm font-bold text-slate-700 mb-2">Mot de passe</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-slate-400 group-focus-within:text-slate-900 transition-colors"></i>
                            </div>
                            <input id="password" type="password" name="password" required
                                   class="w-full pl-11 pr-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-900/10 transition-all duration-300 text-slate-900 font-medium placeholder-slate-400"
                                   placeholder="Minimum 8 caractères">
                        </div>
                        @error('password') <p class="text-red-500 text-sm mt-2 font-medium flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p> @enderror
                    </div>

                    <div class="gsap-stagger">
                        <label for="password_confirmation" class="block text-sm font-bold text-slate-700 mb-2">Confirmation</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-check-circle text-slate-400 group-focus-within:text-slate-900 transition-colors"></i>
                            </div>
                            <input id="password_confirmation" type="password" name="password_confirmation" required
                                   class="w-full pl-11 pr-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-900/10 transition-all duration-300 text-slate-900 font-medium placeholder-slate-400"
                                   placeholder="Confirmez le mot de passe">
                        </div>
                    </div>
                </div>

                <div class="pt-4 gsap-stagger">
                    <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white py-4 rounded-xl font-bold flex items-center justify-center gap-3 text-lg transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl hover:shadow-slate-900/20 active:translate-y-0">
                        Créer mon compte <i class="fas fa-user-plus text-sm"></i>
                    </button>
                </div>

                <div class="relative my-8 gsap-stagger">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-slate-200"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-white text-slate-400 font-bold tracking-widest uppercase">Ou</span>
                    </div>
                </div>

                <div class="gsap-stagger">
                    <a href="{{ route('google.redirect') }}" class="w-full flex items-center justify-center gap-4 bg-white border-2 border-slate-200 hover:border-slate-900 hover:bg-slate-50 text-slate-900 py-4 rounded-xl font-bold transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl active:translate-y-0">
                        <svg class="w-5 h-5" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        S'inscrire avec Google
                    </a>
                </div>

                <p class="text-center text-sm font-medium text-slate-500 mt-6 gsap-stagger">
                    Déjà membre de The Vault ?
                    <a href="{{ route('login') }}" class="text-slate-900 hover:text-slate-700 font-bold underline decoration-2 decoration-slate-300 hover:decoration-slate-900 transition-all underline-offset-4">Se connecter</a>
                </p>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        gsap.fromTo(".gsap-stagger", 
            { y: 30, opacity: 0 }, 
            { y: 0, opacity: 1, duration: 0.8, stagger: 0.08, ease: "power3.out", delay: 0.1 }
        );
    });
</script>
@endpush
@endsection