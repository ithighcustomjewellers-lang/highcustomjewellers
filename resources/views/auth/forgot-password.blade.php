<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>High Custom Jewellers | Forgot Password</title>
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

        .forgot-card {
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

        .forgot-card:hover {
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
            margin-bottom: 1rem;
        }

        .sub-text {
            text-align: center;
            color: #C1AE7A;
            font-size: 0.85rem;
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
        .error-text {
            font-size: 0.7rem;
            color: #F5A97F;
            margin-top: 0.35rem;
            display: block;
        }
        .has-error {
            border-color: #E08E6D !important;
        }
        .btn-send {
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
        .btn-send:hover {
            background: linear-gradient(105deg, #D4AF37, #F3D572);
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(0,0,0,0.4);
        }
        .btn-send:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }
        .back-link {
            text-align: center;
            font-size: 0.85rem;
            color: #C1AE7A;
            border-top: 1px solid rgba(212, 175, 55, 0.25);
            padding-top: 1.2rem;
            margin-top: 0.5rem;
        }
        .back-link a {
            color: #E9CD8A;
            text-decoration: none;
            font-weight: 600;
            margin-left: 6px;
        }
        .back-link a:hover {
            text-decoration: underline;
        }
        @media (max-width: 550px) {
            .forgot-card { padding: 1.5rem; }
            .brand-name { font-size: 1.8rem; }
        }
    </style>
</head>
<body>
<div class="forgot-card">
    <div class="brand-header">
        <div class="brand-name">HIGH CUSTOM JEWELLERS</div>
        <div class="brand-symbol">✦ ✧ ✦</div>
    </div>
    <h2>Forgot Password</h2>
    <div class="sub-text">Enter your email address and we'll send you a password reset link.</div>

    <form id="forgotForm">
        @csrf
        <div class="form-group">
            <label><i class="fas fa-envelope"></i> Email Address</label>
            <input type="email" class="input-field" id="email" name="email" placeholder="Email">
            <div class="error-text" id="email_error"></div>
        </div>

        <button type="submit" class="btn-send" id="submitBtn">
            <i class="fas fa-paper-plane"></i> Send Reset Link
        </button>

        <div class="back-link">
            <i class="fas fa-arrow-left"></i> <a href="{{ route('login-data') }}">Back to Login</a>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        // Clear previous errors on input
        $('#email').on('input', function() {
            $(this).removeClass('has-error');
            $('#email_error').text('');
        });

        $('#forgotForm').submit(function(e) {
            e.preventDefault();

            let email = $('#email').val().trim();
            let isValid = true;
            $('#email_error').text('');
            $('#email').removeClass('has-error');

            if (!email) {
                $('#email_error').text('Email address is required.');
                $('#email').addClass('has-error');
                isValid = false;
            } else if (!/^[^\s@]+@([^\s@]+\.)+[^\s@]+$/.test(email)) {
                $('#email_error').text('Enter a valid email address.');
                $('#email').addClass('has-error');
                isValid = false;
            }

            if (!isValid) {
                toastr.error('Please fix the form errors.', 'Validation Error');
                return;
            }

            let btn = $('#submitBtn');
            let originalText = btn.html();
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-pulse"></i> Sending...');

            $.ajax({
                url: "{{ route('password.email') }}", // Laravel's built-in password reset route
                type: "POST",
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {
                    toastr.success(res.message || 'Password reset link sent to your email.', 'Success');
                    // Store message for next page (optional)
                    localStorage.setItem('success_msg', res.message);
                    // Redirect to login after 1.5 seconds
                    setTimeout(function() {
                        window.location.href = "{{ route('login-data') }}";
                    }, 1500);
                },
                error: function(xhr) {
                    if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                        let errors = xhr.responseJSON.errors;
                        if (errors.email) {
                            $('#email_error').text(errors.email[0]);
                            $('#email').addClass('has-error');
                        }
                        toastr.error('Please correct the highlighted field.', 'Error');
                    } else if (xhr.status === 404 || (xhr.responseJSON && xhr.responseJSON.message === 'Email not found')) {
                        $('#email_error').text('We could not find a user with that email address.');
                        $('#email').addClass('has-error');
                        toastr.error('Email not found.', 'Error');
                    } else {
                        let errorMsg = xhr.responseJSON?.message || 'Something went wrong. Please try again.';
                        toastr.error(errorMsg, 'Error');
                    }
                },
                complete: function() {
                    btn.prop('disabled', false).html(originalText);
                }
            });
        });
    });
</script>
</body>
</html>
