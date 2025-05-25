<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewContactMessageNotification;
use App\Models\User;

class ContactController extends Controller
{
    /**
     * Toon het contactformulier.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        return view('contact.create');
    }

    /**
     * Sla een nieuw contactbericht op.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|min:10', // Minimale lengte voor een bericht
        ]);

        $contactMessage = ContactMessage::create($validated);

        // Probeer alle admin gebruikers op te halen
        $adminUsers = User::where('role', User::ROLE_ADMIN)->get();

        if ($adminUsers->isNotEmpty()) {
            foreach ($adminUsers as $admin) {
                Mail::to($admin->email)->send(new NewContactMessageNotification($contactMessage));
            }
        } else {
            // Fallback als er geen admin users zijn (bv. stuur naar een default adres)
            // Dit zou niet mogen gebeuren als de seeder correct is uitgevoerd.
            // Log::warning('Geen admin gebruikers gevonden om contactbericht notificatie naar te sturen.');
            // Mail::to(config('mail.from.address'))->send(new NewContactMessageNotification($contactMessage)); // Voorbeeld fallback
        }

        return redirect()->route('contact.create')
                         ->with('success', __('Bedankt voor uw bericht! We nemen zo snel mogelijk contact met u op.'));
    }
}
