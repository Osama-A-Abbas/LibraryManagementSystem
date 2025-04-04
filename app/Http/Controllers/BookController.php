<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookController extends Controller
{
    public function create()
    {
        return view('books.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|min:1|max:255',
            'genre' => 'required|string|min:1|max:255',
        ]);

        return 'success';
    }
}
