<!-- Book Modal -->
<div class="modal fade" id="bookModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="bookForm">
            @csrf
            <input type="hidden" name="book_id" id="book_id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Book Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    <div class="mb-3">
                        <label for="title" class="form-label">Book Title</label>
                        <input type="text" name="title" id="title" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="author" class="form-label">Author</label>
                        <input type="text" name="author" id="author" class="form-control" required>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" id="saveBtn" class="btn btn-success">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>
