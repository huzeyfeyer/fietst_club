<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller as BaseController;

class NewsController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    /**
     * Toon een lijst van alle nieuwsberichten.
     */
    public function index()
    {
        $news = News::with('user')
            ->latest()
            ->paginate(10);

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
            'content' => 'required',
        ]);

        $news = Auth::user()->news()->create($validated);

        return redirect()
            ->route('news.show', $news)
            ->with('success', 'Nieuwsbericht succesvol aangemaakt.');
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
        $this->authorize('update', $news);
        return view('news.edit', compact('news'));
    }

    /**
     * Werk het opgegeven nieuwsbericht bij in de database.
     */
    public function update(Request $request, News $news)
    {
        $this->authorize('update', $news);

        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
        ]);

        $news->update($validated);

        return redirect()
            ->route('news.show', $news)
            ->with('success', 'Nieuwsbericht succesvol bijgewerkt.');
    }

    /**
     * Verwijder het opgegeven nieuwsbericht uit de database.
     */
    public function destroy(News $news)
    {
        $this->authorize('delete', $news);
        
        $news->delete();

        return redirect()
            ->route('news.index')
            ->with('success', 'Nieuwsbericht succesvol verwijderd.');
    }
}
