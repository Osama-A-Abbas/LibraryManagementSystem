@extends('layouts.app')

@section('content')
<div class="container">

    <!-- Add Button -->
    <div class="text-end mb-3">
        <button class="btn btn-primary" id="addBookBtn" data-bs-toggle="modal" data-bs-target="#bookModal">
            Add Book
        </button>
    </div>

    <!-- Books Table -->
    <table class="table table-bordered" id="booksTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Author</th>
                <th width="150px">Actions</th>
            </tr>
        </thead>
    </table>

    <!-- Include Modal Partial -->
    @include('books.partials.modal')

</div>
@endsection

@section('scripts')
<script src="{{ asset('js/book-crud.js') }}"></script>
@endsection
