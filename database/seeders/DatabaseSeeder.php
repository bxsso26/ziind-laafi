<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

/**
 * DatabaseSeeder
 *
 * Seeder responsable de l'initialisation des données de test pour le projet Ziind Laafi.
 * Elle crée les 4 utilisateurs de base (manager, agent, bailleur, client) et
 * popule la base avec 15 propriétés de démonstration pour les tests et développement.
 *
 * Exécution :
 * php artisan db:seed
 * ou
 * php artisan migrate:fresh --seed (si vous voulez réinitialiser complètement la base)
 *
 * IMPORTANT : Les identifiants de test sont définis ici. En production, utilisez d'autres credentials !
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Exécute le seeding de la base de données.
     *
     * Processus :
     * 1. Création de 4 utilisateurs de test avec les 4 rôles du système (manager, agent, bailleur, client)
     * 2. Insertion de 15 propriétés de démonstration associées au bailleur
     *
     * Les utilisateurs sont créés avec updateOrCreate() pour éviter les doublons si
     * le seeder est exécuté plusieurs fois. Les propriétés sont insérées directement
     * via DB::table() pour une insertion en masse.
     *
     * @return void
     */
    public function run(): void
    {
        // ====================================================================
        // SECTION 1 : CRÉATION DES 4 UTILISATEURS DE TEST
        // ====================================================================
        
        /**
         * Manager Principal
         * Rôle : Administration globale du système
         * - Gestion des utilisateurs (création d'agents)
         * - Vue d'ensemble des propriétés et demandes de visite
         * - Modération et contrôle du système
         */
        $manager = User::updateOrCreate(
            ['email' => 'manager@ziindlaafi.com'],  // Clé unique pour éviter les doublons
            [
                'name'      => 'Manager Principal',
                'password'  => Hash::make('12345678'),              // Mot de passe hashé (à changer en prod)
                'telephone' => '70000000',
                'role'      => 'manager',
            ]
        );

        /**
         * Agent Immobilier
         * Rôle : Intermédiaire entre bailleurs et clients
         * - Gestion des propriétés des bailleurs
         * - Gestion des demandes de visite
         * - Suivi des locations/ventes
         */
        $agent = User::updateOrCreate(
            ['email' => 'agent@ziindlaafi.com'],
            [
                'name'      => 'Agent Immobilier',
                'password'  => Hash::make('12345678'),
                'telephone' => '71000000',
                'role'      => 'agent',
            ]
        );

        /**
         * Bailleur (Propriétaire)
         * Rôle : Propriétaire qui publie ses propriétés
         * - Création et gestion de ses propriétés
         * - Gestion des demandes de visite reçues
         * - Suivi des locations de ses biens
         */
        $bailleur = User::updateOrCreate(
            ['email' => 'bailleur@ziindlaafi.com'],
            [
                'name'      => 'Bailleur Test',
                'password'  => Hash::make('12345678'),
                'telephone' => '72000000',
                'role'      => 'bailleur',
            ]
        );

        /**
         * Client (Chercheur)
         * Rôle : Utilisateur cherchant une propriété
         * - Consultation du catalogue de propriétés
         * - Demandes de visite auprès des bailleurs
         * - Suivi de ses demandes
         */
        $client = User::updateOrCreate(
            ['email' => 'client@ziindlaafi.com'],
            [
                'name'      => 'Client Test',
                'password'  => Hash::make('12345678'),
                'telephone' => '73000000',
                'role'      => 'client',
                'agent_id'  => null,  // Client non assigné à un agent
            ]
        );

        // ====================================================================
        // SECTION 2 : INSERTION DE 15 PROPRIÉTÉS DE DÉMONSTRATION
        // ====================================================================
        // Toutes les propriétés sont associées au bailleur ($bailleur->id)
        // et ont le statut 'publiée' pour être visibles à tous les clients.
        // Les données couvrent les 5 zones principales de Ouagadougou et Bobo.

        DB::table('properties')->insert([
            // PROPRIÉTÉ 1 : Villa de luxe - Vente
            [
                'user_id'          => $bailleur->id,                    // Propriétaire
                'type'             => 'Villa',                          // Type d'immobile
                'property_usage'   => 'résidence',                      // Usage principal
                'contract_option'  => 'Vente',                          // Type de contrat
                'zone'             => 'Ouaga 2000',                     // Quartier
                'size'             => 450.00,                           // Surface en m²
                'price'            => 75000000.00,                      // Prix en CFA
                'description'      => 'Superbe villa F4 moderne avec piscine et jardin.',
                'status'           => 'publiée',                        // Visible aux clients
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            // PROPRIÉTÉ 2 : Appartement - Location
            [
                'user_id'          => $bailleur->id,
                'type'             => 'Appartement',
                'property_usage'   => 'résidence',
                'contract_option'  => 'Location',
                'zone'             => 'Somgandé',
                'size'             => 120.00,
                'price'            => 250000.00,                        // Loyer mensuel
                'description'      => 'Appartement de standing comprenant un salon lumineux, deux chambres climatisées et une cuisine équipée.',
                'status'           => 'publiée',
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            // PROPRIÉTÉ 3 : Terrain d'angle - Vente (commerce)
            [
                'user_id'          => $bailleur->id,
                'type'             => 'Terrain',
                'property_usage'   => 'commerce',                       // Destiné au commerce
                'contract_option'  => 'Vente',
                'zone'             => 'Saaba',
                'size'             => 300.00,
                'price'            => 12000000.00,
                'description'      => 'Parcelle d\'angle idéale pour investissement commercial.',
                'status'           => 'publiée',
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            // PROPRIÉTÉ 4 : Bureau - Location
            [
                'user_id'          => $bailleur->id,
                'type'             => 'Bureau',
                'property_usage'   => 'commerce',
                'contract_option'  => 'Location',
                'zone'             => 'Koulouba',
                'size'             => 85.00,
                'price'            => 450000.00,                        // Loyer mensuel
                'description'      => 'Local professionnel idéal pour cabinet de conseil ou startup.',
                'status'           => 'publiée',
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            // PROPRIÉTÉ 5 : Appartement meublé - Location
            [
                'user_id'          => $bailleur->id,
                'type'             => 'Appartement',
                'property_usage'   => 'résidence',
                'contract_option'  => 'Location',
                'zone'             => 'Patte d\'Oie',
                'size'             => 150.00,
                'price'            => 350000.00,
                'description'      => 'Bel appartement F3 meublé, proche de toutes commodités.',
                'status'           => 'publiée',
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            // PROPRIÉTÉ 6 : Terrain viabilisé - Vente
            [
                'user_id'          => $bailleur->id,
                'type'             => 'Terrain',
                'property_usage'   => 'résidence',
                'contract_option'  => 'Vente',
                'zone'             => 'Zinarié',
                'size'             => 500.00,
                'price'            => 4500000.00,
                'description'      => 'Grande parcelle viabilisée dans une zone résidentielle en plein essor.',
                'status'           => 'publiée',
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            // PROPRIÉTÉ 7 : Villa de luxe - Vente
            [
                'user_id'          => $bailleur->id,
                'type'             => 'Villa',
                'property_usage'   => 'résidence',
                'contract_option'  => 'Vente',
                'zone'             => 'Zone du Bois',
                'size'             => 600.00,
                'price'            => 140000000.00,
                'description'      => 'Propriété d\'exception avec piscine et grand jardin arboré.',
                'status'           => 'publiée',
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            // PROPRIÉTÉ 8 : Villa F4 - Location (Bobo)
            [
                'user_id'          => $bailleur->id,
                'type'             => 'Villa',
                'property_usage'   => 'résidence',
                'contract_option'  => 'Location',
                'zone'             => 'Bobo - Tounouma',
                'size'             => 350.00,
                'price'            => 180000.00,                        // Loyer mensuel
                'description'      => 'Maison basse F4 spacieuse avec grande terrasse et cour ombragée.',
                'status'           => 'publiée',
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            // PROPRIÉTÉ 9 : Immeuble (R+2) - Vente
            [
                'user_id'          => $bailleur->id,
                'type'             => 'Immeuble',
                'property_usage'   => 'bureau',                         // Vocation professionnelle
                'contract_option'  => 'Vente',
                'zone'             => 'Dassasgho',
                'size'             => 400.00,
                'price'            => 280000000.00,                     // Prix très élevé : immeuble de rapport
                'description'      => 'Immeuble R+2 comprenant 6 appartements déjà loués.',
                'status'           => 'publiée',
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            // PROPRIÉTÉ 10 : Mini-villa F2 - Location
            [
                'user_id'          => $bailleur->id,
                'type'             => 'Appartement',
                'property_usage'   => 'résidence',
                'contract_option'  => 'Location',
                'zone'             => 'Karpala',
                'size'             => 90.00,
                'price'            => 110000.00,
                'description'      => 'Mini-villa F2 récent, entièrement carrelé.',
                'status'           => 'publiée',
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            // PROPRIÉTÉ 11 : Magasin - Location (Bobo)
            [
                'user_id'          => $bailleur->id,
                'type'             => 'Magasin',
                'property_usage'   => 'commerce',
                'contract_option'  => 'Location',
                'zone'             => 'Bobo - Sya',
                'size'             => 65.00,
                'price'            => 150000.00,
                'description'      => 'Boutique idéalement située sur un axe principal très passant.',
                'status'           => 'publiée',
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            // PROPRIÉTÉ 12 : Terrain clôturé - Vente
            [
                'user_id'          => $bailleur->id,
                'type'             => 'Terrain',
                'property_usage'   => 'résidence',
                'contract_option'  => 'Vente',
                'zone'             => 'Tanghin',
                'size'             => 240.00,
                'price'            => 8500000.00,
                'description'      => 'Parcelle clôturée prête pour construction.',
                'status'           => 'publiée',
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            // PROPRIÉTÉ 13 : Villa F5 de standing - Location
            [
                'user_id'          => $bailleur->id,
                'type'             => 'Villa',
                'property_usage'   => 'résidence',
                'contract_option'  => 'Location',
                'zone'             => 'Ouaga 2000',
                'size'             => 380.00,
                'price'            => 600000.00,                        // Loyer mensuel élevé
                'description'      => 'Villa F5 de standing à louer, salon spacieux.',
                'status'           => 'publiée',
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            // PROPRIÉTÉ 14 : Espace de bureaux - Location
            [
                'user_id'          => $bailleur->id,
                'type'             => 'Bureau',
                'property_usage'   => 'bureau',
                'contract_option'  => 'Location',
                'zone'             => 'Gounghin',
                'size'             => 110.00,
                'price'            => 300000.00,
                'description'      => 'Espace de bureaux cloisonné avec salle de réunion.',
                'status'           => 'publiée',
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            // PROPRIÉTÉ 15 : Villa de vacances F3 - Vente
            [
                'user_id'          => $bailleur->id,
                'type'             => 'Villa',
                'property_usage'   => 'résidence',
                'contract_option'  => 'Vente',
                'zone'             => 'Loumbila',
                'size'             => 500.00,
                'price'            => 42000000.00,
                'description'      => 'Jolie villa de vacances F3 non loin de l\'échangeur.',
                'status'           => 'publiée',
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
        ]);
    }
}