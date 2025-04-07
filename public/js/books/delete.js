/**
 * Books Delete Handlers
 *
 * This file contains the code for handling the book delete functionality.
 */

function initDeleteHandlers(table) {
    $('body').on('click', '.deleteButton', function() {
        var id = $(this).data('id');

        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/books/' + id + '/delete',
                    type: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        table.ajax.reload();
                        Swal.fire({
                            title: "Deleted!",
                            text: response.success,
                            icon: "success"
                        });
                    },
                    error: function(error) {
                        Swal.fire({
                            title: "Error!",
                            text: "Something went wrong.",
                            icon: "error"
                        });
                    }
                });
            }
        });
    });
}
