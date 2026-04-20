<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/login-register.css') }}">
</head>
<body>

    {{-- <header>
    </header> --}}

    <main>
        @yield('content')
    </main>

    {{-- <footer>
    </footer> --}}

</body>
</html>
