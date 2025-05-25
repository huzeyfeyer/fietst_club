<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Toon het profielformulier van de gebruiker.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Werk de profielinformatie van de gebruiker bij.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validatedData = $request->validated();

        // Verwerk profielfoto upload
        if ($request->has('remove_profile_photo') && $request->input('remove_profile_photo') == '1') {
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
                $validatedData['profile_photo_path'] = null;
            }
        } elseif ($request->hasFile('profile_photo')) {
            // Verwijder oude foto als die bestaat
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $validatedData['profile_photo_path'] = $path;
        }

        // Vul de gebruiker met gevalideerde data (inclusief mogelijk nieuwe profile_photo_path)
        $user->fill($validatedData);

        // Als e-mailadres is gewijzigd, moet het opnieuw geverifieerd worden
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Verwijder het account van de gebruiker.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        // Verwijder profielfoto als die bestaat
        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
