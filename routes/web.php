<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
Route::get('/books/index', [BookController::class, 'index'])->name('books.index');
Route::post('/books/store', [BookController::class, 'store'])->name('books.store');
Route::get('/books/{id}/edit', [BookController::class, 'edit'])->name('books.edit');
Route::post('/books/{id}/update', [BookController::class, 'update'])->name('books.update');
Route::delete('/books/{id}/delete', [BookController::class, 'destroy'])->name('books.destroy');

// Route::get('/books', [BookController::class, 'index']);
// Route::post('/books', [BookController::class, 'store']);
// Route::get('/books/{id}', [BookController::class, 'show']);
// Route::put('/books/{id}', [BookController::class, 'update']);
// Route::delete('/books/{id}', [BookController::class, 'destroy']);