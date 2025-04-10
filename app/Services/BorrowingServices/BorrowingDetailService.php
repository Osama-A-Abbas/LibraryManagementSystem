<?php

namespace App\Services\BorrowingServices;

use App\Models\Borrowing;

class BorrowingDetailService
{
    /**
     * Get borrowing details with related book and user
     *
     * @param Borrowing $borrowing
     * @return array
     */
    public function getDetails(Borrowing $borrowing)
    {
        $borrowing->load(['book', 'user']);

        return [
            'borrowing' => $borrowing,
            'book_title' => $borrowing->book->title,
            'username' => $borrowing->user->name
        ];
    }
}
