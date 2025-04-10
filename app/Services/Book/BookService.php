<?php

namespace App\Services\Book;

use App\Models\Book;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class BookService
{
    public function __construct(
        protected Book $book,
    ) {}


    // ------------  BookController index method -----------------------------\\
    /**
     * Set up the DataTable for the books list.
     *
     * @return \Yajra\DataTables\DataTableAbstract
     */
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
                $buttons = '';

                // Check if user has permission to edit books
                if (Auth::check() && Gate::allows('edit books')) {
                    $buttons .= '<a href="javascript:void(0)" class="btn btn-sm btn-info editButton" data-id="' . $row->id . '">Edit</a>';
                }

                // Check if user has permission to delete books
                if (Auth::check() && Gate::allows('delete books')) {
                    $buttons .= '<a href="javascript:void(0)" class="btn btn-sm btn-danger deleteButton" data-id="' . $row->id . '">Delete</a>';
                }

                if (Auth::check() && Gate::allows('borrow-book')) {
                    $buttons .= ' <a href="javascript:void(0)" class="btn btn-sm btn-warning borrowButton" data-id="' . $row->id . '" data-is-available="true">Borrow</a>';
                }

                // Add other buttons that don't require special permissions
                $buttons .= '<a href="javascript:void(0)" class="btn btn-sm btn-primary viewButton" data-id="' . $row->id . '">View</a>
                        <a href="javascript:void(0)" class="btn btn-sm btn-success downloadButton" data-id="' . $row->id . '">Download</a>
                        <a href="javascript:void(0)" class="btn btn-sm btn-warning borrowButton" data-id="' . $row->id . '" data-is-available="true">Borrow</a>
                        <a href="javascript:void(0)" class="btn btn-sm btn-secondary returnButton" data-id="' . $row->id . '">Return</a>';

                return $buttons;
            })
            ->rawColumns(['action', 'cover_page']);
    }

    private
    //---------------------------------------------------------------------------------------------\\
    // ------------  BookController Store + Update methods and helpers -----------------------------\\

    /**
     * Store a new book into the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Models\Book
     */
    public function storeBook($request)
    {
        try {
            DB::beginTransaction();

            $book = $this->book->create($request->only(['title', 'genre', 'author', 'description', 'published_at', 'number_of_copies']));
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

    /**
     * Update an existing book in the database.
     *
     * This method handles the update of a book's details including
     * title, genre, author, description, number of copies, and published date. It also
     * manages the upload and replacement of the cover image and PDF file
     * associated with the book, ensuring any existing files are deleted
     * before new ones are uploaded.
     *
     * @param \Illuminate\Http\Request $request The request object containing
     *                                          the book details and files.
     * @param \App\Models\Book $book The book model instance to be updated.
     *
     * @return \App\Models\Book The updated book model instance.
     *
     * @throws \Exception If the update process encounters an error and needs
     *                    to roll back the transaction.
     */

    public function updateBook($request, $book)
    {
        try {
            DB::beginTransaction();

            $book->update($request->only(['title', 'genre', 'author', 'description', 'published_at', 'number_of_copies']));

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

    /**
     * Handle the upload of a file to the storage.
     *
     * @param \Illuminate\Http\UploadedFile $file The uploaded file.
     * @param string $directory The directory in which the file should be stored.
     *
     * @return string The path to the uploaded file.
     */
    private function handleFileUpload($file, string $directory): string
    {
        $fileName = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
        return $file->storeAs($directory, $fileName, 'public');
    }


    /**
     * Deletes a file from the storage if it exists.
     *
     * @param string|null $path The path to the file to delete.
     */
    private function deleteFileIfExists(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    //---------------------------------------------------------------------------------------------\\

    /**
     * Delete a book and its associated files.
     *
     * @param \App\Models\Book $book
     * @return void
     * @throws \Exception
     */
    public function deleteBook($book)
    {
        try {
            DB::beginTransaction();
            //Delete the files from storage
            Storage::disk('public')->deleteDirectory("books/$book->id");
            // Delete the book record
            $book->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
