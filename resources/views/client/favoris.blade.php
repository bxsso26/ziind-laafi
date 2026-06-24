@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-slate-900 mb-6">Mes Propriétés Favorites</h1>

    @if($favoris->isEmpty())
        <div class="p-6 bg-slate-50 border border-slate-100 rounded-3xl text-center text-slate-600">
            <p>Vous n'avez pas encore de propriétés favorites.</p>
            <a href="{{ route('properties.index') }}" class="mt-3 inline-block text-sm font-bold text-blue-900 underline">
                Parcourir le catalogue
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($favoris as $favori)
                @php
                    $prop = $favori->property;
                    $images = [
                        'Villa' => 'https://images.unsplash.com/photo-1613490493576-7fde63acd811?q=80&w=600&auto=format&fit=crop',
                        'Appartement' => 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?q=80&w=600&auto=format&fit=crop',
                        'Terrain' => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?q=80&w=600&auto=format&fit=crop',
                    ];
                    $imageUrl = $images[$prop->type] ?? 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?q=80&w=600&auto=format&fit=crop';
                @endphp

                <div class="bg-white rounded-3xl overflow-hidden border border-slate-100 shadow-md">
                    <img src="{{ $imageUrl }}" alt="{{ $prop->type }}" class="w-full h-48 object-cover">
                    <div class="p-5">
                        <h3 class="text-lg font-bold text-slate-900">{{ $prop->type }} — {{ $prop->zone }}</h3>
                        <p class="text-sm text-slate-500 mt-1">{{ ucfirst($prop->contract_option) }}</p>
                        <p class="text-lg font-black text-red-600 mt-2">{{ number_format($prop->price, 0, '.', ' ') }} F CFA</p>

                        <div class="flex gap-2 mt-4">
                            <a href="{{ route('properties.show', $prop->id) }}"
                                class="flex-1 text-center py-2 bg-blue-900 text-white text-sm font-bold rounded-xl">
                                Voir détails
                            </a>
                            <form action="{{ route('favorites.toggle', $prop->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="py-2 px-3 border-2 border-red-500 text-red-600 text-sm font-bold rounded-xl hover:bg-red-50 bg-transparent cursor-pointer">
                                    ❤️ Retirer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection