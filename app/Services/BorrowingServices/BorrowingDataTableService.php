<?php

namespace App\Services\BorrowingServices;

use App\Models\Borrowing;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

/**
 * BorrowingDataTableService
 *
 * Responsible for configuring and building the DataTable for borrowings display.
 * Handles filter logic, column formatting, and action button generation.
 */
class BorrowingDataTableService
{
    /**
     * Handle DataTable setup for borrowings listing
     *
     * Configures a DataTable for borrowing records with the following features:
     * - User permission-based filtering (users see only their own borrowings unless admin)
     * - Eager loading of book and user relationships for performance
     * - Custom formatting for status display with appropriate styling
     * - Dynamic action buttons based on user permissions and borrowing state
     *
     * @param Borrowing $borrowing The borrowing model for querying
     * @return \Illuminate\Http\JsonResponse JSON response for DataTables
     */
    public function handle(Borrowing $borrowing)
    {
        $query = $borrowing->with(['book', 'user']);

        // Filter based on user permissions
        if (!Auth::check() || !Auth::user()->can('view all borrowings')) {
            // If not authenticated or doesn't have permission to view all, only show their own
            $query->where('user_id', Auth::id());
        }

        return DataTables::of($query)
            ->addColumn('book_title', function ($row) {
                return $row->book->title;
            })
            ->addColumn('username', function ($row) {
                return $row->user->name;
            })
            ->editColumn('borrowing_status', function ($row) {
                $statusClasses = [
                    'pending' => 'badge bg-warning',
                    'approved' => 'badge bg-success',
                    'rejected' => 'badge bg-danger',
                    'returned' => 'badge bg-info'
                ];
                $class = $statusClasses[$row->borrowing_status] ?? 'badge bg-secondary';
                return '<span class="' . $class . '">' . ucfirst($row->borrowing_status) . '</span>';
            })
            ->addColumn('action', function ($row) {
                return $this->generateActionButtons($row);
            })
            ->rawColumns(['borrowing_status', 'action'])
            ->make(true);
    }

    /**
     * Generate action buttons HTML for each borrowing row
     *
     * Creates a set of buttons based on:
     * - The current user's permissions
     * - The status of the borrowing
     * - The relationship between the user and the borrowing
     *
     * Possible buttons include:
     * - Return: For borrowers to return their approved borrowings
     * - Approve/Reject: For admins to process pending requests
     * - Manage: For admins to manage any borrowing
     * - View: For all users to see borrowing details
     *
     * @param Borrowing $row The borrowing record
     * @return string HTML string with action buttons
     */
    private function generateActionButtons($row)
    {
        $buttons = '';

        // Return button (visible to user for their own borrowings)
        if (Auth::check() && (Auth::id() == $row->user_id) && $row->borrowing_status === 'approved') {
            $buttons .= '<a href="javascript:void(0)" class="btn btn-sm btn-primary returnButton me-1" data-id="' . $row->id . '">Return</a> ';
        }

        // Admin actions for pending borrowings
        if (Auth::check() && Auth::user()->can('update borrowings status') && $row->borrowing_status === 'pending') {
            $buttons .= '<a href="javascript:void(0)" class="btn btn-sm btn-success approveButton me-1" data-id="' . $row->id . '">Approve</a> ';
            $buttons .= '<a href="javascript:void(0)" class="btn btn-sm btn-danger rejectButton me-1" data-id="' . $row->id . '">Reject</a> ';
        }

        // Manage button for admins
        if (Auth::check() && Auth::user()->can('edit borrowings')) {
            $buttons .= '<a href="javascript:void(0)" class="btn btn-sm btn-info manageButton me-1" data-id="' . $row->id . '">Manage</a> ';
        }

        // View detail button for everyone
        $buttons .= '<a href="javascript:void(0)" class="btn btn-sm btn-secondary viewButton" data-id="' . $row->id . '">View</a>';

        return $buttons;
    }
}
