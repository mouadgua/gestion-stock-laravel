@extends('layouts.app')
@section('title', 'Vérification Email - The Vault')
@section('content')
<div class="max-w-lg mx-auto py-20 text-center">
    <div class="w-20 h-20 mx-auto mb-8 rounded-2xl bg-slate-900 flex items-center justify-center">
        <i class="fas fa-envelope text-white text-3xl"></i>
    </div>
    <h1 class="text-4xl font-black text-slate-900 tracking-tighter uppercase mb-4">Vérifiez votre email</h1>
    <p class="text-slate-500 font-medium mb-8">
        Un lien de vérification a été envoyé à <strong>{{ auth()->user()->email }}</strong>.<br>
        Cliquez sur ce lien pour activer votre compte.
    </p>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-6 py-4 rounded-xl font-bold text-sm mb-6">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit"
            class="bg-slate-900 text-white px-8 py-4 font-black uppercase tracking-widest hover:bg-slate-800 transition-colors text-sm">
            Renvoyer le lien
        </button>
    </form>

    <form method="POST" action="{{ route('logout') }}" class="mt-6">
        @csrf
        <button type="submit" class="text-sm font-bold text-slate-400 hover:text-slate-700 transition-colors uppercase tracking-widest">
            Se déconnecter
        </button>
    </form>
</div>
@endsection
