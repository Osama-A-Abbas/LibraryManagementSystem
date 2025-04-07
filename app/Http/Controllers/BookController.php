<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
// use DataTable;
use Yajra\DataTables\Facades\DataTables;

class BookController extends Controller
{
    public function __construct(
        protected Book $book,
    ) {}


    public function index(Request $request)
    {
        $books = Book::select('id', 'title', 'genre');

        if ($request->ajax()) {
            return DataTables::of($books)
                ->editColumn('genre', function ($book) {
                    return ucfirst($book->genre); // Capitalize the first letter of the genre
                })
                ->addColumn('action', function ($row) {
                    return
                        '<a href="javascript:void(0)" class="btn-sm btn btn-info editButton" data-id="' . $row->id . '">Edit</a>
                        <a href="javascript:void(0)" class="btn-sm btn btn-danger deleteButton" data-id="' . $row->id . '">Delete</a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }


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


    public function update(Request $request, Book $book)
    {
        $request->validate([
            'title' => 'required|string|min:1|max:255',
            'genre' => 'required|string|min:1|max:255',
        ]);

        $book->update([
            'title' => $request->title,
            'genre' => $request->genre,
        ]);

        return response()->json([
            'success' => "Book updated Successfully",
        ], 200); // Return success response
    }

    public function edit(Book $book)
    {
        if (! $book) {
            abort(404, 'Book not found');
        }
        return $book->only(['id', 'title', 'genre']);
    }

    public function destroy(Book $book)
    {
        try {
            $book->delete();
            return response()->json(['success' => "Book Deleted Successfully"], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong while deleting the book.',
                'message' => $e->getMessage() // optional: remove in production
            ], 500);
        }
    }
}




// public function index(Request $request)
    // {
    //     $books = Book::select('id', 'title', 'genre');

    //     if($request->ajax()) {
    //         return DataTables::of($books)
    //         ->addColumn('action', function() {
    //             return '<a href="javascript.void(0)" class="btn-sm btn btn-info">Edit</a>';
    //         })
    //         ->rawColumns(['action'])
    //         ->make(true);
    //     }
    // }
