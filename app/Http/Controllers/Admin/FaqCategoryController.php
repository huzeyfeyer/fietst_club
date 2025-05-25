<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FaqCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class FaqCategoryController extends Controller
{
    /**
     * Toon een lijst van alle FAQ categorieën.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $categories = FaqCategory::orderBy('name')->paginate(10);
        return view('admin.faq-categories.index', compact('categories'));
    }

    /**
     * Toon het formulier voor het aanmaken van een nieuwe FAQ categorie.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        return view('admin.faq-categories.create');
    }

    /**
     * Sla een nieuw aangemaakte FAQ categorie op in de database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:faq_categories,name',
        ]);

        FaqCategory::create($validated);

        return redirect()->route('admin.faq-categories.index')
                         ->with('success', 'FAQ categorie succesvol aangemaakt.');
    }

    /**
     * Display the specified resource.
     */
    public function show(FaqCategory $faqCategory)
    {
        abort(404);
    }

    /**
     * Toon het formulier voor het bewerken van de opgegeven FAQ categorie.
     *
     * @param  \App\Models\FaqCategory  $faqCategory
     * @return \Illuminate\View\View
     */
    public function edit(FaqCategory $faqCategory): View
    {
        return view('admin.faq-categories.edit', compact('faqCategory'));
    }

    /**
     * Werk de opgegeven FAQ categorie bij in de database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FaqCategory  $faqCategory
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, FaqCategory $faqCategory): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:faq_categories,name,' . $faqCategory->id,
        ]);

        $faqCategory->update($validated);

        return redirect()->route('admin.faq-categories.index')
                         ->with('success', 'FAQ categorie succesvol bijgewerkt.');
    }

    /**
     * Verwijder de opgegeven FAQ categorie uit de database.
     * Overweeg wat te doen met FAQ items in deze categorie (bv. verbieden, loskoppelen, cascade delete).
     * Voor nu: simpelweg verwijderen. Als FaqItem een onDelete('cascade') heeft op category_id, worden items mee verwijderd.
     * Als FaqItem een onDelete('set null') heeft, wordt category_id null.
     * Belangrijk: FaqItem model en relaties zijn nog niet gedefinieerd.
     *
     * @param  \App\Models\FaqCategory  $faqCategory
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(FaqCategory $faqCategory): RedirectResponse
    {
        if ($faqCategory->faqItems()->count() > 0) {
            return redirect()->route('admin.faq-categories.index')
                             ->with('error', 'Kan categorie niet verwijderen, er zijn nog FAQ items aan gekoppeld.');
        }

        $faqCategory->delete();

        return redirect()->route('admin.faq-categories.index')
                         ->with('success', 'FAQ categorie succesvol verwijderd.');
    }
}
