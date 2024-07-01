<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Login</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">

    <style>
        .custom-card {
            max-width: 600px;
            margin: auto;
        }
        .custom-input {
            width: 100%;
        }
    </style>

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5 custom-card">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row justify-content-center">
                            <div class="col-lg-10">
                                <div class="p-5">
                                    <div class="text-center">
                                        {{-- <img src="path/to/your/logo.png" alt="Logo" class="mb-4" style="max-width: 100px;"> --}}
                                        <h1 class="h4 text-gray-900 mb-4">GYM Booking</h1>
                                        {{-- <h2 class="h4 text-gray-900 mb-4">Di Gym Booking Admin</h2> --}}
                                    </div>
                                    @if(session()->has('loginError'))
                                        <div class="alert alert-danger">
                                            {{ session('loginError') }}
                                        </div>
                                    @endif
                                    <form class="user" action="{{ route('login') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user custom-input"
                                                id="email" name="email" placeholder="Masukkan email..." required autofocus value="{{ old('email') }}">
                                            @error('email')<span class="text-danger">{{ $message }}</span>@enderror
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user custom-input"
                                                id="password" name="password" placeholder="Password">
                                            @error('password')<span class="text-danger">{{ $message }}</span>@enderror
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Login
                                        </button>
                                    </form>
                                    <hr>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

</body>

</html>
