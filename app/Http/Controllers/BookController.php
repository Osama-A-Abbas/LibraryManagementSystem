<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    public function create()
    {
        return view('books.create');
    }

    public function index()
    {
        $books = Book::select(['id', 'title', 'genre']);
        return datatables()->of($books)
            ->editColumn('genre', function ($book) {
                return ucfirst($book->genre); // Capitalize the first letter of the genre
            })
            ->addColumn('action', function($row) {
                return '<a href="javascript:void(0)" class="btn btn-sm btn-info editButton" data-id="'.$row->id.'">Edit</a>
                        <a href="javascript:void(0)" class="btn btn-sm btn-danger deleteButton" data-id="'.$row->id.'">Delete</a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'genre' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        Book::create($request->all());

        return response()->json(['success' => 'Book created successfully.']);
    }

    public function edit($id)
    {
        $book = Book::findOrFail($id);
        return response()->json([
            'id' => $book->id,
            'title' => $book->title,
            'genre' => $book->genre
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'genre' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $book = Book::findOrFail($id);
        $book->update($request->all());

        return response()->json(['success' => 'Book updated successfully.']);
    }

    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();

        return response()->json(['success' => 'Book deleted successfully.']);
    }
}
