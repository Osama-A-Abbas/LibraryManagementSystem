/**
 * Books Main JavaScript
 *
 * This file initializes all the book management functionality.
 */

$(document).ready(function() {
    // Set up CSRF token for all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Initialize DataTable
    var table = initDataTable();

    // Initialize modal handlers
    initModalHandlers(table);

    // Initialize edit handlers
    initEditHandlers(table);

    // Initialize delete handlers
    initDeleteHandlers(table);
});
