@extends('layouts.app')

@section('content')
<main class="max-w-4xl mx-auto px-4 py-12">
    <!-- Bouton Retour -->
    <a href="{{ route('properties.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-600 hover:text-blue-900 mb-8 transition-colors">
        ← Retour au catalogue
    </a>

    @php
        $images = [
            'Villa' => 'https://images.unsplash.com/photo-1613490493576-7fde63acd811?q=80&w=1200&auto=format&fit=crop',
            'Appartement' => 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?q=80&w=1200&auto=format&fit=crop',
            'Terrain' => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?q=80&w=1200&auto=format&fit=crop',
            'Bureau' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=1200&auto=format&fit=crop',
            'Immeuble' => 'https://images.unsplash.com/photo-1554469384-e58fac16e23a?q=80&w=1200&auto=format&fit=crop',
            'Magasin' => 'https://images.unsplash.com/photo-1555529669-e69e7aa0ba9a?q=80&w=1200&auto=format&fit=crop'
        ];
        $imageUrl = $images[$property->type] ?? 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?q=80&w=1200&auto=format&fit=crop';
    @endphp

    <div class="bg-white rounded-3xl overflow-hidden shadow-xl border border-slate-100">
        <!-- Grande Image de présentation -->
        <div class="relative h-[400px] w-full bg-slate-100">
            <img src="{{ $imageUrl }}" alt="{{ $property->type }}" class="w-full h-full object-cover">
            <div class="absolute top-6 left-6">
                <span class="px-4 py-2 rounded-xl font-black uppercase text-sm bg-blue-900 text-white shadow-md">
                    {{ $property->contract_option }}
                </span>
            </div>
        </div>

        <!-- Contenu de la fiche technique -->
        <div class="p-8 md:p-12">
            <div class="flex flex-wrap items-start justify-between gap-4 border-b border-slate-100 pb-6 mb-6">
                <div>
                    <span class="text-xs font-bold text-emerald-600 uppercase tracking-widest">{{ $property->property_usage }}</span>
                    <h1 class="text-3xl font-black text-slate-900 mt-1">{{ $property->type }} à {{ $property->zone }}</h1>
                </div>
                <div class="text-3xl font-black text-red-600">
                    {{ number_format($property->price, 0, '.', ' ') }} <span class="text-lg font-bold text-slate-500">F CFA</span>
                </div>
            </div>

            <!-- Caractéristiques secondaires -->
            <div class="flex gap-8 mb-8 text-sm text-slate-600 bg-slate-50 p-4 rounded-xl">
                <div>
                    <span class="block text-xs text-slate-400 font-medium uppercase">Superficie</span>
                    <span class="font-bold text-base text-slate-800">{{ number_format($property->size, 2, '.', ' ') }} m²</span>
                </div>
                <div>
                    <span class="block text-xs text-slate-400 font-medium uppercase">Statut</span>
                    <span class="inline-flex items-center gap-1.5 font-semibold text-emerald-600 mt-0.5">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span> Disponible
                    </span>
                </div>
            </div>

            <!-- Description complète générée par le Seeder -->
            <div class="prose prose-slate">
                <h2 class="text-xl font-bold text-slate-900 mb-3">Description du bien</h2>
                <p class="text-slate-600 leading-relaxed text-base bg-blue-50/30 p-6 rounded-2xl border border-blue-50">
                    {{ $property->description }}
                </p>
            </div>
            <!-- Description complète générée par le Seeder -->
<div class="prose prose-slate">
    <h2 class="text-xl font-bold text-slate-900 mb-3">Description du bien</h2>
    <p class="text-slate-600 leading-relaxed text-base bg-blue-50/30 p-6 rounded-2xl border border-blue-50">
        {{ $property->description }}
    </p>
</div>

@auth
    @if(auth()->user()->role === 'client')
        <form action="{{ route('favorites.toggle', $property->id) }}" method="POST" class="mb-4">
            @csrf
            @php
                $isFavori = \App\Models\Favorite::where('user_id', auth()->id())
                            ->where('property_id', $property->id)->exists();
            @endphp
            <button type="submit" class="w-full py-3 rounded-xl font-bold text-sm border-2 transition
                {{ $isFavori ? 'border-red-500 text-red-600 hover:bg-red-50' : 'border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                {{ $isFavori ? '❤️ Retirer des favoris' : '🤍 Ajouter aux favoris' }}
            </button>
        </form>
    @endif
@endauth
<!-- BLOCK FORMULAIRE DE DEMANDE DE VISITE COLLÉ ICI -->
<div class="mt-8 space-y-6">
    @auth
        @if(auth()->user()->role === 'client')
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
                <h4 class="font-bold text-slate-800 mb-4">📅 Planifier une visite</h4>
                <form action="{{ route('properties.visit', $property->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Date souhaitée</label>
                        <input type="date" name="visit_date" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-semibold focus:border-blue-900 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Message (optionnel)</label>
                        <textarea name="message" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-semibold focus:border-blue-900 outline-none" placeholder="Ex: Disponible en soirée..."></textarea>
                    </div>
                    <button type="submit" class="w-full bg-blue-900 text-white font-bold py-3 rounded-xl hover:bg-blue-800 transition">
                        Envoyer la demande
                    </button>
                </form>
            </div>
        @endif
    @else
        <div class="p-4 bg-blue-50 border border-blue-100 rounded-3xl text-sm text-slate-700">
            Veuillez <a href="{{ route('auth.page') }}" class="font-bold text-blue-900 underline">vous connecter</a> pour demander une visite.
        </div>
    @endauth

    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
        <h4 class="font-bold text-slate-800 mb-3">👤 Coordonnées du propriétaire</h4>
        @auth
            <div class="text-sm text-slate-600 space-y-2">
                <p><strong>Nom :</strong> {{ $property->user->name ?? 'Non spécifié' }}</p>
                <p><strong>Tél :</strong> {{ $property->user->telephone ?? 'Non spécifié' }}</p>
                <p><strong>Email :</strong> {{ $property->user->email ?? 'Non spécifié' }}</p>
            </div>
        @else
            <div class="p-3 bg-amber-50 border border-amber-100 rounded-2xl text-center">
                <p class="text-sm text-slate-600 mb-2">Vous devez être connecté pour afficher les coordonnées du bailleur.</p>
            <a href="/login" class="btn btn-sm btn-primary rounded-xl px-3 bg-blue-900 border-0 text-white font-bold decoration-none">
                Se connecter
            </a>
            </div>
        @endauth
    </div>
</div>
    </div>
</main>
@endsection