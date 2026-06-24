@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-3xl p-8 shadow-xl shadow-slate-100/80 border border-slate-100">
    <div class="mb-8">
        <h2 class="text-2xl font-black tracking-tight text-slate-900">Publier une annonce immobilière</h2>
        <p class="text-sm font-medium text-slate-500 mt-1">Complétez le formulaire ci-dessous. Les champs marqués d'une (*) sont requis.</p>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl text-sm text-red-800 font-semibold">
            <p class="font-bold mb-1">Veuillez corriger les erreurs suivantes :</p>
            <ul class="list-disc pl-5 space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('properties.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div class="space-y-1.5">
                <label for="type" class="text-xs font-bold uppercase tracking-wider text-slate-600">Type de propriété *</label>
                <select name="type" id="type" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 focus:outline-none focus:border-blue-800 focus:bg-white transition-all">
                    <option value="Villa">Villa</option>
                    <option value="Appartement">Appartement</option>
                    <option value="Terrain">Terrain</option>
                </select>
            </div>

            <div class="space-y-1.5">
                <label for="property_usage" class="text-xs font-bold uppercase tracking-wider text-slate-600">Usage *</label>
                <select name="property_usage" id="property_usage" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 focus:outline-none focus:border-blue-800 focus:bg-white transition-all">
                    <option value="résidence">Résidence</option>
                    <option value="bureau">Bureau</option>
                    <option value="commerce">Commerce</option>
                    <option value="agriculture">Agriculture</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div class="space-y-1.5">
                <label for="contract_option" class="text-xs font-bold uppercase tracking-wider text-slate-600">Option contractuelle *</label>
                <select name="contract_option" id="contract_option" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 focus:outline-none focus:border-blue-800 focus:bg-white transition-all">
                    <option value="location">Location</option>
                    <option value="vente">Vente</option>
                </select>
            </div>

            <div class="space-y-1.5">
                <label for="zone" class="text-xs font-bold uppercase tracking-wider text-slate-600">Zone géographique *</label>
                <input type="text" name="zone" id="zone" required placeholder="Ex: Bobo-Dioulasso" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 placeholder-slate-400 focus:outline-none focus:border-blue-800 focus:bg-white transition-all">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div class="space-y-1.5">
                <label for="size" class="text-xs font-bold uppercase tracking-wider text-slate-600">Superficie (en m²) *</label>
                <input type="number" name="size" id="size" required min="1" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 focus:outline-none focus:border-blue-800 focus:bg-white transition-all">
            </div>

            <div class="space-y-1.5">
                <label for="price" class="text-xs font-bold uppercase tracking-wider text-slate-600">Prix / Loyer (F CFA) *</label>
                <input type="number" name="price" id="price" required min="1" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 focus:outline-none focus:border-blue-800 focus:bg-white transition-all">
            </div>
        </div>

        <div class="space-y-1.5">
            <label for="description" class="text-xs font-bold uppercase tracking-wider text-slate-600">Description du bien</label>
            <textarea name="description" id="description" rows="4" placeholder="Détails importants..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 placeholder-slate-400 focus:outline-none focus:border-blue-800 focus:bg-white transition-all"></textarea>
        </div>

        <div class="space-y-1.5">
            <label class="text-xs font-bold uppercase tracking-wider text-slate-600">Illustration photographique *</label>
            <input type="file" name="photo" id="photo" required accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-800 hover:file:bg-blue-100 transition-all cursor-pointer">
        </div>

        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm py-3.5 px-6 rounded-xl shadow-lg shadow-emerald-600/10 active:scale-[0.99] transition-all cursor-pointer pt-4">
            Soumettre l'annonce
        </button>
    </form>
</div>
@endsection