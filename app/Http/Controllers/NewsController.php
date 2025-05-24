<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * Toon een lijst van alle nieuwsberichten.
     */
    public function index()
    {
        $news = News::latest()->paginate(10);
        return view('news.index', compact('news'));
    }

    /**
     * Toon het formulier voor het maken van een nieuw nieuwsbericht.
     */
    public function create()
    {
        return view('news.create');
    }

    /**
     * Bewaar een nieuw aangemaakt nieuwsbericht in de database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required'
        ]);

        $news = News::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'user_id' => auth()->id()
        ]);

        return redirect()->route('news.index')
            ->with('success', 'Nieuwsbericht succesvol toegevoegd.');
    }

    /**
     * Toon een specifiek nieuwsbericht.
     */
    public function show(News $news)
    {
        return view('news.show', compact('news'));
    }

    /**
     * Toon het formulier voor het bewerken van een bestaand nieuwsbericht.
     */
    public function edit(News $news)
    {
        return view('news.edit', compact('news'));
    }

    /**
     * Werk het opgegeven nieuwsbericht bij in de database.
     */
    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required'
        ]);

        $news->update($validated);

        return redirect()->route('news.index')
            ->with('success', 'Nieuwsbericht succesvol bijgewerkt.');
    }

    /**
     * Verwijder het opgegeven nieuwsbericht uit de database.
     */
    public function destroy(News $news)
    {
        $news->delete();

        return redirect()->route('news.index')
            ->with('success', 'Nieuwsbericht succesvol verwijderd.');
    }
}
