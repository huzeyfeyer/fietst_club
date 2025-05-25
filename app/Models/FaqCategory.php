<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class FaqCategory extends Model
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
     * Override de boot methode om automatisch een slug te genereren bij het opslaan.
     * Dit zorgt ervoor dat de slug wordt aangemaakt of bijgewerkt wanneer de naam verandert.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($category) {
            if (empty($category->slug) || $category->isDirty('name')) {
                $originalSlug = Str::slug($category->name);
                $slug = $originalSlug;
                $count = 1;
                // Zorg voor uniciteit van de slug
                while (static::where('slug', $slug)->where('id', '<>', $category->id ?? 0)->exists()) {
                    $slug = $originalSlug . '-' . $count++;
                }
                $category->slug = $slug;
            }
        });
    }

    /**
     * Haal de FAQ items op die bij deze categorie horen.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function faqItems(): HasMany
    {
        return $this->hasMany(FaqItem::class);
    }

    /**
     * Gebruik 'slug' voor route model binding in plaats van 'id'.
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
