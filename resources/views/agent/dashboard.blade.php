{{-- 
    agent.blade.php
    
    Vue : Espace de modération pour l'agent immobilier
    
    Fonctionnalités :
    - EF-D1 : Validation/refus des annonces en attente
    - EF-D2 : Gestion des demandes de visite
    - EF-D3 : Gestion des clients affectés
    - Retrait d'annonces (rôle manager uniquement)
--}}

@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    {{-- ====================================================================
         SECTION 1 : EN-TÊTE ET BOUTON D'ACTION
         ==================================================================== --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-slate-900 m-0">Espace Modération Agent</h1>
        
        {{-- Bouton pour créer une nouvelle annonce (le rôle agent peut créer des annonces) --}}
        <a href="{{ route('properties.create') }}" 
           class="btn btn-primary bg-blue-900 border-0 rounded-xl px-4 py-2 font-bold text-white decoration-none text-sm">
            <i class="fas fa-plus me-2"></i> Ajouter une annonce d'agence
        </a>
    </div>

    {{-- ====================================================================
         SECTION 2 : EF-D1 - ANNONCES EN ATTENTE DE VALIDATION
         ==================================================================== --}}
    {{-- 
        Cette section affiche les annonces déposées qui attendent la validation
        de l'agent avant de pouvoir être publiées.
        
        Workflows :
        - 'Valider' : Annonce devient 'publiée' (visible à tous les clients)
        - 'Refuser' : Annonce est retirée (le bailleur est notifié)
        - 'Retirer' : Uniquement disponible pour le MANAGER (role_check)
    --}}
    @if($pendingProperties->isEmpty())
        {{-- Aucune annonce en attente : affichage du message vide --}}
        <div class="p-6 bg-slate-50 border border-slate-100 rounded-3xl text-center text-slate-600">
            <p>Aucune annonce en attente de validation pour le moment. Beau travail !</p>
        </div>
    @else
        {{-- Tableau des annonces en attente --}}
        <div class="bg-white shadow-sm border border-slate-100 rounded-3xl overflow-hidden">
            <table class="table mb-0 align-middle">
                {{-- En-tête du tableau --}}
                <thead class="bg-slate-50 text-slate-700 font-bold text-sm border-bottom">
                    <tr>
                        <th class="p-4">Bien</th>
                        <th class="p-4">Zone</th>
                        <th class="p-4">Prix</th>
                        <th class="p-4 text-end">Actions de modération</th>
                    </tr>
                </thead>
                
                {{-- Contenu du tableau : boucle sur chaque annonce en attente --}}
                <tbody class="text-sm text-slate-600">
                    @foreach($pendingProperties as $property)
                        <tr class="border-bottom">
                            {{-- Colonne 1 : Type et contrat de la propriété --}}
                            <td class="p-4">
                                <div class="font-semibold text-slate-900">
                                    {{ ucfirst($property->type) }} — {{ ucfirst($property->contract_option) }}
                                </div>
                                
                                {{-- 
                                    Bouton "Retirer le bien" : VISIBLE UNIQUEMENT POUR LE MANAGER
                                    - Role check : auth()->user()->role === 'manager'
                                    - Condition supplémentaire : $property->status !== 'Retirée'
                                    - Route : manager.properties.retirer (PATCH)
                                    - Confirmation : Popup "Êtes-vous sûr ?"
                                --}}
                                @if(auth()->user()->role === 'manager' && $property->status !== 'Retirée')
                                    <form action="{{ route('manager.properties.retirer', $property->id) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Êtes-vous sûr ?');" 
                                          class="mt-1">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="text-red-600 text-xs font-bold underline">
                                            Retirer le bien
                                        </button>
                                    </form>
                                @endif
                            </td>
                            
                            {{-- Colonne 2 : Zone géographique --}}
                            <td class="p-4">{{ $property->zone }}</td>
                            
                            {{-- Colonne 3 : Prix formaté en FCFA --}}
                            <td class="p-4 font-bold text-blue-900">
                                {{ number_format($property->price, 0, ',', ' ') }} FCFA
                            </td>
                            
                            {{-- Colonne 4 : Actions de modération (3 boutons) --}}
                            <td class="p-4 text-end">
                                <div class="flex justify-end gap-2">
                                    {{-- Bouton "Voir" : ouvre l'annonce dans un nouvel onglet --}}
                                    <a href="/properties/{{ $property->id }}" 
                                       target="_blank" 
                                       class="btn btn-sm btn-light border rounded-xl px-3 text-slate-700 decoration-none">
                                        <i class="fas fa-eye me-1"></i> Voir
                                    </a>
                                    
                                    {{-- Bouton "Valider" : change le statut de l'annonce en 'publiée' --}}
                                    {{-- Route : agent.properties.validate (PATCH) --}}
                                    <form action="{{ route('agent.properties.validate', $property->id) }}" 
                                          method="POST" 
                                          class="inline m-0">
                                        @csrf @method('PATCH')
                                        <button type="submit" 
                                                class="btn btn-sm btn-success rounded-xl px-3 border-0 bg-green-600 font-bold text-white">
                                            Valider
                                        </button>
                                    </form>
                                    
                                    {{-- Bouton "Refuser" : rejette l'annonce (marque comme 'rejetée') --}}
                                    {{-- Route : agent.properties.reject (PATCH) --}}
                                    <form action="{{ route('agent.properties.reject', $property->id) }}" 
                                          method="POST" 
                                          class="inline m-0">
                                        @csrf @method('PATCH')
                                        <button type="submit" 
                                                class="btn btn-sm btn-danger rounded-xl px-3 border-0 bg-red-600 font-bold text-white">
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
</div>

{{-- ====================================================================
     SECTION 3 : EF-D3 - CLIENTS AFFECTÉS À CET AGENT
     ==================================================================== --}}
{{-- 
    Cette section affiche la liste des clients qui ont été affectés à cet agent.
    Un client affecté est un client que le manager a assigné spécifiquement à cet agent
    pour du suivi et de la relation client.
    
    Données affichées :
    - Nom du client
    - Email
    - Téléphone
    - Date d'inscription
--}}
<h2 class="text-2xl font-bold text-slate-900 mb-6 mt-12">Mes Clients Affectés</h2>

@if($clients->isEmpty())
    {{-- Aucun client affecté à cet agent --}}
    <div class="p-6 bg-slate-50 border border-slate-100 rounded-3xl text-center text-slate-600">
        <p>Aucun client ne vous est encore affecté.</p>
    </div>
@else
    {{-- Tableau des clients affectés --}}
    <div class="bg-white shadow-sm border border-slate-100 rounded-3xl overflow-hidden mb-8">
        <table class="table mb-0 align-middle">
            {{-- En-tête du tableau --}}
            <thead class="bg-slate-50 text-slate-700 font-bold text-sm border-bottom">
                <tr>
                    <th class="p-4">Nom</th>
                    <th class="p-4">Email</th>
                    <th class="p-4">Téléphone</th>
                    <th class="p-4">Inscrit le</th>
                </tr>
            </thead>
            
            {{-- Contenu du tableau : boucle sur chaque client affecté --}}
            <tbody class="text-sm text-slate-600">
                @foreach($clients as $client)
                    <tr class="border-bottom">
                        {{-- Colonne 1 : Nom du client --}}
                        <td class="p-4 font-semibold text-slate-900">{{ $client->name }}</td>
                        
                        {{-- Colonne 2 : Email --}}
                        <td class="p-4">{{ $client->email }}</td>
                        
                        {{-- Colonne 3 : Numéro de téléphone --}}
                        <td class="p-4">{{ $client->telephone }}</td>
                        
                        {{-- Colonne 4 : Date d'inscription (formatée JJ/MM/AAAA) --}}
                        <td class="p-4">{{ $client->created_at->format('d/m/Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

{{-- ====================================================================
     SECTION 4 : EF-D2 - DEMANDES DE VISITE À TRAITER
     ==================================================================== --}}
{{-- 
    Cette section affiche les demandes de visite en attente de traitement.
    Les clients demandent à visiter des propriétés, et l'agent doit :
    - Accepter la visite (change le statut en 'acceptée')
    - Refuser la visite (change le statut en 'refusée')
    
    Une demande de visite liée une demande (VisitRequest) à un bien (Property) 
    et contient la date souhaitée pour la visite.
--}}
<h2 class="text-2xl font-bold text-slate-900 mb-6 mt-12">Demandes de visite à traiter</h2>

@if($pendingVisits->isEmpty())
    {{-- Aucune demande de visite en attente --}}
    <div class="p-6 bg-slate-50 border border-slate-100 rounded-3xl text-center text-slate-600">
        <p>Aucune demande de visite en attente pour le moment.</p>
    </div>
@else
    {{-- Tableau des demandes de visite --}}
    <div class="bg-white shadow-sm border border-slate-100 rounded-3xl overflow-hidden">
        <table class="table mb-0 align-middle">
            {{-- En-tête du tableau --}}
            <thead class="bg-slate-50 text-slate-700 font-bold text-sm border-bottom">
                <tr>
                    <th class="p-4">Client</th>
                    <th class="p-4">Bien concerné</th>
                    <th class="p-4">Date de visite souhaitée</th>
                    <th class="p-4 text-end">Actions</th>
                </tr>
            </thead>
            
            {{-- Contenu du tableau : boucle sur chaque demande de visite en attente --}}
            <tbody class="text-sm text-slate-600">
                @foreach($pendingVisits as $visit)
                    <tr class="border-bottom">
                        {{-- Colonne 1 : Nom du client qui demande la visite --}}
                        <td class="p-4 font-semibold text-slate-900">
                            {{ $visit->user->name ?? 'Client inconnu' }}
                        </td>
                        
                        {{-- Colonne 2 : Détails du bien (type + zone) --}}
                        <td class="p-4">
                            {{ ucfirst($visit->property->type ?? 'Bien') }} — {{ $visit->property->zone ?? 'Zone inconnue' }}
                        </td>
                        
                        {{-- Colonne 3 : Date de visite souhaitée (formatée JJ/MM/AAAA) --}}
                        <td class="p-4 font-bold text-blue-900">
                            {{ \Carbon\Carbon::parse($visit->visit_date)->format('d/m/Y') }}
                        </td>
                        
                        {{-- Colonne 4 : Actions (2 boutons) --}}
                        <td class="p-4 text-end">
                            <div class="flex justify-end gap-2">
                                {{-- Bouton "Accepter la visite" : change le statut en 'acceptée' --}}
                                {{-- Route : agent.visits.validate (PATCH) --}}
                                <form action="{{ route('agent.visits.validate', $visit->id) }}" 
                                      method="POST" 
                                      class="inline m-0">
                                    @csrf @method('PATCH')
                                    <button type="submit" 
                                            class="btn btn-sm bg-green-600 hover:bg-green-700 font-bold text-white rounded-xl px-3 border-0">
                                        Accepter la visite
                                    </button>
                                </form>

                                {{-- Bouton "Refuser" : change le statut en 'refusée' --}}
                                {{-- Route : agent.visits.reject (PATCH) --}}
                                <form action="{{ route('agent.visits.reject', $visit->id) }}" 
                                      method="POST" 
                                      class="inline m-0">
                                    @csrf @method('PATCH')
                                    <button type="submit" 
                                            class="btn btn-sm bg-red-600 hover:bg-red-700 font-bold text-white rounded-xl px-3 border-0">
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