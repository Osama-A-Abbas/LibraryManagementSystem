<?php

namespace App\Models;

use App\Observers\BorrowingObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy([BorrowingObserver::class])]

class Borrowing extends Model
{
    /** @use HasFactory<\Database\Factories\BorrowingFactory> */
    use HasFactory;


    protected $fillable = [
        'user_id',
        'book_id',
        'borrowing_status',
        'borrow_at',
        'return_at',
        'notes',
    ];

    protected $casts = [
        'borrow_at' => 'date',
        'return_at' => 'date',
        'borrowing_status' => 'string',
    ];

    public function getDateAtAttribute($value)
    {
        return $value ? \Carbon\Carbon::parse($value)->format('Y-m-d') : null;
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
