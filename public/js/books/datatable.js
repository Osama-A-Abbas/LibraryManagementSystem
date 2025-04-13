/**
 * Books DataTable Handlers
 *
 * This file contains the code for handling the book DataTable.
 */

function initDataTable() {
    return $('#booksTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/books/index',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'cover_page', name: 'cover_page', orderable: false, searchable: false },
            { data: 'title', name: 'title' },
            { data: 'genres', name: 'genres', orderable: false },
            { data: 'author', name: 'author' },
            { data: 'description', name: 'description' },
            { data: 'published_at', name: 'published_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
}
