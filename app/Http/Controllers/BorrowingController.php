<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Http\Requests\StoreBorrowingRequest;
use App\Http\Requests\UpdateBorrowingRequest;

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
        $validated = $request->validated();
        try {
            $this->borrowing->create([
                ...$validated,
                'user_id' => $request->user()->id // or any user id you need to assign
            ]);

            return response()->json(['success' => 'Borrow Request Sent.'], 201);
        } catch (\Exception $e) {
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
