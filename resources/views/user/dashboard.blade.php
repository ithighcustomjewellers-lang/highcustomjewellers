<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <title>Dashboard</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            overflow-x: hidden;
        }

        .sidebar {
            height: 100vh;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background: #343a40;
            padding-top: 20px;
        }

        .sidebar a {
            color: #ddd;
            padding: 10px 20px;
            display: block;
            text-decoration: none;
        }

        .sidebar a:hover {
            background: #495057;
            color: #fff;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
        }

        .navbar {
            margin-left: 250px;
        }

        .profile-img {
            width: 35px;
            height: 35px;
            border-radius: 50%;
        }

        .hc-user-code {
            color: #fff;
            border-radius: 18px;
            margin-left: auto;
            padding: 13px 25px;
            background-image: linear-gradient(to right, #6793d6 0%, #413b3b 40%, #0939a0 60%);
            background-size: 400%;
            font-weight: 600;
            animation: Gradient 5s ease infinite;
        }

        @keyframes Gradient {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h4 class="text-white text-center">My Dashboard</h4>
        <a href="#">Dashboard</a>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid d-flex justify-content-end align-items-center gap-3">

        <!-- User Code -->
        <div class="hc-user-code">
            <strong>{{ $user->user_code }}</strong>
        </div>

        <!-- User Dropdown -->
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle"
                id="dropdownUser" data-bs-toggle="dropdown">

                <img src="{{ asset($user->user_image ?? 'images/user-icon.jpg') }}" class="profile-img me-2">
                <strong>{{ $user->name }} {{ $user->lastname }}</strong>
            </a>

            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('user-profile') }}">Profile</a></li>
                <li>
                    <a class="dropdown-item" href="{{ url('/connect-gmail') }}">Connect Gmail</a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item text-danger" href="#" id="logoutBtn">Logout</a>
                </li>
            </ul>
        </div>

    </div>
</nav>

    <!-- Main Content -->
    <div class="content">
        <h2>Welcome to Dashboard</h2>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        $(document).ready(function() {

            $('#logoutBtn').click(function(e) {
                e.preventDefault();

                $.ajax({
                    url: "{{ route('logout') }}",
                    type: "POST",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        toastr.success("Logged out successfully");

                        // redirect after 1 second
                        setTimeout(function() {
                            window.location.href = "{{ route('login-data') }}";
                        }, 1000);
                    },
                    error: function() {
                        toastr.error("Logout failed");
                    }
                });
            });

        });
    </script>

</body>

</html>
