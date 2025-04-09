/**
 * Books Modal Handlers
 *
 * This file contains the code for handling the book modal.
 */

function initModalHandlers(table) {
    // Reset form when modal is hidden
    $('.ajax-modal').on('hidden.bs.modal', function() {
        console.log('Modal hidden, resetting form');
        $('#bookForm')[0].reset();
        $('#book_id').val(''); // Clear the book_id
        $('.error-messages').html(''); // Clear any error messages
        $('#modalTitle').html('Add Book'); // Reset modal title
        $('#saveBtn').html('Save Book'); // Reset button text
    });

    // Set initial text for modal title and save button
    $('#modalTitle').html('Add Book');
    $('#saveBtn').html('Save Book');

    // Handle file upload preview
    $('#cover_page').on('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#cover_preview').html(`<img src="${e.target.result}" class="img-thumbnail" style="max-height: 200px;">`);
            }
            reader.readAsDataURL(file);
        } else {
            $('#cover_preview').empty();
        }
    });

    // Reset form and preview when modal is closed
    $('#exampleModal').on('hidden.bs.modal', function() {
        console.log('Modal closed, resetting form and preview');
        $('#bookForm')[0].reset();
        $('#cover_preview').empty();
        $('.error-messages').empty();
    });

    // Save button click handler
    $('#saveBtn').click(function() {
        $('#saveBtn').attr('disabled', true);
        $('#saveBtn').html('Saving...');

        $('.error-messages').html(''); // Clear error messages

        var formData = new FormData($('#bookForm')[0]); // Get form data
        var bookId = $('#book_id').val(); // Check if book_id is set
        console.log('Saving book with ID:', bookId);
        console.log('Form data:', Object.fromEntries(formData));

        var url = bookId ? '/books/' + bookId + '/update' : '/books/store'; // Determine URL

        $.ajax({
            url: url,
            method: 'POST',
            processData: false,
            contentType: false,
            data: formData,

            success: function(response) {
                $('#saveBtn').attr('disabled', false);
                $('#saveBtn').html(bookId ? 'Update Book' : 'Save Book');
                $('.ajax-modal').modal('hide'); // Hide modal
                table.ajax.reload(); // Refresh DataTable

                Swal.fire({
                    title: response.success,
                    icon: "success",
                    draggable: true
                });
            },

            error: function(error) {
                $('#saveBtn').attr('disabled', false);
                $('#saveBtn').html(bookId ? 'Update Book' : 'Save Book');

                if (error.status === 422) {
                    const errors = error.responseJSON.errors;
                    $('#titleError').html(errors.title ? errors.title[0] : '');
                    $('#genreError').html(errors.genre ? errors.genre[0] : '');
                    $('#authorError').html(errors.author ? errors.author[0] : '');
                    $('#descriptionError').html(errors.description ? errors.description[0] : '');
                    $('#published_atError').html(errors.published_at ? errors.published_at[0] : '');
                    $('#cover_pageError').html(errors.cover_page ? errors.cover_page[0] : '');
                    $('#book_pdfError').html(errors.book_pdf ? errors.book_pdf[0] : '');
                    $('#number_of_copiesError').html(errors.number_of_copies ? errors.number_of_copies[0] : '');

                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Something went wrong. Please try again.'
                    });
                }
            }
        });
    });
}
