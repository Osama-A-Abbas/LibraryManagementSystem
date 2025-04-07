<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" /> <!-- add csrf token globally -->
    <title>Create Book Genre</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- for sweet alert -->
    <!--------data table cdn-------->
    <link href="https://cdn.datatables.net/v/dt/dt-2.2.2/datatables.min.css" rel="stylesheet"
        integrity="sha384-2vMryTPZxTZDZ3GnMBDVQV8OtmoutdrfJxnDTg0bVam9mZhi7Zr3J1+lkVFRr71f" crossorigin="anonymous">

</head>

<body>

    <!-- Modal -->
    <div class="modal fade ajax-modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <form id="bookForm">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle"></h5> <!--model title here -->
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body"> <!--lables and fields can be added below this line-->
                        <input type="hidden" name="book_id" id="book_id">
                        <div class="form-group mb-3">
                            <label for="title">Book Title</label> <!--label for book title -->
                            <input type="text" id="title" name="title" class="form-control"
                                placeholder="e.g. Harry Potter" />
                            <!--input field for book title -->
                            <span id="titleError" class="text-danger error-messages"></span>
                            <!--error message for book title -->
                        </div>
                        <div class="form-group mb-3">
                            <label for="genre">Genre</label> <!--label for genre -->
                            <select id="genre" name="genre" class="form-control"> <!--select field for genre -->
                                <option disabled selected>Choose Genre...</option> <!--default option -->
                                <option value="fiction">Fiction</option> <!--other options... -->
                                <option value="nonfiction">Nonfiction</option>
                            </select>
                            <span id="genreError" class="text-danger error-messages"></span>
                            <!--error message for book genre -->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <!--close button -->
                        <button type="button" class="btn btn-primary" id="saveBtn"></button> <!--save button here -->
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Add Book Button || Triggers modal -->
    <div class="row">
        <div class="col-md-6 offset-3" style="margin-top: 100px">
            <a class="btn btn-info mb-3" data-bs-toggle="modal" data-bs-target="#exampleModal">Add Book</a>
            <!--data table-->
            <table id="booksTable" class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Title</th>
                        <th scope="col">Genre</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody> <!--we are not adding data  from here, we will add it from a backend query-->
                </tbody>
            </table>
        </div>
    </div>





    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <!--------data table cdn-------->
    <script src="https://cdn.datatables.net/v/dt/dt-2.2.2/datatables.min.js"
        integrity="sha384-2Ul6oqy3mEjM7dBJzKOck1Qb/mzlO+k/0BQv3D3C7u+Ri9+7OBINGa24AeOv5rgu" crossorigin="anonymous">
    </script>

    <!-- Load our JavaScript files -->
    <script src="{{ asset('js/books/utils.js') }}"></script>
    <script src="{{ asset('js/books/datatable.js') }}"></script>
    <script src="{{ asset('js/books/modal.js') }}"></script>
    <script src="{{ asset('js/books/delete.js') }}"></script>
    <script src="{{ asset('js/books/main.js') }}"></script>
</body>

</html>
