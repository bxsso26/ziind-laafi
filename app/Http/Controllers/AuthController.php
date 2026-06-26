<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * AuthController
 *
 * Contrôleur responsable de la gestion de l'authentification des utilisateurs :
 * connexion, inscription et déconnexion.
 * Il gère également la redirection dynamique selon le rôle de l'utilisateur connecté.
 */
class AuthController extends Controller
{
    /**
     * Traite la tentative de connexion d'un utilisateur.
     *
     * - Valide les champs email et mot de passe
     * - Tente l'authentification via Auth::attempt()
     * - Régénère la session pour prévenir les attaques de fixation de session
     * - Redirige l'utilisateur vers son espace selon son rôle (manager, agent, bailleur, client)
     * - Retourne une erreur si les identifiants sont incorrects
     *
     * @param  Request $request  Les données du formulaire de connexion
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // Validation des champs requis pour la connexion
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // Tentative d'authentification avec les identifiants fournis
        if (Auth::attempt($credentials)) {

            // Régénération de l'ID de session pour sécuriser contre la fixation de session
            $request->session()->regenerate();

            // Redirection dynamique selon le rôle de l'utilisateur connecté
            return match(Auth::user()->role) {
                'manager'  => redirect()->route('manager.dashboard'),  // Espace administrateur
                'agent'    => redirect()->route('agent.dashboard'),    // Espace agent immobilier
                'bailleur' => redirect()->route('bailleur.index'),     // Espace propriétaire bailleur
                default    => redirect()->route('properties.index')    // Espace client (catalogue)
                                ->with('success', 'Connexion réussie !'),
            };
        }

        // Échec de l'authentification : retour avec message d'erreur sur l'email uniquement
        return back()
            ->withErrors(['email' => 'Identifiants incorrects.'])
            ->onlyInput('email'); // Conserve l'email saisi mais pas le mot de passe
    }

    /**
     * Traite l'inscription d'un nouvel utilisateur.
     *
     * - Valide tous les champs du formulaire d'inscription
     * - Restreint les rôles autorisés à 'client' et 'bailleur' (les rôles 'agent' et
     *   'manager' ne peuvent pas s'auto-inscrire, ils sont créés par un administrateur)
     * - Hache le mot de passe avant stockage
     * - Connecte automatiquement l'utilisateur après inscription
     * - Redirige vers le catalogue de propriétés
     *
     * @param  Request $request  Les données du formulaire d'inscription
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        // Validation des données d'inscription avec règles de sécurité
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users', // L'email doit être unique en base
            'telephone' => 'required|string|max:20',                     // Numéro de téléphone obligatoire
            'password'  => 'required|string|min:8|confirmed',            // Minimum 8 caractères, confirmé
            // Sécurité : seuls les rôles 'client' et 'bailleur' sont autorisés via l'inscription publique.
            // Les rôles 'agent' et 'manager' sont créés manuellement par l'administrateur.
            'role'      => 'required|in:client,bailleur',
        ]);

        // Création du nouvel utilisateur en base de données
        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'telephone' => $request->telephone,
            'role'      => $request->role,
            'password'  => Hash::make($request->password), // Hachage sécurisé du mot de passe (bcrypt)
        ]);

        // Connexion automatique de l'utilisateur après inscription réussie
        Auth::login($user);

        // Redirection vers le catalogue avec message de confirmation
        return redirect()->route('properties.index')->with('success', 'Compte créé !');
    }

    /**
     * Déconnecte l'utilisateur actuellement authentifié.
     *
     * - Invalide la session en cours
     * - Régénère le token CSRF pour sécuriser les futures requêtes
     * - Redirige vers le catalogue public avec un message de confirmation
     *
     * @param  Request $request  La requête HTTP en cours
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        // Déconnexion de l'utilisateur
        Auth::logout();

        // Invalidation complète de la session pour effacer toutes les données
        $request->session()->invalidate();

        // Régénération du token CSRF pour sécuriser les prochaines requêtes
        $request->session()->regenerateToken();

        // Redirection vers le catalogue avec message de confirmation
        return redirect()->route('properties.index')->with('success', 'Déconnexion réussie.');
    }
}