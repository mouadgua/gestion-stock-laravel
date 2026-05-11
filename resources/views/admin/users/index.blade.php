@extends('layouts.app')

@section('title', 'Membres - Admin - The Vault')

@section('content')
<div class="mb-12 gsap-fade-up">
    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 block">Administration</span>
    <div class="flex flex-col lg:flex-row lg:items-end justify-between border-b-4 border-slate-900 pb-6 gap-6">
        <h1 class="text-5xl md:text-7xl font-black text-slate-900 tracking-tighter uppercase leading-none">
            Membres.
        </h1>
        
        <div class="flex flex-wrap gap-3">
            <button onclick="filterUsers('all')" id="tab-all" 
               class="border-2 border-slate-900 bg-slate-900 text-white font-black uppercase tracking-widest text-xs px-6 py-3 transition-colors">
                Tous ({{ $users->total() }})
            </button>
            <button onclick="filterUsers('acheteur')" id="tab-acheteur" 
               class="border-2 border-slate-200 bg-white text-slate-500 font-bold uppercase tracking-widest text-xs px-6 py-3 hover:border-slate-900 hover:text-slate-900 transition-colors">
                Clients Uniquement
            </button>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="bg-emerald-500 text-white px-6 py-4 mb-8 text-xs font-black uppercase tracking-widest flex items-center gap-3 gsap-fade-up">
        <i class="fas fa-check-square"></i> {{ session('success') }}
    </div>
@endif

<div class="overflow-x-auto mb-8 gsap-fade-up border-2 border-slate-200 bg-white">
    <table class="w-full text-left min-w-[900px] border-collapse">
        <thead>
            <tr class="bg-slate-50 border-b-2 border-slate-900">
                <th class="py-5 px-6 text-xs font-bold text-slate-400 uppercase tracking-widest">Identité</th>
                <th class="py-5 px-6 text-xs font-bold text-slate-400 uppercase tracking-widest">Contact</th>
                <th class="py-5 px-6 text-xs font-bold text-slate-400 uppercase tracking-widest">Rôle</th>
                <th class="py-5 px-6 text-xs font-bold text-slate-400 uppercase tracking-widest">Inscription</th>
                <th class="py-5 px-6 text-xs font-bold text-slate-400 uppercase tracking-widest text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-200" id="users-table">
            @foreach($users as $user)
                <tr class="hover:bg-slate-50 transition-colors user-row group" data-role="{{ $user->role }}">
                    <td class="py-5 px-6">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-slate-900 text-white font-black flex items-center justify-center text-xs shrink-0 group-hover:scale-110 transition-transform">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <span class="font-black text-slate-900 text-sm uppercase">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td class="py-5 px-6 text-sm font-bold text-slate-500">
                        {{ $user->email }}
                    </td>
                    <td class="py-5 px-6">
                        @if($user->role === 'admin')
                            <span class="px-3 py-1.5 text-[10px] font-black uppercase tracking-widest border-2 border-purple-200 bg-purple-50 text-purple-700">
                                Administration
                            </span>
                        @else
                            <span class="px-3 py-1.5 text-[10px] font-black uppercase tracking-widest border-2 border-slate-200 bg-slate-50 text-slate-700">
                                Client
                            </span>
                        @endif
                    </td>
                    <td class="py-5 px-6 font-mono text-xs font-bold text-slate-500">
                        {{ $user->created_at->format('d/m/Y') }}
                    </td>
                    <td class="py-5 px-6 text-right">
                        @if($user->role !== 'admin')
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Exclure ce membre de The Vault de manière permanente ?');">
                                @csrf 
                                @method('DELETE')
                                <button class="text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-red-600 transition-colors group-hover:underline underline-offset-4">
                                    <i class="fas fa-times mr-1"></i> Exclure
                                </button>
                            </form>
                        @else
                            <span class="text-[10px] font-bold text-slate-300 uppercase tracking-widest">Protégé</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="gsap-fade-up">
    {{ $users->links() }}
</div>

@push('scripts')
<script>
    // Logique Laravel conservée, adaptée pour Tailwind Brutaliste
    function filterUsers(role) {
        // Classes pour l'onglet actif (Noir, texte blanc)
        const activeClasses = ['border-slate-900', 'bg-slate-900', 'text-white', 'font-black'];
        // Classes pour l'onglet inactif (Blanc, bordure grise, texte gris)
        const inactiveClasses = ['border-slate-200', 'bg-white', 'text-slate-500', 'font-bold', 'hover:border-slate-900', 'hover:text-slate-900'];

        // On reset tous les boutons
        document.querySelectorAll('[id^=tab-]').forEach(btn => {
            btn.classList.remove(...activeClasses);
            btn.classList.add(...inactiveClasses);
        });

        // On applique le style actif au bouton cliqué
        const activeTab = document.getElementById('tab-' + role);
        activeTab.classList.remove(...inactiveClasses);
        activeTab.classList.add(...activeClasses);

        // On filtre les lignes du tableau
        document.querySelectorAll('.user-row').forEach(row => {
            row.style.display = (role === 'all' || row.dataset.role === role) ? '' : 'none';
        });
    }

    // Animations GSAP
    document.addEventListener("DOMContentLoaded", () => {
        gsap.fromTo(".gsap-fade-up", { y: 30, opacity: 0 }, { y: 0, opacity: 1, duration: 0.8, stagger: 0.1, ease: "power3.out" });
        gsap.fromTo(".user-row", { x: -10, opacity: 0 }, { x: 0, opacity: 1, duration: 0.4, stagger: 0.05, ease: "power2.out", delay: 0.2 });
    });
</script>
@endpush
@endsection