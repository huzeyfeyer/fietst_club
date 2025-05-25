<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class NewsCategory extends Model
{
    use HasFactory;

    /**
     * De attributen die massiveel toewijsbaar zijn.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * Definieer de many-to-many relatie met News (nieuwsitems).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function newsItems(): BelongsToMany
    {
        // Pivot tabel: news_item_category
        // Foreign key in pivot tabel voor dit model: news_category_id
        // Foreign key in pivot tabel voor het gerelateerde model: news_id
        return $this->belongsToMany(News::class, 'news_item_category', 'news_category_id', 'news_id');
    }

    /**
     * Override de boot methode om automatisch een slug te genereren bij het opslaan.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($category) {
            if (empty($category->slug) || $category->isDirty('name')) {
                $category->slug = Str::slug($category->name);
                // Zorg voor uniciteit van de slug (optioneel, maar aanbevolen voor productie)
                $count = static::where('slug', 'like', $category->slug . '%')->where('id', '<>', $category->id ?? 0)->count();
                if ($count > 0) {
                    $category->slug .= '-' . ($count + 1); // Voeg een suffix toe als de basis slug al bestaat
                }
            }
        });
    }

    /**
     * Route model binding via slug in plaats van ID.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
