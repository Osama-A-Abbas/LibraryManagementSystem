@php
    use Illuminate\Support\Facades\Auth;
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'Book Management')</title>

    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdn.datatables.net/v/dt/dt-2.2.2/datatables.min.css" rel="stylesheet"
        integrity="sha384-2vMryTPZxTZDZ3GnMBDVQV8OtmoutdrfJxnDTg0bVam9mZhi7Zr3J1+lkVFRr71f" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

    <style>
        .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice {
            background-color: #0d6efd;
            border: none;
            color: white;
            padding: 2px 8px;
            margin: 2px;
            border-radius: 16px;
        }
        .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice__remove {
            color: white;
            margin-right: 5px;
        }
        .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice__remove:hover {
            color: #fff;
            background: transparent;
        }
        .select2-container--bootstrap-5 .select2-selection {
            min-height: 38px;
        }
    </style>

    @stack('styles')
    @yield('styles')
</head>

<body class="{{ Auth::check() ? 'user-authenticated' : 'user-guest' }}">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/">Library System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('books.create') }}">Books</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/borrowings">Borrowings</a>
                    </li>
                    {{-- @if (Auth::check() && Auth::user()->hasRole('admin'))
                    <li class="nav-item">
                        <a class="nav-link" href="/admin">Admin</a>
                    </li>
                    @endif --}}
                </ul>
                <ul class="navbar-nav">
                    @if (Auth::check())
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="/profile">Profile</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    @else
                    <li class="nav-item">
                        <a class="nav-link" href="/login">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/register">Register</a>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="container">
        @yield('content')
    </div>

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/v/dt/dt-2.2.2/datatables.min.js"
        integrity="sha384-2Ul6oqy3mEjM7dBJzKOck1Qb/mzlO+k/0BQv3D3C7u+Ri9+7OBINGa24AeOv5rgu" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Load our JavaScript files -->
    <script src="{{ asset('js/books/utils.js') }}"></script>
    <script src="{{ asset('js/books/datatable.js') }}"></script>
    <script src="{{ asset('js/books/modal.js') }}"></script>
    <script src="{{ asset('js/books/delete.js') }}"></script>
    <script src="{{ asset('js/books/edit.js') }}"></script>
    <script src="{{ asset('js/books/tooltip.js') }}"></script>
    <script src="{{ asset('js/books/main.js') }}"></script>
    <script src="{{ asset('js/books/view-book.js') }}"></script>
    <script src="{{ asset('js/books/borrow.js') }}"></script>
    <script src="{{ asset('js/books/download.js') }}"></script>
    <script src="{{ asset('js/books/select2-init.js') }}"></script>

    @stack('scripts')
    @yield('scripts')
</body>

</html>
