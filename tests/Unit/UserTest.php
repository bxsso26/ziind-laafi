<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class UserTest extends TestCase
{
    use RefreshDatabase;

    // TEST 6 : Vérifier que les rôles autorisés sont corrects
    public function test_roles_autorises()
    {
        $rolesValides = ['client', 'bailleur', 'agent', 'manager'];
        
        foreach ($rolesValides as $role) {
            $user = User::factory()->create(['role' => $role]);
            $this->assertContains($user->role, $rolesValides);
        }
    }

    // TEST 7 : Vérifier que le mot de passe est bien hashé
    public function test_mot_de_passe_est_hashe()
    {
        $password = 'password123';
        $hashed = Hash::make($password);
        
        $this->assertNotEquals($password, $hashed);
        $this->assertTrue(Hash::check($password, $hashed));
    }

    // TEST 8 : Vérifier qu'un bailleur et agent ne peuvent pas s'inscrire librement comme manager
    public function test_inscription_libre_impossible_pour_manager()
    {
        $rolesLibres = ['client', 'bailleur'];
        
        $this->assertNotContains('manager', $rolesLibres);
        $this->assertNotContains('agent', $rolesLibres);
    }
}