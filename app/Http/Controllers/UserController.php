<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get(); // Charge les utilisateurs avec leurs rôles
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        $modules = ['sales', 'inventory', 'hr', 'finance']; // Liste des modules disponibles
        // Vous pouvez également récupérer les modules depuis la base de données si nécessaire
        return view('users.create', compact('roles', 'modules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'phone' => 'nullable|string|max:15',
            'role_id' => 'nullable|exists:roles,id',
            'address' => 'nullable|string|max:255',
            'birthdate' => 'nullable|date',
            'locale' => 'nullable|string|max:5',
            'currency' => 'nullable|string|max:3',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validation image
            // Ajoutez d’autres champs si besoin
        ]);

        $data = $request->only('name', 'email', 'password', 'phone', 'address', 'birthdate', 'locale', 'currency', 'role_id');

        // Si une photo est uploadée, on la sauvegarde
        if ($request->hasFile('photo')) {
            // stocke dans 'public/images' et récupère le chemin
            $path = $request->file('photo')->store('public/images');
            // Récupère le chemin "public/images/xxxxx.jpg"
            $data['photo'] = str_replace('public/', '', $path); // pour stocker le chemin relatif
        }

        // Créer l'utilisateur
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'birthdate' => $data['birthdate'] ?? null,
            'locale' => $data['locale'] ?? null,
            'currency' => $data['currency'] ?? null,
            'role_id' => $data['role_id'] ?? null,
            'photo' => $data['photo'] ?? null,
        ]);

        // Si vous avez un système de rôles :
        if (isset($request->roles)) {
            $user->syncRoles($request->roles);
        }

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed', // Validation pour le mot de passe (optionnel)
        ]);

        // Récupérer les données à mettre à jour
        $data = $request->only('name', 'email');

        // Vérifiez si un nouveau mot de passe est fourni
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password); // Hachez le nouveau mot de passe
        }

        // Mettre à jour l'utilisateur avec les données
        $user->update($data);

        // Synchroniser les rôles
        $user->syncRoles($request->roles);

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }

    // Méthode pour assigner un rôle à un utilisateur
    public function assignRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|exists:roles,name',
        ]);

        $user->assignRole($request->role);

        return redirect()->route('users.index')
            ->with('success', 'Role assigned successfully.');
    }

    // Méthode pour retirer un rôle d'un utilisateur
    public function removeRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|exists:roles,name',
        ]);

        $user->removeRole($request->role);

        return redirect()->route('users.index')
            ->with('success', 'Role removed successfully.');
    }

    // Méthode pour vérifier si un utilisateur a un rôle
    public function checkRole(User $user)
    {
        $hasRole = $user->hasRole('admin'); // Remplacez 'admin' par le rôle que vous souhaitez vérifier

        return response()->json(['hasRole' => $hasRole]);
    }

    // Méthode de connexion
    public function connexion(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            return redirect()->intended('/') // Remplacez 'dashboard' par la route vers laquelle vous souhaitez rediriger
                ->with('success', 'Vous êtes connecté avec succès.');
        }

        return back()->withErrors([
            'email' => 'Les informations d\'identification fournies ne correspondent pas à nos enregistrements.',
        ]);
    }

    // Méthode de déconnexion
    public function deconnexion()
    {
        Session::flush();
        Auth::logout();
        return redirect()->route('login') // Remplacez 'login' par la route de votre page de connexion
            ->with('success', 'Vous êtes déconnecté avec succès.');
    }
}
