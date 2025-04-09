<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'borrowed_at' => 'date',
        'returned_at' => 'date',
        'borrowing_status' => 'enum:pending,approved,rejected,returned',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
