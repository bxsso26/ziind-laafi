<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\VisitRequest;
use Illuminate\Http\Request;
use App\Models\Favorite;

/**
 * PropertyController
 *
 * Contrôleur central de la plateforme Ziind Laafi.
 * Gère toutes les opérations liées aux annonces immobilières :
 * - Consultation du catalogue public avec filtres
 * - Dépôt, modification et suppression d'annonces (bailleurs/agents)
 * - Demandes de visite et gestion par les agents
 * - Favoris des clients
 * - Tableau de bord agent
 */
class PropertyController extends Controller
{
    /**
     * Affiche le catalogue public des annonces disponibles.
     *
     * - Filtre uniquement les annonces au statut 'publiée'
     * - Applique les filtres multicritères (type, usage, option, zone)
     * - Trie par date de création décroissante (plus récentes en premier)
     *
     * @param  Request $request  Paramètres de filtrage passés via GET
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $properties = Property::where('status', 'publiée')
            ->filter($request->only(['type', 'usage', 'option', 'zone'])) // Scope de filtrage du modèle
            ->orderBy('created_at', 'desc')
            ->get();

        return view('properties.index', compact('properties'));
    }

    /**
     * Affiche le formulaire de dépôt d'une nouvelle annonce.
     *
     * - Vérifie que l'utilisateur est connecté
     * - Réserve l'accès aux rôles 'bailleur' et 'agent' uniquement
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        // Redirection vers la page de connexion si non authentifié
        if (!auth()->check()) {
            return redirect()->route('auth.page')->with('error', 'Connectez-vous d\'abord.');
        }

        // Seuls les bailleurs et agents peuvent déposer une annonce
        if (!in_array(auth()->user()->role, ['bailleur', 'agent'])) {
            abort(403, 'Action non autorisée.');
        }

        return view('properties.create');
    }

    /**
     * Traite et enregistre une nouvelle annonce immobilière.
     *
     * - Valide tous les champs du formulaire
     * - Stocke la photo dans le disque public (dossier uploads/)
     * - Définit le statut selon le rôle : 'publiée' pour un agent, 'en attente' pour un bailleur
     * - Redirige vers l'espace bailleur avec un message adapté au rôle
     *
     * @param  Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validation des champs obligatoires et de l'image
        $validated = $request->validate([
            'type'            => 'required|string',
            'property_usage'  => 'required|string',
            'contract_option' => 'required|string',
            'zone'            => 'required|string|max:100',
            'size'            => 'required|numeric|min:1',
            'price'           => 'required|numeric|min:1',
            'description'     => 'nullable|string',
            'photo'           => 'required|image|mimes:jpeg,png,jpg|max:2048', // Max 2 Mo
        ]);

        // Stockage physique de l'image dans storage/app/public/uploads/
        $path = $request->file('photo')->store('uploads', 'public');

        // Un agent publie directement, un bailleur attend la validation
        $status = (auth()->user()->role === 'agent') ? 'publiée' : 'en attente';

        // Création de l'annonce en base de données
        Property::create([
            'user_id'         => auth()->id(),
            'type'            => $validated['type'],
            'property_usage'  => $validated['property_usage'],
            'contract_option' => $validated['contract_option'],
            'zone'            => $validated['zone'],
            'size'            => $validated['size'],
            'price'           => $validated['price'],
            'description'     => $validated['description'],
            'photo_path'      => $path,   // Chemin relatif vers l'image stockée
            'status'          => $status,
        ]);

        // Message de confirmation adapté au rôle de l'utilisateur
        $message = (auth()->user()->role === 'agent')
            ? 'Annonce publiée avec succès !'
            : 'Votre annonce a été soumise et est en attente de validation par un agent.';

        return redirect()->route('bailleur.index')->with('success', $message);
    }

    /**
     * Affiche les annonces déposées par le bailleur ou l'agent connecté.
     *
     * - Réservé aux rôles 'bailleur' et 'agent'
     * - Filtre par user_id pour n'afficher que ses propres annonces
     *
     * @return \Illuminate\View\View
     */
    public function mesAnnonces()
    {
        // Sécurité : accès réservé aux bailleurs et agents connectés
        if (!auth()->check() || !in_array(auth()->user()->role, ['bailleur', 'agent'])) {
            abort(403, 'Accès réservé aux bailleurs et aux agents.');
        }

        // Récupère uniquement les annonces créées par cet utilisateur
        $properties = Property::where('user_id', auth()->id())
                               ->orderBy('created_at', 'desc')
                               ->get();

        return view('bailleur.index', compact('properties'));
    }

    /**
     * Affiche la fiche détaillée d'une annonce immobilière.
     *
     * @param  int $id  Identifiant de l'annonce
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $property = Property::findOrFail($id);
        return view('properties.show', compact('property'));
    }

    /**
     * Affiche la page d'authentification (connexion / inscription).
     *
     * @return \Illuminate\View\View
     */
    public function showAuthPage()
    {
        return view('auth');
    }

