<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Create Book Genre</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>

<body>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title"></h5> <!--model title here -->
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body"> <!--lables and fields can be added below this line-->
                    <div class="form-group mb-3">
                        <label for="bookName">Book Name</label> <!--label for book name -->
                        <input type="text" id="bookName" class="form-control" placeholder="e.g. Harry Potter" /> <!--input field for book name -->
                    </div>
                    <div class="form-group mb-3">
                        <label for="genre">Genre</label> <!--label for genre -->
                        <select id="genre" class="form-control"> <!--select field for genre -->
                            <option disabled selected>Choose Genre...</option> <!--default option -->
                            <option value="fiction">Fiction</option>    <!--other options... -->
                            <option value="non_fiction">Non-Fiction</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> <!--close button -->
                    <button type="button" class="btn btn-primary" id="saveBtn"></button> <!--save button here -->
                </div>
            </div>
        </div>
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
</body>

</html>
