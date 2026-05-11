@extends('layouts.app')

@section('title', 'Mon Profil - The Vault')

@section('content')
<div class="max-w-5xl mx-auto">
    
    <div class="mb-12 gsap-fade-up">
        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 block">Paramètres du compte</span>
        <div class="border-b-4 border-slate-900 pb-6">
            <h1 class="text-5xl md:text-7xl font-black text-slate-900 tracking-tighter uppercase leading-none">
                Identité.
            </h1>
        </div>
    </div>

    <div class="bg-white border-4 border-slate-900 p-8 md:p-12 mb-12 relative gsap-fade-up">
        <div class="absolute top-0 right-0 bg-slate-900 text-white px-4 py-2 text-[10px] font-black uppercase tracking-widest">
            Dossier Public
        </div>
        
        <h2 class="text-2xl font-black text-slate-900 uppercase tracking-tight mb-8">Informations Personnelles</h2>

        <!-- Avatar Upload Section -->
        <div class="mb-8 p-6 bg-slate-50 border-2 border-slate-200">
            <label class="block text-xs font-bold text-slate-900 uppercase tracking-widest mb-4">Photo de profil</label>
            <div class="flex items-center gap-6">
                <div class="w-20 h-20 rounded-full bg-slate-200 flex items-center justify-center overflow-hidden shrink-0 border-2 border-slate-300">
                    @if($user->avatar)
                        <img src="{{ $user->avatar }}" alt="Avatar" class="w-full h-full object-cover">
                    @else
                        <span class="text-2xl font-black text-slate-500">{{ substr($user->name, 0, 1) }}</span>
                    @endif
                </div>
                <div class="flex-1">
                    <input type="file" name="avatar" id="avatar" accept="image/jpeg,image/png,image/webp,image/gif,image/avif"
                        class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:border-0 file:text-xs file:font-black file:uppercase file:tracking-widest file:bg-slate-900 file:text-white hover:file:bg-slate-800 cursor-pointer border border-slate-200 bg-white p-2">
                    <p class="mt-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Formats: JPEG, PNG, WEBP, GIF, AVIF. Max 5MB</p>
                </div>
            </div>
        </div>
        
        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label for="name" class="block text-xs font-bold text-slate-900 uppercase tracking-widest mb-3">Nom complet</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                        class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-300 focus:border-slate-900 focus:bg-white focus:ring-0 text-slate-900 font-bold transition-colors uppercase @error('name') border-red-500 @enderror">
                    @error('name') <p class="text-red-500 text-xs font-bold mt-2">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold text-slate-900 uppercase tracking-widest mb-3">Contact Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                        class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-300 focus:border-slate-900 focus:bg-white focus:ring-0 text-slate-900 font-bold transition-colors @error('email') border-red-500 @enderror">
                    @error('email') <p class="text-red-500 text-xs font-bold mt-2">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="telephone" class="block text-xs font-bold text-slate-900 uppercase tracking-widest mb-3">Ligne téléphonique</label>
                    <input type="text" name="telephone" id="telephone" value="{{ old('telephone', $user->telephone) }}"
                        class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-300 focus:border-slate-900 focus:bg-white focus:ring-0 text-slate-900 font-bold font-mono transition-colors @error('telephone') border-red-500 @enderror">
                    @error('telephone') <p class="text-red-500 text-xs font-bold mt-2">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="adresse" class="block text-xs font-bold text-slate-900 uppercase tracking-widest mb-3">Adresse de livraison (Par défaut)</label>
                    <textarea name="adresse" id="adresse" rows="3"
                        class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-300 focus:border-slate-900 focus:bg-white focus:ring-0 text-slate-900 font-bold transition-colors resize-y uppercase @error('adresse') border-red-500 @enderror">{{ old('adresse', $user->adresse) }}</textarea>
                    @error('adresse') <p class="text-red-500 text-xs font-bold mt-2">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="pt-6 border-t-2 border-slate-100">
                <button type="submit" class="bg-slate-900 text-white px-8 py-4 font-black uppercase tracking-widest hover:bg-slate-800 transition-colors text-sm">
                    Mettre à jour l'identité
                </button>
            </div>
        </form>
    </div>

    <div class="bg-indigo-600 text-white border-4 border-indigo-900 p-8 md:p-12 mb-12 relative gsap-fade-up">
        <div class="absolute top-0 right-0 bg-indigo-900 text-white px-4 py-2 text-[10px] font-black uppercase tracking-widest">
            Protocoles de Sécurité
        </div>
        
        <h2 class="text-2xl font-black text-white uppercase tracking-tight mb-8 border-b-2 border-indigo-400 pb-4">Clé d'accès</h2>
        
        <form method="POST" action="{{ route('profile.password') }}" class="space-y-8">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="md:col-span-2">
                    <label for="current_password" class="block text-xs font-bold text-indigo-200 uppercase tracking-widest mb-3">Mot de passe actuel</label>
                    <input type="password" name="current_password" id="current_password" required
                        class="w-full px-5 py-4 bg-indigo-800 border-2 border-indigo-400 focus:border-white focus:bg-indigo-900 focus:ring-0 text-white font-bold transition-colors font-mono tracking-widest @error('current_password') border-red-400 @enderror">
                    @error('current_password') <p class="text-red-300 text-xs font-bold mt-2">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-indigo-200 uppercase tracking-widest mb-3">Nouvelle clé</label>
                    <input type="password" name="password" id="password" required
                        class="w-full px-5 py-4 bg-indigo-800 border-2 border-indigo-400 focus:border-white focus:bg-indigo-900 focus:ring-0 text-white font-bold transition-colors font-mono tracking-widest @error('password') border-red-400 @enderror">
                    @error('password') <p class="text-red-300 text-xs font-bold mt-2">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-indigo-200 uppercase tracking-widest mb-3">Vérification de la clé</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="w-full px-5 py-4 bg-indigo-800 border-2 border-indigo-400 focus:border-white focus:bg-indigo-900 focus:ring-0 text-white font-bold transition-colors font-mono tracking-widest">
                </div>
            </div>

            <div class="pt-6">
                <button type="submit" class="bg-white text-indigo-900 px-8 py-4 font-black uppercase tracking-widest hover:bg-slate-100 transition-colors text-sm">
                    Renouveler la clé
                </button>
            </div>
        </form>
    </div>

    <div class="bg-red-50 border-4 border-red-600 p-8 md:p-12 relative gsap-fade-up">
        <div class="absolute top-0 right-0 bg-red-600 text-white px-4 py-2 text-[10px] font-black uppercase tracking-widest">
            Zone Critique
        </div>
        
        <h2 class="text-2xl font-black text-red-700 uppercase tracking-tight mb-4">Clôture du dossier</h2>
        <p class="text-red-600 font-bold mb-8 uppercase text-xs tracking-widest border-l-4 border-red-600 pl-4 py-1">
            Attention : L'effacement de ce dossier est permanent et irréversible. L'intégralité de vos archives sera détruite.
        </p>
        
        <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Confirmez-vous la destruction totale de votre identité sur The Vault ?');" class="space-y-6">
            @csrf
            @method('DELETE')

            <div>
                <label for="delete_password" class="block text-xs font-bold text-red-700 uppercase tracking-widest mb-3">Validation par mot de passe</label>
                <input type="password" name="password" id="delete_password" required placeholder="ENTREZ VOTRE CLÉ ACTUELLE"
                    class="w-full max-w-md px-5 py-4 bg-white border-2 border-red-300 focus:border-red-600 focus:ring-0 text-red-900 font-bold transition-colors font-mono tracking-widest @error('password') border-red-600 @enderror">
                @error('password') <p class="text-red-600 text-xs font-bold mt-2">{{ $message }}</p> @enderror
            </div>

            <div class="pt-4">
                <button type="submit" class="bg-red-600 text-white px-8 py-4 font-black uppercase tracking-widest hover:bg-red-700 transition-colors text-sm inline-flex items-center gap-2">
                    <i class="fas fa-skull-crossbones"></i> Détruire le compte
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", () => {
        gsap.fromTo(".gsap-fade-up", 
            { y: 30, opacity: 0 }, 
            { y: 0, opacity: 1, duration: 0.8, stagger: 0.15, ease: "power3.out" }
        );
    });
</script>
@endpush
@endsection