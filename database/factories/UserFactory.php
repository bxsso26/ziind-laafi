<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * UserFactory
 *
 * Factory responsable de la génération de données fictives pour les tests et le seeding.
 * Elle crée des utilisateurs avec des données aléatoires mais réalistes.
 * 
 * Utilisation :
 * - User::factory()->create() : crée un utilisateur avec les paramètres par défaut
 * - User::factory()->withRole('bailleur')->create() : crée un utilisateur avec le rôle spécifié
 * - User::factory(50)->create() : crée 50 utilisateurs
 *
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * Stocke le mot de passe par défaut utilisé par la factory.
     * Permet une réutilisation cohérente du même mot de passe lors de la génération.
     *
     * @var ?string
     */
    protected static ?string $password;

    /**
     * Définit l'état par défaut d'un utilisateur généré.
     *
     * Génère des données aléatoires mais réalistes :
     * - Nom et email uniques via le faker
     * - Numéro de téléphone commençant par '7' (format BF - Burkina Faso)
     * - Rôle par défaut 'client' (peut être changé via withRole())
     * - Email vérifié par défaut (email_verified_at défini à maintenant)
     * - Mot de passe hashé : 'password' (pour faciliter les tests)
     * - Token de "se souvenir de moi" aléatoire pour les sessions persistantes
     *
     * @return array<string, mixed> Tableau contenant tous les attributs de l'utilisateur
     */
    public function definition(): array
    {
        return [
            'name'              => fake()->name(),                              // Génère un nom aléatoire
            'email'             => fake()->unique()->safeEmail(),              // Email unique et sûr
            'telephone'         => '7' . fake()->numerify('########'),          // Format téléphone BF : 7XXXXXXXX
            'role'              => 'client',                                    // Rôle par défaut (client)
            'email_verified_at' => now(),                                       // Email considéré comme vérifié
            'password'          => Hash::make('password'),                      // Mot de passe hashé (pour les tests)
            'remember_token'    => Str::random(10),                            // Token pour "se souvenir de moi"
        ];
    }

    /**
     * Crée un utilisateur avec email non vérifié.
     *
     * Utilisé pour tester les workflows d'authentification et de vérification d'email.
     * Modifie l'attribut email_verified_at en null pour simuler un email en attente de vérification.
     *
     * @return static
     * 
     * Exemple d'utilisation :
     * User::factory()->unverified()->create()
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,  // Email non vérifié
        ]);
    }

    /**
     * Crée un utilisateur avec un rôle spécifique.
     *
     * Permet de générer des utilisateurs avec les 4 rôles du système :
     * - 'client'     : accès au catalogue de propriétés et demandes de visite
     * - 'bailleur'   : gestion de ses propriétés et locations
     * - 'agent'      : gestion des propriétés pour les bailleurs
     * - 'manager'    : administration globale du système
     *
     * @param  string $role Le rôle à assigner à l'utilisateur
     * @return static
     * 
     * Exemple d'utilisation :
     * - User::factory()->withRole('bailleur')->create()
     * - User::factory(10)->withRole('agent')->create()
     */
    public function withRole(string $role): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => $role,  // Assigne le rôle spécifié à l'utilisateur
        ]);
    }
}