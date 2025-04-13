<!-- Book Modal Component -->
<div class="modal fade ajax-modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form id="bookForm" enctype="multipart/form-data">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="book_id" id="book_id">

                    <!-- TITLE -->
                    <div class="form-group mb-3">
                        <label for="title">Book Title</label>
                        <input type="text" id="title" name="title" class="form-control"
                            placeholder="e.g. Harry Potter" />
                        <span id="titleError" class="text-danger error-messages"></span>
                    </div>

                    <!-- GENRES -->
                    <div class="form-group mb-3">
                        <label for="genres">Genres</label>
                        <select id="genres" name="genres[]" class="form-select" multiple>
                            @foreach(\App\Models\Genre::orderBy('name')->get() as $genre)
                                <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">You can select multiple genres</small>
                        <span id="genresError" class="text-danger error-messages"></span>
                    </div>

                    <!-- AUTHOR -->
                    <div class="form-group mb-3">
                        <label for="author">Author</label>
                        <input type="text" id="author" name="author" class="form-control"
                            placeholder="e.g. J.K. Rowling" />
                        <span id="authorError" class="text-danger error-messages"></span>
                    </div>

                    <!-- DESCRIPTION -->
                    <div class="form-group mb-3">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" class="form-control" rows="3" placeholder="Book description..."></textarea>
                        <span id="descriptionError" class="text-danger error-messages"></span>
                    </div>

                    <!-- PUBLISHED AT -->
                    <div class="form-group mb-3">
                        <label for="published_at">Published Date</label>
                        <input type="date" id="published_at" name="published_at" class="form-control" />
                        <span id="published_atError" class="text-danger error-messages"></span>
                    </div>

                    <!-- COVER PAGE -->
                    <div class="form-group mb-3">
                        <label for="cover_page">Cover Image</label>
                        <input type="file" id="cover_page" name="cover_page" class="form-control" accept="image/*" />
                        <span id="cover_pageError" class="text-danger error-messages"></span>
                        <div id="cover_preview" class="mt-2"></div>
                    </div>

                    <!-- BOOK PDF -->
                    <div class="form-group mb-3">
                        <label for="book_pdf">Book PDF File</label>
                        <input type="file" id="book_pdf" name="book_pdf" class="form-control"
                            accept="application/pdf" />
                        <span id="book_pdfError" class="text-danger error-messages"></span>
                    </div>

                    <!-- NUMBER OF COPIES -->
                    <div class="form-group mb-3">
                        <label for="number_of_copies">Number of Copies in Stock</label>
                        <input type="number" id="number_of_copies" name="number_of_copies" class="form-control"
                            min="0" />
                        <span id="number_of_copiesError" class="text-danger error-messages"></span>
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

@push('styles')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container {
        width: 100% !important;
    }
    .select2-container--default .select2-selection--multiple {
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        min-height: 38px;
        padding: 2px;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #0d6efd;
        border: none;
        color: white;
        padding: 2px 8px;
        margin: 2px;
        border-radius: 16px;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: white;
        margin-right: 5px;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
        color: #fff;
        background: transparent;
    }
</style>
@endpush

@push('scripts')
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2
        function initSelect2() {
            $('#genres').select2({
                width: '100%',
                placeholder: 'Select genres',
                allowClear: true,
                closeOnSelect: false,
                dropdownParent: $('#exampleModal')
            });
        }

        // Initialize on document ready
        initSelect2();

        // Re-initialize when modal is shown
        $('#exampleModal').on('shown.bs.modal', function () {
            initSelect2();
        });

        // Clear on modal hide
        $('#exampleModal').on('hidden.bs.modal', function () {
            $('#genres').val(null).trigger('change');
        });

        // Handle edit button click
        $(document).on('click', '.editButton', function() {
            const bookId = $(this).data('id');

            $.ajax({
                url: `/books/${bookId}/edit`,
                type: 'GET',
                success: function(response) {
                    if (response.genres && response.genres.length > 0) {
                        const genreIds = response.genres.map(genre => genre.id);
                        $('#genres').val(genreIds).trigger('change');
                    }
                },
                error: function(error) {
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
</script>
@endpush
