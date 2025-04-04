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
</head>

<body>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <form id="bookForm">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle"></h5> <!--model title here -->
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body"> <!--lables and fields can be added below this line-->
                        <div class="form-group mb-3">
                            <label for="title">Book Title</label> <!--label for book name -->
                            <input type="text" name="title" class="form-control" placeholder="e.g. Harry Potter" /> <!--input field for book name -->
                            <span id="titleError" class="text-danger"></span> <!--error message for book title -->
                        </div>
                        <div class="form-group mb-3">
                            <label for="genre">Genre</label> <!--label for genre -->
                            <select name="genre" class="form-control"> <!--select field for genre -->
                                <option disabled selected>Choose Genre...</option> <!--default option -->
                                <option value="fiction">Fiction</option> <!--other options... -->
                                <option value="non_fiction">Non-Fiction</option>
                            </select>
                            <span id="genreError" class="text-danger"></span> <!--error message for book genre -->
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

    <!--  Trigger modal -->
    <div class="row">
        <div class="col-md-6 offset-3" style="margin-top: 100px">
            <a class="btn btn-info" data-bs-toggle="modal" data-bs-target="#exampleModal">Add Book genre</a>
        </div>
    </div>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

    <script>
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#modalTitle').html('Add Book'); // grab the model-title (by it id) and set the html to Add Book
            $('#saveBtn').html('Save Book'); // grab the saveBtn (by it id) and set the html to Save Book
            var bookForm = $('#bookForm')[
            0]; // grab the form with the id bookForm and store as a variable || [0] is used to get all the elements in the form

            //grab the values and send the to the server
            $('#saveBtn').click(function() { // click event when the button is clicked this function will execute

                var formData = new FormData(bookForm); // define *name* not *id* because we are getting it from form data || bookForm is passed as a param

                $.ajax({
                    url: '{{ route('books.store') }}', // which route to send this request to
                    method: 'POST', // method to use (GET, POST...), here POST since we are creating a book and sending data to the server
                    processData: false,
                    contentType: false,
                    data: formData, // which data to send to the server, if you have multiple data you can use {}

                    // in case of success this function will execute
                    success: function(response) {
                        console.log(response)
                    },
                    // in case of error this function will execute
                    error: function(error) {
                        if(error) {
                            console.log(error.responseJSON.errors.title)
                            $('#titleError').html(error.responseJSON.errors.title);
                            $('#genreError').html(error.responseJSON.errors.genre);

                        }
                    }
                });
            })
        });
    </script>
</body>

</html>
