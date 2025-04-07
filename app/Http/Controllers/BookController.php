<?php

namespace App\Http\Controllers;

use App\Http\Requests\Book\BookRequest;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{

    public function __construct(
        protected Book $book,
    ) {}

    public function create()
    {
        return view('books.create');
    }

    public function index()
    {
        $books = $this->book->select(['id', 'title', 'genre', 'author', 'description', 'published_at', 'cover_page']);
        return datatables()->of($books)
            ->editColumn('genre', function ($book) {
                return ucfirst($book->genre); // Capitalize the first letter of the genre
            })
            ->addColumn('action', function ($row) {
                return '<a href="javascript:void(0)" class="btn btn-sm btn-info editButton" data-id="' . $row->id . '">Edit</a>
                        <a href="javascript:void(0)" class="btn btn-sm btn-danger deleteButton" data-id="' . $row->id . '">Delete</a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(BookRequest $request)
    {
        // $this->book->create($request->only(['id', 'title', 'genre', 'author', 'description', 'published_at',]));
        $book = new Book();
        $book->title = $request->title;
        $book->genre = $request->genre;
        $book->author = $request->author;
        $book->description = $request->description;
        $book->published_at = $request->published_at;

        // Get the original file extension
        $extension = $request->file('cover_page')->getClientOriginalExtension();
        // Create a unique filename with timestamp
        $fileName = uniqid() . '_' . time() . '.' . $extension;
        // Store the file in the public/books/covers directory
        $path = $request->file('cover_page')->storeAs(
            'books/covers',
            $fileName,
            'public'
        );
        // Save the path to the database
        $book->cover_page = $path;
        $book->save();

        return response()->json(['success' => 'Book created successfully.']);
    }

    public function edit(Book $book)
    {
        return response()->json([
            'id' => $book->id,
            'title' => $book->title,
            'genre' => $book->genre,
            'author' => $book->author,
            'description' => $book->description,
            'published_at' => $book->published_at,
            'cover_page' => $book->cover_page
        ]);
    }

    public function update(BookRequest $request, Book $book)
    {
        $book->update($request->all());

        return response()->json(['success' => 'Book updated successfully.']);
    }

    public function destroy(Book $book)
    {
        $book->delete();

        return response()->json(['success' => 'Book deleted successfully.']);
    }
}
