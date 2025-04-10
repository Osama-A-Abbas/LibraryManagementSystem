<?php

namespace App\Services\BorrowingServices;

use App\Models\Borrowing;
use Illuminate\Support\Facades\Log;

class BorrowingCreateService
{
    /**
     * Create a new borrowing record
     *
     * @param Borrowing $borrowing
     * @param array $validated
     * @param int $userId
     * @return array
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
