<?php

namespace App\Services\BorrowingServices;

use App\Models\Borrowing;

/**
 * BorrowingDetailService
 *
 * Handles retrieving and formatting detailed information about borrowing records.
 * This service provides a centralized way to load and prepare borrowing data
 * for display in the application.
 */
class BorrowingDetailService
{
    /**
     * Get borrowing details with related book and user information
     *
     * Loads a borrowing record along with its related book and user records
     * to provide complete information about the borrowing. The method:
     * - Eagerly loads the relationships to reduce database queries
     * - Formats the data for easy consumption by frontend components
     * - Includes key information like book title and username
     *
     * @param Borrowing $borrowing The borrowing record to retrieve details for
     * @return array Array containing borrowing details and related information
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
