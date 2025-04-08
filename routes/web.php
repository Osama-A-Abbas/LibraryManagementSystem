<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;

use App\Http\Controllers\PdfController;

Route::get('/', function () {
    return view('welcome');
});

//Book Controller
Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
Route::get('/books/index', [BookController::class, 'index'])->name('books.index');
Route::post('/books/store', [BookController::class, 'store'])->name('books.store');
Route::get('/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
Route::post('/books/{book}/update', [BookController::class, 'update'])->name('books.update');
Route::delete('/books/{book}/delete', [BookController::class, 'destroy'])->name('books.destroy');


//PDF Controller
Route::get('/books/{book}/download', [PdfController::class, 'downloadPdf'])->name('books.download');
Route::get('/books/{book}/view', [PdfController::class, 'viewBookPdf'])->name('books.view');


// Route::get('/books', [BookController::class, 'index']);
// Route::post('/books', [BookController::class, 'store']);
// Route::get('/books/{id}', [BookController::class, 'show']);
// Route::put('/books/{id}', [BookController::class, 'update']);
// Route::delete('/books/{id}', [BookController::class, 'destroy']);
