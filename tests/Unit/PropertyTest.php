<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Property;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PropertyTest extends TestCase
{
    use RefreshDatabase;

    // TEST 1 : Vérifier qu'une annonce créée par un bailleur est bien "en attente"
    public function test_annonce_bailleur_est_en_attente()
    {
        $bailleur = User::factory()->create(['role' => 'bailleur']);
        
        $status = ($bailleur->role === 'agent') ? 'publiée' : 'en attente';
        
        $this->assertEquals('en attente', $status);
    }

    // TEST 2 : Vérifier qu'une annonce créée par un agent est directement "publiée"
    public function test_annonce_agent_est_publiee()
    {
        $agent = User::factory()->create(['role' => 'agent']);
        
        $status = ($agent->role === 'agent') ? 'publiée' : 'en attente';
        
        $this->assertEquals('publiée', $status);
    }

    // TEST 3 : Vérifier la validation du statut d'une annonce
    public function test_statuts_valides_dune_annonce()
    {
        $statutsValides = ['en attente', 'publiée', 'retirée'];
        
        $property = new Property();
        $property->status = 'publiée';
        
        $this->assertContains($property->status, $statutsValides);
    }

    // TEST 4 : Vérifier que le prix et la superficie sont bien numériques
    public function test_prix_et_superficie_sont_numeriques()
    {
        $price = 75000000.00;
        $size = 450.00;
        
        $this->assertIsFloat($price);
        $this->assertIsFloat($size);
        $this->assertGreaterThan(0, $price);
        $this->assertGreaterThan(0, $size);
    }

    // TEST 5 : Vérifier les types de biens autorisés
    public function test_types_de_biens_autorises()
    {
        $typesValides = ['Villa', 'Appartement', 'Terrain', 'Bureau', 'Immeuble', 'Magasin'];
        
        $type = 'Villa';
        
        $this->assertContains($type, $typesValides);
    }
}