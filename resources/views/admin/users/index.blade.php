@extends('layouts.app')

@section('title', 'Gestion des Utilisateurs - Admin')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">Gestion des Utilisateurs</h1>
    <p class="text-gray-500 mt-1">Gérez les comptes clients et livreurs</p>
</div>

@if(session('success'))
    <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-700 px-6 py-4 rounded-lg mb-6 shadow-sm">
        {{ session('success') }}
    </div>
@endif

<!-- Tabs -->
<div class="bg-white rounded-2xl shadow-soft overflow-hidden">
    <div class="border-b border-gray-100">
        <nav class="flex gap-8 px-6 pt-4">
            <button onclick="filterUsers('all')" id="tab-all" class="pb-3 border-b-2 border-primary-500 text-primary-600 font-medium text-sm">
                Tous ({{ $users->total() }})
            </button>
            <button onclick="filterUsers('acheteur')" id="tab-acheteur" class="pb-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium text-sm">
                Acheteurs
            </button>
            <button onclick="filterUsers('livreur')" id="tab-livreur" class="pb-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium text-sm">
                Livreurs
            </button>
        </nav>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Utilisateur</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Rôle</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Commandes</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Inscrit le</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($users as $user)
                    <tr class="hover:bg-gray-50 transition user-row" data-role="{{ $user->role }}">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-r from-primary-400 to-indigo-400 flex items-center justify-center text-white font-bold">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div class="ml-4">
                                    <div class="font-medium text-gray-900">{{ $user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($user->role === 'admin')
                                <span class="px-3 py-1 text-xs rounded-full bg-purple-100 text-purple-700 font-medium">Admin</span>
                            @elseif($user->role === 'livreur')
                                <span class="px-3 py-1 text-xs rounded-full bg-orange-100 text-orange-700 font-medium">Livreur</span>
                            @else
                                <span class="px-3 py-1 text-xs rounded-full bg-blue-100 text-blue-700 font-medium">Acheteur</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $user->orders_count ?? 0 }} commande(s)
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $user->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <a href="#" class="text-primary-600 hover:text-primary-700 font-medium mr-3">
                                Voir
                            </a>
                            @if($user->role !== 'admin')
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer cet utilisateur ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-700 font-medium">
                                        Supprimer
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4 px-6 pb-6">
        {{ $users->links() }}
    </div>
</div>

<script>
function filterUsers(role) {
    // Update tabs
    document.querySelectorAll('nav button').forEach(btn => {
        btn.classList.remove('border-primary-500', 'text-primary-600');
        btn.classList.add('border-transparent', 'text-gray-500');
    });
    document.getElementById('tab-' + role).classList.remove('border-transparent', 'text-gray-500');
    document.getElementById('tab-' + role).classList.add('border-primary-500', 'text-primary-600');

    // Filter rows
    document.querySelectorAll('.user-row').forEach(row => {
        if (role === 'all' || row.dataset.role === role) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
</script>
@endsection