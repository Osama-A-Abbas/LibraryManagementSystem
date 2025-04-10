<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Library Management System</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

        <style>
            body {
                background: #f8f9fa;
                font-family: 'Figtree', sans-serif;
            }
            .hero-section {
                background: linear-gradient(135deg, #3a0647 0%, #1e40af 100%);
                color: white;
                padding: 4rem 0;
                border-radius: 0.5rem;
            }
            .card {
                border: none;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                transition: transform 0.3s ease;
            }
            .card:hover {
                transform: translateY(-5px);
            }
        </style>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand" href="/">Library System</a>
                <div class="d-flex">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ route('books.create') }}" class="btn btn-outline-light me-2">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-light me-2">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-light">Register</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </nav>

        <div class="container my-5">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="hero-section text-center mb-5">
                        <h1 class="display-4 mb-3">Welcome to the Library Management System</h1>
                        <p class="lead">
                            Browse our collection, borrow books, and manage your reading journey with ease.
                        </p>
                        @guest
                            <div class="mt-4">
                                <a href="{{ route('login') }}" class="btn btn-light btn-lg me-2">Log in</a>
                                <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg">Register</a>
                            </div>
                        @endguest
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <div class="card-body text-center p-4">
                                    <h3 class="card-title">Browse Books</h3>
                                    <p class="card-text">
                                        Explore our extensive collection of books spanning various genres.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <div class="card-body text-center p-4">
                                    <h3 class="card-title">Borrow Books</h3>
                                    <p class="card-text">
                                        Registered users can borrow books with a few simple clicks.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <div class="card-body text-center p-4">
                                    <h3 class="card-title">Track Your Borrowings</h3>
                                    <p class="card-text">
                                        Keep track of your current and past borrowings easily.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer class="bg-dark text-white py-4 mt-5">
            <div class="container text-center">
                <p class="mb-0">© {{ date('Y') }} Library Management System. All rights reserved.</p>
            </div>
        </footer>

        <!-- Scripts -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
            crossorigin="anonymous"></script>
    </body>
</html>
