@extends('layouts.layout')

@section('title', 'Login')

<style>
    .forgot-link {
        font-size: 14px;
        color: #0d6efd;
        text-decoration: none;
        transition: 0.3s;
    }

    .forgot-link:hover {
        color: #0a58ca;
        text-decoration: underline;
    }
</style>

@section('content')
    <div class="form-popup">
        <div class="form-box signup">
            <div class="form-content">
                <h2>LOGIN</h2>
                <form action="" id="LoginForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="input-field">
                        <input type="email" name="email" id="email" placeholder="Email">
                        {{-- <label>Email</label> --}}
                    </div>
                    <div class="input-field">
                        <input type="password" name="password" id="password" placeholder="Password">
                        {{-- <label>Password</label> --}}
                    </div>
                    <div class="d-flex justify-content-end mb-3">
                        <a href="{{ route('password.request') }}" class="forgot-link">
                            🔒 Forgot Password?
                        </a>
                    </div>
                    <button type="submit">Login</button>

                </form>

                <div class="bottom-link">
                    Don't have an account?
                    <a href="{{ route('register-data') }}">Register</a>
                </div>
            </div>
        </div>
    </div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    $(document).ready(function() {
        $('#LoginForm').submit(function(e) {
            e.preventDefault(); // Prevent the default form submission
            var formData = $(this).serialize();
            $.ajax({
                url: "{{ route('login') }}",
                type: "POST",
                data: formData,
                success: function(response) {
                    if (response.role === 'admin') {
                        window.location.href = "{{ route('admin-dashboard') }}";
                    } else {
                        window.location.href = "{{ route('dashboard') }}";
                    }
                },
                error: function(xhr) {
                    // Handle errors (e.g., display error messages)
                    toastr.error('Login failed. Please check your email and password.');
                }
            });
        });
    });
</script>
