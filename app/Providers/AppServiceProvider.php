<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

/**
 * AppServiceProvider
 *
 * Fournisseur de services principal de l'application.
 * C'est ici que sont enregistrés et initialisés les services
 * globaux nécessaires au démarrage de l'application Laravel.
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Enregistre les services de l'application dans le conteneur IoC.
     * Utilisé pour lier des interfaces à leurs implémentations concrètes.
     *
     * @return void
     */
    public function register(): void
    {
        // Aucun service personnalisé à enregistrer pour le moment
    }

    /**
     * Initialise les services après le chargement de tous les providers.
     *
     * - Fixe la longueur maximale des colonnes de type string à 191 caractères
     *   pour assurer la compatibilité avec MySQL/MariaDB et l'encodage utf8mb4
     *   (évite l'erreur 1071 : "Specified key was too long")
     *
     * @return void
     */
    public function boot(): void
    {
        // Limite globale de longueur pour les colonnes string des migrations
        // Nécessaire avec l'encodage utf8mb4 sur certaines versions de MySQL
        Schema::defaultStringLength(191);
    }
}