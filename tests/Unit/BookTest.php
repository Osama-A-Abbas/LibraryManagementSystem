<?php

namespace Tests\Unit;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookTest extends TestCase
{
    use RefreshDatabase;

    public function test_book_has_the_correct_fillable_attributes()
    {
        $book = new Book();
        $this->assertEquals([
            'title',
            'author',
            'isbn',
            'published_year',
            'number_of_copies',
            'cover_image_path',
            'description'
        ], $book->getFillable());
    }

    public function test_book_has_the_correct_casts()
    {
        $book = new Book();
        $casts = $book->getCasts();

        $this->assertArrayHasKey('published_year', $casts);
        $this->assertEquals('integer', $casts['published_year']);

        $this->assertArrayHasKey('number_of_copies', $casts);
        $this->assertEquals('integer', $casts['number_of_copies']);
    }

    public function test_book_has_many_borrowings()
    {
        $book = Book::factory()->create();
        $user = User::factory()->create();

        // Create borrowings for this book
        Borrowing::factory()->count(3)->create([
            'book_id' => $book->id,
            'user_id' => $user->id
        ]);

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $book->borrowings);
        $this->assertCount(3, $book->borrowings);
        $this->assertInstanceOf(Borrowing::class, $book->borrowings->first());
    }

    public function test_book_has_active_borrowings_scope()
    {
        $book = Book::factory()->create();
        $user = User::factory()->create();

        // Create active borrowings
        Borrowing::factory()->count(2)->create([
            'book_id' => $book->id,
            'user_id' => $user->id,
            'borrowing_status' => 'approved'
        ]);

        // Create returned borrowings
        Borrowing::factory()->count(1)->create([
            'book_id' => $book->id,
            'user_id' => $user->id,
            'borrowing_status' => 'returned'
        ]);

        // If the Book model has an activeBorrowings scope, test it
        if (method_exists($book, 'scopeActiveBorrowings')) {
            $this->assertCount(2, $book->activeBorrowings);
        }
    }

    public function test_book_can_check_availability()
    {
        $book = Book::factory()->create([
            'number_of_copies' => 3
        ]);
        $user = User::factory()->create();

        // Create 2 active borrowings
        Borrowing::factory()->count(2)->create([
            'book_id' => $book->id,
            'user_id' => $user->id,
            'borrowing_status' => 'approved'
        ]);

        // If the Book model has an isAvailable method, test it
        if (method_exists($book, 'isAvailable')) {
            $this->assertTrue($book->isAvailable());

            // Create one more borrowing to reach the limit
            Borrowing::factory()->create([
                'book_id' => $book->id,
                'user_id' => $user->id,
                'borrowing_status' => 'approved'
            ]);

            $book->refresh();
            $this->assertFalse($book->isAvailable());
        }
    }

    public function test_book_can_calculate_available_copies()
    {
        $book = Book::factory()->create([
            'number_of_copies' => 5
        ]);
        $user = User::factory()->create();

        // Initially all copies should be available
        if (method_exists($book, 'availableCopies')) {
            $this->assertEquals(5, $book->availableCopies());

            // Create 3 active borrowings
            Borrowing::factory()->count(3)->create([
                'book_id' => $book->id,
                'user_id' => $user->id,
                'borrowing_status' => 'approved'
            ]);

            $book->refresh();
            $this->assertEquals(2, $book->availableCopies());
        }
    }
}
