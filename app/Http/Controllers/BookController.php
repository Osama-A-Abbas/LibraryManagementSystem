<?php

namespace App\Http\Controllers;

use App\Http\Requests\Book\StoreBookRequest;
use App\Http\Requests\Book\UpdateBookRequest;
use App\Models\Book;
use App\Services\Book\BookService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{

    public function __construct(
        protected Book $book,
        protected BookService $bookService,
    ) {}

    public function create()
    {
        return view('books.create');
    }

    public function index()
    {
        $dataTables = $this->bookService->setBookDataTable();
       return $dataTables->make(true);
    }

    public function store(StoreBookRequest $request)
    {
        try {
            $this->bookService->store($request);
            return response()->json(['success' => 'Book created successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create book: ' . $e->getMessage()], 500);
        }
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
            'cover_page' => $book->cover_page ? asset('storage/' . $book->cover_page) : null
        ]);
    }

    public function update(UpdateBookRequest $request, Book $book)
{
    // Update non-file fields
    $book->update($request->only(['title', 'genre', 'author', 'description', 'published_at']));

    // Check if a new file is uploaded
    if ($request->hasFile('cover_page')) {
        // Delete the old file if it exists
        if ($book->cover_page && Storage::disk('public')->exists($book->cover_page)) {
            Storage::disk('public')->delete($book->cover_page);
        }

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

        // Update the cover_page field in the database
        $book->update(['cover_page' => $path]);
    }

    return response()->json(['success' => 'Book updated successfully.']);
}

    public function destroy(Book $book)
    {
        $book->delete();

        return response()->json(['success' => 'Book deleted successfully.']);
    }
}
