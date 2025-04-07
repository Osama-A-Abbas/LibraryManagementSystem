/**
 * Books Modal Handlers
 *
 * This file contains the code for handling the book modal.
 */

function initModalHandlers(table) {
    // Reset form when modal is hidden
    $('.ajax-modal').on('hidden.bs.modal', function() {
        $('#bookForm')[0].reset();
        $('#book_id').val(''); // Clear the book_id
        $('.error-messages').html(''); // Clear any error messages
        $('#modalTitle').html('Add Book'); // Reset modal title
        $('#saveBtn').html('Save Book'); // Reset button text
    });

    // Set initial text for modal title and save button
    $('#modalTitle').html('Add Book');
    $('#saveBtn').html('Save Book');

    // Save button click handler
    $('#saveBtn').click(function() {
        $('#saveBtn').attr('disabled', true);
        $('#saveBtn').html('Saving...');

        $('.error-messages').html(''); // Clear error messages

        var formData = new FormData($('#bookForm')[0]); // Get form data
        var bookId = $('#book_id').val(); // Check if book_id is set

        var url = bookId ? '/books/' + bookId + '/update' : '/books/store'; // Determine URL
        var method = bookId ? 'PUT' : 'POST'; // Determine HTTP method

        if (method === 'PUT') {
            formData.append('_method', 'PUT'); // Add _method field for PUT requests
        }

        $.ajax({
            url: url,
            method: 'POST', // Always use POST, _method field will handle the actual method
            processData: false,
            contentType: false,
            data: formData,

            success: function(response) {
                $('#saveBtn').attr('disabled', false);
                $('#saveBtn').html('Save Book');
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
                $('#saveBtn').html('Save Book');

                if (error.responseJSON && error.responseJSON.errors) {
                    $('#titleError').html(error.responseJSON.errors.title);
                    $('#genreError').html(error.responseJSON.errors.genre);
                } else {
                    console.error(error);
                }
            }
        });
    });

    // Edit button click handler
    $('body').on('click', '.editButton', function() {
        var id = $(this).data('id');

        $.ajax({
            url: '/books/' + id + '/edit',
            type: 'GET',

            success: function(response) {
                $('.ajax-modal').modal('show');
                $('#modalTitle').html('Edit Book');
                $('#saveBtn').html('Update Book');

                $('#title').val(response.title);
                $('#book_id').val(response.id);
                $('#genre').val(response.genre);
            },

            error: function(error) {
                console.error(error);
            }
        });
    });
}
