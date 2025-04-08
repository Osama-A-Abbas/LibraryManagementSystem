<?php

namespace App\Services\Book;

use App\Models\Book;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BookService
{
    public function __construct(
        protected Book $book,
    ) {}

    /**
     * Prepare the books listing for the DataTables.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function setBookDataTable(): \Yajra\DataTables\DataTableAbstract // used in BookController *index* method
    {
        $books = $this->book->select(['id', 'title', 'genre', 'author', 'description', 'published_at', 'cover_page']);
        $dataTable = datatables()->of($books)
            ->editColumn('genre', function ($book) {
                return ucfirst($book->genre); // Capitalize the first letter of the genre
            })
            ->editColumn('cover_page', function ($book) {
                if ($book->cover_page) {
                    return '<img src="' . asset('storage/' . $book->cover_page) . '" alt="Book Cover" class="img-thumbnail" style="max-height: 50px;">';
                }
                return 'No Cover';
            })
            ->addColumn('action', function ($row) {
                return '<a href="javascript:void(0)" class="btn btn-sm btn-info editButton" data-id="' . $row->id . '">Edit</a>
                        <a href="javascript:void(0)" class="btn btn-sm btn-danger deleteButton" data-id="' . $row->id . '">Delete</a>';
            })
            ->rawColumns(['action', 'cover_page']);

        return $dataTable;
    }

    /**
     * Store a new book with cover image.
     *
     * @param \Illuminate\Http\Request $request
     * @return \App\Models\Book
     * @throws \Exception
     */
    public function store($request)
    {
        $path = null;

        try {
            DB::beginTransaction();

            $book = new Book();
            $book->title = $request->title;
            $book->genre = $request->genre;
            $book->author = $request->author;
            $book->description = $request->description;
            $book->published_at = $request->published_at;

            // Handle file upload if present
            if ($request->hasFile('cover_page')) {
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
            }

            $book->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            // If there was a file upload, try to delete it
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            throw $e;
        }
    }
}
