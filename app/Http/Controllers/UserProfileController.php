<?php

namespace App\Http\Controllers;

use App\Models\User; // Importeer User model
use Illuminate\Http\Request;
use Illuminate\View\View; // Importeer View

class UserProfileController extends Controller
{
    /**
     * Toon het publieke profiel van een gebruiker.
     *
     * @param  \App\Models\User  $user Het User model gebonden via route model binding.
     * @return \Illuminate\View\View
     */
    public function show(User $user): View
    {
        return view('users.profile.show', compact('user'));
    }
}
