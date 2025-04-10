<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Borrowing>
 */
class BorrowingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Borrowing::class;

    public function definition(): array
    {
        // Get random available book (with copies > 0)
        $bookIds = Book::where('number_of_copies', '>', 0)->pluck('id')->toArray();
        if (empty($bookIds)) {
            // If no available books, create one
            $book = Book::factory()->available()->create();
            $bookId = $book->id;
        } else {
            $bookId = $this->faker->randomElement($bookIds);
        }

        // Get or create a user
        $userIds = User::pluck('id')->toArray();
        if (empty($userIds)) {
            // If no users, create one
            $user = User::factory()->create();
            $userId = $user->id;
        } else {
            $userId = $this->faker->randomElement($userIds);
        }

        // Create a borrow date between 1 year ago and now
        $borrowAt = $this->faker->dateTimeBetween('-1 year', 'now');

        // Create a return date between the borrow date and 60 days later
        $returnAt = $this->faker->dateTimeBetween(
            $borrowAt,
            (clone $borrowAt)->modify('+60 days')
        );

        return [
            'user_id' => $userId,
            'book_id' => $bookId,
            'borrowing_status' => $this->faker->randomElement(['pending', 'approved', 'rejected', 'returned']),
            'borrow_at' => $borrowAt,
            'return_at' => $returnAt,
            'notes' => $this->faker->optional(0.7)->sentence(),
        ];
    }

    /**
     * Indicate that the borrowing is pending.
     */
    public function pending(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'borrowing_status' => 'pending',
            ];
        });
    }

    /**
     * Indicate that the borrowing is approved.
     */
    public function approved(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'borrowing_status' => 'approved',
            ];
        });
    }

    /**
     * Indicate that the borrowing is rejected.
     */
    public function rejected(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'borrowing_status' => 'rejected',
            ];
        });
    }

    /**
     * Indicate that the borrowing is returned.
     */
    public function returned(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'borrowing_status' => 'returned',
            ];
        });
    }
}
