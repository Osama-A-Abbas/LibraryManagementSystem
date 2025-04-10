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
        order: [[0, 'desc']],
        // Add custom CSS classes to improve DataTable styling
        initComplete: function() {
            // Add spacing to action buttons
            $('.btn-sm').addClass('me-1');

            // Add spacing to entries per page text
            $('.dataTables_length label').addClass('me-2');
        },
        drawCallback: function() {
            // Ensure buttons have spacing on each redraw
            $('.btn-sm').addClass('me-1');
        }
    });
}
