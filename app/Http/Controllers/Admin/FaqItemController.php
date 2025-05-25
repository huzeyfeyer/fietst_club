<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FaqItem;
use App\Models\FaqCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class FaqItemController extends Controller
{
    /**
     * Toon een lijst van alle FAQ items.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $faqItems = FaqItem::with('faqCategory')
                            ->orderBy('question')
                            ->paginate(15);
        
        $categories = FaqCategory::orderBy('name')->get();

        return view('admin.faq-items.index', compact('faqItems', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        $categories = FaqCategory::orderBy('name')->get();
        return view('admin.faq-items.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
            'faq_category_id' => 'nullable|exists:faq_categories,id',
        ]);

        // Als faq_category_id leeg is (bv. ""), zet het om naar null voor de database.
        if (empty($validated['faq_category_id'])) {
            $validated['faq_category_id'] = null;
        }

        FaqItem::create($validated);

        return redirect()->route('admin.faq-items.index')
                         ->with('success', 'FAQ item succesvol aangemaakt.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FaqItem  $faqItem
     * @return never // This method will not return normally
     */
    public function show(FaqItem $faqItem): never // Update return type
    {
        abort(404); // Show is niet geimplementeerd voor admin
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FaqItem  $faqItem
     * @return \Illuminate\View\View
     */
    public function edit(FaqItem $faqItem): View
    {
        $categories = FaqCategory::orderBy('name')->get();
        return view('admin.faq-items.edit', compact('faqItem', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FaqItem  $faqItem
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, FaqItem $faqItem): RedirectResponse
    {
        $validated = $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
            'faq_category_id' => 'nullable|exists:faq_categories,id',
        ]);

        // Als faq_category_id leeg is, zet het om naar null.
        if (empty($validated['faq_category_id'])) {
            $validated['faq_category_id'] = null;
        }

        $faqItem->update($validated);

        return redirect()->route('admin.faq-items.index')
                         ->with('success', 'FAQ item succesvol bijgewerkt.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FaqItem  $faqItem
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(FaqItem $faqItem): RedirectResponse
    {
        $faqItem->delete();

        return redirect()->route('admin.faq-items.index')
                         ->with('success', 'FAQ item succesvol verwijderd.');
    }
}
