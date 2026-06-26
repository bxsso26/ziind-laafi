<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Property;
use App\Models\Visit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * ManagerUserController
 *
 * Contrôleur réservé au manager (administrateur de la plateforme Ziind Laafi).
 * Il gère :
 * - Le CRUD complet des utilisateurs (clients, bailleurs, agents)
 * - L'affectation des clients à un agent responsable
 * - Le tableau de bord avec les statistiques globales de la plateforme
 * - La modération des annonces immobilières
 */
class ManagerUserController extends Controller
{
    /**
     * Affiche la liste paginée de tous les utilisateurs gérables.
     * Exclut les managers pour éviter toute modification accidentelle
     * des comptes administrateurs.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Récupère uniquement les clients, bailleurs et agents (pas les managers)
        $users = User::whereIn('role', ['client', 'bailleur', 'agent'])->paginate(15);
        return view('managers.users.index', compact('users'));
    }

    /**
     * Affiche le formulaire de création d'un nouvel utilisateur.
     * Accessible uniquement par le manager.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('managers.users.create');
    }

    /**
     * Enregistre un nouvel utilisateur en base de données.
     *
     * - Valide les champs du formulaire
     * - Autorise uniquement les rôles client, bailleur et agent
     * - Hache le mot de passe avant stockage
     *
     * @param  Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validation des données avec contraintes de sécurité
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users', // Email unique en base
            'telephone' => 'required|string|max:20',
            'role'      => 'required|string|in:client,bailleur,agent',   // Rôles autorisés uniquement
            'password'  => 'required|string|min:8',
        ]);

        // Création de l'utilisateur avec mot de passe haché
        User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'telephone' => $validated['telephone'],
            'role'      => $validated['role'],
            'password'  => Hash::make($validated['password']), // Hachage sécurisé (bcrypt)
        ]);

        return redirect()->route('manager.users.index')
                         ->with('success', 'Utilisateur créé avec succès !');
    }

    /**
     * Affiche le formulaire de modification d'un utilisateur existant.
     *
     * - Interdit la modification d'un compte manager (protection critique)
     * - Fournit la liste des agents disponibles pour l'affectation d'un client
     *
     * @param  int $id  Identifiant de l'utilisateur à modifier
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);

        // Sécurité : un manager ne peut pas modifier un autre compte manager
        if ($user->role === 'manager') {
            return redirect()->route('managers.users.index')
                             ->with('error', 'Action non autorisée.');
        }

        // Récupère la liste des agents pour permettre l'affectation d'un client à un agent
        $agents = User::where('role', 'agent')->get();

        // Passe l'utilisateur et la liste des agents à la vue d'édition
        return view('managers.users.edit', compact('user', 'agents'));
    }

    /**
     * Met à jour les informations d'un utilisateur existant.
     *
     * - Interdit la modification d'un compte manager
     * - Permet de changer le rôle, les coordonnées et le mot de passe
     * - Permet d'affecter un agent responsable à un client
     * - Le mot de passe n'est mis à jour que s'il est fourni (champ optionnel)
     *
     * @param  Request $request
     * @param  int     $id  Identifiant de l'utilisateur à mettre à jour
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Sécurité : empêche toute modification d'un compte manager
        if ($user->role === 'manager') {
            return redirect()->route('managers.users.index')
                             ->with('error', 'Action non autorisée.');
        }

        // Validation des champs modifiables
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users,email,' . $id, // Ignore l'email actuel de cet utilisateur
            'telephone' => 'required|string|max:20',
            'role'      => 'required|string|in:client,bailleur,agent',
            'password'  => 'nullable|string|min:8',       // Optionnel : seulement si on veut changer le mot de passe
            'agent_id'  => 'nullable|exists:users,id',    // L'agent affecté doit exister en base
        ]);

        // Mise à jour des informations de base
        $user->name      = $validated['name'];
        $user->email     = $validated['email'];
        $user->telephone = $validated['telephone'];
        $user->role      = $validated['role'];

        // Affectation d'un agent responsable uniquement pour les clients
        if ($user->role === 'client') {
            // Si aucun agent sélectionné, on désaffecte (null)
            $user->agent_id = $validated['agent_id'] ?? null;
        }

        // Mise à jour du mot de passe uniquement si un nouveau est fourni
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('manager.users.index')
                         ->with('success', 'L\'utilisateur et son affectation ont bien été mis à jour !');
    }

    /**
     * Supprime définitivement un utilisateur de la base de données.
     *
     * @param  int $id  Identifiant de l'utilisateur à supprimer
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('manager.users.index')
                         ->with('success', 'Utilisateur supprimé avec succès !');
    }

    /**
     * Affiche le tableau de bord statistique du manager.
     *
     * Fournit une vue globale de la plateforme :
     * - Répartition des annonces par type de bien
     * - Nombre d'annonces en attente de validation
     * - Évolution mensuelle des demandes de visite sur l'année en cours
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        // Sécurité : accès réservé exclusivement au manager
        if (auth()->user()->role !== 'manager') {
            abort(403, 'Action non autorisée.');
        }

        // Statistique 1 : Nombre total d'annonces groupées par type de bien (Villa, Terrain, etc.)
        $propertiesByType = Property::select('type', DB::raw('count(*) as total'))
                                    ->groupBy('type')
                                    ->get();

        // Statistique 2 : Nombre d'annonces en attente de validation par un agent
        $pendingPropertiesCount = Property::where('status', 'en attente')->count();

        // Statistique 3 : Nombre de demandes de visite par mois sur l'année en cours
        // Utilise la table 'visit_requests' (nom exact en base de données)
        $visitsByMonth = DB::table('visit_requests')
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', 2026)   // Filtre sur l'année en cours
            ->groupBy('month')
            ->orderBy('month')               // Tri chronologique
            ->get();

        return view('managers.users.dashboard', compact(
            'propertiesByType',
            'pendingPropertiesCount',
            'visitsByMonth'
        ));
    }

    /**
     * Retire (masque) une annonce immobilière de la plateforme.
     *
     * Change le statut de l'annonce à 'retirée' sans la supprimer définitivement.
     * Accessible uniquement par le manager.
     *
     * @param  int $id  Identifiant de l'annonce à retirer
     * @return \Illuminate\Http\RedirectResponse
     */
    public function retirer($id)
    {
        // Sécurité : seul le manager peut retirer une annonce
        if (auth()->user()->role !== 'manager') {
            abort(403);
        }

        $property = Property::findOrFail($id);

        // Passage du statut à 'retirée' (soft-disable, pas de suppression)
        $property->update(['status' => 'retirée']);

        return back()->with('success', 'Annonce retirée avec succès.');
    }

    /**
     * Affiche la liste paginée de toutes les annonces immobilières.
     * Permet au manager de superviser l'ensemble du catalogue.
     *
     * @return \Illuminate\View\View
     */
    public function manageIndex()
    {
        // Récupère toutes les annonces, triées par date de création décroissante
        $properties = Property::orderBy('created_at', 'desc')->paginate(20);
        return view('managers.properties.index', compact('properties'));
    }
}