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
        $request->validate([ //data validation
            'title' => 'required|string|min:1|max:255',
            'genre' => 'required|string|min:1|max:255',
        ]);

        $this->book->create([ //create a new book
            'title' => $request->title,
            'genre' => $request->genre,
        ]);

        return response()->json([ // return a json response
            'success' => "Book Created Successfully",
        ], 201); // it is important to return a correct status code here
    }
}
