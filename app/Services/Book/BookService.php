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

    public function setBookDataTable(): \Yajra\DataTables\DataTableAbstract
    {
        $books = $this->book->select(['id', 'title', 'genre', 'author', 'description', 'published_at', 'cover_page']);

        return datatables()->of($books)
            ->editColumn('genre', fn($book) => ucfirst($book->genre))
            ->editColumn('cover_page', function ($book) {
                return $book->cover_page
                    ? '<img src="' . asset('storage/' . $book->cover_page) . '" alt="Book Cover" class="img-thumbnail" style="max-height: 50px;">'
                    : 'No Cover';
            })
            ->addColumn('action', function ($row) {
                return '<a href="javascript:void(0)" class="btn btn-sm btn-info editButton" data-id="' . $row->id . '">Edit</a>
                        <a href="javascript:void(0)" class="btn btn-sm btn-danger deleteButton" data-id="' . $row->id . '">Delete</a>';
            })
            ->rawColumns(['action', 'cover_page']);
    }

    public function storeBook($request)
    {
        try {
            DB::beginTransaction();

            $book = new Book($request->only(['title', 'genre', 'author', 'description', 'published_at']));
            $book->save(); // Save first to get ID

            // Upload files using book ID
            if ($request->hasFile('cover_page')) {
                $book->cover_page = $this->handleFileUpload($request->file('cover_page'), "books/{$book->id}/cover");
            }

            if ($request->hasFile('book_pdf')) {
                $book->book_pdf = $this->handleFileUpload($request->file('book_pdf'), "books/{$book->id}/pdf");
            }

            $book->save(); // Save again to persist file paths

            DB::commit();
            return $book;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }


    public function updateBook($request, $book)
{
    try {
        DB::beginTransaction();

        $book->update($request->only(['title', 'genre', 'author', 'description', 'published_at']));

        if ($request->hasFile('cover_page')) {
            $this->deleteFileIfExists($book->cover_page);
            $book->cover_page = $this->handleFileUpload($request->file('cover_page'), "books/{$book->id}/cover");
        }

        if ($request->hasFile('book_pdf')) {
            $this->deleteFileIfExists($book->book_pdf);
            $book->book_pdf = $this->handleFileUpload($request->file('book_pdf'), "books/{$book->id}/pdf");
        }

        $book->save();

        DB::commit();
        return $book;
    } catch (\Exception $e) {
        DB::rollBack();
        throw $e;
    }
}


    private function handleFileUpload($file, string $directory): string
    {
        $fileName = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
        return $file->storeAs($directory, $fileName, 'public');
    }

    private function deleteFileIfExists(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
