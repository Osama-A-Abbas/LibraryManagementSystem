/**
 * Borrowings DataTable Handlers
 *
 * This file contains the code for handling the borrowings DataTable.
 */

function initBorrowingsDataTable() {
    return $('#borrowingsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/borrowings',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'book_title', name: 'book_title' },
            { data: 'username', name: 'username' },
            { data: 'borrowing_status', name: 'borrowing_status' },
            { data: 'borrow_at', name: 'borrow_at' },
            { data: 'return_at', name: 'return_at' },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        order: [[0, 'desc']]
    });
}
