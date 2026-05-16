<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <title>Dashboard</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            overflow-x: hidden;
            background:#f5f7fb;
        }

        .sidebar{
            height:100vh;
            width:250px;
            position:fixed;
            top:0;
            left:0;
            background:#111827;
            padding-top:20px;
            overflow-y:auto;
            z-index:999;
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

        .navbar{
            position:fixed;
            top:0;
            right:0;
            left:250px;
            height:70px;
            z-index:998;
            background:#ffffff !important;
            box-shadow:0 2px 10px rgba(0,0,0,0.06);
            padding:0 20px;
        }

        .main-content{
            margin-left:250px;
            margin-top:70px;
            padding:20px;
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
  {{-- <div class="content">
        <h2>Welcome to Dashboard</h2>
    </div> --}}
    <!-- Sidebar -->
    <div class="sidebar">
        <h4 class="text-white text-center">My Dashboard</h4>
        <a href="#">Dashboard</a>
         <a href="{{ route('master-data-list') }}">Master</a>
        <a href="{{ route('master-link-document') }}">Link</a>
        <a href="{{ route('leads-index') }}">Leads</a>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid d-flex justify-content-end align-items-center gap-3">
            <!-- User Code -->
            <div class="hc-user-code">
                <strong>{{ auth()->user()->user_code }}</strong>
            </div>
            <!-- User Dropdown -->
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle"
                    id="dropdownUser" data-bs-toggle="dropdown">
                    <img src="{{ asset(auth()->user()->user_image ?? 'images/user-icon.jpg') }}" class="profile-img me-2">
                    <strong>{{ auth()->user()->name }} {{ auth()->user()->lastname }}</strong>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('user-profile') }}">Profile</a></li>
                    <li>
                        <a class="dropdown-item" href="{{ url('/connect-gmail') }}">Connect Gmail</a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a class="dropdown-item" href="#" id="logoutBtn">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

   <div class="main-content">
    @yield('content')
</div>
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
