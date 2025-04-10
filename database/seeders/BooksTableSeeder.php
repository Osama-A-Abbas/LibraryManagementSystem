<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BooksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 800 available books
        Book::factory()
            ->available()
            ->count(800)
            ->create();

        // Create 200 unavailable books
        Book::factory()
            ->unavailable()
            ->count(200)
            ->create();

        $this->command->info('1000 book records created successfully: 800 available, 200 unavailable.');
    }
}
