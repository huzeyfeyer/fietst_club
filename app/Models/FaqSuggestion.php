<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FaqSuggestion extends Model
{
    use HasFactory;

    /**
     * De attributen die massaal toewijsbaar zijn.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'question',
        'status',
        'admin_notes',
    ];

    /**
     * Haal de gebruiker op die deze suggestie heeft ingediend.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
