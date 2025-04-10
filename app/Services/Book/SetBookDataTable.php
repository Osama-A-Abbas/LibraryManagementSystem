<?php

namespace App\Services\Book;

use App\Models\Book;
use App\Models\Borrowing;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Access\Gate;
use Yajra\DataTables\DataTables;

/**
 * SetBookDataTable Service
 *
 * This service sets up and configures DataTables for the book listing functionality.
 * It handles the representation of book data in tables, including column formatting
 * and action buttons based on user permissions.
 */
class SetBookDataTable
{
    /**
     * Constructor with dependency injection.
     *
     * @param Book $book Book model for database queries
     * @param DataTables $dataTables DataTables service for table generation
     * @param Guard $auth Authentication service to check user login status
     * @param Gate $gate Authorization service to check user permissions
     * @param Borrowing $borrowing Borrowing model to check book availability
     */
    public function __construct(
        protected Book $book,
        protected DataTables $dataTables,
        protected Guard $auth,
        protected Gate $gate,
        protected Borrowing $borrowing
    ) {}

    /**
     * Set up the DataTable for the books list.
     *
     * Configures the DataTable with book data, formats columns,
     * and adds action buttons based on user permissions.
     *
     * @return \Yajra\DataTables\DataTableAbstract Configured DataTable instance
     */
    public function execute(): \Yajra\DataTables\DataTableAbstract
    {
        $books = $this->book->select([
            'id',
            'title',
            'genre',
            'author',
            'description',
            'published_at',
            'cover_page',
            'is_available'
        ]);

        return $this->dataTables->of($books)
            ->editColumn('genre', fn($book) => ucfirst($book->genre))
            ->editColumn('cover_page', $this->getCoverImageColumn())
            ->addColumn('action', function ($row) {
                return $this->buildActionButtons($row);
            })
            ->rawColumns(['action', 'cover_page']);
    }

    /**
     * Format the cover image column for display in the DataTable.
     *
     * @return Closure Function that formats the cover image HTML
     */
    protected function getCoverImageColumn(): Closure
    {
        return function ($book) {
            return $book->cover_page
                ? '<img src="' . asset('storage/' . $book->cover_page) . '"
                     alt="Book Cover"
                     class="img-thumbnail"
                     style="max-height: 50px;">'
                : 'No Cover';
        };
    }

    /**
     * Build the set of action buttons for each book row.
     *
     * Collects and filters buttons based on user permissions.
     *
     * @param Book $book The book model for the current row
     * @return string HTML string containing all action buttons
     */
    protected function buildActionButtons($book): string
    {
        $buttons = collect([
            $this->getEditButton($book),
            $this->getDeleteButton($book),
            $this->getViewButton($book),
            $this->getDownloadButton($book),
            $this->getBorrowButton($book),
            // $this->getReturnButton($book)
        ]);

        return $buttons->filter()->implode('');
    }

    /**
     * Generate the edit button if user has permission.
     *
     * @param Book $book The book model
     * @return string|null HTML for edit button or null if not allowed
     */
    protected function getEditButton($book): ?string
    {
        if ($this->auth->check() && $this->gate->allows('edit books')) {
            return $this->createButton('edit', 'info', 'Edit', $book->id);
        }
        return null;
    }

    /**
     * Generate the delete button if user has permission.
     *
     * @param Book $book The book model
     * @return string|null HTML for delete button or null if not allowed
     */
    protected function getDeleteButton($book): ?string
    {
        if ($this->auth->check() && $this->gate->allows('delete books')) {
            return $this->createButton('delete', 'danger', 'Delete', $book->id);
        }
        return null;
    }

    /**
     * Generate the view button for book details.
     *
     * @param Book $book The book model
     * @return string HTML for view button
     */
    protected function getViewButton($book): string
    {
        return $this->createButton('view', 'primary', 'View', $book->id);
    }

    /**
     * Generate the download button for book PDF.
     *
     * @param Book $book The book model
     * @return string HTML for download button
     */
    protected function getDownloadButton($book): string
    {
        return $this->createButton('download', 'success', 'Download', $book->id);
    }

    /**
     * Generate the borrow button for a book.
     *
     * Button is disabled if:
     * - Book is not available
     * - User has already borrowed this book
     *
     * @param Book $book The book model
     * @return string|null HTML for borrow button or disabled button with tooltip
     */
    protected function getBorrowButton($book): ?string
    {
        if (!$book->is_available) {
            return $this->createDisabledButton(
                'borrow',
                'warning',
                'Borrow',
                $book->id,
                'This book is currently not available for borrowing'
            );
        }

        if ($this->userHasActiveBorrowing($book->id)) {
            return $this->createDisabledButton(
                'borrow',
                'warning',
                'Borrow',
                $book->id,
                'You have already borrowed this book'
            );
        }

        return $this->createButton('borrow', 'warning', 'Borrow', $book->id);
    }

    /**
     * Return button method - currently commented out.
     * Book returns are handled in the borrowing section instead.
     *
     * @param Book $book The book model
     * @return string HTML for return button
     */
    // protected function getReturnButton($book): string
    // {
    //     return $this->createButton('return', 'secondary', 'Return', $book->id);
    // }

    /**
     * Check if the current user has an active borrowing for this book.
     *
     * @param int $bookId The book ID to check
     * @return bool True if user has an active borrowing for this book
     */
    protected function userHasActiveBorrowing(int $bookId): bool
    {
        return $this->auth->check() &&
            $this->borrowing->where('book_id', $bookId)
            ->where('user_id', $this->auth->id())
            ->where('borrowing_status', '!=', 'returned')
            ->exists();
    }

    /**
     * Create a standard action button.
     *
     * @param string $type Button type (edit, delete, view, etc.)
     * @param string $color Bootstrap button color
     * @param string $label Button text
     * @param int $id Book ID
     * @return string HTML button element
     */
    protected function createButton(
        string $type,
        string $color,
        string $label,
        int $id
    ): string {
        return sprintf(
            '<a href="javascript:void(0)" class="btn btn-sm btn-%s %sButton" data-id="%d">%s</a>',
            $color,
            $type,
            $id,
            $label
        );
    }

    /**
     * Create a disabled action button with tooltip.
     *
     * @param string $type Button type (edit, delete, view, etc.)
     * @param string $color Bootstrap button color
     * @param string $label Button text
     * @param int $id Book ID
     * @param string $tooltip Tooltip text explaining why button is disabled
     * @return string HTML button element with tooltip
     */
    protected function createDisabledButton(
        string $type,
        string $color,
        string $label,
        int $id,
        string $tooltip
    ): string {
        return sprintf(
            '<a href="javascript:void(0)" class="btn btn-sm btn-%s %sButton disabled"
                data-id="%d"
                data-bs-toggle="tooltip"
                title="%s">%s</a>',
            $color,
            $type,
            $id,
            e($tooltip),
            $label
        );
    }
}
