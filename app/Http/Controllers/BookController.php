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
    ){}


    public function index(Request $request)
    {
        $books = Book::all();

        if($request->ajax()) {
            return DataTables::of($books)->make(true);
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
}




// public function index(Request $request)
//     {
//         $books = Book::all();

//         if($request->ajax) {
//             return DataTables::of($books)
//                 ->addColumn('action', function ($book) {
//                     return '<a href="'.route('books.edit', $book->id).'" class="btn btn-primary">Edit</a>
//                             <form action="'.route('books.destroy', $book->id).'" method="POST" style="display:inline;">
//                                 '.csrf_field().'
//                                 '.method_field('DELETE').'
//                                 <button type="submit" class="btn btn-danger">Delete</button>
//                             </form>';
//                 })
//                 ->make(true);
//         } else {
//             return DataTables::of($books)->make(true);
//         }
//     }