    /**
     * Enregistre une demande de visite pour une annonce.
     *
     * - Réservé aux clients connectés uniquement
     * - Valide la date (doit être dans le futur)
     * - Crée la demande avec le statut 'en attente'
     *
     * @param  Request $request
     * @param  int     $id  Identifiant de l'annonce concernée
     * @return \Illuminate\Http\RedirectResponse
     */
    public function visit(Request $request, $id)
    {
        // Seul un client connecté peut soumettre une demande de visite
        if (!auth()->check() || auth()->user()->role !== 'client') {
            return back()->with('error', 'Seuls les clients connectés peuvent effectuer une demande de visite.');
        }

        $request->validate([
            'visit_date' => 'required|date|after:today', // La date doit être dans le futur
            'message'    => 'nullable|string|max:500',
        ]);

        // Création de la demande de visite en base
        VisitRequest::create([
            'user_id'     => auth()->id(),
            'property_id' => $id,
            'visit_date'  => $request->visit_date,
            'message'     => $request->message,
            'status'      => 'en attente', // Statut initial avant traitement par un agent
        ]);

        return back()->with('success', 'Votre demande de visite a bien été transmise à l\'agent responsable !');
    }

    /**
     * Affiche l'historique des demandes de visite du client connecté.
     *
     * - Réservé aux clients uniquement
     * - Charge les informations de la propriété liée à chaque demande
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function mesVisites()
    {
        // Sécurité : accès réservé aux clients connectés
        if (!auth()->check() || auth()->user()->role !== 'client') {
            return redirect()->route('auth.page')->with('error', 'Veuillez vous connecter en tant que client.');
        }

        // Charge les demandes avec les détails de la propriété associée (eager loading)
        $visites = VisitRequest::with('property')
                    ->where('user_id', auth()->id())
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('client.visites', compact('visites'));
    }

    /**
     * Supprime définitivement une annonce du bailleur connecté.
     *
     * - Vérifie que l'annonce appartient bien à l'utilisateur connecté
     * - Suppression physique de l'enregistrement en base
     *
     * @param  int $id  Identifiant de l'annonce à supprimer
     * @return \Illuminate\Http\RedirectResponse
     */
    public function retirer($id)
    {
        $property = Property::findOrFail($id);

        // Sécurité : seul le propriétaire de l'annonce peut la supprimer
        if ($property->user_id !== auth()->id()) {
            abort(403, 'Action non autorisée.');
        }

        $property->delete();

        return back()->with('success', 'Votre annonce a été supprimée avec succès !');
    }

    /**
     * Affiche le formulaire de modification d'une annonce existante.
     *
     * - Vérifie que l'annonce appartient bien à l'utilisateur connecté
     *
     * @param  int $id  Identifiant de l'annonce à modifier
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $property = Property::findOrFail($id);

        // Sécurité : seul le propriétaire peut accéder au formulaire d'édition
        if ($property->user_id !== auth()->id()) {
            abort(403, 'Action non autorisée.');
        }

        return view('properties.edit', compact('property'));
    }

    /**
     * Met à jour une annonce immobilière existante.
     *
     * - Vérifie que l'annonce appartient à l'utilisateur connecté
     * - Met à jour la photo uniquement si une nouvelle est fournie
     * - Conserve l'ancienne photo si aucun nouveau fichier n'est uploadé
     * - Repasse l'annonce en statut 'en attente' après modification (re-validation requise)
     *
     * @param  Request $request
     * @param  int     $id  Identifiant de l'annonce à mettre à jour
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $property = Property::findOrFail($id);

        // Sécurité : seul le propriétaire de l'annonce peut la modifier
        if ($property->user_id !== auth()->id()) {
            abort(403, 'Action non autorisée.');
        }

        $validated = $request->validate([
            'type'            => 'required|string',
            'property_usage'  => 'required|string',
            'contract_option' => 'required|string',
            'zone'            => 'required|string',
            'size'            => 'required|numeric',
            'price'           => 'required|numeric',
            'description'     => 'required|string',
            'photo'           => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Optionnel lors d'une modification
        ]);

        // Nouvelle photo uploadée : on la stocke et on met à jour le chemin
        if ($request->hasFile('photo')) {
            $validated['photo_path'] = $request->file('photo')->store('uploads', 'public');
        }

        $property->update([
            'type'            => $validated['type'],
            'property_usage'  => $validated['property_usage'],
            'contract_option' => $validated['contract_option'],
            'zone'            => $validated['zone'],
            'size'            => $validated['size'],
            'price'           => $validated['price'],
            'description'     => $validated['description'],
            // Utilise la nouvelle photo si fournie, sinon conserve l'ancienne
            'photo_path'      => $validated['photo_path'] ?? $property->photo_path,
            // L'annonce repasse en attente de validation après toute modification
            'status'          => 'en attente',
        ]);

        return redirect()->route('bailleur.index')
                         ->with('success', 'Annonce modifiée et en attente de validation !');
    }

    /**
     * Affiche le tableau de bord de l'agent immobilier.
     *
     * - Réservé aux agents uniquement
     * - Liste les annonces en attente de validation
     * - Liste les demandes de visite en attente de traitement
     * - Liste les clients affectés à cet agent
     *
     * @return \Illuminate\View\View
     */
    public function agentDashboard()
    {
        // Sécurité : accès réservé aux agents
        if (auth()->user()->role !== 'agent') {
            abort(403);
        }

        // Annonces soumises par les bailleurs, en attente de validation
        $pendingProperties = Property::where('status', 'en attente')
                                     ->orderBy('created_at', 'desc')
                                     ->get();

        // Demandes de visite en attente, avec les infos du client et de la propriété
        $pendingVisits = VisitRequest::with(['user', 'property'])
                                     ->where('status', 'en attente')
                                     ->orderBy('created_at', 'desc')
                                     ->get();

        // Clients affectés à cet agent spécifiquement
        $clients = \App\Models\User::where('agent_id', auth()->id())
                                   ->where('role', 'client')
                                   ->orderBy('name')
                                   ->get();

        return view('agent.dashboard', compact('pendingProperties', 'pendingVisits', 'clients'));
    }

