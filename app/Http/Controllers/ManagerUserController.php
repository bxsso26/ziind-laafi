<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Property;            // <-- À METTRE ICI
use App\Models\Visit;               // <-- À METTRE ICI
use Illuminate\Support\Facades\DB;  // <-- À METTRE ICI
use Illuminate\Support\Facades\Hash;

class ManagerUserController extends Controller
{

    // EF-D5 : Liste des utilisateurs
    public function index()
{
    $users = User::whereIn('role', ['client', 'bailleur', 'agent'])->paginate(15);
    return view('managers.users.index', compact('users'));
}

    // EF-D5 : Formulaire de création
    public function create()
    {
        return view('managers.users.create');
    }

    // EF-D5 : Enregistrement d'un nouvel utilisateur
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'telephone' => 'required|string|max:20',
            'role' => 'required|string|in:client,bailleur,agent',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'telephone' => $validated['telephone'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('manager.users.index')->with('success', 'Utilisateur créé avec succès !');
    }

    // EF-D5 : Formulaire de modification
    public function edit($id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'manager') {
            return redirect()->route('managers.users.index')->with('error', 'Action non autorisée.');
        }

        // On récupère uniquement les utilisateurs qui ont le rôle 'agent'
        $agents = User::where('role', 'agent')->get();

        // On passe l'utilisateur ET la liste des agents à la vue
        return view('managers.users.edit', compact('user', 'agents'));
    }

    // EF-D5 : Mise à jour de l'utilisateur
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if ($user->role === 'manager') {
        return redirect()->route('managers.users.index')->with('error', 'Action non autorisée.');}

        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users,email,'.$id,
            'telephone' => 'required|string|max:20',
            'role'      => 'required|string|in:client,bailleur,agent',
            'password'  => 'nullable|string|min:8',
            'agent_id'  => 'nullable|exists:users,id', // <-- AJOUT de la validation de l'agent
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->telephone = $validated['telephone'];
        $user->role = $validated['role'];
        
        // Si c'est un client, on lui associe l'agent choisi (ou null pour le désaffecter)
        if ($user->role === 'client') {
            $user->agent_id = $validated['agent_id'] ?? null;
        }

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('manager.users.index')->with('success', 'L\'utilisateur et son affectation ont bien été mis à jour !');
    }

    // EF-D5 : Suppression de l'utilisateur
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('manager.users.index')->with('success', 'Utilisateur supprimé avec succès !');
    }
    public function dashboard()
{
    // Sécurité : Seul le manager peut voir les statistiques Ziind Laafi
    if (auth()->user()->role !== 'manager') {
        abort(403, 'Action non autorisée.');
    }
    // 1. Nombre total de propriétés par type
    $propertiesByType = Property::select('type', DB::raw('count(*) as total'))
                                ->groupBy('type')
                                ->get();

    // 2. Nombre de annonces en attente de validation
    // (En supposant que le statut soit 'En attente', 'En attente de validation' ou 'Brouillon')
    $pendingPropertiesCount = Property::where('status', 'en attente')->count();
    // 3. Nombre de visites demandées par mois (sur l'année en cours)
    // S'adapte selon la structure de ta table (ex: créée en 2026)
    // Remplace 'visits' par 'visites' (avec un 'e')
$visitsByMonth = DB::table('visit_requests') // Le nom exact de la table en BD
    ->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
    ->whereYear('created_at', 2026)
    ->groupBy('month')
    ->orderBy('month')
    ->get();

  return view('managers.users.dashboard', compact('propertiesByType', 'pendingPropertiesCount', 'visitsByMonth'));
}
function retirer($id) {
    // Vérification de sécurité
    if (auth()->user()->role !== 'manager') { abort(403); }

    $property = Property::findOrFail($id);
    $property->update(['status' => 'retirée']);

    return back()->with('success', 'Annonce retirée avec succès.');}
    public function manageIndex()
{
    $properties = Property::orderBy('created_at', 'desc')->paginate(20);
    return view('managers.properties.index', compact('properties'));
}
}