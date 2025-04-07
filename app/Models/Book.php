<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'title',
        'genre',
        'author',
        'cover_page',
        'description',
        'published_at',
        'number_of_copies',
        'is_available',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    // public function getCoverPageAttribute($value)
    // {
    //     return asset($value);
    // }
}
