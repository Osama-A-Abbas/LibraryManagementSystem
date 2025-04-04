<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function __construct(
        protected Book $book,
    ){}
    public function create()
    {
        return view('books.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|min:1|max:255',
            'genre' => 'required|string|min:1|max:255',
        ]);

        $this->book->create([
            'title' => $request->title,
            'genre' => $request->genre,
        ]);

        return response()->json([
            'message' => "Book Created Successfully",
        ], 201);
    }
}
