<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Borrowing;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * Observer for the Borrowing model.
 * Handles book copy management when borrowings are created.
 */
final class BorrowingObserver
{
    /**
     * Handle the Borrowing "created" event.
     * Decrements the number of available copies when a book is borrowed.
     *
     * @param Borrowing $borrowing The borrowing instance that was created
     * @return void
     */
    public function created(Borrowing $borrowing): void
    {
        // Decrement number_of_copies of the borrowed book by 1 when a new Borrowing is created
        try {
            if ($borrowing->book && $borrowing->book->number_of_copies > 0) {
                $borrowing->book->decrement('number_of_copies');
            }
        } catch (Exception $e) {
            Log::error('Error in BorrowingObserver@created: ' . $e->getMessage());
        }
    }
}
