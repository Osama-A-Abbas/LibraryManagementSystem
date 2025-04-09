<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
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

Route::resource('borrows', BorrowingController::class);







Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__.'/auth.php';
