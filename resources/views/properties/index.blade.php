@extends('layouts.app')
@section('content')
<div class="space-y-10">
    <div>
        <h2 class="text-3xl font-black tracking-tight text-slate-900">Biens immobiliers disponibles</h2>
        <p class="mt-2 text-sm font-medium text-slate-500">Trouvez votre future acquisition ou location au Burkina Faso.</p>
    </div>

    <section class="bg-white p-6 rounded-2xl shadow-xl shadow-slate-100/70 border border-slate-100">
        <form action="{{ route('properties.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5 items-end">
            
            <div class="space-y-2">
                <label for="type" class="text-xs font-bold uppercase tracking-wider text-slate-500">Type de bien</label>
                <select name="type" id="type" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 focus:outline-none">
                    <option value="">Tous les types</option>
                    <option value="Villa" {{ request('type') == 'Villa' ? 'selected' : '' }}>Villa</option>
                    <option value="Appartement" {{ request('type') == 'Appartement' ? 'selected' : '' }}>Appartement</option>
                    <option value="Terrain" {{ request('type') == 'Terrain' ? 'selected' : '' }}>Terrain</option>
                </select>
            </div>

            <div class="space-y-2">
                <label for="usage" class="text-xs font-bold uppercase tracking-wider text-slate-500">Usage</label>
                <select name="usage" id="usage" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 focus:outline-none">
                    <option value="">Tous</option>
                    <option value="résidence" {{ request('usage') == 'résidence' ? 'selected' : '' }}>Résidence</option>
                    <option value="bureau" {{ request('usage') == 'bureau' ? 'selected' : '' }}>Bureau</option>
                    <option value="commerce" {{ request('usage') == 'commerce' ? 'selected' : '' }}>Commerce</option>
                    <option value="agriculture" {{ request('usage') == 'agriculture' ? 'selected' : '' }}>Agriculture</option>
                </select>
            </div>

            <div class="space-y-2">
                <label for="option" class="text-xs font-bold uppercase tracking-wider text-slate-500">Contrat</label>
                <select name="option" id="option" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 focus:outline-none">
                    <option value="">Toutes options</option>
                    <option value="location" {{ request('option') == 'location' ? 'selected' : '' }}>Location</option>
                    <option value="vente" {{ request('option') == 'vente' ? 'selected' : '' }}>Vente</option>
                </select>
            </div>

            <div class="space-y-2">
                <label for="zone" class="text-xs font-bold uppercase tracking-wider text-slate-500">Localisation</label>
                <input type="text" name="zone" id="zone" value="{{ request('zone') }}" placeholder="Ex: Ouaga 2000" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm">
            </div>

            <button type="submit" class="w-full bg-blue-800 hover:bg-blue-900 text-white font-bold text-sm py-3 px-6 rounded-xl shadow-lg shadow-blue-800/20 active:scale-[0.98] transition">
                Appliquer les filtres
            </button>
        </form>
    </section>

    <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($properties as $prop)
            @php
                $images = [
                    'Villa' => 'https://images.unsplash.com/photo-1613490493576-7fde63acd811?q=80&w=600&auto=format&fit=crop',
                    'Appartement' => 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?q=80&w=600&auto=format&fit=crop',
                    'Terrain' => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?q=80&w=600&auto=format&fit=crop',
                    'Bureau' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=600&auto=format&fit=crop',
                    'Immeuble' => 'https://images.unsplash.com/photo-1554469384-e58fac16e23a?q=80&w=600&auto=format&fit=crop',
                    'Magasin' => 'https://images.unsplash.com/photo-1555529669-e69e7aa0ba9a?q=80&w=600&auto=format&fit=crop'
                ];
                $imageUrl = $images[$prop->type] ?? 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?q=80&w=600&auto=format&fit=crop';
            @endphp

            <a href="{{ route('properties.show', $prop->id) }}" class="block group bg-white rounded-3xl overflow-hidden border border-slate-100 shadow-md hover:shadow-2xl hover:-translate-y-1">
                <div class="relative overflow-hidden h-56 bg-slate-100">
                    <img src="{{ $imageUrl }}" alt="Photo du bien" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                    <div class="absolute top-4 left-4">
                        <span class="px-3 py-1.5 rounded-lg text-xs font-extrabold uppercase tracking-wider bg-white/90 text-slate-900 backdrop-blur-sm shadow-sm">
                            {{ $prop->contract_option }}
                        </span>
                    </div>
                </div>

                <div class="p-6">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-xl font-bold text-slate-900">{{ $prop->type }}</h3>
                        <span class="px-2.5 py-1 rounded-md text-xs font-semibold bg-slate-100 text-slate-600 uppercase">
                            {{ $prop->property_usage }}
                        </span>
                    </div>

                    <p class="text-slate-500 text-sm flex items-center gap-1.5 mb-4">
                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        {{ $prop->zone }}
                    </p>

                    <p class="text-xs text-slate-400 mb-4">
                        Superficie : <span class="font-medium text-slate-700">{{ number_format($prop->size, 2, '.', ' ') }}</span> m²
                    </p>

                    <div class="border-t border-slate-50 pt-4 flex items-center justify-between">
                        <div class="text-lg font-black text-red-600">
                            {{ number_format($prop->price, 0, '.', ' ') }} <span class="text-xs font-bold text-slate-500">F CFA</span>
                        </div>
                        <span class="text-sm font-bold text-blue-600 hover:text-blue-800 transition-colors flex items-center gap-1">
                            Voir les détails <span class="transform group-hover:translate-x-1 transition-transform">→</span>
                        </span>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-full bg-slate-50 border border-dashed border-slate-200 rounded-2xl p-12 text-center">
                <p class="text-lg font-medium text-slate-600">Aucun bien disponible</p>
                <p class="text-sm text-slate-400 mt-1">Modifiez vos critères de recherche pour obtenir des résultats.</p>
            </div>
        {{-- Dans resources/views/managers/properties/index.blade.php --}}
        @endforelse
    </section>
</div>
@endsection