console.log('Borrow.js loaded');

// Wait for document to be ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('Document ready');

    // Add a global click handler to catch all clicks
    document.addEventListener('click', function(e) {
        console.log('Element clicked:', e.target);

        // Check if this is a borrow button
        if (e.target.classList.contains('borrowButton') || e.target.closest('.borrowButton')) {
            console.log('BORROW BUTTON CLICKED!');

            // Get the button or its parent if the inner text was clicked
            const button = e.target.classList.contains('borrowButton') ? e.target : e.target.closest('.borrowButton');
            const bookId = button.getAttribute('data-id');

            console.log('Book ID:', bookId);

            // Prevent default action
            e.preventDefault();

            // Show SweetAlert form
            Swal.fire({
                title: 'Borrow Book',
                html: `
                    <form id="borrowForm">
                        <div class="form-group mb-3">
                            <label for="borrow_at">Borrow Date</label>
                            <input type="date" id="borrow_at" class="form-control" value="${new Date().toISOString().split('T')[0]}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="return_at">Return Date (Optional)</label>
                            <input type="date" id="return_at" class="form-control">
                        </div>
                        <div class="form-group mb-3">
                            <label for="notes">Notes (Optional)</label>
                            <textarea id="notes" class="form-control"></textarea>
                        </div>
                    </form>
                `,
                showCancelButton: true,
                confirmButtonText: 'Borrow',
                cancelButtonText: 'Cancel',
                preConfirm: () => {
                    const borrowAt = document.getElementById('borrow_at').value;
                    const returnAt = document.getElementById('return_at').value;
                    const notes = document.getElementById('notes').value;

                    if (!borrowAt) {
                        Swal.showValidationMessage('Please select a borrow date');
                        return false;
                    }

                    return {
                        borrowAt: borrowAt,
                        returnAt: returnAt || null,
                        notes: notes || null
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    console.log('Form submitted with values:', result.value);

                    // Get CSRF token
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    console.log('CSRF Token:', token);

                    // Create form data
                    const formData = new FormData();
                    formData.append('_token', token);
                    formData.append('book_id', bookId);
                    formData.append('borrow_at', result.value.borrowAt);

                    if (result.value.returnAt) {
                        formData.append('return_at', result.value.returnAt);
                    }

                    if (result.value.notes) {
                        formData.append('notes', result.value.notes);
                    }

                    // Send AJAX request
                    fetch('/borrowing', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                        },
                        body: formData
                    })
                    .then(response => {
                        console.log('Response status:', response.status);
                        return response.json();
                    })
                    .then(data => {
                        console.log('Response data:', data);

                        Swal.fire({
                            title: 'Success!',
                            text: 'Book has been borrowed successfully.',
                            icon: 'success'
                        }).then(() => {
                            location.reload();
                        });
                    })
                    .catch(error => {
                        console.error('Error:', error);

                        Swal.fire({
                            title: 'Error!',
                            text: 'Something went wrong while processing your request.',
                            icon: 'error'
                        });
                    });
                }
            });
        }
    });
});