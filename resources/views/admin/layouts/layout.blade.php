<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- CSS -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    @include('admin.layouts.main')

</head>

<body>

<main class="d-flex">

    <!-- Sidebar -->
    <div class="sidebar">
        <h4>Admin Panel</h4>
        <a href="{{ route('admin-dashboard') }}">Dashboard</a>
        <a href="{{ route('admin-users-index') }}">Users</a>
        <a href="{{ route('admin-sequences-index') }}">Master</a>
        <a href="{{ route('admin-contacts-index') }}">Contacts</a>
    </div>

    <!-- Content -->
    <div class="main-content w-100">

        <!-- Navbar -->
        <div class="topbar d-flex justify-content-end p-2">
            <div class="dropdown">
                {{-- <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">
                    <img src="/images/user.webp" width="30" class="rounded-circle">
                    Admin User
                </a> --}}

                {{-- <img src="{{ asset($user->user_image ?? 'images/user-icon.jpg') }}" class="profile-img me-2">
                <strong>{{ $user->name }} {{ $user->lastname }}</strong> --}}
                <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">
                    @if(Auth::check())
                        <img src="{{ asset(Auth::user()->user_image ?? 'images/user-icon.jpg') }}" class="profile-img me-2">
                        <strong>{{ Auth::user()->name }} {{ Auth::user()->lastname }}</strong>
                    @endif
                </a>

                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('admin-profile')}}">Profile</a></li>
                    <li><a class="dropdown-item text-danger" id="logoutBtn" href="#">Logout</a></li>
                </ul>
            </div>
        </div>

        <!-- Page Content -->
        <div class="p-4">
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
