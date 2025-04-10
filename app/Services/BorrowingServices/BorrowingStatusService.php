<?php

namespace App\Services\BorrowingServices;

use App\Models\Borrowing;
use Illuminate\Support\Facades\Log;

/**
 * BorrowingStatusService
 *
 * Manages all borrowing status changes in the application.
 * Provides specialized methods for common status operations like returning,
 * approving/rejecting, and manual status updates.
 *
 * Includes error handling and logging for all operations.
 */
class BorrowingStatusService
{
    /**
     * Handle returning a borrowed book
     *
     * Updates a borrowing record's status to 'returned' when a user returns a book.
     * Includes error handling with appropriate error messages and logging.
     *
     * @param Borrowing $borrowing The borrowing record to mark as returned
     * @return array Response with success/error message and optional status code
     */
    public function handleReturn(Borrowing $borrowing)
    {
        try {
            $borrowing->borrowing_status = 'returned';
            $borrowing->save();

            return ['success' => 'Book has been returned successfully.'];
        } catch (\Exception $e) {
            $this->logError('Failed to return book', $e);
            return ['error' => 'Failed to return book: ' . $e->getMessage(), 'status' => 500];
        }
    }

    /**
     * Handle approving or rejecting a borrowing request
     *
     * Updates a borrowing record's status to either 'approved' or 'rejected'
     * based on the admin's decision. Supports the borrowing workflow for
     * processing pending requests.
     *
     * @param Borrowing $borrowing The borrowing record to update
     * @param string $action Either 'approve' or 'reject'
     * @return array Response with success/error message and optional status code
     */
    public function handleApproveReject(Borrowing $borrowing, string $action)
    {
        try {
            $borrowing->borrowing_status = $action === 'approve' ? 'approved' : 'rejected';
            $borrowing->save();

            return ['success' => 'Borrowing request has been ' . $action . 'd successfully.'];
        } catch (\Exception $e) {
            $this->logError('Failed to update borrowing status', $e);
            return ['error' => 'Failed to update borrowing status: ' . $e->getMessage(), 'status' => 500];
        }
    }

    /**
     * Handle manual status update
     *
     * Allows administrators to manually set a borrowing record to any valid status.
     * Includes validation to ensure the new status is one of the allowed values.
     * This provides flexibility for managing exceptional cases.
     *
     * @param Borrowing $borrowing The borrowing record to update
     * @param string $newStatus The new status to set (pending/approved/rejected/returned)
     * @return array Response with success/error message and optional status code
     */
    public function handleStatusUpdate(Borrowing $borrowing, string $newStatus)
    {
        if (!in_array($newStatus, ['pending', 'approved', 'rejected', 'returned'])) {
            return ['error' => 'Invalid status', 'status' => 400];
        }

        try {
            $borrowing->borrowing_status = $newStatus;
            $borrowing->save();

            return ['success' => 'Borrowing status updated successfully.'];
        } catch (\Exception $e) {
            $this->logError('Failed to update borrowing status', $e);
            return ['error' => 'Failed to update borrowing status: ' . $e->getMessage(), 'status' => 500];
        }
    }

    /**
     * Log error details
     *
     * Centralizes error logging for all status update operations.
     * Records the error message and stack trace for debugging purposes.
     *
     * @param string $message Description of the error context
     * @param \Exception $e The exception that occurred
     * @return void
     */
    private function logError(string $message, \Exception $e)
    {
        Log::error($message, [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
}
