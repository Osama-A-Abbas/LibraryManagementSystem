/**
 * Books Management - Main JavaScript File
 *
 * This file initializes all the books functionality.
 */

$(document).ready(function() {
    // Setup CSRF token for all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Initialize DataTable
    const booksTable = initDataTable();

    // Initialize modal handlers
    initModalHandlers(booksTable);

    // Initialize delete button handlers
    initDeleteHandlers(booksTable);
});
