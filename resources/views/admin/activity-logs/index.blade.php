@extends('layouts.app')

@section('title', 'Activités - Admin - The Vault')

@section('content')
<div class="mb-12 gsap-fade-up">
    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 block">Administration</span>
    <div class="flex flex-col md:flex-row md:items-end justify-between border-b border-slate-900 pb-6 gap-6">
        <h1 class="text-5xl md:text-7xl font-black text-slate-900 tracking-tighter uppercase leading-none">
            Registre.
        </h1>
        <a href="{{ route('admin.activity-logs.export', request()->query()) }}" class="bg-slate-900 text-white font-black uppercase tracking-widest px-8 py-4 hover:bg-slate-800 transition-colors inline-flex items-center justify-center gap-2 text-xs shrink-0">
            <i class="fas fa-download"></i> Exporter CSV
        </a>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12 gsap-fade-up">
    <div class="bg-indigo-600 text-white p-8 relative overflow-hidden group">
        <div class="relative z-10">
            <p class="text-xs font-bold uppercase tracking-widest mb-2 opacity-80">Total des événements</p>
            <p class="text-5xl font-black tracking-tighter mb-2">{{ $totalLogs }}</p>
        </div>
        <i class="fas fa-database absolute -bottom-6 -right-4 text-8xl opacity-20 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500"></i>
    </div>

    <div class="bg-teal-600 text-white p-8 relative overflow-hidden group">
        <div class="relative z-10">
            <p class="text-xs font-bold uppercase tracking-widest mb-2 opacity-80">Aujourd'hui</p>
            <p class="text-5xl font-black tracking-tighter mb-2">{{ $todayLogs }}</p>
        </div>
        <i class="fas fa-bolt absolute -bottom-6 -right-4 text-8xl opacity-20 group-hover:scale-110 group-hover:-rotate-6 transition-all duration-500"></i>
    </div>

    <div class="bg-rose-600 text-white p-8 relative overflow-hidden group">
        <div class="relative z-10">
            <p class="text-xs font-bold uppercase tracking-widest mb-2 opacity-80">Membres impliqués</p>
            <p class="text-5xl font-black tracking-tighter mb-2">{{ $uniqueUsers }}</p>
        </div>
        <i class="fas fa-fingerprint absolute -bottom-6 -right-4 text-8xl opacity-20 group-hover:scale-110 transition-all duration-500"></i>
    </div>
</div>

<div class="bg-slate-50 p-8 border border-slate-200 mb-12 gsap-fade-up">
    <form action="{{ route('admin.activity-logs.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6">
        
        <div>
            <label class="block text-xs font-bold text-slate-900 uppercase tracking-widest mb-3">Recherche</label>
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Mot clé..."
                       class="w-full px-4 py-3 pl-10 bg-white border border-slate-300 focus:border-slate-900 focus:ring-0 text-slate-900 font-bold transition-colors">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-900 uppercase tracking-widest mb-3">Membre</label>
            <div class="relative">
                <select name="user_id" class="w-full px-4 py-3 bg-white border border-slate-300 focus:border-slate-900 focus:ring-0 text-slate-900 font-bold transition-colors appearance-none cursor-pointer">
                    <option value="">TOUS LES MEMBRES</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ mb_strtoupper($user->name) }}
                        </option>
                    @endforeach
                </select>
                <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-xs text-slate-500 pointer-events-none"></i>
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-900 uppercase tracking-widest mb-3">Type d'action</label>
            <div class="relative">
                <select name="action" class="w-full px-4 py-3 bg-white border border-slate-300 focus:border-slate-900 focus:ring-0 text-slate-900 font-bold transition-colors appearance-none cursor-pointer">
                    <option value="">TOUTES LES ACTIONS</option>
                    @foreach($recentActions as $action)
                        <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                            {{ mb_strtoupper($action) }}
                        </option>
                    @endforeach
                </select>
                <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-xs text-slate-500 pointer-events-none"></i>
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-900 uppercase tracking-widest mb-3">Période temporelle</label>
            <div class="flex gap-2">
                <input type="date" name="start_date" value="{{ request('start_date') }}" 
                       class="w-1/2 px-3 py-3 bg-white border border-slate-300 focus:border-slate-900 focus:ring-0 text-slate-900 font-bold transition-colors text-xs">
                <input type="date" name="end_date" value="{{ request('end_date') }}" 
                       class="w-1/2 px-3 py-3 bg-white border border-slate-300 focus:border-slate-900 focus:ring-0 text-slate-900 font-bold transition-colors text-xs">
            </div>
        </div>

        <div class="md:col-span-4 flex justify-end pt-6 border-t border-slate-200 mt-2">
            <button type="submit" class="bg-slate-900 text-white font-black uppercase tracking-widest px-8 py-4 hover:bg-slate-800 transition-colors text-xs inline-flex items-center gap-2">
                <i class="fas fa-filter"></i> Filtrer les archives
            </button>
        </div>
    </form>
