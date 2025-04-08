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
        // 'book_pdf_file',
        'description',
        'published_at',
        'number_of_copies',
        'is_available',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    //format the date using Carbon, e.g output 2025-02-12
    public function getPublishedAtAttribute($value)
    {
        return $value ? \Carbon\Carbon::parse($value)->format('Y-m-d') : null;
    }


    // public function getGenreAttribute($value)
    // {
    //     return $value ? ucfirst($value) : null;
    // }

    // public function getCoverPageAttribute($value)
    // {
    //     return asset($value);
    // }
}
