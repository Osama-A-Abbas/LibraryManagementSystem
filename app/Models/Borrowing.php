<?php

declare(strict_types=1);

namespace App\Models;

use App\Observers\BorrowingObserver;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

/**
 * @property int $id
 * @property int $user_id
 * @property int $book_id
 * @property string $borrowing_status
 * @property Carbon $borrow_at
 * @property Carbon|null $return_at
 * @property string|null $notes
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
#[ObservedBy([BorrowingObserver::class])]
final class Borrowing extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'book_id',
        'borrowing_status',
        'borrow_at',
        'return_at',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'borrow_at' => 'datetime',
        'return_at' => 'datetime',
        'borrowing_status' => 'string',
    ];

    /**
     * Format borrow_at date to Y-m-d format
     *
     * @param mixed $value
     * @return string|null
     */
    public function getBorrowAtAttribute($value): ?string
    {
        return $value ? Carbon::parse($value)->format('Y-m-d') : null;
    }

    /**
     * Format return_at date to Y-m-d format
     *
     * @param mixed $value
     * @return string|null
     */
    public function getReturnAtAttribute($value): ?string
    {
        return $value ? Carbon::parse($value)->format('Y-m-d') : null;
    }

    /**
     * Format created_at date to Y-m-d H:i format
     *
     * @param mixed $value
     * @return string|null
     */
    public function getCreatedAtAttribute($value): ?string
    {
        return $value ? Carbon::parse($value)->format('Y-m-d H:i') : null;
    }

    /**
     * Get the user that owns the borrowing.
     *
     * @return BelongsTo<User>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the book that was borrowed.
     *
     * @return BelongsTo<Book>
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
