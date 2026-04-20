@extends('layouts.layout')

@section('title', 'Login')

@section('content')
    <div class="form-popup">
        <div class="form-box signup">
            <div class="form-content">
                <h2>LOGIN</h2>
                <form action="" id="LoginForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="input-field">
                        <input type="email" name="email" id="email" placeholder="Email">
                        <label>Email</label>
                    </div>
                    <div class="input-field">
                        <input type="password" name="password" id="password" placeholder="Password">
                        <label>Password</label>
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
<script>
    $(document).ready(function() {
        $('#LoginForm').submit(function(e) {
            e.preventDefault(); // Prevent the default form submission

            var formData = $(this).serialize(); // Serialize the form data

            $.ajax({
                url: "{{ route('submit_login') }}", // The route to handle login
                type: "POST",
                data: formData,
                success: function(response) {
                    // Handle successful login (e.g., redirect to dashboard)
                    window.location.href = "{{ route('dashboard') }}";
                },
                error: function(xhr) {
                    // Handle errors (e.g., display error messages)
                    alert('Login failed. Please check your credentials and try again.');
                }
            });
        });
    });

</script>
