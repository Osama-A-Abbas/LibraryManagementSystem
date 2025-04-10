<!-- Borrowing Table Component -->
@php
    use Illuminate\Support\Facades\Auth;
@endphp

<div class="row">
    <div class="col-md-10 offset-1" style="margin-top: 100px">
        <h1>Borrowing Management</h1>
        <p>
            @if(Auth::check() && Auth::user()->can('view all borrowings'))
                Showing all borrowing records
            @else
                Showing your borrowing records
            @endif
        </p>

        <table id="borrowingsTable" class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Book Title</th>
                    <th scope="col">User</th>
                    <th scope="col">Status</th>
                    <th scope="col">Borrow Date</th>
                    <th scope="col">Return Date</th>
                    <th scope="col">Created At</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Data will be loaded via DataTables -->
            </tbody>
        </table>
    </div>
</div>

<!-- View Borrowing Modal -->
<div class="modal fade" id="viewBorrowingModal" tabindex="-1" aria-labelledby="viewBorrowingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewBorrowingModalLabel">Borrowing Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="borrowing-details">
                    <h4 id="book-title"></h4>
                    <p><strong>Borrower:</strong> <span id="username"></span></p>
                    <p><strong>Status:</strong> <span id="borrowing-status"></span></p>
                    <p><strong>Borrow Date:</strong> <span id="borrow-date"></span></p>
                    <p><strong>Return Date:</strong> <span id="return-date"></span></p>
                    <p><strong>Notes:</strong> <span id="notes"></span></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Manage Borrowing Modal (Admin Only) -->
<div class="modal fade" id="manageBorrowingModal" tabindex="-1" aria-labelledby="manageBorrowingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="manageBorrowingModalLabel">Manage Borrowing</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="manageBorrowingForm">
                    <input type="hidden" id="borrowing-id">
                    <div class="mb-3">
                        <label for="book-title-manage" class="form-label">Book Title</label>
                        <input type="text" class="form-control" id="book-title-manage" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="username-manage" class="form-label">Borrower</label>
                        <input type="text" class="form-control" id="username-manage" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="status-manage" class="form-label">Status</label>
                        <select class="form-select" id="status-manage">
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                            <option value="returned">Returned</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="borrow-date-manage" class="form-label">Borrow Date</label>
                        <input type="date" class="form-control" id="borrow-date-manage">
                    </div>
                    <div class="mb-3">
                        <label for="return-date-manage" class="form-label">Return Date</label>
                        <input type="date" class="form-control" id="return-date-manage">
                    </div>
                    <div class="mb-3">
                        <label for="notes-manage" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes-manage"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveStatusBtn">Save Changes</button>
            </div>
        </div>
    </div>
</div>
