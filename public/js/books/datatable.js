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
            { data: 'title', name: 'title' },
            { data: 'genre', name: 'genre' },
            { data: 'author', name: 'author' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
}
