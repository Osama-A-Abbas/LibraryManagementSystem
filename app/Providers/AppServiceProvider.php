<?php

namespace App\Providers;

use App\Models\Book;
use App\Models\Borrowing;
use Illuminate\Support\ServiceProvider;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('borrow-book', function (User $user, Book $book) {
            return !Borrowing::where('user_id', $user->id)
                ->where('book_id', $book->id)
                ->whereNull('returned_at')
                ->exists();
        });
    }
}
