<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FaqItem extends Model
{
    use HasFactory;

    /**
     * De attributen die massiveel toewijsbaar zijn.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'question',
        'answer',
        'faq_category_id',
    ];

    /**
     * Haal de FAQ categorie op waartoe dit item behoort.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function faqCategory(): BelongsTo
    {
        return $this->belongsTo(FaqCategory::class, 'faq_category_id');
    }

    // Optioneel: Als je vaak de categorienaam direct via het FaqItem wilt benaderen,
    // kun je een accessor toevoegen, maar het is vaak beter om de relatie te gebruiken:
    // $faqItem->faqCategory->name
}
