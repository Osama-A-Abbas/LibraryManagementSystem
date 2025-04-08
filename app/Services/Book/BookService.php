<?php

namespace App\Services\Book;

use App\Models\Book;

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
    public function setBookDataTable() // used in BookController *index* method
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
}
