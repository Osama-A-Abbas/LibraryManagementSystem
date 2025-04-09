<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Http\Requests\StoreBorrowingRequest;
use App\Http\Requests\UpdateBorrowingRequest;
use Illuminate\Support\Facades\Log;

class BorrowingController extends Controller
{

    public function __construct(
        protected Borrowing $borrowing,
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        //
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
    public function update(UpdateBorrowingRequest $request, Borrowing $borrowing)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Borrowing $borrowing)
    {
        //
    }
}
