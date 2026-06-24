@extends('layouts.app') <!-- Ou le nom de ton layout principal -->

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-slate-900 mb-6">Mon historique de demandes de visite</h1>

    @if($visites->isEmpty())
        <div class="p-6 bg-slate-50 border border-slate-100 rounded-3xl text-center text-slate-600">
            <p class="mb-3">Vous n'avez pas encore effectué de demande de visite.</p>
            <a href="/" class="inline-flex items-center justify-center px-5 py-2 text-sm font-bold text-white bg-blue-900 rounded-3xl border-0">
                Découvrir nos biens
            </a>
        </div>
    @else
        <div class="bg-white shadow-sm border border-slate-100 rounded-3xl overflow-hidden">
            <table class="table mb-0 align-middle">
                <thead class="bg-slate-50 text-slate-700 font-bold text-sm border-bottom">
                    <tr>
                        <th class="p-4">Propriété</th>
                        <th class="p-4">Date demandée</th>
                        <th class="p-4">Date de la demande</th>
                        <th class="p-4">Statut</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-slate-600">
                    @foreach($visites as $visite)
                        <tr class="border-bottom">
                            <td class="p-4 font-semibold text-slate-900">
                                @if($visite->property)
                                    <a href="/properties/{{ $visite->property->id }}" class="hover:text-blue-900 underline">
                                        {{ $visite->property->title ?? 'Bien immobilier' }}
                                    </a>
                                @else
                                    <span class="text-slate-400">Propriété non disponible</span>
                                @endif
                            </td>
                            <td class="p-4">
                                {{ \Carbon\Carbon::parse($visite->visit_date)->format('d/m/Y') }}
                            </td>
                            <td class="p-4 text-xs text-slate-400">
                                {{ $visite->created_at->format('d/m/Y à H:i') }}
                            </td>
                            <td class="p-4">
                                @if($visite->status === 'en attente')
                                    <span class="px-3 py-1 text-xs font-bold bg-amber-100 text-amber-800 rounded-full">
                                        En attente
                                    </span>
                                @elseif($visite->status === 'validée')
                                    <span class="px-3 py-1 text-xs font-bold bg-green-100 text-green-800 rounded-full">
                                        Validée
                                    </span>
                                @elseif($visite->status === 'refusée')
                                    <span class="px-3 py-1 text-xs font-bold bg-red-100 text-red-800 rounded-full">
                                        Refusée
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection