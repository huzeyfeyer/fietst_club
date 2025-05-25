<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class News extends Model
{
    use HasFactory;

    /**
     * De attributen die massiveel toewijsbaar zijn.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'content',
        'image_path', // Pad naar de afbeelding van het nieuwsitem
        // user_id wordt meestal via de relatie gevuld, niet direct via fillable, maar kan indien nodig.
        // category_id (of categories) wordt via de relatie afgehandeld.
    ];

    /**
     * Haal de gebruiker (auteur) van het nieuwsbericht op.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Definieer de many-to-many relatie met NewsCategory (categorieën).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        // Pivot tabel: news_item_category
        // Foreign key in pivot tabel voor dit model: news_id
        // Foreign key in pivot tabel voor het gerelateerde model: news_category_id
        return $this->belongsToMany(NewsCategory::class, 'news_item_category', 'news_id', 'news_category_id');
    }
} 