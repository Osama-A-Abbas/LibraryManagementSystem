 // Download button click handler
 $(document).on('click', '.downloadButton', function() {
    const bookId = $(this).data('id');
    window.location.href = `/books/${bookId}/download`;
});
