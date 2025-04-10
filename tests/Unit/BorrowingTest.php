<?php

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('borrowing has the correct fillable attributes', function () {
    $borrowing = new Borrowing();

    expect($borrowing->getFillable())->toEqual([
        'user_id',
        'book_id',
        'borrowing_status',
        'borrow_at',
        'return_at',
        'notes',
    ]);
});

test('borrowing has the correct casts', function () {
    $borrowing = new Borrowing();

    expect($borrowing->getCasts())->toHaveKeys([
        'borrow_at', 'return_at', 'borrowing_status'
    ]);

    expect($borrowing->getCasts()['borrow_at'])->toBe('datetime');
    expect($borrowing->getCasts()['return_at'])->toBe('datetime');
    expect($borrowing->getCasts()['borrowing_status'])->toBe('string');
});

test('borrowing formats borrow_at date correctly', function () {
    $date = now();
    $borrowing = Borrowing::factory()->create([
        'borrow_at' => $date,
    ]);

    expect($borrowing->borrow_at)->toBe($date->format('Y-m-d'));
});

test('borrowing formats return_at date correctly', function () {
    $date = now()->addDays(14);
    $borrowing = Borrowing::factory()->create([
        'return_at' => $date,
    ]);

    expect($borrowing->return_at)->toBe($date->format('Y-m-d'));
});

test('borrowing formats created_at date correctly', function () {
    $borrowing = Borrowing::factory()->create();
    $createdAt = Carbon::parse($borrowing->getRawOriginal('created_at'));

    expect($borrowing->created_at)->toBe($createdAt->format('Y-m-d H:i'));
});

test('borrowing belongs to a user', function () {
    $user = User::factory()->create();
    $borrowing = Borrowing::factory()->create([
        'user_id' => $user->id,
    ]);

    expect($borrowing->user)->toBeInstanceOf(User::class);
    expect($borrowing->user->id)->toBe($user->id);
});

test('borrowing belongs to a book', function () {
    $book = Book::factory()->create();
    $borrowing = Borrowing::factory()->create([
        'book_id' => $book->id,
    ]);

    expect($borrowing->book)->toBeInstanceOf(Book::class);
    expect($borrowing->book->id)->toBe($book->id);
});
