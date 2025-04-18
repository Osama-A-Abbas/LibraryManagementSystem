console.log('Borrow.js loaded');

// Helper function to format a Date object to 'YYYY-MM-DD' format
function formatDate(date) {
    return date.toISOString().split('T')[0];
}

// Returns today's date with time zeroed out to midnight
function getToday() {
    const date = new Date();
    date.setHours(0, 0, 0, 0); // important for avoiding time-based discrepancies
    return date;
}

// Returns a new date with a number of years added to the given date
function getDatePlusYears(date, years) {
    const newDate = new Date(date);
    newDate.setFullYear(newDate.getFullYear() + years);
    return newDate;
}

// Returns a new date with a number of days added to the given date
function getDatePlusDays(date, days) {
    const newDate = new Date(date);
    newDate.setDate(newDate.getDate() + days);
    return newDate;
}

// Builds the borrow form HTML with dynamic default, min, and max date values
function getBorrowFormHTML(borrowAtDefault, borrowMax, returnMin, returnMax) {
    return `
        <form id="borrowForm">
            <div class="form-group mb-3">
                <label for="borrow_at">Borrow Date</label>
                <input type="date" id="borrow_at" class="form-control"
                    value="${borrowAtDefault}"
                    min="${borrowAtDefault}"
                    max="${borrowMax}" required>
            </div>
            <div class="form-group mb-3">
                <label for="return_at">Return Date</label>
                <input type="date" id="return_at" class="form-control"
                    min="${returnMin}"
                    max="${returnMax}" required>
            </div>
            <div class="form-group mb-3">
                <label for="notes">Notes (Optional)</label>
                <textarea id="notes" class="form-control"></textarea>
            </div>
        </form>
    `;
}

document.addEventListener('DOMContentLoaded', function () {
    console.log('Document ready');

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('borrowButton') || e.target.closest('.borrowButton')) {
            const button = e.target.classList.contains('borrowButton') ? e.target : e.target.closest('.borrowButton');
            const bookId = button.getAttribute('data-id');

            // Check if user is authenticated
            const isAuthenticated = document.body.classList.contains('user-authenticated');

            if (!isAuthenticated) {
                // User is not logged in - show message
                Swal.fire({
                    title: 'Authentication Required',
                    text: 'Please register or login first to borrow a book',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Login',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redirect to login page
                        window.location.href = '/login';
                    }
                });
                return;
            }

            // Continue with borrowing process for authenticated users
            const today = getToday();
            const borrowDefault = formatDate(today);
            const borrowMaxLimit = formatDate(getDatePlusYears(today, 20));
            const returnMinDefault = formatDate(getDatePlusDays(today, 7));
            const returnMaxLimit = formatDate(getDatePlusYears(today, 2));

            Swal.fire({
                title: 'Borrow Book',
                html: getBorrowFormHTML(borrowDefault, borrowMaxLimit, returnMinDefault, returnMaxLimit),
                showCancelButton: true,
                confirmButtonText: 'Borrow',
                cancelButtonText: 'Cancel',
                didOpen: () => {
                    const borrowInput = document.getElementById('borrow_at');
                    const returnInput = document.getElementById('return_at');

                    // Function to update return date range based on borrow date
                    const updateReturnDateLimits = (baseDate) => {
                        const borrowDate = new Date(baseDate);
                        borrowDate.setHours(0, 0, 0, 0); // Normalize time

                        const newReturnMin = formatDate(getDatePlusDays(borrowDate, 7));
                        const newReturnMax = formatDate(getDatePlusYears(borrowDate, 2));

                        returnInput.min = newReturnMin;
                        returnInput.max = newReturnMax;

                        // Clear invalid return date if it falls outside the new range
                        if (returnInput.value) {
                            const currentReturn = new Date(returnInput.value);
                            if (currentReturn < new Date(newReturnMin) || currentReturn > new Date(newReturnMax)) {
                                returnInput.value = '';
                            }
                        }
                    };

                    // Initialize limits
                    updateReturnDateLimits(borrowInput.value);

                    // Update return limits on borrow date change
                    borrowInput.addEventListener('change', () => {
                        updateReturnDateLimits(borrowInput.value);
                    });
                },
                preConfirm: () => {
                    const borrowAt = document.getElementById('borrow_at').value;
                    const returnAt = document.getElementById('return_at').value;
                    const notes = document.getElementById('notes').value;

                    if (!borrowAt) {
                        Swal.showValidationMessage('Please select a borrow date');
                        return false;
                    }

                    if (!returnAt) {
                        Swal.showValidationMessage('Please select a return date');
                        return false;
                    }

                    const borrowDate = new Date(borrowAt);
                    borrowDate.setHours(0, 0, 0, 0); // Normalize

                    const today = getToday();
                    const maxBorrowDate = getDatePlusYears(today, 20);

                    // Validate borrow date range
                    if (borrowDate < today || borrowDate > maxBorrowDate) {
                        Swal.showValidationMessage('Please choose a valid borrow date');
                        return false;
                    }

                    const returnDate = new Date(returnAt);
                    returnDate.setHours(0, 0, 0, 0); // Normalize
                    const minReturnDate = getDatePlusDays(borrowDate, 7);
                    const maxReturnDate = getDatePlusYears(borrowDate, 2);

                    // Validate return date range
                    if (returnDate < minReturnDate || returnDate > maxReturnDate) {
                        Swal.showValidationMessage('Return date must be at least 7 days after borrow date and within 2 years');
                        return false;
                    }

                    return {
                        borrowAt: borrowAt,
                        returnAt: returnAt,
                        notes: notes || null
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const formData = new URLSearchParams();
                    formData.append('_token', token);
                    formData.append('book_id', bookId);
                    formData.append('borrow_at', result.value.borrowAt);
                    formData.append('return_at', result.value.returnAt);
                    if (result.value.notes) formData.append('notes', result.value.notes);

                    // Submit form data via AJAX
                    $.ajax({
                        url: '/borrowing',
                        type: 'POST',
                        data: Object.fromEntries(formData),
                        headers: {
                            'X-CSRF-TOKEN': token
                        },
                        success: function () {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Book has been borrowed successfully.',
                                icon: 'success'
                            }).then(() => {
                                const dataTable = $('#booksTable').DataTable();
                                if (dataTable && typeof dataTable.ajax.reload === 'function') {
                                    dataTable.ajax.reload(null, false);
                                } else {
                                    console.error('Failed to get DataTable instance');
                                    location.reload(); // fallback
                                }
                            });
                        },
                        error: function () {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Failed to borrow book. Please try again.',
                                icon: 'error'
                            });
                        }
                    });
                }
            });
        }
    });
});
