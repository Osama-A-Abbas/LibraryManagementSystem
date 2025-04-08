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
            $this->bookService->storeBook($request);
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
            'cover_page' => $book->cover_page ? asset('storage/' . $book->cover_page) : null,
            // 'book_pdf' => $book->book_pdf ? asset('storage/' . $book->book_pdf) : null
        ]);
    }

    public function update(UpdateBookRequest $request, Book $book)
    {
        try {
            $this->bookService->updateBook($request, $book);
            return response()->json(['success' => 'Book updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update book: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Book $book)
    {
        $this->bookService->deleteBook($book);
        return response()->json(['success' => 'Book deleted successfully.']);
    }

}
