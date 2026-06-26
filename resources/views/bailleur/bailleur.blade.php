{{-- 
    bailleur.blade.php
    
    Vue : Espace de gestion des annonces pour le bailleur (propriétaire)
    
    Fonctionnalités :
    - EF-C1 : Liste des annonces déposées
    - EF-C3 : Actions CRUD (Voir, Modifier, Supprimer)
    - EF-C4 : Visualisation des statuts (En attente, Publiée, Retirée)
--}}

@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    {{-- ====================================================================
         SECTION 1 : EN-TÊTE ET BOUTON D'ACTION
         ==================================================================== --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Mes Annonces Déposées</h1>
        
        {{-- Bouton pour créer une nouvelle annonce --}}
        <a href="{{ route('properties.create') }}" 
           class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-bold text-white bg-blue-900 rounded-3xl border-0 decoration-none">
            Déposer une nouvelle annonce
        </a>
    </div>

    {{-- ====================================================================
         SECTION 2 : EF-C1 - LISTE DES ANNONCES DÉPOSÉES
         ==================================================================== --}}
    {{-- 
        Cette section affiche toutes les annonces que le bailleur a déposées.
        
        Statuts possibles :
        - 'en attente'  : Annonce en cours de validation par un agent
        - 'publiée'     : Annonce validée et visible à tous les clients
        - 'retirée'     : Annonce retirée du catalogue (par bailleur ou manager)
        
        Pour chaque annonce, le bailleur peut :
        - Voir : Consulter la fiche complète de l'annonce
        - Modifier : Éditer les informations (zone, prix, description, etc.)
        - Supprimer : Retirer définitivement l'annonce
    --}}
    @if($properties->isEmpty())
        {{-- Aucune annonce déposée --}}
        <div class="p-6 bg-slate-50 border border-slate-100 rounded-3xl text-center text-slate-600">
            <p>Vous n'avez pas encore déposé d'annonce sur Ziind Laafi.</p>
        </div>
    @else
        {{-- Tableau des annonces déposées --}}
        <div class="bg-white shadow-sm border border-slate-100 rounded-3xl overflow-hidden">
            <table class="table mb-0 align-middle">
                {{-- En-tête du tableau --}}
                <thead class="bg-slate-50 text-slate-700 font-bold text-sm border-bottom">
                    <tr>
                        <th class="p-4">Type</th>
                        <th class="p-4">Zone</th>
                        <th class="p-4">Prix</th>
                        <th class="p-4">Statut</th>
                        <th class="p-4 text-end">Actions</th>
                    </tr>
                </thead>
                
                {{-- Contenu du tableau : boucle sur chaque annonce du bailleur --}}
                <tbody class="text-sm text-slate-600">
                    @foreach($properties as $property)
                        <tr class="border-bottom">
                            {{-- Colonne 1 : Type et usage de la propriété --}}
                            <td class="p-4 font-semibold text-slate-900">
                                {{ ucfirst($property->type) }} ({{ ucfirst($property->property_usage) }})
                            </td>
                            
                            {{-- Colonne 2 : Zone géographique --}}
                            <td class="p-4">{{ $property->zone }}</td>
                            
                            {{-- Colonne 3 : Prix formaté en FCFA --}}
                            <td class="p-4 font-bold text-blue-900">
                                {{ number_format($property->price, 0, ',', ' ') }} FCFA
                            </td>
                            
                            {{-- Colonne 4 : Statut avec code couleur (EF-C4) --}}
                            {{-- 
                                Badge de statut avec couleurs distinctes :
                                - Ambre (En attente) : en cours de validation
                                - Vert (Publiée) : visible aux clients
                                - Gris (Retirée) : plus visible
                            --}}
                            <td class="p-4">
                                @if($property->status === 'en attente')
                                    <span class="px-3 py-1 text-xs font-bold bg-amber-100 text-amber-800 rounded-full">
                                        En attente
                                    </span>
                                @elseif($property->status === 'publiée')
                                    <span class="px-3 py-1 text-xs font-bold bg-green-100 text-green-800 rounded-full">
                                        Publiée
                                    </span>
                                @elseif($property->status === 'retirée')
                                    <span class="px-3 py-1 text-xs font-bold bg-slate-100 text-slate-800 rounded-full">
                                        Retirée
                                    </span>
                                @endif
                            </td>
                            
                            {{-- Colonne 5 : Actions CRUD (EF-C3) --}}
                            <td class="p-4 text-end">
                                <div class="flex justify-end gap-2">
                                    {{-- Bouton "Voir" : ouvre la fiche complète de l'annonce --}}
                                    <a href="/properties/{{ $property->id }}" 
                                       class="btn btn-sm btn-light border rounded-xl px-3 text-slate-700 decoration-none">
                                        <i class="fas fa-eye me-1"></i> Voir
                                    </a>
                                    
                                    {{-- Bouton "Modifier" : permet d'éditer l'annonce (si non publiée) --}}
                                    <a href="/properties/{{ $property->id }}/edit" 
                                       class="btn btn-sm btn-outline-primary border rounded-xl px-3 decoration-none">
                                        <i class="fas fa-edit me-1"></i> Modifier
                                    </a>

                                    {{-- Bouton "Supprimer" : supprime définitivement l'annonce --}}
                                    {{-- 
                                        - Requête : DELETE /properties/{id}
                                        - Confirmation : Popup "Êtes-vous sûr ?"
                                        - Effet : Annonce supprimée de la base
                                    --}}
                                    <form action="/properties/{{ $property->id }}" 
                                          method="POST" 
                                          class="inline m-0" 
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette annonce ?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-outline-danger border rounded-xl px-3">
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