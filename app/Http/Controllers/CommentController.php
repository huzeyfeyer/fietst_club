<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class CommentController extends Controller
{
    /**
     * Sla een nieuwe commentaar op voor een nieuwsbericht.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, News $news): RedirectResponse
    {
        $request->validate([
            'content' => 'required|string|min:3|max:2000', // Valideer de inhoud van de commentaar
        ]);

        // Maak de commentaar aan en koppel deze aan het nieuwsbericht en de ingelogde gebruiker
        $comment = new Comment();
        $comment->content = $request->input('content');
        $comment->user_id = Auth::id(); // Haal de ID van de ingelogde gebruiker op
        // $comment->news_id = $news->id; // Dit wordt via de relatie gedaan

        $news->comments()->save($comment); // Sla de commentaar op via de relatie

        return back()->with('success', 'Uw commentaar is succesvol geplaatst!');
    }
}
