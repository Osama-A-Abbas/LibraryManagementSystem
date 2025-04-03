<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GenreController extends Controller
{
    public function create()
    {
        return view('genre.create');
    }
}
