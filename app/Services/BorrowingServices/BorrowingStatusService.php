<?php

namespace App\Services\BorrowingServices;

use App\Models\Borrowing;
use Illuminate\Support\Facades\Log;

class BorrowingStatusService
{
    /**
     * Handle returning a borrowed book
     *
     * @param Borrowing $borrowing
     * @return array
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
     * @param Borrowing $borrowing
     * @param string $action
     * @return array
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
     * @param Borrowing $borrowing
     * @param string $newStatus
     * @return array
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
     * @param string $message
     * @param \Exception $e
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
