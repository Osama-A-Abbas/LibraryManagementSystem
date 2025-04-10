<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create admin role
    $adminRole = Role::create(['name' => 'admin']);

    // Create an admin user
    $this->admin = User::factory()->create();
    $this->admin->assignRole($adminRole);

    // Create a regular user
    $this->user = User::factory()->create();

    // Create a book for borrowing
    $this->book = Book::factory()->create([
        'number_of_copies' => 5
    ]);
});

test('user can create a borrowing request', function () {
    // Login as a regular user
    Auth::login($this->user);

    // Create a new borrowing request
    $borrowing = Borrowing::create([
        'user_id' => $this->user->id,
        'book_id' => $this->book->id,
        'borrowing_status' => 'pending',
        'borrow_at' => now(),
        'return_at' => now()->addDays(14),
        'notes' => 'Need this book for my research',
    ]);

    // Assert the borrowing was created
    $this->assertDatabaseHas('borrowings', [
        'id' => $borrowing->id,
        'user_id' => $this->user->id,
        'book_id' => $this->book->id,
        'borrowing_status' => 'pending',
    ]);

    // Verify borrowing relationships
    expect($borrowing->user->id)->toBe($this->user->id);
    expect($borrowing->book->id)->toBe($this->book->id);
});

test('admin can approve a borrowing request', function () {
    // Create a pending borrowing request
    $borrowing = Borrowing::factory()->create([
        'user_id' => $this->user->id,
        'book_id' => $this->book->id,
        'borrowing_status' => 'pending',
    ]);

    // Login as admin
    Auth::login($this->admin);

    // Approve the borrowing
    $borrowing->update([
        'borrowing_status' => 'approved',
        'notes' => 'Approved by admin',
    ]);

    // Assert the borrowing status was changed
    $this->assertDatabaseHas('borrowings', [
        'id' => $borrowing->id,
        'borrowing_status' => 'approved',
        'notes' => 'Approved by admin',
    ]);
});

test('admin can reject a borrowing request', function () {
    // Create a pending borrowing request
    $borrowing = Borrowing::factory()->create([
        'user_id' => $this->user->id,
        'book_id' => $this->book->id,
        'borrowing_status' => 'pending',
    ]);

    // Login as admin
    Auth::login($this->admin);

    // Reject the borrowing
    $borrowing->update([
        'borrowing_status' => 'rejected',
        'notes' => 'Book not available for borrowing',
    ]);

    // Assert the borrowing status was changed
    $this->assertDatabaseHas('borrowings', [
        'id' => $borrowing->id,
        'borrowing_status' => 'rejected',
        'notes' => 'Book not available for borrowing',
    ]);
});

test('admin can mark a borrowing as returned', function () {
    // Create an approved borrowing
    $borrowing = Borrowing::factory()->create([
        'user_id' => $this->user->id,
        'book_id' => $this->book->id,
        'borrowing_status' => 'approved',
        'borrow_at' => now()->subDays(7),
        'return_at' => now()->addDays(7),
    ]);

    // Login as admin
    Auth::login($this->admin);

    // Mark as returned
    $borrowing->update([
        'borrowing_status' => 'returned',
        'notes' => 'Book returned in good condition',
    ]);

    // Assert the borrowing status was changed
    $this->assertDatabaseHas('borrowings', [
        'id' => $borrowing->id,
        'borrowing_status' => 'returned',
        'notes' => 'Book returned in good condition',
    ]);
});
