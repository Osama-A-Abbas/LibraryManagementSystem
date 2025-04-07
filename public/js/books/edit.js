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
            Swal.fire({
                title: "Error!",
                text: "Failed to load book data.",
                icon: "error"
            });
        }
    });
});
