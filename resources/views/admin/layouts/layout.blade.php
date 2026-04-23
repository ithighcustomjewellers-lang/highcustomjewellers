<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>
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
                    <li><a class="dropdown-item text-danger" href="#">Logout</a></li>
                </ul>
            </div>
        </div>

        <!-- 🔥 THIS IS IMPORTANT -->
        <div class="p-4">
            @yield('content')
        </div>
    </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</body>
</html>
