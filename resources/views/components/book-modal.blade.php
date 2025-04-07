<!-- Book Modal Component -->
<div class="modal fade ajax-modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form id="bookForm">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="book_id" id="book_id">
                    <div class="form-group mb-3">
                        <label for="title">Book Title</label>
                        <input type="text" id="title" name="title" class="form-control" placeholder="e.g. Harry Potter" />
                        <span id="titleError" class="text-danger error-messages"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="genre">Genre</label>
                        <select id="genre" name="genre" class="form-control">
                            <option disabled selected>Choose Genre...</option>
                            <option value="fiction">Fiction</option>
                            <option value="nonfiction">Nonfiction</option>
                        </select>
                        <span id="genreError" class="text-danger error-messages"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveBtn"></button>
                </div>
            </div>
        </div>
    </form>
</div>
