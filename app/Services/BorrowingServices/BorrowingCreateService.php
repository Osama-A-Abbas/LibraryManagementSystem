<?php

namespace App\Services\BorrowingServices;

use App\Models\Borrowing;
use Illuminate\Support\Facades\Log;

/**
 * BorrowingCreateService
 *
 * Handles the creation of new borrowing records in the system.
 * Manages the process of creating borrowing requests including validation,
 * database operations, and error handling.
 */
class BorrowingCreateService
{
    /**
     * Create a new borrowing record
     *
     * Processes a validated borrowing request and creates a new record in the database.
     * The method:
     * - Logs the incoming request data for audit/debugging
     * - Creates the borrowing with a default 'pending' status
     * - Handles error conditions with appropriate message and status code
     * - Logs successful creation for tracking
     *
     * @param Borrowing $borrowing The borrowing model
     * @param array $validated Validated request data from the controller
     * @param int $userId ID of the user making the borrowing request
     * @return array Response with success/error message and status code
     */
    public function handle(Borrowing $borrowing, array $validated, int $userId)
    {
        try {
            // Log the request data for debugging
            Log::info('Borrowing request received', ['data' => $validated]);

            // Create the borrowing record with correct field names
            $borrowingRecord = $borrowing->create([
                'book_id' => $validated['book_id'],
                'user_id' => $userId,
                'borrowing_status' => 'pending',
                'borrow_at' => $validated['borrow_at'],
                'return_at' => $validated['return_at'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            Log::info('Borrowing created successfully', ['borrowing' => $borrowingRecord->toArray()]);

            return [
                'success' => 'Borrow Request Sent.',
                'status' => 201
            ];
        } catch (\Exception $e) {
            Log::error('Failed to create borrowing', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'error' => 'Failed to send borrow request: ' . $e->getMessage(),
                'status' => 500
            ];
        }
    }
}
