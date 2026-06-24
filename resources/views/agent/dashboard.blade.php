@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-slate-900 m-0">Espace Modération Agent</h1>
        <a href="{{ route('properties.create') }}" class="btn btn-primary bg-blue-900 border-0 rounded-xl px-4 py-2 font-bold text-white decoration-none text-sm">
            <i class="fas fa-plus me-2"></i> Ajouter une annonce d'agence
        </a>
    </div>

    @if($pendingProperties->isEmpty())
        <div class="p-6 bg-slate-50 border border-slate-100 rounded-3xl text-center text-slate-600">
            <p>Aucune annonce en attente de validation pour le moment. Beau travail !</p>
        </div>
    @else
        <div class="bg-white shadow-sm border border-slate-100 rounded-3xl overflow-hidden">
            <table class="table mb-0 align-middle">
                <thead class="bg-slate-50 text-slate-700 font-bold text-sm border-bottom">
                    <tr>
                        <th class="p-4">Bien</th>
                        <th class="p-4">Zone</th>
                        <th class="p-4">Prix</th>
                        <th class="p-4 text-end">Actions de modération</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-slate-600">
                    @foreach($pendingProperties as $property)
                        <tr class="border-bottom">
                            <td class="p-4">
                                <div class="font-semibold text-slate-900">{{ ucfirst($property->type) }} — {{ ucfirst($property->contract_option) }}</div>
                                @if(auth()->user()->role === 'manager' && $property->status !== 'Retirée')
                                    <form action="{{ route('manager.properties.retirer', $property->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr ?');" class="mt-1">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="text-red-600 text-xs font-bold underline">Retirer le bien</button>
                                    </form>
                                @endif
                            </td>
                            <td class="p-4">{{ $property->zone }}</td>
                            <td class="p-4 font-bold text-blue-900">{{ number_format($property->price, 0, ',', ' ') }} FCFA</td>
                            <td class="p-4 text-end">
                                <div class="flex justify-end gap-2">
                                    <a href="/properties/{{ $property->id }}" target="_blank" class="btn btn-sm btn-light border rounded-xl px-3 text-slate-700 decoration-none">
                                        <i class="fas fa-eye me-1"></i> Voir
                                    </a>
                                    <form action="{{ route('agent.properties.validate', $property->id) }}" method="POST" class="inline m-0">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-success rounded-xl px-3 border-0 bg-green-600 font-bold text-white">Valider</button>
                                    </form>
                                    <form action="{{ route('agent.properties.reject', $property->id) }}" method="POST" class="inline m-0">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-danger rounded-xl px-3 border-0 bg-red-600 font-bold text-white">Refuser</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
<!-- Section EF-D2 & EF-D3 : Demandes de visite -->
    <h2 class="text-2xl font-bold text-slate-900 mb-6 mt-12">Demandes de visite à traiter</h2>

    {{-- Section EF-D3 : Clients affectés --}}
<h2 class="text-2xl font-bold text-slate-900 mb-6 mt-12">Mes Clients Affectés</h2>

@if($clients->isEmpty())
    <div class="p-6 bg-slate-50 border border-slate-100 rounded-3xl text-center text-slate-600">
        <p>Aucun client ne vous est encore affecté.</p>
    </div>
@else
    <div class="bg-white shadow-sm border border-slate-100 rounded-3xl overflow-hidden mb-8">
        <table class="table mb-0 align-middle">
            <thead class="bg-slate-50 text-slate-700 font-bold text-sm border-bottom">
                <tr>
                    <th class="p-4">Nom</th>
                    <th class="p-4">Email</th>
                    <th class="p-4">Téléphone</th>
                    <th class="p-4">Inscrit le</th>
                </tr>
            </thead>
            <tbody class="text-sm text-slate-600">
                @foreach($clients as $client)
                    <tr class="border-bottom">
                        <td class="p-4 font-semibold text-slate-900">{{ $client->name }}</td>
                        <td class="p-4">{{ $client->email }}</td>
                        <td class="p-4">{{ $client->telephone }}</td>
                        <td class="p-4">{{ $client->created_at->format('d/m/Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

    @if($pendingVisits->isEmpty())
        <div class="p-6 bg-slate-50 border border-slate-100 rounded-3xl text-center text-slate-600">
            <p>Aucune demande de visite en attente pour le moment.</p>
        </div>
    @else
        <div class="bg-white shadow-sm border border-slate-100 rounded-3xl overflow-hidden">
            <table class="table mb-0 align-middle">
                <thead class="bg-slate-50 text-slate-700 font-bold text-sm border-bottom">
                    <tr>
                        <th class="p-4">Client</th>
                        <th class="p-4">Bien concerné</th>
                        <th class="p-4">Date de visite souhaitée</th>
                        <th class="p-4 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-slate-600">
                    @foreach($pendingVisits as $visit)
                        <tr class="border-bottom">
                            <td class="p-4 font-semibold text-slate-900">
                                {{ $visit->user->name ?? 'Client inconnu' }}
                            </td>
                            <td class="p-4">
                                {{ ucfirst($visit->property->type ?? 'Bien') }} — {{ $visit->property->zone ?? 'Zone inconnue' }}
                            </td>
                            <td class="p-4 font-bold text-blue-900">
                                {{ \Carbon\Carbon::parse($visit->visit_date)->format('d/m/Y') }}
                            </td>
                            <td class="p-4 text-end">
                                <div class="flex justify-end gap-2">
                                    <!-- Valider la visite -->
                                    <form action="{{ route('agent.visits.validate', $visit->id) }}" method="POST" class="inline m-0">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm bg-green-600 hover:bg-green-700 font-bold text-white rounded-xl px-3 border-0">
                                            Accepter la visite
                                        </button>
                                    </form>

                                    <!-- Refuser la visite -->
                                    <form action="{{ route('agent.visits.reject', $visit->id) }}" method="POST" class="inline m-0">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm bg-red-600 hover:bg-red-700 font-bold text-white rounded-xl px-3 border-0">
                                            Refuser
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection