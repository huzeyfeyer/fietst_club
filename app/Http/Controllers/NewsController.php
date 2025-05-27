<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;

class NewsController extends BaseController
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->middleware('auth')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    /**
     * Toon een lijst van alle nieuwsberichten, eventueel gefilterd op categorie.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request): View
    {
        $categorySlug = $request->query('category');
        $activeCategory = null;
        $allCategories = NewsCategory::orderBy('name')->get();

        $newsQuery = News::with('user', 'categories')->latest();

        if ($categorySlug) {
            $activeCategory = NewsCategory::where('slug', $categorySlug)->first();
            if ($activeCategory) {
                $newsQuery->whereHas('categories', function ($query) use ($activeCategory) {
                    $query->where('news_categories.id', $activeCategory->id);
                });
            }
        }

        $newsItems = $newsQuery->paginate(10)->withQueryString();

        return view('news.index', [
            'news' => $newsItems,
            'categories' => $allCategories,
            'activeCategory' => $activeCategory,
        ]);
    }

    /**
     * Toon het formulier voor het maken van een nieuw nieuwsbericht.
     */
    public function create()
    {
        $this->authorize('create', News::class);
        $categories = NewsCategory::orderBy('name')->get();
        return view('news.create', compact('categories'));
    }

    /**
     * Bewaar een nieuw aangemaakt nieuwsbericht in de database.
     */
    public function store(Request $request)
    {
        $this->authorize('create', News::class);

        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif', 'max:2048'],
            'categories' => ['nullable', 'array'],
            'categories.*' => ['exists:news_categories,id'],
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('news-images', 'public');
        }

        $dataToStore = [
            'title' => $validated['title'],
            'content' => $validated['content'],
        ];
        if ($imagePath) {
            $dataToStore['image_path'] = $imagePath;
        }

        $newsItem = Auth::user()->news()->create($dataToStore);

        if (!empty($validated['categories'])) {
            $newsItem->categories()->sync($validated['categories']);
        }

        return redirect()
            ->route('news.show', $newsItem)
            ->with('success', 'Nieuwsbericht succesvol aangemaakt.');
    }

    /**
     * Toon een specifiek nieuwsbericht.
     */
    public function show(News $news): View
    {
        $news->loadMissing('user', 'categories');
        return view('news.show', compact('news'));
    }

    /**
     * Toon het formulier voor het bewerken van een bestaand nieuwsbericht.
     */
    public function edit(News $news)
    {
        $this->authorize('update', $news);
        $categories = NewsCategory::orderBy('name')->get();
        $selectedCategories = $news->categories()->pluck('id')->toArray();
        return view('news.edit', compact('news', 'categories', 'selectedCategories'));
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
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif', 'max:2048'],
            'remove_image' => ['nullable', 'boolean'],
            'categories' => ['nullable', 'array'],
            'categories.*' => ['exists:news_categories,id'],
        ]);

        $dataToUpdate = [
            'title' => $validated['title'],
            'content' => $validated['content'],
        ];

        if ($request->has('remove_image') && $request->input('remove_image') == '1') {
            if ($news->image_path) {
                Storage::disk('public')->delete($news->image_path);
                $dataToUpdate['image_path'] = null;
            }
        } elseif ($request->hasFile('image')) {
            if ($news->image_path) {
                Storage::disk('public')->delete($news->image_path);
            }
            $path = $request->file('image')->store('news-images', 'public');
            $dataToUpdate['image_path'] = $path;
        }

        $news->update($dataToUpdate);

        $news->categories()->sync($request->input('categories', []));

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
        
        if ($news->image_path) {
            Storage::disk('public')->delete($news->image_path);
        }

        $news->delete();

        return redirect()
            ->route('news.index')
            ->with('success', 'Nieuwsbericht succesvol verwijderd.');
    }
}
