<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

/**
 * @property int $id
 * @property string $title
 * @property string $genre
 * @property string $author
 * @property string|null $cover_page
 * @property string|null $book_pdf
 * @property string $isbn
 * @property string $description
 * @property Carbon $published_at
 * @property int $number_of_copies
 * @property bool $is_available
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
final class Book extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
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

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'published_at' => 'datetime',
        'is_available' => 'boolean',
    ];

    protected static function booted(): void
    {
        //set is_available attribute to false/true based on the number of copies, if = 0 then false, if > 0 then true
        static::saving(function (Book $book): void {
            $book->is_available = $book->number_of_copies > 0;
        });
    }

    /**
     * Get the borrowings for the book.
     *
     * @return HasMany<Borrowing>
     */
    public function borrowings(): HasMany
    {
        return $this->hasMany(Borrowing::class);
    }

    /**
     * Format the published date.
     *
     * @param mixed $value
     * @return string|null
     */
    public function getPublishedAtAttribute($value): ?string
    {
        return $value ? Carbon::parse($value)->format('Y-m-d') : null;
    }
}
