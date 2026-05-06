<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>High Custom Jewellers | Staff Login</title>
    <!-- Google Fonts + Font Awesome -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Cormorant+Garamond:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            background: radial-gradient(ellipse at 30% 40%, #0C0A0F 0%, #020202 100%);
            font-family: 'Inter', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1.5rem;
            position: relative;
        }

        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 80 80" opacity="0.08"><path fill="none" d="M14 16L40 4l26 12v24L40 52 14 40V16z" stroke="%23D4AF37" stroke-width="0.8"/><circle cx="40" cy="28" r="3" fill="%23D4AF37" fill-opacity="0.2"/><path d="M20 32L40 20l20 12v16L40 60 20 48V32z" stroke="%23D4AF37" stroke-width="0.5" fill="none"/></svg>');
            background-repeat: repeat;
            pointer-events: none;
            z-index: 0;
        }

        .login-card {
            max-width: 480px;
            width: 100%;
            background: rgba(10, 8, 15, 0.85);
            backdrop-filter: blur(12px);
            border-radius: 2rem;
            border: 1px solid rgba(212, 175, 55, 0.4);
            padding: 2rem 2rem 2.2rem;
            box-shadow: 0 30px 50px rgba(0, 0, 0, 0.6);
            position: relative;
            z-index: 2;
            transition: all 0.3s ease;
        }

        .login-card:hover {
            border-color: rgba(212, 175, 55, 0.7);
            box-shadow: 0 35px 55px rgba(0, 0, 0, 0.7);
        }

        .brand-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .brand-name {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2.4rem;
            font-weight: 600;
            letter-spacing: 2px;
            background: linear-gradient(135deg, #F9E0A0 0%, #D4AF37 45%, #B88A1A 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .brand-symbol {
            font-size: 0.9rem;
            letter-spacing: 6px;
            color: #D4AF37;
            opacity: 0.7;
        }

        h2 {
            text-align: center;
            color: #F5E7D3;
            font-family: 'Cormorant Garamond', serif;
            font-weight: 500;
            font-size: 1.8rem;
            margin-bottom: 1.8rem;
        }

        .form-group {
            margin-bottom: 1.3rem;
            position: relative;
        }

        label {
            display: block;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #CBB67C;
            margin-bottom: 0.5rem;
        }

        label i {
            margin-right: 6px;
            color: #D4AF37;
        }

        .input-field {
            width: 100%;
            background: rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(212, 175, 55, 0.4);
            border-radius: 16px;
            padding: 0.85rem 1rem;
            font-size: 0.9rem;
            font-weight: 500;
            color: #FBF5E8;
            transition: all 0.2s ease;
            outline: none;
        }

        .input-field:focus {
            border-color: #D4AF37;
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.2);
            background: rgba(0, 0, 0, 0.7);
        }

        .password-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .password-wrapper input {
            padding-right: 2.6rem;
        }

        .toggle-password {
            position: absolute;
            right: 1rem;
            color: #a99260;
            cursor: pointer;
            font-size: 1rem;
            background: transparent;
            border: none;
            outline: none;
        }

        .toggle-password:hover {
            color: #D4AF37;
        }

        .error-text {
            font-size: 0.7rem;
            color: #F5A97F;
            margin-top: 0.35rem;
            display: block;
        }

        .has-error {
            border-color: #E08E6D !important;
        }

        /* Forgot password link styles */
        .forgot-link {
            color: #D4AF37;
            font-size: 0.75rem;
            text-decoration: none;
            transition: color 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .forgot-link:hover {
            color: #F5DEB3;
            text-decoration: underline;
        }

        .d-flex {
            display: flex;
        }

        .justify-content-end {
            justify-content: flex-end;
        }

        .mb-3 {
            margin-bottom: 0.75rem;
        }

        .mt-1 {
            margin-top: 0.25rem;
        }

        .btn-login {
            width: 100%;
            background: linear-gradient(105deg, #B3862D 0%, #D4AF37 55%, #F5DEB3 100%);
            border: none;
            padding: 1rem;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1rem;
            letter-spacing: 2px;
            color: #0E0C13;
            margin: 0.5rem 0 1.2rem;
            cursor: pointer;
            transition: 0.25s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .btn-login:hover {
            background: linear-gradient(105deg, #D4AF37, #F3D572);
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.4);
        }

        .signin-link {
            text-align: center;
            font-size: 0.85rem;
            color: #C1AE7A;
            border-top: 1px solid rgba(212, 175, 55, 0.25);
            padding-top: 1.2rem;
        }

        .signin-link a {
            color: #E9CD8A;
            text-decoration: none;
            font-weight: 600;
            margin-left: 6px;
        }

        .signin-link a:hover {
            text-decoration: underline;
        }

        @media (max-width: 550px) {
            .login-card {
                padding: 1.5rem;
            }

            .brand-name {
                font-size: 1.8rem;
            }
        }
    </style>
</head>

<body>
    <div class="login-card">
        <div class="brand-header">
            <div class="brand-name">HIGH CUSTOM JEWELLERS</div>
            <div class="brand-symbol">✦ ✧ ✦</div>
        </div>

        <form id="LoginForm" method="post">
            @csrf
            <div class="form-group">
                <label><i class="fas fa-envelope"></i> Email Address</label>
                <input type="text" class="input-field" id="login" name="login"
                    placeholder="Email or User Code">
                <div class="error-text" id="email_error"></div>
            </div>

            <div class="form-group">
                <label><i class="fas fa-lock"></i> Password</label>
                <div class="password-wrapper">
                    <input type="password" class="input-field" id="password" name="password" placeholder="••••••••">
                    <i class="fas fa-eye-slash toggle-password" data-target="password"></i>
                </div>
                <div class="error-text" id="password_error"></div>
                <!-- Forgot Password link positioned right-aligned -->
                <div class="d-flex justify-content-end mb-3 mt-1">
                    <a href="{{ route('password.request') }}" class="forgot-link">
                        🔒 Forgot Password?
                    </a>
                </div>
            </div>

            <button type="submit" class="btn-login"><i class="fas fa-gem"></i> SIGN IN <i
                    class="fas fa-arrow-right"></i></button>

            <div class="signin-link">
                Don't have an account? <a href="{{ route('register-data') }}">Create Account</a>
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            // 👁️ Toggle password
            $('.toggle-password').on('click', function() {
                var targetId = $(this).data('target');
                var input = $('#' + targetId);
                var type = input.attr('type') === 'password' ? 'text' : 'password';
                input.attr('type', type);
                $(this).toggleClass('fa-eye-slash fa-eye');
            });

            function clearErrors() {
                $('.error-text').text('');
                $('.input-field').removeClass('has-error');
            }

            function clientValidate() {
                let isValid = true;
                clearErrors();

                let login = $('#login').val().trim();
                let password = $('#password').val();

                if (!login) {
                    $('#login_error').text('Email or User Code is required.');
                    $('#login').addClass('has-error');
                    isValid = false;
                }

                if (!password) {
                    $('#password_error').text('Password is required.');
                    $('#password').addClass('has-error');
                    isValid = false;
                } else if (password.length < 8) {
                    $('#password_error').text('Password must be at least 8 characters.');
                    $('#password').addClass('has-error');
                    isValid = false;
                }

                return isValid;
            }

            $('#LoginForm').on('submit', function(e) {
                e.preventDefault();

                if (!clientValidate()) {
                    toastr.error('Please fix the form errors.');
                    return;
                }

                let formData = new FormData(this);

                $.ajax({
                    url: '{{ route('login') }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },

                    success: function(response) {
                        toastr.success(response.message || 'Login successful');

                        if (response.role === 'admin') {
                            window.location.href = "{{ route('admin-dashboard') }}";
                        } else {
                            window.location.href = "{{ route('dashboard') }}";
                        }
                    },

                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;

                            $('.error-text').text('');
                            $('.input-field').removeClass('has-error');

                            $.each(errors, function(key, messages) {
                                $('#' + key + '_error').text(messages[0]);
                                $('#' + key).addClass('has-error');
                            });

                            toastr.error('Validation Error');
                        } else {
                            toastr.error(xhr.responseJSON?.message || 'Login Failed');
                        }
                    }
                });
            });

            $('#login, #password').on('input', function() {
                $(this).removeClass('has-error');
                $('#' + $(this).attr('id') + '_error').text('');
            });
        });
    </script>
</body>

</html>
