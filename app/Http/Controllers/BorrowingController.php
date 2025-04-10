<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Http\Requests\StoreBorrowingRequest;
use App\Services\BorrowingServices\BorrowingCreateService;
use App\Services\BorrowingServices\BorrowingDataTableService;
use App\Services\BorrowingServices\BorrowingDetailService;
use App\Services\BorrowingServices\BorrowingStatusService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class BorrowingController extends Controller
{
    public function __construct(
        protected Borrowing $borrowing,
        protected BorrowingDataTableService $dataTableService,
        protected BorrowingCreateService $createService,
        protected BorrowingDetailService $detailService,
        protected BorrowingStatusService $statusService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->dataTableService->handle($this->borrowing);
        }

        return view('borrowings.index');
    }

    /**
     * Store a newly created resource in storage.
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
     * Display the specified resource.
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, Borrowing $borrowing)
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
