@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Mes Annonces Déposées</h1>
        <a href="{{ route('properties.create') }}" class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-bold text-white bg-blue-900 rounded-3xl border-0 decoration-none">
            Déposer une nouvelle annonce
        </a>
    </div>

    @if($properties->isEmpty())
        <div class="p-6 bg-slate-50 border border-slate-100 rounded-3xl text-center text-slate-600">
            <p>Vous n'avez pas encore déposé d'annonce sur Ziind Laafi.</p>
        </div>
    @else
        <div class="bg-white shadow-sm border border-slate-100 rounded-3xl overflow-hidden">
            <table class="table mb-0 align-middle">
                <thead class="bg-slate-50 text-slate-700 font-bold text-sm border-bottom">
                    <tr>
                        <th class="p-4">Type</th>
                        <th class="p-4">Zone</th>
                        <th class="p-4">Prix</th>
                        <th class="p-4">Statut</th>
                        <th class="p-4 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-slate-600">
                    @foreach($properties as $property)
                        <tr class="border-bottom">
                            <td class="p-4 font-semibold text-slate-900">
                                {{ ucfirst($property->type) }} ({{ ucfirst($property->property_usage) }})
                            </td>
                            <td class="p-4">{{ $property->zone }}</td>
                            <td class="p-4 font-bold text-blue-900">{{ number_format($property->price, 0, ',', ' ') }} FCFA</td>
                            <td class="p-4">
                                <!-- EF-C4 : Visualisation claire des statuts -->
                                @if($property->status === 'en attente')
                                    <span class="px-3 py-1 text-xs font-bold bg-amber-100 text-amber-800 rounded-full">En attente</span>
                                @elseif($property->status === 'publiée')
                                    <span class="px-3 py-1 text-xs font-bold bg-green-100 text-green-800 rounded-full">Publiée</span>
                                @elseif($property->status === 'retirée')
                                    <span class="px-3 py-1 text-xs font-bold bg-slate-100 text-slate-800 rounded-full">Retirée</span>
                                @endif
                            </td>
                            <td class="p-4 text-end">
                                <!-- EF-C3 : Consulter, Modifier, Supprimer -->
                                <div class="flex justify-end gap-2">
                                    <a href="/properties/{{ $property->id }}" class="btn btn-sm btn-light border rounded-xl px-3 text-slate-700 decoration-none">
                                        <i class="fas fa-eye me-1"></i> Voir
                                    </a>
                                    
                                    <a href="/properties/{{ $property->id }}/edit" class="btn btn-sm btn-outline-primary border rounded-xl px-3 decoration-none">
                                        <i class="fas fa-edit me-1"></i> Modifier
                                    </a>

                                    <form action="/properties/{{ $property->id }}" method="POST" class="inline m-0" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette annonce ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger border rounded-xl px-3">
                                            <i class="fas fa-trash me-1"></i> Supprimer
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
</div>
@endsection