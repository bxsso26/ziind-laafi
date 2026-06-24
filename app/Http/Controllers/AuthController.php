<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Gérer la connexion
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Redirection dynamique par rôle
            return match(Auth::user()->role) {
                'manager'  => redirect()->route('manager.dashboard'),
                'agent'    => redirect()->route('agent.dashboard'),
                'bailleur' => redirect()->route('bailleur.index'),
                default    => redirect()->route('properties.index')->with('success', 'Connexion réussie !'),
            };
        }

        return back()->withErrors(['email' => 'Identifiants incorrects.'])->onlyInput('email');
    }

    // Gérer l'inscription
    public function register(Request $request)
    {
        $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'telephone' => 'required|string|max:20', // Prise en compte du champ obligatoire
        'password' => 'required|string|min:8|confirmed',
        'role' => 'required|in:client,bailleur', // Sécurité EF-A4 : Seuls Client ou Bailleur autorisés via l'inscription
    ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect()->route('properties.index')->with('success', 'Compte créé !');
    }

    // Gérer la déconnexion
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('properties.index')->with('success', 'Déconnexion réussie.');
    }
}