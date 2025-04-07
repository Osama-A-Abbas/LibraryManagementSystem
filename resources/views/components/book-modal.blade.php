<!-- Book Modal Component -->
<div class="modal fade ajax-modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form id="bookForm">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body"> <!--HERE YOU CAN ADD NEW FIELDS     -->
                    <input type="hidden" name="book_id" id="book_id">
                    <!-- TITLE, id = title-->
                    <div class="form-group mb-3">
                        <label for="title">Book Title</label>
                        <input type="text" id="title" name="title" class="form-control"
                            placeholder="e.g. Harry Potter" />
                        <span id="titleError" class="text-danger error-messages"></span>
                    </div>

                    <!--GENRE, id = genre-->
                    <div class="form-group mb-3">
                        <label for="genre">Genre</label>
                        <select id="genre" name="genre" class="form-control">
                            <option disabled selected>Choose Genre...</option>
                            <option value="fiction">Fiction</option>
                            <option value="nonfiction">Nonfiction</option>
                        </select>
                        <span id="genreError" class="text-danger error-messages"></span>
                    </div>

                    <!-- AUTHOR, id = author-->
                    <div class="form-group mb-3">
                        <label for="author">Author</label>
                        <input type="text" id="author" name="author" class="form-control"
                            placeholder="e.g. J.K. Rowling" />
                        <span id="authorError" class="text-danger error-messages"></span>
                    </div>

                     <!-- Description, id = description-->
                     <div class="form-group mb-3">
                        <label for="description">Description</label>
                        <input type="text" id="description" name="description" class="form-control"
                            placeholder="Book description, summary, etc. e.g. A book about a boy who goes to a magic school" />
                        <span id="descriptionError" class="text-danger error-messages"></span>
                    </div>

                    <!-- Published at, id = published_at-->
                    <div class="form-group mb-3">
                        <label for="published_at">Published At</label>
                        <input type="date" id="published_at" name="published_at" class="form-control" />
                        <span id="publishedAtError" class="text-danger error-messages"></span>
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
