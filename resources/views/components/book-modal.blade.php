<!-- Book Modal Component -->
<div class="modal fade ajax-modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form id="bookForm" enctype="multipart/form-data">
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

                    <!-- DESCRIPTION, id = description-->
                    <div class="form-group mb-3">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" class="form-control" rows="3"
                            placeholder="Book description..."></textarea>
                        <span id="descriptionError" class="text-danger error-messages"></span>
                    </div>

                    <!-- PUBLISHED AT, id = published_at-->
                    <div class="form-group mb-3">
                        <label for="published_at">Published Date</label>
                        <input type="date" id="published_at" name="published_at" class="form-control" />
                        <span id="published_atError" class="text-danger error-messages"></span>
                    </div>

                    <!-- COVER PAGE, id = cover_page-->
                    <div class="form-group mb-3">
                        <label for="cover_page">Cover Image</label>
                        <input type="file" id="cover_page" name="cover_page" class="form-control" accept="image/*" />
                        <span id="cover_pageError" class="text-danger error-messages"></span>
                        <div id="cover_preview" class="mt-2"></div>
                    </div>
                     <!-- BOOK PDF, id = book_pdf-->
                     <div class="form-group mb-3">
                        <label for="book_pdf">Book PDF File</label>
                        <input type="file" id="book_pdf" name="book_pdf" class="form-control" accept="application/pdf" />
                        <span id="book_pdfError" class="text-danger error-messages"></span>
                        {{-- <div id="book_pdf_preview" class="mt-2"></div> --}}
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
