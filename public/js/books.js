/**
 * Books Management JavaScript
 *
 * This file includes all the JavaScript files needed for the books management system.
 */

// Load utility functions
// @include "books/utils.js"

// Load DataTable initialization
// @include "books/datatable.js"

// Load modal handlers
// @include "books/modal.js"

// Load delete handlers
// @include "books/delete.js"

// Initialize when document is ready
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

    initBorrowHandlers(booksTable);
});
