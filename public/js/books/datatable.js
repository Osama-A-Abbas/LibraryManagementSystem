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
            {
                data: 'id',
                name: 'action',
                orderable: false,
                searchable: false,
                render: function(data) {
                    return `
                        <a href="javascript:void(0)" class="btn btn-sm btn-info editButton" data-id="${data}">Edit</a>
                        <a href="javascript:void(0)" class="btn btn-sm btn-danger deleteButton" data-id="${data}">Delete</a>
                    `;
                }
            }
        ]
    });
}
