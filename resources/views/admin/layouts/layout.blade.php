<!DOCTYPE html>
<html>

<head>
    <link rel="canonical" href="{{ url()->current() }}">
    <title>@yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- CSS -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    @include('admin.layouts.main')

    <style>
        body {
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            min-height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: #111827;
            padding: 20px;
            z-index: 999;
        }

        /* Main Content */
        .main-content {
            margin-left: 250px;
            width: calc(100% - 250px);
        }

        /* Fixed Navbar */
        .topbar {
            position: fixed;
            top: 0;
            right: 0;
            width: calc(100% - 250px);

            background: #ffffff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);

            z-index: 9999;
            height: 70px;

            align-items: center;
            padding: 0 20px;
        }

        /* Page Content */
        .page-content {
            margin-top: 80px;
            padding: 20px;
        }
    </style>

</head>

<body>

    <main class="d-flex">

        <!-- Sidebar -->
        <div class="sidebar">
            <h4>Admin Panel</h4>
            <a href="{{ route('admin-dashboard') }}">Dashboard</a>
            <a href="{{ route('admin-users-index') }}">Users</a>
            <a href="{{ route('social.links') }}">Social Links</a>
            <a href="{{ route('user-sequence-list') }}">All tracking</a>
        </div>

        <!-- Content -->
        <div class="main-content w-100">

            <!-- Navbar -->
            <div class="topbar d-flex justify-content-end p-2">
                <div class="dropdown">
                    <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">
                        @if (Auth::check())
                            <img src="{{ asset(Auth::user()->user_image ?? 'images/user-icon.jpg') }}"
                                class="profile-img me-2">
                            <strong>{{ Auth::user()->name }} {{ Auth::user()->lastname }}</strong>
                        @endif
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('admin-profile') }}">Profile</a></li>
                        <li><a class="dropdown-item text-danger" id="logoutBtn" href="#">Logout</a></li>
                    </ul>
                </div>
            </div>

            <!-- Page Content -->
            <div class="page-content">
                @yield('content')
            </div>

        </div>
    </main>


    <!-- Common JS -->
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
                    success: function() {
                        toastr.success("Logged out successfully");

                        setTimeout(function() {
                            window.location.href = "{{ route('login-data') }}";
                        }, 1000);
                    }
                });

            });

        });
    </script>

</body>

</html>
