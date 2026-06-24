<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Création des utilisateurs
        $manager = User::updateOrCreate(
            ['email' => 'manager@ziindlaafi.com'],
            [
                'name' => 'Manager Principal',
                'password' => Hash::make('12345678'),
                'telephone' => '70000000',
                'role' => 'manager',
            ]
        );

        $agent = User::updateOrCreate(
            ['email' => 'agent@ziindlaafi.com'],
            [
                'name' => 'Agent Immobilier',
                'password' => Hash::make('12345678'),
                'telephone' => '71000000',
                'role' => 'agent',
            ]
        );

        $bailleur = User::updateOrCreate(
            ['email' => 'bailleur@ziindlaafi.com'],
            [
                'name' => 'Bailleur Test',
                'password' => Hash::make('12345678'),
                'telephone' => '72000000',
                'role' => 'bailleur',
            ]
        );

        $client = User::updateOrCreate(
            ['email' => 'client@ziindlaafi.com'],
            [
                'name' => 'Client Test',
                'password' => Hash::make('12345678'),
                'telephone' => '73000000',
                'role' => 'client',
                'agent_id' => null,
            ]
        );

        // 2. Insertion des propriétés avec user_id et statut en minuscule
        DB::table('properties')->insert([
            [
                'user_id' => $bailleur->id,
                'type' => 'Villa',
                'property_usage' => 'résidence',
                'contract_option' => 'Vente',
                'zone' => 'Ouaga 2000',
                'size' => 450.00,
                'price' => 75000000.00,
                'description' => 'Superbe villa F4 moderne avec piscine et jardin.',
                'status' => 'publiée',
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'user_id' => $bailleur->id,
                'type' => 'Appartement',
                'property_usage' => 'résidence',
                'contract_option' => 'Location',
                'zone' => 'Somgandé',
                'size' => 120.00,
                'price' => 250000.00,
                'description' => 'Appartement de standing comprenant un salon lumineux, deux chambres climatisées et une cuisine équipée.',
                'status' => 'publiée',
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'user_id' => $bailleur->id,
                'type' => 'Terrain',
                'property_usage' => 'commerce',
                'contract_option' => 'Vente',
                'zone' => 'Saaba',
                'size' => 300.00,
                'price' => 12000000.00,
                'description' => 'Parcelle d\'angle idéale pour investissement commercial.',
                'status' => 'publiée',
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'user_id' => $bailleur->id,
                'type' => 'Bureau',
                'property_usage' => 'commerce',
                'contract_option' => 'Location',
                'zone' => 'Koulouba',
                'size' => 85.00,
                'price' => 450000.00,
                'description' => 'Local professionnel idéal pour cabinet de conseil ou startup.',
                'status' => 'publiée',
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'user_id' => $bailleur->id,
                'type' => 'Appartement',
                'property_usage' => 'résidence',
                'contract_option' => 'Location',
                'zone' => 'Patte d\'Oie',
                'size' => 150.00,
                'price' => 350000.00,
                'description' => 'Bel appartement F3 meublé, proche de toutes commodités.',
                'status' => 'publiée',
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'user_id' => $bailleur->id,
                'type' => 'Terrain',
                'property_usage' => 'résidence',
                'contract_option' => 'Vente',
                'zone' => 'Zinarié',
                'size' => 500.00,
                'price' => 4500000.00,
                'description' => 'Grande parcelle viabilisée dans une zone résidentielle en plein essor.',
                'status' => 'publiée',
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'user_id' => $bailleur->id,
                'type' => 'Villa',
                'property_usage' => 'résidence',
                'contract_option' => 'Vente',
                'zone' => 'Zone du Bois',
                'size' => 600.00,
                'price' => 140000000.00,
                'description' => 'Propriété d\'exception avec piscine et grand jardin arboré.',
                'status' => 'publiée',
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'user_id' => $bailleur->id,
                'type' => 'Villa',
                'property_usage' => 'résidence',
                'contract_option' => 'Location',
                'zone' => 'Bobo - Tounouma',
                'size' => 350.00,
                'price' => 180000.00,
                'description' => 'Maison basse F4 spacieuse avec grande terrasse et cour ombragée.',
                'status' => 'publiée',
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'user_id' => $bailleur->id,
                'type' => 'Immeuble',
                'property_usage' => 'bureau',
                'contract_option' => 'Vente',
                'zone' => 'Dassasgho',
                'size' => 400.00,
                'price' => 280000000.00,
                'description' => 'Immeuble R+2 comprenant 6 appartements déjà loués.',
                'status' => 'publiée',
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'user_id' => $bailleur->id,
                'type' => 'Appartement',
                'property_usage' => 'résidence',
                'contract_option' => 'Location',
                'zone' => 'Karpala',
                'size' => 90.00,
                'price' => 110000.00,
                'description' => 'Mini-villa F2 récent, entièrement carrelé.',
                'status' => 'publiée',
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'user_id' => $bailleur->id,
                'type' => 'Magasin',
                'property_usage' => 'commerce',
                'contract_option' => 'Location',
                'zone' => 'Bobo - Sya',
                'size' => 65.00,
                'price' => 150000.00,
                'description' => 'Boutique idéalement située sur un axe principal très passant.',
                'status' => 'publiée',
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'user_id' => $bailleur->id,
                'type' => 'Terrain',
                'property_usage' => 'résidence',
                'contract_option' => 'Vente',
                'zone' => 'Tanghin',
                'size' => 240.00,
                'price' => 8500000.00,
                'description' => 'Parcelle clôturée prête pour construction.',
                'status' => 'publiée',
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'user_id' => $bailleur->id,
                'type' => 'Villa',
                'property_usage' => 'résidence',
                'contract_option' => 'Location',
                'zone' => 'Ouaga 2000',
                'size' => 380.00,
                'price' => 600000.00,
                'description' => 'Villa F5 de standing à louer, salon spacieux.',
                'status' => 'publiée',
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'user_id' => $bailleur->id,
                'type' => 'Bureau',
                'property_usage' => 'bureau',
                'contract_option' => 'Location',
                'zone' => 'Gounghin',
                'size' => 110.00,
                'price' => 300000.00,
                'description' => 'Espace de bureaux cloisonné avec salle de réunion.',
                'status' => 'publiée',
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'user_id' => $bailleur->id,
                'type' => 'Villa',
                'property_usage' => 'résidence',
                'contract_option' => 'Vente',
                'zone' => 'Loumbila',
                'size' => 500.00,
                'price' => 42000000.00,
                'description' => 'Jolie villa de vacances F3 non loin de l\'échangeur.',
                'status' => 'publiée',
                'created_at' => now(), 'updated_at' => now(),
            ],
        ]);
    }
}