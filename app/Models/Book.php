<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    protected $fillable = [
        'title',
        'genre',
        'author',
        'cover_page',
        'book_pdf',
        'isbn',
        'description',
        'published_at',
        'number_of_copies',
        'is_available',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    
    public function borrowings(): HasMany
    {
        return $this->hasMany(Borrowing::class);
    }


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