</div>

<div class="flex flex-col border-b border-slate-200 mb-12 overflow-x-auto gsap-fade-up">
    <table class="w-full text-left min-w-[900px]">
        <thead>
            <tr class="border-b-2 border-slate-900">
                <th class="py-4 px-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Horodatage</th>
                <th class="py-4 px-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Auteur</th>
                <th class="py-4 px-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Action</th>
                <th class="py-4 px-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Détails de l'événement</th>
                <th class="py-4 px-4 text-xs font-bold text-slate-400 uppercase tracking-widest text-right">Source IP</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
            @forelse($logs as $log)
                <tr class="hover:bg-slate-50 transition-colors gsap-row">
                    <td class="py-5 px-4">
                        <span class="font-black text-slate-900 text-sm whitespace-nowrap">{{ $log->created_at->format('d/m/Y') }}</span>
                        <span class="text-xs font-bold text-slate-400 ml-2">{{ $log->created_at->format('H:i:s') }}</span>
                    </td>
                    
                    <td class="py-5 px-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-slate-900 text-white font-black flex items-center justify-center text-xs shrink-0">
                                {{ substr($log->user->name ?? 'S', 0, 1) }}
                            </div>
                            <span class="font-bold text-slate-900 text-sm uppercase">
                                {{ $log->user->name ?? 'SYSTÈME' }}
                            </span>
                        </div>
                    </td>
                    
                    <td class="py-5 px-4">
                        <span class="bg-slate-100 text-slate-900 border border-slate-200 text-[10px] font-black uppercase tracking-widest px-3 py-1.5 whitespace-nowrap">
                            {{ $log->action }}
                        </span>
                    </td>
                    
                    <td class="py-5 px-4 text-sm font-medium text-slate-600 max-w-sm truncate">
                        {{ $log->description ?? '-' }}
                    </td>
                    
                    <td class="py-5 px-4 text-right">
                        <span class="font-mono text-xs text-slate-500 bg-slate-50 px-2 py-1 border border-slate-200">
                            {{ $log->ip_address ?? '-' }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="py-16 text-center border-b border-slate-200 border-dashed">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-slate-50 mb-4">
                            <i class="fas fa-folder-open text-2xl text-slate-300"></i>
                        </div>
                        <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">Aucune activité enregistrée</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mb-12 gsap-fade-up">
    {{ $logs->links() }}
</div>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", () => {
        gsap.fromTo(".gsap-fade-up", 
            { y: 30, opacity: 0 }, 
            { y: 0, opacity: 1, duration: 0.8, stagger: 0.1, ease: "power3.out" }
        );

        gsap.fromTo(".gsap-row", 
            { x: -10, opacity: 0 }, 
            { x: 0, opacity: 1, duration: 0.4, stagger: 0.05, ease: "power2.out", delay: 0.2 }
        );
    });
</script>
@endpush
@endsection