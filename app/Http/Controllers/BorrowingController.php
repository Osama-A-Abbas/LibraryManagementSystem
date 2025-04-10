<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Http\Requests\StoreBorrowingRequest;
use App\Http\Requests\UpdateBorrowingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class BorrowingController extends Controller
{
    public function __construct(
        protected Borrowing $borrowing,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = $this->borrowing->with(['book', 'user']);

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
                    $buttons = '';

                    // Return button (visible to user for their own borrowings)
                    if (Auth::check() && (Auth::id() == $row->user_id) && $row->borrowing_status === 'approved') {
                        $buttons .= '<a href="javascript:void(0)" class="btn btn-sm btn-primary returnButton" data-id="' . $row->id . '">Return</a> ';
                    }

                    // Admin actions for pending borrowings
                    if (Auth::check() && Auth::user()->can('update borrowings status') && $row->borrowing_status === 'pending') {
                        $buttons .= '<a href="javascript:void(0)" class="btn btn-sm btn-success approveButton" data-id="' . $row->id . '">Approve</a> ';
                        $buttons .= '<a href="javascript:void(0)" class="btn btn-sm btn-danger rejectButton" data-id="' . $row->id . '">Reject</a> ';
                    }

                    // Manage button for admins
                    if (Auth::check() && Auth::user()->can('edit borrowings')) {
                        $buttons .= '<a href="javascript:void(0)" class="btn btn-sm btn-info manageButton" data-id="' . $row->id . '">Manage</a> ';
                    }

                    // View detail button for everyone
                    $buttons .= '<a href="javascript:void(0)" class="btn btn-sm btn-secondary viewButton" data-id="' . $row->id . '">View</a>';

                    return $buttons;
                })
                ->rawColumns(['borrowing_status', 'action'])
                ->make(true);
        }

        return view('borrowings.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBorrowingRequest $request)
    {
        // Log the request data for debugging
        Log::info('Borrowing request received', ['data' => $request->all()]);

        $validated = $request->validated();
        try {
            // Create the borrowing record with correct field names
            $borrowing = $this->borrowing->create([
                'book_id' => $validated['book_id'],
                'user_id' => $request->user()->id,
                'borrowing_status' => 'pending',
                'borrow_at' => $validated['borrow_at'],
                'return_at' => $validated['return_at'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            Log::info('Borrowing created successfully', ['borrowing' => $borrowing->toArray()]);

            return response()->json(['success' => 'Borrow Request Sent.'], 201);
        } catch (\Exception $e) {
            Log::error('Failed to create borrowing', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['error' => 'Failed to send borrow request: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Borrowing $borrowing)
    {
        // Check if user has permission to view this borrowing
        if (!Auth::check() || (Auth::id() !== $borrowing->user_id && !Auth::user()->can('view all borrowings'))) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $borrowing->load(['book', 'user']);

        return response()->json([
            'borrowing' => $borrowing,
            'book_title' => $borrowing->book->title,
            'username' => $borrowing->user->name
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Borrowing $borrowing)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Borrowing $borrowing)
    {
        // Check permissions based on the action
        $action = $request->input('action');

        if ($action === 'return') {
            // Users can return their own books
            if (!Auth::check() || (Auth::id() !== $borrowing->user_id && !Auth::user()->can('update borrowings status'))) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            try {
                $borrowing->borrowing_status = 'returned';
                $borrowing->save();

                return response()->json(['success' => 'Book has been returned successfully.']);
            } catch (\Exception $e) {
                Log::error('Failed to return book', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);

                return response()->json(['error' => 'Failed to return book: ' . $e->getMessage()], 500);
            }
        } else if (in_array($action, ['approve', 'reject'])) {
            // Only admins can approve/reject borrowing requests
            if (!Auth::check() || !Auth::user()->can('update borrowings status')) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            try {
                $borrowing->borrowing_status = $action === 'approve' ? 'approved' : 'rejected';
                $borrowing->save();

                return response()->json(['success' => 'Borrowing request has been ' . $action . 'd successfully.']);
            } catch (\Exception $e) {
                Log::error('Failed to update borrowing status', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);

                return response()->json(['error' => 'Failed to update borrowing status: ' . $e->getMessage()], 500);
            }
        } else if ($action === 'update_status') {
            // Only admins can manually update status
            if (!Auth::check() || !Auth::user()->can('update borrowings status')) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            try {
                $newStatus = $request->input('status');
                if (!in_array($newStatus, ['pending', 'approved', 'rejected', 'returned'])) {
                    return response()->json(['error' => 'Invalid status'], 400);
                }

                $borrowing->borrowing_status = $newStatus;
                $borrowing->save();

                return response()->json(['success' => 'Borrowing status updated successfully.']);
            } catch (\Exception $e) {
                Log::error('Failed to update borrowing status', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);

                return response()->json(['error' => 'Failed to update borrowing status: ' . $e->getMessage()], 500);
            }
        }

        return response()->json(['error' => 'Invalid action'], 400);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Borrowing $borrowing)
    {
        //
    }
}
