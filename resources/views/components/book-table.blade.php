<!-- Book Table Component -->
@php
    use Illuminate\Support\Facades\Auth;
    $isAdmin = Auth::check() && Auth::user()->can('create books');
@endphp

<div class="row">
    <div class="col-md-12" style="margin-top: 50px">
        <h1>{{ $isAdmin ? 'Browse and Manage Books' : 'Browse Books' }}</h1>

        @if ($isAdmin)
            <a class="btn btn-info mb-3" data-bs-toggle="modal" data-bs-target="#exampleModal">Add Book</a>
        @endif

        <table id="booksTable" class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Cover</th>
                    <th scope="col">Title</th>
                    <th scope="col">Genres</th>
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
