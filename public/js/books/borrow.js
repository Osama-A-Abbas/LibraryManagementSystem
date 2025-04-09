/**
 * Books Delete Handlers
 *
 * This file contains the code for handling the book delete functionality.
 */

function initBorrowHandlers(table) {
    $('body').on('click', '.borrowButton', function() {
        var id = $(this).data('id');

        Swal.fire({
            title: "Confirm Borrowing",
            text: "You Will be borrowing this book",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, borrow it!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/borrow/',
                    type: 'POST',
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
