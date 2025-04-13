<?php

namespace App\Http\Controllers;

use App\Http\Requests\Book\StoreBookRequest;
use App\Http\Requests\Book\UpdateBookRequest;
use App\Models\Book;
use App\Services\Book\BookService;
use App\Services\Book\SetBookDataTable;
use Illuminate\Support\Facades\Log;

/**
 * BookController handles all book-related operations in the library system.
 *
 * This controller manages book creation, listing, editing, updating, and deletion,
 * working with the Book model and related services to implement business logic.
 */
class BookController extends Controller
{
    /**
     * Constructor with dependency injection.
     *
     * @param Book $book Book model instance for database operations
     * @param BookService $bookService Service handling book business logic
     * @param SetBookDataTable $setBookDataTable Service for DataTables integration
     */
    public function __construct(
        protected Book $book,
        protected BookService $bookService,
        protected SetBookDataTable $setBookDataTable
    ) {}

    /**
     * Display the book management interface.
     *
     * @return \Illuminate\View\View The books main component view
     */
    public function create()
    {
        return view('components.books-main');
    }

    /**
     * Get books data for DataTables.
     *
     * This method is called via AJAX to populate the DataTables
     * with book information.
     *
     * @return \Illuminate\Http\JsonResponse DataTables response
     */
    public function index()
    {
        $dataTables = $this->setBookDataTable->handle();
        return $dataTables->make(true);
    }

    /**
     * Store a newly created book in the database.
     *
     * @param StoreBookRequest $request Validated book creation request
     * @return \Illuminate\Http\JsonResponse JSON response with result
     */
    public function store(StoreBookRequest $request)
    {
        try {
            $book = $this->bookService->storeBook($request);

            // Sync genres
            if ($request->has('genres')) {
                $book->genres()->sync($request->genres);
            }

            return response()->json(['success' => 'Book created successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create book: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get a specific book's data for editing.
     *
     * @param Book $book The book model to edit (route model binding)
     * @return \Illuminate\Http\JsonResponse JSON with book data
     */
    public function edit(Book $book)
    {
        $response = [
            'id' => $book->id,
            'title' => $book->title,
            'genres' => $book->genres,
            'author' => $book->author,
            'description' => $book->description,
            'published_at' => $book->published_at,
            'cover_page' => $book->cover_page ? asset('storage/' . $book->cover_page) : null,
            'number_of_copies' => $book->number_of_copies,
        ];

        return response()->json($response);
    }

    /**
     * Update an existing book in the database.
     *
     * @param UpdateBookRequest $request Validated book update request
     * @param Book $book The book model to update (route model binding)
     * @return \Illuminate\Http\JsonResponse JSON response with result
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        try {
            $this->bookService->updateBook($request, $book);

            // Sync genres
            if ($request->has('genres')) {
                $book->genres()->sync($request->genres);
            }

            return response()->json(['success' => 'Book updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update book: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove a book from the database.
     *
     * @param Book $book The book model to delete (route model binding)
     * @return \Illuminate\Http\JsonResponse JSON response with result
     */
    public function destroy(Book $book)
    {
        $this->bookService->deleteBook($book);
        return response()->json(['success' => 'Book deleted successfully.']);
    }
}
