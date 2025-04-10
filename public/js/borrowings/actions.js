/**
 * Borrowings Actions Handlers
 *
 * This file contains handlers for various borrowing actions (view, return, approve, reject, manage).
 */

// Initialize event handlers for borrowing actions
function initBorrowingActionHandlers(table) {
    // View borrowing details
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('viewButton') || e.target.closest('.viewButton')) {
            const button = e.target.classList.contains('viewButton') ? e.target : e.target.closest('.viewButton');
            const borrowingId = button.getAttribute('data-id');
            viewBorrowingDetails(borrowingId);
        }
    });

    // Return a book
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('returnButton') || e.target.closest('.returnButton')) {
            const button = e.target.classList.contains('returnButton') ? e.target : e.target.closest('.returnButton');
            const borrowingId = button.getAttribute('data-id');
            confirmReturn(borrowingId, table);
        }
    });

    // Approve a borrowing request
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('approveButton') || e.target.closest('.approveButton')) {
            const button = e.target.classList.contains('approveButton') ? e.target : e.target.closest('.approveButton');
            const borrowingId = button.getAttribute('data-id');
            confirmStatusUpdate(borrowingId, 'approve', 'Approve this borrowing request?', table);
        }
    });

    // Reject a borrowing request
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('rejectButton') || e.target.closest('.rejectButton')) {
            const button = e.target.classList.contains('rejectButton') ? e.target : e.target.closest('.rejectButton');
            const borrowingId = button.getAttribute('data-id');
            confirmStatusUpdate(borrowingId, 'reject', 'Reject this borrowing request?', table);
        }
    });

    // Manage borrowing (admin)
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('manageButton') || e.target.closest('.manageButton')) {
            const button = e.target.classList.contains('manageButton') ? e.target : e.target.closest('.manageButton');
            const borrowingId = button.getAttribute('data-id');
            openManageBorrowingModal(borrowingId);
        }
    });

    // Save status changes from manage modal
    document.getElementById('saveStatusBtn').addEventListener('click', function() {
        saveStatusChanges(table);
    });
}

// View borrowing details
function viewBorrowingDetails(borrowingId) {
    $.ajax({
        url: `/borrowings/${borrowingId}`,
        type: 'GET',
        success: function(response) {
            // Populate the modal with borrowing details
            $('#book-title').text(response.book_title);
            $('#username').text(response.username);
            $('#borrowing-status').text(response.borrowing.borrowing_status);
            $('#borrow-date').text(response.borrowing.borrow_at);
            $('#return-date').text(response.borrowing.return_at || 'Not specified');
            $('#notes').text(response.borrowing.notes || 'No notes');

            // Show the modal
            var viewModal = new bootstrap.Modal(document.getElementById('viewBorrowingModal'));
            viewModal.show();
        },
        error: function(xhr) {
            Swal.fire({
                title: 'Error!',
                text: xhr.responseJSON?.error || 'Failed to load borrowing details',
                icon: 'error'
            });
        }
    });
}

// Confirm return action
function confirmReturn(borrowingId, table) {
    Swal.fire({
        title: 'Return Book',
        text: 'Are you sure you want to return this book?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, return it',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            updateBorrowingStatus(borrowingId, 'return', table);
        }
    });
}

// Confirm status update (approve/reject)
function confirmStatusUpdate(borrowingId, action, confirmMessage, table) {
    Swal.fire({
        title: action.charAt(0).toUpperCase() + action.slice(1) + ' Request',
        text: confirmMessage,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, ' + action,
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            updateBorrowingStatus(borrowingId, action, table);
        }
    });
}

// Update borrowing status
function updateBorrowingStatus(borrowingId, action, table) {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    $.ajax({
        url: `/borrowings/${borrowingId}`,
        type: 'PUT',
        data: {
            _token: token,
            action: action
        },
        success: function(response) {
            Swal.fire({
                title: 'Success!',
                text: response.success,
                icon: 'success'
            }).then(() => {
                if (table && typeof table.ajax.reload === 'function') {
                    table.ajax.reload(null, false);
                } else {
                    location.reload(); // fallback
                }
            });
        },
        error: function(xhr) {
            Swal.fire({
                title: 'Error!',
                text: xhr.responseJSON?.error || 'Failed to update borrowing status',
                icon: 'error'
            });
        }
    });
}

// Open manage borrowing modal
function openManageBorrowingModal(borrowingId) {
    $.ajax({
        url: `/borrowings/${borrowingId}`,
        type: 'GET',
        success: function(response) {
            // Populate the modal with borrowing details
            $('#borrowing-id').val(borrowingId);
            $('#book-title-manage').val(response.book_title);
            $('#username-manage').val(response.username);
            $('#status-manage').val(response.borrowing.borrowing_status);

            // Format dates for input fields
            if (response.borrowing.borrow_at) {
                $('#borrow-date-manage').val(response.borrowing.borrow_at.split(' ')[0]);
            }
            if (response.borrowing.return_at) {
                $('#return-date-manage').val(response.borrowing.return_at.split(' ')[0]);
            }

            $('#notes-manage').val(response.borrowing.notes || '');

            // Show the modal
            var manageModal = new bootstrap.Modal(document.getElementById('manageBorrowingModal'));
            manageModal.show();
        },
        error: function(xhr) {
            Swal.fire({
                title: 'Error!',
                text: xhr.responseJSON?.error || 'Failed to load borrowing details',
                icon: 'error'
            });
        }
    });
}

// Save status changes from manage modal
function saveStatusChanges(table) {
    const borrowingId = $('#borrowing-id').val();
    const status = $('#status-manage').val();
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    $.ajax({
        url: `/borrowings/${borrowingId}`,
        type: 'PUT',
        data: {
            _token: token,
            action: 'update_status',
            status: status
        },
        success: function(response) {
            // Close the modal
            var manageModal = bootstrap.Modal.getInstance(document.getElementById('manageBorrowingModal'));
            manageModal.hide();

            Swal.fire({
                title: 'Success!',
                text: response.success,
                icon: 'success'
            }).then(() => {
                if (table && typeof table.ajax.reload === 'function') {
                    table.ajax.reload(null, false);
                } else {
                    location.reload(); // fallback
                }
            });
        },
        error: function(xhr) {
            Swal.fire({
                title: 'Error!',
                text: xhr.responseJSON?.error || 'Failed to update borrowing status',
                icon: 'error'
            });
        }
    });
}