    /**
     * Valide une annonce soumise par un bailleur.
     *
     * - Réservé aux agents uniquement
     * - Passe le statut de l'annonce à 'publiée' (visible dans le catalogue public)
     *
     * @param  int $id  Identifiant de l'annonce à valider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function validate($id)
    {
        if (auth()->user()->role !== 'agent') {
            abort(403);
        }

        $property = Property::findOrFail($id);
        $property->update(['status' => 'publiée']); // L'annonce devient visible publiquement

        return back()->with('success', 'L\'annonce a été validée et est maintenant publique.');
    }

    /**
     * Refuse et retire une annonce soumise par un bailleur.
     *
     * - Réservé aux agents uniquement
     * - Passe le statut à 'retirée' sans supprimer l'enregistrement
     *
     * @param  int $id  Identifiant de l'annonce à refuser
     * @return \Illuminate\Http\RedirectResponse
     */
    public function retirerannonce($id)
    {
        if (auth()->user()->role !== 'agent') {
            abort(403);
        }

        $property = Property::findOrFail($id);
        $property->update(['status' => 'retirée']);

        return back()->with('success', 'L\'annonce a été refusée.');
    }

    /**
     * Valide une demande de visite soumise par un client.
     *
     * - Réservé aux agents uniquement
     * - Passe le statut de la demande à 'validée'
     *
     * @param  int $id  Identifiant de la demande de visite
     * @return \Illuminate\Http\RedirectResponse
     */
    public function validateVisit($id)
    {
        if (auth()->user()->role !== 'agent') {
            abort(403);
        }

        $visit = VisitRequest::findOrFail($id);
        $visit->update(['status' => 'validée']);

        return back()->with('success', 'Visite validée avec succès.');
    }

    /**
     * Refuse une demande de visite soumise par un client.
     *
     * - Réservé aux agents uniquement
     * - Passe le statut de la demande à 'refusée'
     *
     * @param  int $id  Identifiant de la demande de visite
     * @return \Illuminate\Http\RedirectResponse
     */
    public function rejectVisit($id)
    {
        if (auth()->user()->role !== 'agent') {
            abort(403);
        }

        $visit = VisitRequest::findOrFail($id);
        $visit->update(['status' => 'refusée']);

        return back()->with('success', 'Visite refusée.');
    }

    /**
     * Ajoute ou retire une annonce des favoris du client connecté.
     *
     * - Réservé aux clients uniquement
     * - Si l'annonce est déjà en favori : la retire (toggle)
     * - Si l'annonce n'est pas en favori : l'ajoute
     *
     * @param  int $id  Identifiant de l'annonce
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleFavorite($id)
    {
        // Sécurité : seuls les clients peuvent gérer leurs favoris
        if (!auth()->check() || auth()->user()->role !== 'client') {
            return back()->with('error', 'Seuls les clients peuvent ajouter des favoris.');
        }

        // Vérifie si l'annonce est déjà dans les favoris du client
        $existing = Favorite::where('user_id', auth()->id())
                            ->where('property_id', $id)
                            ->first();

        if ($existing) {
            // L'annonce est déjà en favori : on la retire
            $existing->delete();
            return back()->with('success', 'Retiré de vos favoris.');
        } else {
            // L'annonce n'est pas encore en favori : on l'ajoute
            Favorite::create([
                'user_id'     => auth()->id(),
                'property_id' => $id,
            ]);
            return back()->with('success', 'Ajouté à vos favoris !');
        }
    }

    /**
     * Affiche la liste des annonces mises en favori par le client connecté.
     *
     * - Réservé aux clients uniquement
     * - Charge les détails de chaque annonce favorite via eager loading
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function mesFavoris()
    {
        // Sécurité : accès réservé aux clients connectés
        if (!auth()->check() || auth()->user()->role !== 'client') {
            return redirect()->route('auth.page');
        }

        // Charge les favoris avec les infos de chaque annonce associée
        $favoris = Favorite::with('property')
                    ->where('user_id', auth()->id())
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('client.favoris', compact('favoris'));
    }
}