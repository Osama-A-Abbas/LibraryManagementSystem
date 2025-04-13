/**
 * Initialize Select2 for multiple select dropdowns
 */
$(document).ready(function() {
    // Initialize Select2 for multiple select
    $('.select2-multiple').select2({
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: 'Select genres',
        allowClear: true,
        closeOnSelect: false,
        selectionCssClass: 'select2--large',
        dropdownCssClass: 'select2--large',
        containerCssClass: 'select2-container--full',
    });

    // Re-initialize Select2 when modal is shown
    $('#exampleModal').on('shown.bs.modal', function () {
        $('.select2-multiple').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: 'Select genres',
            allowClear: true,
            closeOnSelect: false,
            selectionCssClass: 'select2--large',
            dropdownCssClass: 'select2--large',
            containerCssClass: 'select2-container--full',
        });
    });

    // Clear Select2 on modal hidden
    $('#exampleModal').on('hidden.bs.modal', function () {
        $('.select2-multiple').val(null).trigger('change');
    });

    // Handle edit button click to set selected genres
    $(document).on('click', '.editButton', function() {
        const bookId = $(this).data('id');

        $.ajax({
            url: `/books/${bookId}/edit`,
            type: 'GET',
            success: function(response) {
                // Initialize modal with book data
                if (response.genres && response.genres.length > 0) {
                    const genreIds = response.genres.map(genre => genre.id);
                    $('#genres').val(genreIds).trigger('change');
                } else {
                    $('#genres').val(null).trigger('change');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching book details:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to load book details. Please try again.'
                });
            }
        });
    });
});
