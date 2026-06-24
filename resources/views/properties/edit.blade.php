@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100">
        <h1 class="text-2xl font-black text-slate-900 mb-8">Modifier mon annonce</h1>

        <form action="{{ route('properties.update', $property->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase text-slate-600">Type de bien</label>
                    <select name="type" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold focus:border-blue-900 outline-none transition-all">
                        <option value="maison" {{ $property->type == 'maison' ? 'selected' : '' }}>Maison</option>
                        <option value="appartement" {{ $property->type == 'appartement' ? 'selected' : '' }}>Appartement</option>
                        <option value="terrain" {{ $property->type == 'terrain' ? 'selected' : '' }}>Terrain</option>
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase text-slate-600">Usage</label>
                    <select name="property_usage" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold focus:border-blue-900 outline-none transition-all">
                        <option value="habitation" {{ $property->property_usage == 'habitation' ? 'selected' : '' }}>Habitation</option>
                        <option value="commercial" {{ $property->property_usage == 'commercial' ? 'selected' : '' }}>Commercial</option>
                    </select>
                </div>
            </div>

            <div class="space-y-1.5">
                <label class="text-xs font-bold uppercase text-slate-600">Modifier la photo (optionnel)</label>
                <input type="file" name="photo" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-900 font-bold">
                @if($property->photo_path)
                    <p class="text-xs text-slate-400">Une photo existe déjà pour ce bien.</p>
                @endif
            </div>

            <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
                <a href="{{ route('bailleur.index') }}" class="px-5 py-2.5 text-sm font-bold text-slate-600 hover:text-slate-900">Annuler</a>
                <button type="submit" class="bg-blue-900 hover:bg-blue-800 text-white font-bold px-6 py-2.5 rounded-xl transition">
                    Sauvegarder les modifications
                </button>
            </div>
        </form>
    </div>
</div>
@endsection