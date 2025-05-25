<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

    /**
     * De attributen die massiveel toewijsbaar zijn via het contactformulier.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
    ];

    /**
     * De attributen die moeten worden gecast naar native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_read' => 'boolean',
        'archived_at' => 'datetime',
    ];

    // Nederlandse commentaren voor eventuele scopes of relaties in de toekomst.
    // Bijvoorbeeld, een scope voor ongelezen berichten:
    // public function scopeUnread($query)
    // {
    //     return $query->where('is_read', false);
    // }
}
