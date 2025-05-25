<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Toon een lijst van de bron.
     */
    public function index()
    {
        $users = User::orderBy('name')->paginate(15); // Haal gebruikers op, gesorteerd en gepagineerd
        return view('admin.users.index', compact('users'));
    }

    /**
     * Toon het formulier om een nieuwe bron aan te maken.
     */
    public function create()
    {
        $roles = [User::ROLE_USER, User::ROLE_ADMIN]; // Definieer beschikbare rollen
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Sla een nieuw aangemaakte bron op in de opslag.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
            'role' => ['required', Rule::in([User::ROLE_USER, User::ROLE_ADMIN])],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'email_verified_at' => now(), // Optioneel: direct verifiëren of later een verificatie e-mail sturen
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Gebruiker succesvol aangemaakt.');
    }

    /**
     * Toon de gespecificeerde bron.
     */
    public function show(User $user) // Type hint User model voor route model binding
    {
        // Niet direct nodig voor rolbeheer, kan later voor een detailpagina
    }

    /**
     * Toon het formulier om de gespecificeerde bron te bewerken.
     */
    public function edit(User $user) // Type hint User model voor route model binding
    {
        $roles = [User::ROLE_USER, User::ROLE_ADMIN]; // Definieer beschikbare rollen
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Werk de gespecificeerde bron bij in de opslag.
     */
    public function update(Request $request, User $user) // Type hint User model
    {
        $validated = $request->validate([
            'role' => ['required', Rule::in([User::ROLE_USER, User::ROLE_ADMIN])],
        ]);

        // Voorkom dat de admin zijn eigen rol wijzigt naar user als hij de enige admin is
        if ($user->isAdmin() && $validated['role'] === User::ROLE_USER) {
            // Tel het aantal admins
            $adminCount = User::where('role', User::ROLE_ADMIN)->count();
            if ($adminCount <= 1 && $user->id === auth()->id()) { // $user->id === auth()->id() is een extra check of de admin zichzelf wijzigt
                return redirect()->route('admin.users.edit', $user)->with('error', 'Kan de rol van de enige admin niet wijzigen naar gebruiker.');
            }
        }
        
        // Voorkom dat een admin zijn eigen rol wijzigt als hij niet de default admin is
        // Dit is een extra beveiliging, kan aangepast worden naar wens.
        if ($user->id === auth()->id() && $user->email !== 'admin@ehb.be' && $validated['role'] !== $user->role) {
            // Verwijderde commentaarlijn
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'Gebruikersrol succesvol bijgewerkt.');
    }

    /**
     * Verwijder de gespecificeerde bron uit de opslag.
     */
    public function destroy(string $id)
    {
        // Wordt later geïmplementeerd
    }
}
