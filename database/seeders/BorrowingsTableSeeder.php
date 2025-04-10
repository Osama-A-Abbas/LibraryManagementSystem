<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BorrowingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure we have enough available books for borrowings
        $availableBookCount = Book::where('number_of_copies', '>', 0)->count();
        if ($availableBookCount < 50) {
            $this->command->info('Creating additional available books...');
            Book::factory()->available()->count(50 - $availableBookCount)->create();
        }

        // Ensure we have enough users for borrowings
        $userCount = User::count();
        if ($userCount < 20) {
            $this->command->info('Creating additional users...');
            User::factory()->count(20 - $userCount)->create();
        }

        // Create borrowings with different statuses
        $this->command->info('Creating borrowings with different statuses...');

        // Create 100 pending borrowings
        Borrowing::factory()
            ->pending()
            ->count(100)
            ->create();

        // Create 300 approved borrowings
        Borrowing::factory()
            ->approved()
            ->count(300)
            ->create();

        // Create 200 rejected borrowings
        Borrowing::factory()
            ->rejected()
            ->count(200)
            ->create();

        // Create 400 returned borrowings
        Borrowing::factory()
            ->returned()
            ->count(400)
            ->create();

        $this->command->info('1000 borrowing records created successfully:');
        $this->command->info('- 100 pending borrowings');
        $this->command->info('- 300 approved borrowings');
        $this->command->info('- 200 rejected borrowings');
        $this->command->info('- 400 returned borrowings');
    }
}
