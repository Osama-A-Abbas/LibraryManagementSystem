<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Book::class;

    public function definition(): array
    {
        $genres = [
            'Fiction', 'Non-Fiction', 'Science Fiction', 'Fantasy', 'Mystery',
            'Biography', 'Self-Help', 'Romance', 'Thriller', 'Historical',
            'Poetry', 'Graphic Novel', "Children's", 'Young Adult', 'Cookbook',
            'Travel', 'Health', 'Religion', 'Philosophy', 'Business',
            'Technology', 'True Crime', 'Memoir', 'Action', 'Adventure',
            'Comedy', 'Drama', 'Horror', 'Western',
        ];

        // Generate a random date between 20 years ago and today
        $publishDate = $this->faker->dateTimeBetween('-20 years', 'now');

        // Generate a random number of copies between 0 and 50
        $copies = $this->faker->numberBetween(0, 50);

        // Available is automatically set by the model based on copies

        return [
            'title' => rtrim($this->faker->sentence(rand(3, 8)), '.'),
            'genre' => $this->faker->randomElement($genres),
            'author' => $this->faker->name(),
            'isbn' => $this->faker->isbn13(),
            'description' => $this->faker->paragraphs(rand(1, 3), true),
            'published_at' => $publishDate,
            'number_of_copies' => $copies,
            // is_available will be set automatically by the model's saving event
            // cover_page and book_pdf are not set by the factory as they're file paths
        ];
    }

    /**
     * Indicate that the book is available for borrowing.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function available()
    {
        return $this->state(function (array $attributes) {
            return [
                'number_of_copies' => $this->faker->numberBetween(1, 50),
            ];
        });
    }

    /**
     * Indicate that the book is not available for borrowing.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unavailable()
    {
        return $this->state(function (array $attributes) {
            return [
                'number_of_copies' => 0,
            ];
        });
    }
}
