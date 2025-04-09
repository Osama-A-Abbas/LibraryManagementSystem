<!-- Book Table Component -->
<div class="row">
    <div class="col-md-10 offset-1" style="margin-top: 100px">
        @if (Auth::check() && Auth::user()->hasPermissionTo('create books'))
            <a class="btn btn-info mb-3" data-bs-toggle="modal" data-bs-target="#exampleModal">Add Book</a>
        @endif
        <table id="booksTable" class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Cover</th>
                    <th scope="col">Title</th>
                    <th scope="col">Genre</th>
                    <th scope="col">Author</th>
                    <th scope="col">Description</th>
                    <th scope="col">Published Date</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Data will be loaded via DataTables -->
            </tbody>
        </table>
    </div>
</div>
