/**
 * Books Edit Handlers
 *
 * This file contains the code for handling the book edit functionality.
 */

function initEditHandlers(table) {
    // Edit button click handler
    $(document).on('click', '.editButton', function() {
        const bookId = $(this).data('id');

        $.ajax({
            url: `/books/${bookId}/edit`,
            type: 'GET',
            success: function(response) {
                $('#book_id').val(response.id);
                $('#title').val(response.title);
                $('#genre').val(response.genre);
                $('#author').val(response.author);
                $('#description').val(response.description);
                $('#published_at').val(response.published_at);
                $('#number_of_copies').val(response.number_of_copies);
                // Display existing cover image if available
                if (response.cover_page) {
                    $('#cover_preview').html(`<img src="${response.cover_page}" class="img-thumbnail" style="max-height: 200px;">`);
                } else {
                    $('#cover_preview').empty();
                }
                // Display existing book PDF if available
                if (response.book_pdf) {
                    $('#book_pdf_preview').html(`
                        <div class="mt-2">
                            <a href="/books/${response.id}/download" class="btn btn-sm btn-primary">
                                <i class="fas fa-file-pdf"></i> Download PDF
                            </a>
                        </div>
                    `);
                } else {
                    $('#book_pdf_preview').empty();
                }

                $('#modalTitle').html('Edit Book');
                $('#saveBtn').html('Update Book');
                $('#exampleModal').modal('show');
            },
            error: function(error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to load book details. Please try again.'
                });
            }
        });
    });

    // Download button click handler
    $(document).on('click', '.downloadButton', function() {
        const bookId = $(this).data('id');
        window.location.href = `/books/${bookId}/download`;
    });
}
