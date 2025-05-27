<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class NewsCategoryController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $this->authorize('viewAny', NewsCategory::class); // Voorbeeld policy check
        $categories = NewsCategory::latest()->paginate(10);
        return view('admin.news-categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // $this->authorize('create', NewsCategory::class); // Voorbeeld policy check
        return view('admin.news-categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $this->authorize('create', NewsCategory::class); // Voorbeeld policy check
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:news_categories,name',
            // 'slug' => 'required|string|max:255|unique:news_categories,slug', // Slug wordt automatisch gegenereerd
        ]);

        $category = NewsCategory::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']), // Genereer slug automatisch
        ]);

        return redirect()->route('admin.news-categories.index')->with('success', 'Nieuwscategorie succesvol aangemaakt.');
    }

    /**
     * Display the specified resource.
     */
    public function show(NewsCategory $newsCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(NewsCategory $newsCategory)
    {
        // $this->authorize('update', $newsCategory); // Voorbeeld policy check
        return view('admin.news-categories.edit', compact('newsCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, NewsCategory $newsCategory)
    {
        // $this->authorize('update', $newsCategory); // Voorbeeld policy check
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:news_categories,name,' . $newsCategory->id,
            // 'slug' => 'required|string|max:255|unique:news_categories,slug,' . $newsCategory->id, // Slug wordt automatisch gegenereerd
        ]);

        $newsCategory->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']), // Genereer slug automatisch
        ]);

        return redirect()->route('admin.news-categories.index')->with('success', 'Nieuwscategorie succesvol bijgewerkt.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NewsCategory $newsCategory)
    {
        // $this->authorize('delete', $newsCategory); // Voorbeeld policy check
        
        // Optioneel: controleer of er nieuwsberichten aan deze categorie gekoppeld zijn
        if ($newsCategory->news()->count() > 0) {
            return redirect()->route('admin.news-categories.index')->with('error', 'Deze categorie kan niet worden verwijderd omdat er nog nieuwsberichten aan gekoppeld zijn.');
        }

        $newsCategory->delete();
        return redirect()->route('admin.news-categories.index')->with('success', 'Nieuwscategorie succesvol verwijderd.');
    }
}
