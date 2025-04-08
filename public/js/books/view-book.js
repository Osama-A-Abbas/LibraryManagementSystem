/**
 * View Book PDF Handler
 */
function initViewHandlers() {
    $(document).on('click', '.viewButton', function() {
        const bookId = $(this).data('id');
        const viewUrl = `/books/${bookId}/view`;

        // Open PDF in a new window/tab
        window.open(viewUrl, '_blank');
    });
}

// Initialize view handlers when document is ready
$(document).ready(function() {
    initViewHandlers();
});
