<?php

namespace App\Models;

use App\Observers\BorrowingObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy([BorrowingObserver::class])]

class Borrowing extends Model
{
    protected $fillable = [
        'user_id',
        'book_id',
        'borrowing_status',
        'borrow_at',
        'return_at',
        'notes',
    ];

    protected $casts = [
        'borrow_at' => 'datetime',
        'return_at' => 'datetime',
        'borrowing_status' => 'string',
    ];

    /**
     * Format borrow_at date to Y-m-d-h-m format
     *
     * @param mixed $value
     * @return string|null
     */
    public function getBorrowAtAttribute($value)
    {
        return $value ? \Carbon\Carbon::parse($value)->format('Y-m-d') : null;
    }

    /**
     * Format return_at date to Y-m-d-h-m format
     *
     * @param mixed $value
     * @return string|null
     */
    public function getReturnAtAttribute($value)
    {
        return $value ? \Carbon\Carbon::parse($value)->format('Y-m-d') : null;
    }

    /**
     * Format created_at date to Y-m-d-h-m format
     *
     * @param mixed $value
     * @return string|null
     */
    public function getCreatedAtAttribute($value)
    {
        return $value ? \Carbon\Carbon::parse($value)->format('Y-m-d H:i') : null;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
