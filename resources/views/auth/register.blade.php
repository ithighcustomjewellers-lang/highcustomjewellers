@extends('layouts.layout')
@section('title', 'Register')

@section('content')
    <div class="form-popup">
        <div class="form-box signup">
            <div class="form-content">
                <h2>SIGNUP</h2>

                <form action="" id="registerForm" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="input-field">
                            <input type="text" name="firstname" id="firstname" placeholder="First Name">
                            {{-- <label>First Name</label> --}}
                        </div>

                        <div class="input-field">
                            <input type="text" name="lastname" id="lastname" placeholder="Last Name">
                            {{-- <label>Last Name</label> --}}
                        </div>
                    </div>

                    <div class="input-field">
                        <input type="email" name="email" id="email" placeholder="Email">
                        {{-- <label>Email</label> --}}
                    </div>

                    <div class="input-field">
                        <input type="number" name="phone" id="phone" placeholder="Phone Number">
                        {{-- <label>Phone Number</label> --}}
                    </div>

                    <div class="input-field">
                        <input type="password" name="password" id="password" placeholder="Password">
                        {{-- <label>Password</label> --}}
                    </div>

                    <div class="input-field">
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            placeholder="Confirm Password">
                        {{-- <label>Confirm Password</label> --}}
                    </div>

                    <button type="submit">Sign Up</button>
                </form>

                <div class="bottom-link">
                    Already have an account?
                    <a href="{{ route('login-data') }}">Login</a>
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

        $('#registerForm').submit(function(e) {
            e.preventDefault();

            let formData = new FormData(this);

            $.ajax({
                url: '{{ route('submit_register') }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,

                success: function(response) {
                    toastr.success(response.message);
                    $('#registerForm')[0].reset();
                    $('.error-text').remove();

                    setTimeout(function() {
                        window.location.href = "{{ route('login-data') }}";
                    }, 1000);
                },

                error: function(xhr) {
                    let errors = xhr.responseJSON.errors;
                    $('.error-text').remove();
                    $('.input-field').removeClass('has-error');

                    $.each(errors, function(key, value) {
                        let input = $('#' + key);
                        // Add error message
                        input.closest('.input-field') .append('<span class="error-text">' + value[0] + '</span>');
                        //ADD ERROR CLASS
                        input.closest('.input-field').addClass('has-error');
                    });
                }
            });
        });
    });
</script>


