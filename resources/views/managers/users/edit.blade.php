@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('manager.users.index') }}" class="text-sm font-bold text-slate-500 hover:text-slate-800 inline-flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Retour à la liste
        </a>
        <h1 class="text-2xl font-bold text-slate-900 mt-2">Modifier : {{ $user->name }}</h1>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
        <form action="{{ route('manager.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nom complet</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-blue-900 text-sm">
                    @error('name') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Adresse Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-blue-900 text-sm">
                    @error('email') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Numéro de téléphone</label>
                    <input type="text" name="telephone" value="{{ old('telephone', $user->telephone) }}" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-blue-900 text-sm">
                    @error('telephone') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Rôle</label>
                    <select name="role" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white focus:outline-none focus:border-blue-900 text-sm">
                        <option value="client" {{ old('role', $user->role) === 'client' ? 'selected' : '' }}>Client</option>
                        <option value="bailleur" {{ old('role', $user->role) === 'bailleur' ? 'selected' : '' }}>Bailleur</option>
                        <option value="agent" {{ old('role', $user->role) === 'agent' ? 'selected' : '' }}>Agent</option>
                    </select>
                    @error('role') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- SECTION AFFECTATION AGENT — intégrée dans le formulaire --}}
                @if($user->role === 'client')
                <div class="p-4 bg-blue-50 border border-blue-200 rounded-xl">
                    <label class="block text-xs font-bold text-blue-800 uppercase tracking-wider mb-2">
                        Agent immobilier responsable
                    </label>
                    <select name="agent_id"
                        class="w-full px-4 py-2.5 rounded-xl border border-blue-300 bg-white focus:outline-none focus:border-blue-900 text-sm font-semibold text-slate-700">
                        <option value="">-- Aucun agent assigné --</option>
                        @foreach($agents as $agent)
                            <option value="{{ $agent->id }}" {{ $user->agent_id == $agent->id ? 'selected' : '' }}>
                                {{ $agent->name }} — (Tél: {{ $agent->telephone }})
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-blue-600 mt-2">Sélectionnez un agent pour affecter ce client à son suivi.</p>
                </div>
                @endif

                <div class="pt-2 border-t border-slate-100">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nouveau mot de passe (optionnel)</label>
                    <input type="password" name="password"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-blue-900 text-sm"
                        placeholder="Laissez vide pour ne pas changer">
                    <p class="text-xs text-slate-400 mt-1">Remplir uniquement si vous souhaitez modifier son mot de passe actuel.</p>
                    @error('password') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-6 pt-6 border-t border-slate-100 flex justify-end">
                <button type="submit"
                    class="bg-blue-900 hover:bg-blue-800 text-white font-bold text-sm px-6 py-2.5 rounded-xl transition shadow-sm border-0 cursor-pointer">
                    Mettre à jour l'utilisateur
                </button>
            </div>
        </form>
    </div>
</div>
@endsection