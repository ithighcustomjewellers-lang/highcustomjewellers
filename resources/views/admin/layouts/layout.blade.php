<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/admin/layouts.css') }}">
    <!-- Bootstrap -->

</head>
<body>
<main class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar">
        <h4>Admin Panel</h4>
        <a href="{{ route('admin-dashboard') }}">Dashboard</a>
        <a href="{{ route('admin-users-index') }}">Users</a>
    </div>
    <!-- Main Content -->
    <div class="main-content w-100">
        <!-- Navbar -->
        <div class="topbar d-flex justify-content-end p-2">
            <div class="dropdown">
                <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">
                    <img src="/images/user.webp" class="profile-img">
                    Admin User
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#">Profile</a></li>
                    <li><a class="dropdown-item text-danger" id="logoutBtn" href="{{ route('logout') }}">Logout</a></li>
                </ul>
            </div>
        </div>

        <!-- 🔥 THIS IS IMPORTANT -->
        <div class="p-4">
            @yield('content')
        </div>
    </div>
</main>

</body>
</html>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
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
