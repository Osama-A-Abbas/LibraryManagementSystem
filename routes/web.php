<?php

use App\Http\Controllers\BookController;
use App\Models\Category;
use Illuminate\Support\Facades\Route;

Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
