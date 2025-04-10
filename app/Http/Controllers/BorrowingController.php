<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Http\Requests\Borrowing\StoreBorrowingRequest;
use App\Http\Requests\Borrowing\UpdateBorrowingRequest;
use App\Services\BorrowingServices\BorrowingCreateService;
use App\Services\BorrowingServices\BorrowingDataTableService;
use App\Services\BorrowingServices\BorrowingDetailService;
use App\Services\BorrowingServices\BorrowingStatusService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


/**
 * BorrowingController
 *
 * Manages all borrowing operations including listing, creation,
 * viewing details, and updating borrowing statuses.
 *
 * This controller uses specialized services to handle different aspects of
 * borrowing management, following SOLID principles and separation of concerns.
 */
class BorrowingController extends Controller
{
    /**
     * Constructor with dependency injection for all required services
     *
     * @param Borrowing $borrowing The Borrowing model
     * @param BorrowingDataTableService $dataTableService Service for DataTable operations
     * @param BorrowingCreateService $createService Service for creating borrowings
     * @param BorrowingDetailService $detailService Service for fetching borrowing details
     * @param BorrowingStatusService $statusService Service for managing borrowing statuses
     */
    public function __construct(
        protected Borrowing $borrowing,
        protected BorrowingDataTableService $dataTableService,
        protected BorrowingCreateService $createService,
        protected BorrowingDetailService $detailService,
        protected BorrowingStatusService $statusService
    ) {}

    /**
     * Display a listing of borrowings
     *
     * Returns a DataTable view for AJAX requests or the index view for regular requests.
     * Uses the BorrowingDataTableService to handle DataTable configuration and rendering.
     *
     * @param Request $request The HTTP request
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse View or JSON response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->dataTableService->handle($this->borrowing);
        }

        return view('borrowings.index');
    }

    /**
     * Store a new borrowing request
     *
     * Uses BorrowingCreateService to handle validation, creation, and error handling.
     * Returns appropriate JSON response based on the operation result.
     *
     * @param StoreBorrowingRequest $request Validated request with borrowing data
     * @return \Illuminate\Http\JsonResponse JSON response with status
     */
    public function store(StoreBorrowingRequest $request)
    {
        $result = $this->createService->handle(
            $this->borrowing,
            $request->validated(),
            $request->user()->id
        );

        return response()->json(
            isset($result['success'])
                ? ['success' => $result['success']]
                : ['error' => $result['error']],
            $result['status'] ?? 200
        );
    }

    /**
     * Display detailed information about a specific borrowing
     *
     * First checks if the user has permission to view the borrowing,
     * then uses BorrowingDetailService to load and format borrowing data.
     *
     * @param Borrowing $borrowing The borrowing to display
     * @return \Illuminate\Http\JsonResponse JSON response with borrowing details
     */
    public function show(Borrowing $borrowing)
    {
        // Check if user has permission to view this borrowing
        if (!Auth::check() || (Auth::id() !== $borrowing->user_id && !Auth::user()->can('view all borrowings'))) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json($this->detailService->getDetails($borrowing));
    }

    /**
     * Update a borrowing's status based on the requested action
     *
     * Handles different status change operations including:
     * - return: Mark a borrowing as returned
     * - approve/reject: Approve or reject a pending borrowing request
     * - update_status: Manually update status to any valid status
     *
     * Uses BorrowingStatusService to handle the specific logic for each action.
     * Includes permission checks before allowing status changes.
     *
     * @param UpdateBorrowingRequest $request The validated request with action and status data
     * @param Borrowing $borrowing The borrowing to update
     * @return \Illuminate\Http\JsonResponse JSON response with operation result
     */
    public function update(UpdateBorrowingRequest $request, Borrowing $borrowing)
    {
        $action = $request->input('action');
        $result = [];

        switch ($action) {
            case 'return':
                // Users can return their own books
                if (!Auth::check() || (Auth::id() !== $borrowing->user_id && !Auth::user()->can('update borrowings status'))) {
                    return response()->json(['error' => 'Unauthorized'], 403);
                }
                $result = $this->statusService->handleReturn($borrowing);
                break;

            case 'approve':
            case 'reject':
                // Only admins can approve/reject borrowing requests
                if (!Auth::check() || !Auth::user()->can('update borrowings status')) {
                    return response()->json(['error' => 'Unauthorized'], 403);
                }
                $result = $this->statusService->handleApproveReject($borrowing, $action);
                break;

            case 'update_status':
                // Only admins can manually update status
                if (!Auth::check() || !Auth::user()->can('update borrowings status')) {
                    return response()->json(['error' => 'Unauthorized'], 403);
                }
                $result = $this->statusService->handleStatusUpdate($borrowing, $request->input('status'));
                break;

            default:
                return response()->json(['error' => 'Invalid action'], 400);
        }

        return response()->json(
            isset($result['success'])
                ? ['success' => $result['success']]
                : ['error' => $result['error']],
            $result['status'] ?? 200
        );
    }
}
