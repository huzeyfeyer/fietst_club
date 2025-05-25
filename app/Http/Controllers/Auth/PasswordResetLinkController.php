<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Toon de pagina voor het aanvragen van een wachtwoordherstellink.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Verwerk een inkomend verzoek voor een wachtwoordherstellink.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // We sturen de wachtwoordherstellink naar deze gebruiker. Nadat we hebben geprobeerd
        // de link te sturen, onderzoeken we de respons en bekijken we welk bericht we
        // aan de gebruiker moeten tonen. Ten slotte sturen we een correcte respons.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('email'))
                        ->withErrors(['email' => __($status)]);
    }
}
