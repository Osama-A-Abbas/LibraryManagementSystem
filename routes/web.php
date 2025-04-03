<?php

use App\Http\Controllers\GenreController;
use App\Models\Category;
use Illuminate\Support\Facades\Route;

Route::get('/genre/create', [GenreController::class, 'create'])->name('genre.create');
