<?php

namespace App\Services\Book;

use App\Models\Book;
use App\Models\Borrowing;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Access\Gate;
use Yajra\DataTables\DataTables;

class SetBookDataTable
{
    public function __construct(
        protected Book $book,
        protected DataTables $dataTables,
        protected Guard $auth,
        protected Gate $gate,
        protected Borrowing $borrowing
    ) {}

    /**
     * Set up the DataTable for the books list.
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

    protected function getEditButton($book): ?string
    {
        if ($this->auth->check() && $this->gate->allows('edit books')) {
            return $this->createButton('edit', 'info', 'Edit', $book->id);
        }
        return null;
    }

    protected function getDeleteButton($book): ?string
    {
        if ($this->auth->check() && $this->gate->allows('delete books')) {
            return $this->createButton('delete', 'danger', 'Delete', $book->id);
        }
        return null;
    }

    protected function getViewButton($book): string
    {
        return $this->createButton('view', 'primary', 'View', $book->id);
    }

    protected function getDownloadButton($book): string
    {
        return $this->createButton('download', 'success', 'Download', $book->id);
    }

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

    // -------------------  return button / not needed for now / implement in borrowing  -----------------------------\\
    // protected function getReturnButton($book): string
    // {
    //     return $this->createButton('return', 'secondary', 'Return', $book->id);
    // }

    protected function userHasActiveBorrowing(int $bookId): bool
    {
        return $this->auth->check() &&
            $this->borrowing->where('book_id', $bookId)
            ->where('user_id', $this->auth->id())
            ->where('borrowing_status', '!=', 'returned')
            ->exists();
    }

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
