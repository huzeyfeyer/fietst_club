<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    /**
     * De attributen die massiveel toewijsbaar zijn.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'news_id',
        'content',
    ];

    /**
     * Haal de gebruiker (auteur) van de commentaar op.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Haal het nieuwsbericht op waartoe deze commentaar behoort.
     */
    public function news(): BelongsTo
    {
        return $this->belongsTo(News::class);
    }
}
