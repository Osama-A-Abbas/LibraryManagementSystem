/**
 * Borrowings Main JavaScript
 *
 * This file initializes all the borrowing management functionality.
 */

$(document).ready(function() {
    // Set up CSRF token for all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Initialize DataTable
    var table = initBorrowingsDataTable();

    // Initialize action handlers
    initBorrowingActionHandlers(table);
});
