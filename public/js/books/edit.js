/**
 * Books Edit Handlers
 *
 * This file contains the code for handling the book edit functionality.
 */

function initEditHandlers(table) {
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
                $('#author').val(response.author);
                $('#description').val(response.description);
            },

            error: function(error) {
                Swal.fire({
                    title: "Error!",
                    text: "Failed to load book data.",
                    icon: "error"
                });
            }
        });
    });
}
