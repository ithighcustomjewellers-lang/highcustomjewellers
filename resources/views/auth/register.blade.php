<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>High Custom Jewellers | Staff Registration Portal</title>
    <!-- Google Fonts + Font Awesome 6 -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- jQuery (required for Toastr & AJAX) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- intl-tel-input v17 (stable, no ES module export errors) + utils -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/css/intlTelInput.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js"></script>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/css/intlTelInput.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            background: radial-gradient(ellipse at 30% 40%, #0C0A0F 0%, #020202 100%);
            background-attachment: fixed;
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

        .registration-card {
            max-width: 800px;
            width: 100%;
            background: rgba(10, 8, 15, 0.78);
            backdrop-filter: blur(12px);
            border-radius: 2rem;
            border: 1px solid rgba(212, 175, 55, 0.3);
            box-shadow: 0 30px 50px rgba(0, 0, 0, 0.6), 0 0 0 1px rgba(212, 175, 55, 0.1) inset;
            padding: 2rem 2rem 2.2rem;
            transition: all 0.3s ease;
            position: relative;
            z-index: 2;
        }

        .registration-card:hover {
            border-color: rgba(212, 175, 55, 0.6);
            box-shadow: 0 35px 55px rgba(0, 0, 0, 0.7), 0 0 0 1px rgba(212, 175, 55, 0.3) inset;
        }

        .brand-header {
            text-align: center;
            margin-bottom: 1.2rem;
        }
        .brand-name {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2.6rem;
            font-weight: 600;
            letter-spacing: 2px;
            background: linear-gradient(135deg, #F9E0A0 0%, #D4AF37 45%, #B88A1A 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        .brand-symbol {
            display: inline-block;
            font-size: 0.9rem;
            letter-spacing: 6px;
            color: #D4AF37;
            margin-top: 0;
            opacity: 0.7;
        }

        h2 {
            font-family: 'Cormorant Garamond', serif;
            font-weight: 500;
            font-size: 1.9rem;
            text-align: center;
            color: #F5E7D3;
            letter-spacing: 1px;
            margin-top: 0.1rem;
            margin-bottom: 0.5rem;
        }

        .admin-badge {
            text-align: center;
            margin-bottom: 1.8rem;
        }
        .access-banner {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            padding: 0.55rem 1.4rem;
            border-radius: 60px;
            border: 1px solid rgba(212, 175, 55, 0.4);
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #E9D6A7;
        }
        .access-banner i {
            font-size: 0.8rem;
            color: #D4AF37;
        }
        .access-banner span {
            font-weight: 500;
            background: rgba(212, 175, 55, 0.2);
            padding: 0.2rem 0.5rem;
            border-radius: 20px;
            margin: 0 0.2rem;
        }

        .form-grid {
            margin-top: 1rem;
        }

        .name-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
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
            font-size: 0.7rem;
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
            font-family: 'Inter', monospace;
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

        /* intl-tel-input styling */
        .iti {
            width: 100%;
            display: block;
        }
        .iti__flag-container {
            border-radius: 16px 0 0 16px;
        }
        .iti--allow-dropdown .iti__flag-container:hover .iti__selected-flag {
            background-color: rgba(212, 175, 55, 0.15);
        }
        .iti__selected-flag {
            background: rgba(0, 0, 0, 0.5);
            border-radius: 16px 0 0 16px;
            padding: 0 8px 0 12px;
        }
        .iti__selected-dial-code {
            color: #EBD698;
            font-weight: 500;
            margin-left: 6px;
        }
        .iti__country-list {
            background: #1A1722;
            border-color: #D4AF37;
            border-radius: 16px;
        }
        .iti__country {
            color: #F5E7D3;
        }
        .iti__country.iti__highlight {
            background-color: rgba(212, 175, 55, 0.3);
        }
        .iti__country-name, .iti__dial-code {
            color: #E9D6A7;
        }
        .iti__tel-input {
            background: rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(212, 175, 55, 0.4);
            border-radius: 16px;
            padding: 0.85rem 1rem 0.85rem 58px;
            font-size: 0.9rem;
            font-weight: 500;
            color: #FBF5E8;
            width: 100%;
            transition: all 0.2s ease;
            font-family: 'Inter', monospace;
            outline: none;
        }
        .iti__tel-input:focus {
            border-color: #D4AF37;
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.2);
            background: rgba(0, 0, 0, 0.7);
        }

        .error-text {
            color: #F5A97F;
            font-size: 0.7rem;
            margin-top: 0.35rem;
            display: block;
        }
        .has-error {
            border-color: #E08E6D !important;
        }

        .btn-create {
            width: 100%;
            background: linear-gradient(105deg, #B3862D 0%, #D4AF37 55%, #F5DEB3 100%);
            border: none;
            padding: 1rem;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1rem;
            letter-spacing: 2px;
            color: #0E0C13;
            margin: 1rem 0 1.2rem;
            cursor: pointer;
            transition: 0.25s;
            font-family: 'Inter', sans-serif;
            text-transform: uppercase;
            box-shadow: 0 6px 14px rgba(0,0,0,0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }
        .btn-create i {
            font-size: 1rem;
            transition: transform 0.2s;
        }
        .btn-create:hover {
            background: linear-gradient(105deg, #D4AF37, #F3D572);
            transform: translateY(-2px);
            box-shadow: 0 12px 22px rgba(0,0,0,0.4);
            color: #000000;
        }
        .btn-create:hover i {
            transform: translateX(4px);
        }

        .signin-link {
            text-align: center;
            font-size: 0.85rem;
            color: #C1AE7A;
            border-top: 1px solid rgba(212, 175, 55, 0.25);
            padding-top: 1.2rem;
            margin-top: 0.2rem;
        }
        .signin-link a {
            color: #E9CD8A;
            text-decoration: none;
            font-weight: 600;
            transition: 0.2s;
            margin-left: 6px;
        }
        .signin-link a:hover {
            color: #fff3cf;
            text-decoration: underline;
        }

        @media (max-width: 550px) {
            .registration-card {
                padding: 1.5rem;
            }
            .name-row {
                grid-template-columns: 1fr;
                gap: 0;
            }
            .brand-name {
                font-size: 2rem;
            }
            h2 {
                font-size: 1.5rem;
            }
            .access-banner {
                font-size: 0.6rem;
                padding: 0.4rem 1rem;
                flex-wrap: wrap;
                justify-content: center;
            }
            .iti__tel-input {
                padding-left: 52px;
            }
        }

        ::placeholder {
            color: #8b7c5c;
            font-weight: 400;
            font-size: 0.85rem;
            opacity: 0.8;
        }

        .registration-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 20% 30%, rgba(212,175,55,0.08), transparent 70%);
            pointer-events: none;
            border-radius: 2rem;
        }
    </style>
</head>
<body>

<div class="registration-card">
    <div class="brand-header">
        <div class="brand-name">HIGH CUSTOM JEWELLERS</div>
        <div class="brand-symbol">✦ ✧ ✦</div>
    </div>

    <form id="staffRegisterForm" class="form-grid" action="#" method="post">
        @csrf <!-- Laravel CSRF protection - kept for blade compatibility -->
        <div class="name-row">
            <div class="form-group">
                <label><i class="fas fa-user-pen"></i> First Name</label>
                <input type="text" class="input-field" id="first_name" name="first_name" placeholder=" First Name">
                <div class="error-text" id="first_name_error"></div>
            </div>
            <div class="form-group">
                <label><i class="fas fa-user-pen"></i> Last Name</label>
                <input type="text" class="input-field" id="last_name" name="last_name" placeholder="Last Name">
                <div class="error-text" id="last_name_error"></div>
            </div>
        </div>

        <div class="form-group">
            <label><i class="fas fa-user"></i> Employer Code</label>
            <input type="text" class="input-field" id="employee_code" name="employee_code" placeholder="Employer Code">
            <div class="error-text" id="employee_code_error"></div>
        </div>

        <div class="form-group">
            <label><i class="fas fa-envelope"></i> Email Address</label>
            <input type="email" class="input-field" id="email" name="email" placeholder="Email Address">
            <div class="error-text" id="email_error"></div>
        </div>

        <div class="form-group">
            <label><i class="fas fa-globe-asia"></i> Phone Number</label>
            <input type="tel" id="phone" name="phone" class="iti__tel-input" placeholder="98765 43210">
            <div class="error-text" id="phone_error"></div>
        </div>

        <div class="form-group">
            <label><i class="fas fa-lock"></i> Password</label>
            <div class="password-wrapper">
                <input type="password" class="input-field" id="password" name="password" placeholder="••••••••">
                <i class="fas fa-eye-slash toggle-password" data-target="password"></i>
            </div>
            <div class="error-text" id="password_error"></div>
        </div>

        <div class="form-group">
            <label><i class="fas fa-check-circle"></i> Confirm Password</label>
            <div class="password-wrapper">
                <input type="password" class="input-field" id="password_confirmation" name="password_confirmation" placeholder="confirm password">
                <i class="fas fa-eye-slash toggle-password" data-target="password_confirmation"></i>
            </div>
            <div class="error-text" id="password_confirmation_error"></div>
        </div>

        <button type="submit" class="btn-create"><i class="fas fa-gem"></i> CREATE ACCOUNT <i class="fas fa-arrow-right"></i></button>

        <div class="signin-link">
            Already have access? <a href="{{ route('login-data') }}" id="returnSignInLink"><i class="fas fa-sign-in-alt"></i> Return to Sign In</a>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        // 1. Initialize intl-tel-input (stable version, no export errors)
        var iti = window.intlTelInput(document.querySelector("#phone"), {
            initialCountry: "in",
            separateDialCode: true,
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js",
            preferredCountries: ['in', 'us', 'gb', 'ae', 'sg', 'ca', 'au']
        });

        // 2. Toggle password visibility
        $('.toggle-password').on('click', function() {
            var targetId = $(this).data('target');
            var input = $('#' + targetId);
            var type = input.attr('type') === 'password' ? 'text' : 'password';
            input.attr('type', type);
            $(this).toggleClass('fa-eye-slash fa-eye');
        });

        // 3. Helper: Clear previous errors
        function clearErrors() {
            $('.error-text').text('');
            $('.input-field, #phone').removeClass('has-error');
        }

        // 4. Client-side validation (optional, but double-checks before AJAX)
        function clientValidate() {
            let isValid = true;
            clearErrors();
            let firstName = $('#first_name').val().trim();
            let lastName = $('#last_name').val().trim();
            let email = $('#email').val().trim();
            let phoneRaw = $('#phone').val().trim();
            let password = $('#password').val();
            let confirmPwd = $('#password_confirmation').val();

            if (!firstName) {
                $('#first_name_error').text('First name is required.');
                $('#first_name').addClass('has-error');
                isValid = false;
            }
            if (!lastName) {
                $('#last_name_error').text('Last name is required.');
                $('#last_name').addClass('has-error');
                isValid = false;
            }
             if (!employee_code) {
                $('#employee_code_error').text('Employee code is required.');
                $('#employee_code').addClass('has-error');
                isValid = false;
            }
            if (!email) {
                $('#email_error').text('Email address is required.');
                $('#email').addClass('has-error');
                isValid = false;
            } else if (!/^[^\s@]+@([^\s@]+\.)+[^\s@]+$/.test(email)) {
                $('#email_error').text('Enter a valid email address.');
                $('#email').addClass('has-error');
                isValid = false;
            }
            if (!phoneRaw) {
                $('#phone_error').text('Phone number is required.');
                $('#phone').addClass('has-error');
                isValid = false;
            } else if (!iti.isValidNumber()) {
                $('#phone_error').text('Please enter a valid phone number with correct country code.');
                $('#phone').addClass('has-error');
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
            } else if (!/[A-Za-z]/.test(password) || !/\d/.test(password)) {
                $('#password_error').text('Password must contain both letters and numbers.');
                $('#password').addClass('has-error');
                isValid = false;
            }
            if (password !== confirmPwd) {
                $('#password_confirmation_error').text('Passwords do not match.');
                $('#password_confirmation').addClass('has-error');
                isValid = false;
            }
            return isValid;
        }

        // 5. Form submission via AJAX (Laravel backend)
        $('#staffRegisterForm').on('submit', function(e) {
            e.preventDefault();

            // First, clear old errors and perform client validation
            if (!clientValidate()) {
                toastr.error('Please fix the form errors.', 'Validation Error');
                return;
            }

            // ✅ Update phone input value to full international number (E.164)
            // This ensures backend receives the complete number with country code
            let fullPhoneNumber = iti.getNumber(); // e.g., +919876543210
            $('#phone').val(fullPhoneNumber);

            let formData = new FormData(this);

            $.ajax({
                url: '{{ route("submit_register") }}',  // Laravel route (replace accordingly)
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}'
                },
                success: function(response) {
                    toastr.success(response.message || 'Registration successful!', 'Success');
                    $('#staffRegisterForm')[0].reset();
                    // Reset intl-tel-input to default country
                    iti.setCountry('in');
                    iti.setNumber('');
                    $('.error-text').text('');
                    $('.input-field, #phone').removeClass('has-error');
                    // Redirect to login after 1 second
                    setTimeout(function() {
                        window.location.href = "{{ route('login-data') }}";
                    }, 1000);
                },
                error: function(xhr) {
                    if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                        let errors = xhr.responseJSON.errors;
                        $('.error-text').text('');
                        $('.input-field, #phone').removeClass('has-error');
                        $.each(errors, function(key, messages) {
                            let inputField = $('#' + key);
                            let errorDiv = $('#' + key + '_error');
                            if (inputField.length && errorDiv.length) {
                                errorDiv.text(messages[0]);
                                inputField.addClass('has-error');
                            } else if (key === 'phone' && messages[0]) {
                                $('#phone_error').text(messages[0]);
                                $('#phone').addClass('has-error');
                            }
                        });
                        toastr.error('Please correct the highlighted fields.', 'Validation Error');
                    } else {
                        toastr.error(xhr.responseJSON?.message || 'Something went wrong. Please try again.', 'Error');
                    }
                }
            });
        });

        // 6. Return to Sign In (demo)
        // $('#returnSignInLink').on('click', function(e) {
        //     e.preventDefault();
        //     toastr.info('Redirecting to Sign In page (simulated).', 'Info');
        //     // window.location.href = "{{ route('login-data') }}";
        // });

        // 7. Real-time field error clearance
        $('#first_name, #last_name, #email, #password, #password_confirmation').on('input', function() {
            $(this).removeClass('has-error');
            $('#' + $(this).attr('id') + '_error').text('');
        });
        $('#phone').on('input countrychange', function() {
            $(this).removeClass('has-error');
            $('#phone_error').text('');
        });
    });
</script>
</body>
</html>
