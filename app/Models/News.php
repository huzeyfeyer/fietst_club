<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $fillable = [
        'title',
        'content',
        'user_id'
    ];

    // Relatie met User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 