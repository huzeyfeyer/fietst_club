<?php

namespace App\Http\Controllers;

use App\Models\FaqSuggestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FaqSuggestionController extends Controller
{
    /**
     * Sla een nieuwe FAQ suggestie op, ingediend door een ingelogde gebruiker.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'question' => ['required', 'string', 'min:10', 'max:1000'],
        ]);

        FaqSuggestion::create([
            'user_id' => Auth::id(),
            'question' => $validated['question'],
            // 'status' wordt standaard 'pending' door de database default
        ]);

        return redirect()->route('faq.index')->with('success', 'Bedankt voor je vraag! We bekijken je suggestie zo snel mogelijk.');
    }
}
