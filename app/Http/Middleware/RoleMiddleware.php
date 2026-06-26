<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * RoleMiddleware
 *
 * Middleware de contrôle d'accès basé sur le rôle de l'utilisateur.
 * Protège les routes en vérifiant que l'utilisateur connecté possède
 * l'un des rôles autorisés passés en paramètre.
 *
 * Utilisation dans les routes :
 *   Route::middleware(['role:agent,manager'])->group(...)
 */
class RoleMiddleware
{
    /**
     * Intercepte la requête et vérifie les droits d'accès.
     *
     * - Redirige vers la page de connexion si l'utilisateur n'est pas authentifié
     * - Bloque avec une erreur 403 si le rôle ne correspond pas aux rôles autorisés
     * - Laisse passer la requête si tout est valide
     *
     * @param  Request  $request   La requête HTTP entrante
     * @param  Closure  $next      Le prochain middleware ou contrôleur à appeler
     * @param  string[] ...$roles  Les rôles autorisés à accéder à la route
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        // Vérifie que l'utilisateur est bien connecté
        if (!auth()->check()) {
            return redirect()->route('auth.page');
        }

        // Vérifie que le rôle de l'utilisateur est dans la liste des rôles autorisés
        if (!in_array(auth()->user()->role, $roles)) {
            abort(403, 'Accès non autorisé.');
        }

        // Tout est valide : on laisse passer la requête
        return $next($request);
    }
}