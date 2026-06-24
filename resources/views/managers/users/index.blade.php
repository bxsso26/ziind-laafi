@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 m-0">Gestion des Utilisateurs</h1>
            <p class="text-sm text-slate-500 mt-1">Gérez les comptes des Clients, Bailleurs et Agents immobiliers.</p>
        </div>
        <a href="{{ route('manager.users.create') }}" class="inline-flex items-center bg-blue-900 hover:bg-blue-800 text-white font-bold text-sm px-4 py-2.5 rounded-xl transition decoration-none shadow-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Ajouter un utilisateur
        </a>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Nom & Prénom</th>
                        <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Email</th>
                        <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Téléphone</th>
                        <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Rôle</th>
                        <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="p-4 text-sm font-semibold text-slate-900">{{ $user->name }}</td>
                            <td class="p-4 text-sm text-slate-600">{{ $user->email }}</td>
                            <td class="p-4 text-sm text-slate-600">{{ $user->telephone }}</td>
                            <td class="p-4 text-sm">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wide
                                    @if($user->role === 'agent') bg-purple-50 text-purple-700 border border-purple-200
                                    @elseif($user->role === 'bailleur') bg-blue-50 text-blue-700 border border-blue-200
                                    @else bg-amber-50 text-amber-700 border border-amber-200 @endif">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="p-4 text-sm text-right space-x-2">
                                <a href="{{ route('manager.users.edit', $user->id) }}" class="inline-flex items-center text-blue-600 hover:text-blue-900 font-bold decoration-none text-sm">
                                    Modifier
                                </a>
                                <form action="{{ route('manager.users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:text-rose-900 font-bold text-sm bg-transparent border-0 p-0 cursor-pointer ms-2">
                                        Supprimer
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                    <tr>
    <td>{{ $property->title }}</td>
    <td>{{ $property->status }}</td>
    <td>
        @if($property->status !== 'Retirée')
            <form action="{{ route('manager.properties.retirer', $property->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-danger">Retirer</button>
            </form>
        @endif
    </td>
</tr>
                        <tr>
                            <td colspan="5" class="p-8 text-center text-sm text-slate-500">Aucun utilisateur trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4">
    {{ $users->links() }}
</div>
        </div>
    </div>
</div>
@endsection