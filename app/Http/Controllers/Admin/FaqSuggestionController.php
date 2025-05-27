<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FaqSuggestion;
use Illuminate\Http\Request;

class FaqSuggestionController extends Controller
{
    /**
     * Toon een lijst van alle FAQ suggesties.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = FaqSuggestion::with('user')->latest();

        // Filter op status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $suggestions = $query->paginate(15)->withQueryString();
        
        // Bewaar huidige filters in sessie voor redirects
        session(['faq_suggestions_index_filters' => $request->only('status')]);

        return view('admin.faq-suggestions.index', [
            'suggestions' => $suggestions,
            'currentStatus' => $request->status ?? 'all',
        ]);
    }

    /**
     * Toon het formulier voor het aanmaken van een nieuwe suggestie.
     * (Momenteel niet gebruikt, route is uitgesloten)
     */
    public function create()
    {
        abort(404);
    }

    /**
     * Sla een nieuw aangemaakte suggestie op in de database.
     * (Momenteel niet gebruikt, route is uitgesloten)
     */
    public function store(Request $request)
    {
        abort(404);
    }

    /**
     * Toon een specifieke suggestie.
     * (Momenteel niet gebruikt, details kunnen in index of edit view)
     */
    public function show(FaqSuggestion $faq_suggestion) // Variabelenaam aangepast
    {
        abort(404);
    }

    /**
     * Toon het formulier voor het bewerken van een specifieke suggestie.
     * (Kan gebruikt worden voor admin_notes, maar voor nu via update methode)
     */
    public function edit(FaqSuggestion $faq_suggestion) // Variabelenaam aangepast
    {
        abort(404);
    }

    /**
     * Werk de status of admin_notes van een FAQ suggestie bij.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FaqSuggestion  $faq_suggestion
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, FaqSuggestion $faq_suggestion)
    {
        $validated = $request->validate([
            'admin_notes' => ['nullable', 'string', 'max:2000'],
            'status' => ['sometimes', 'string', 'in:pending,approved,rejected'], // Optioneel status direct updaten
        ]);

        $faq_suggestion->update($validated);

        return redirect()->route('admin.faq-suggestions.index', session('faq_suggestions_index_filters', []))
                         ->with('success', 'FAQ suggestie succesvol bijgewerkt.');
    }

    /**
     * Keur een FAQ suggestie goed.
     *
     * @param  \App\Models\FaqSuggestion  $faq_suggestion
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve(FaqSuggestion $faq_suggestion)
    {
        $faq_suggestion->update(['status' => 'approved']);
        // Optioneel: Maak hier direct een FAQ item aan, of redirect naar formulier met prefill
        return redirect()->route('admin.faq-suggestions.index', session('faq_suggestions_index_filters', []))
                         ->with('success', 'Suggestie succesvol goedgekeurd.');
    }

    /**
     * Wijs een FAQ suggestie af.
     *
     * @param  \App\Models\FaqSuggestion  $faq_suggestion
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject(FaqSuggestion $faq_suggestion)
    {
        $faq_suggestion->update(['status' => 'rejected']);
        return redirect()->route('admin.faq-suggestions.index', session('faq_suggestions_index_filters', []))
                         ->with('success', 'Suggestie succesvol afgewezen.');
    }

    /**
     * Verwijder een FAQ suggestie uit de database.
     *
     * @param  \App\Models\FaqSuggestion  $faq_suggestion
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(FaqSuggestion $faq_suggestion)
    {
        $faq_suggestion->delete();
        return redirect()->route('admin.faq-suggestions.index', session('faq_suggestions_index_filters', []))
                         ->with('success', 'Suggestie succesvol verwijderd.');
    }
}
