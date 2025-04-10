<?php
namespace App\Services\Book;

use App\Models\Book;
use App\Models\Borrowing;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class SetBookDataTable
{
    public function __construct(
        protected Book $book,
        protected Borrowing $borrowing,
    ) {}

    // Used as index method in BookController
    /**
 * Set up the DataTable for the books list.
 *
 * @return \Yajra\DataTables\DataTableAbstract
 */
public function execute(): \Yajra\DataTables\DataTableAbstract
{
    $books = $this->book->select(['id', 'title', 'genre', 'author', 'description', 'published_at', 'cover_page', 'is_available']);
    $currentUserId = Auth::id();

    return datatables()->of($books)
        ->editColumn('genre', fn($book) => ucfirst($book->genre))
        ->editColumn('cover_page', $this->getCoverImageColumn())
        ->addColumn('action', function ($row) use ($currentUserId) {
            return $this->buildActionButtons($row, $currentUserId);
        })
        ->rawColumns(['action', 'cover_page']);
}

/**
 * Returns the cover image column formatter.
 */
protected function getCoverImageColumn(): Closure
{
    return function ($book) {
        return $book->cover_page
            ? '<img src="' . asset('storage/' . $book->cover_page) . '" alt="Book Cover" class="img-thumbnail" style="max-height: 50px;">'
            : 'No Cover';
    };
}

/**
 * Builds the action buttons for a book row.
 */
protected function buildActionButtons($book, $currentUserId): string
{
    $buttons = collect();

    $buttons->push($this->getEditButton($book));
    $buttons->push($this->getDeleteButton($book));
    $buttons->push($this->getViewButton($book));
    $buttons->push($this->getDownloadButton($book));
    $buttons->push($this->getBorrowButton($book, $currentUserId));
    $buttons->push($this->getReturnButton($book));

    return $buttons->filter()->implode('');
}

/**
 * Generates edit button if user has permission.
 */
protected function getEditButton($book): ?string
{
    if (Auth::check() && Gate::allows('edit books')) {
        return '<a href="javascript:void(0)" class="btn btn-sm btn-info editButton" data-id="' . $book->id . '">Edit</a>';
    }
    return null;
}

/**
 * Generates delete button if user has permission.
 */
protected function getDeleteButton($book): ?string
{
    if (Auth::check() && Gate::allows('delete books')) {
        return '<a href="javascript:void(0)" class="btn btn-sm btn-danger deleteButton" data-id="' . $book->id . '">Delete</a>';
    }
    return null;
}

/**
 * Generates view button.
 */
protected function getViewButton($book): string
{
    return '<a href="javascript:void(0)" class="btn btn-sm btn-primary viewButton" data-id="' . $book->id . '">View</a>';
}

/**
 * Generates download button.
 */
protected function getDownloadButton($book): string
{
    return '<a href="javascript:void(0)" class="btn btn-sm btn-success downloadButton" data-id="' . $book->id . '">Download</a>';
}

/**
 * Generates appropriate borrow button based on availability.
 */
protected function getBorrowButton($book, $currentUserId): string
{
    if (!$book->is_available) {
        return $this->getDisabledBorrowButton($book, 'This book is currently not available for borrowing');
    }

    if ($this->userHasActiveBorrowing($book->id, $currentUserId)) {
        return $this->getDisabledBorrowButton($book, 'You have already borrowed this book');
    }

    return '<a href="javascript:void(0)" class="btn btn-sm btn-warning borrowButton" data-id="' . $book->id . '">Borrow</a>';
}

/**
 * Generates disabled borrow button with tooltip.
 */
protected function getDisabledBorrowButton($book, string $tooltip): string
{
    return '<a href="javascript:void(0)" class="btn btn-sm btn-warning borrowButton disabled"
            data-id="' . $book->id . '"
            data-bs-toggle="tooltip"
            title="' . e($tooltip) . '">Borrow</a>';
}

/**
 * Checks if user has active borrowing for the book.
 */
protected function userHasActiveBorrowing(int $bookId, int $userId): bool
{
    return Auth::check() && $this->borrowing->where('book_id', $bookId)
        ->where('user_id', $userId)
        ->where('borrowing_status', '!=', 'returned')
        ->exists();
}

/**
 * Generates return button.
 */
protected function getReturnButton($book): string
{
    return '<a href="javascript:void(0)" class="btn btn-sm btn-secondary returnButton" data-id="' . $book->id . '">Return</a>';
}
}
