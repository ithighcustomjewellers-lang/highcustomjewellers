<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>High Custom Jewellers | Update Profile</title>
    <!-- Google Fonts + Font Awesome -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Cormorant+Garamond:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- intl-tel-input CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/css/intlTelInput.css">
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- Cropper.js CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
    <!-- jQuery & other libs -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <!-- intl-tel-input JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js"></script>
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- Cropper.js JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
    <style>
        /* (previous styles remain the same) */
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

        .profile-card {
            max-width: 600px;
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

        .profile-card:hover {
            border-color: rgba(212, 175, 55, 0.7);
        }

        .brand-header {
            text-align: center;
            margin-bottom: 1rem;
        }
        .brand-name {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2.2rem;
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
            margin-bottom: 0.5rem;
        }

        .sub-text {
            text-align: center;
            color: #C1AE7A;
            font-size: 0.85rem;
            margin-bottom: 1.5rem;
        }

        .current-image {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .current-img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #D4AF37;
            box-shadow: 0 0 10px rgba(212, 175, 55, 0.4);
            background: #1A1722;
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
        .input-field, .file-input {
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
        .file-input {
            padding: 0.7rem 1rem;
            cursor: pointer;
        }
        .file-input::-webkit-file-upload-button {
            background: #D4AF37;
            border: none;
            border-radius: 12px;
            padding: 0.4rem 1rem;
            margin-right: 1rem;
            cursor: pointer;
            font-weight: 600;
        }
        .input-field:focus, .file-input:focus {
            border-color: #D4AF37;
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.2);
            background: rgba(0, 0, 0, 0.7);
        }
        .iti {
            width: 100%;
        }
        .iti__selected-flag {
            background: rgba(0, 0, 0, 0.5);
            border-radius: 16px 0 0 16px;
            padding: 0 8px 0 12px;
        }
        .iti__selected-dial-code {
            color: #EBD698;
        }
        .iti__country-list {
            background: #1A1722;
            border-color: #D4AF37;
        }
        .iti__country {
            color: #F5E7D3;
        }
        .iti__country.iti__highlight {
            background-color: rgba(212, 175, 55, 0.3);
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
        .btn-update {
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
        .btn-update:hover {
            background: linear-gradient(105deg, #D4AF37, #F3D572);
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(0,0,0,0.4);
        }
        .btn-update:disabled {
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
        }
        .back-link a {
            color: #E9CD8A;
            text-decoration: none;
            font-weight: 600;
        }
        .back-link a:hover {
            text-decoration: underline;
        }
        /* Cropper modal styles */
        .cropper-modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.85);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }
        .cropper-container-box {
            background: #1e1b24;
            border-radius: 24px;
            padding: 20px;
            max-width: 90vw;
            max-height: 90vh;
            width: 600px;
            box-shadow: 0 0 30px #D4AF37;
        }
        .cropper-image-container {
            max-height: 60vh;
            overflow: hidden;
            border-radius: 12px;
        }
        .cropper-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 20px;
        }
        .cropper-btn {
            background: #D4AF37;
            border: none;
            padding: 8px 20px;
            border-radius: 40px;
            font-weight: bold;
            cursor: pointer;
        }
        .cropper-btn-cancel {
            background: #3a2e2e;
            color: white;
        }
        @media (max-width: 550px) {
            .profile-card { padding: 1.5rem; }
            .brand-name { font-size: 1.8rem; }
            .current-img { width: 80px; height: 80px; }
        }
    </style>
</head>
<body>
<div class="profile-card">
    <div class="brand-header">
        <div class="brand-name">HIGH CUSTOM JEWELLERS</div>
        <div class="brand-symbol">✦ ✧ ✦</div>
    </div>
    <h2>Profile Settings</h2>
    <div class="sub-text">Update your personal information & profile picture</div>

    <form id="userProfile" method="post" enctype="multipart/form-data">
        @csrf
        <div class="current-image">
            <img src="{{ asset($user->user_image ?? 'images/user-icon.jpg') }}" class="current-img" id="profilePreview">
        </div>

        <div class="form-group">
            <label><i class="fas fa-user"></i> First Name</label>
            <input type="text" class="input-field" id="first_name" name="first_name" placeholder="First Name" value="{{ $user->name }}">
            <div class="error-text" id="first_name_error"></div>
        </div>

        <div class="form-group">
            <label><i class="fas fa-user"></i> Last Name</label>
            <input type="text" class="input-field" id="last_name" name="last_name" placeholder="Last Name" value="{{ $user->lastname }}">
            <div class="error-text" id="last_name_error"></div>
        </div>

        <div class="form-group">
            <label><i class="fas fa-envelope"></i> Email Address</label>
            <input type="email" class="input-field" id="email" name="email" readonly placeholder="Email" value="{{ $user->email }}">
            <div class="error-text" id="email_error"></div>
        </div>

        <div class="form-group">
            <label><i class="fas fa-phone-alt"></i> Phone Number</label>
            <input type="tel" id="phone" name="phone" class="input-field" value="{{ $user->mobile ?? '' }}" placeholder="Phone Number">
            <div class="error-text" id="phone_error"></div>
        </div>

        <!-- File input triggers cropper instead of direct preview -->
        <div class="full">
            <label><i class="fas fa-image"></i> Profile Image</label>
            <input type="file" name="user_image" class="file-input" id="profile_image" accept="image/jpeg,image/png,image/jpg,image/webp">
            <div class="error-text" id="image_error"></div>
        </div>

        <button type="submit" class="btn-update"><i class="fas fa-save"></i> Update Profile</button>

        <div class="back-link">
            <a href="{{ route('dashboard') }}"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
        </div>
    </form>
</div>

<!-- Cropper Modal -->
<div id="cropperModal" class="cropper-modal-overlay">
    <div class="cropper-container-box">
        <div class="cropper-image-container">
            <img id="cropperImage" src="" alt="Crop Image">
        </div>
        <div class="cropper-buttons">
            <button class="cropper-btn cropper-btn-cancel" id="cancelCrop">Cancel</button>
            <button class="cropper-btn" id="saveCrop">Crop & Save</button>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // -------- intl-tel-input initialization (unchanged) --------
        var phoneInput = document.querySelector("#phone");
        var iti = window.intlTelInput(phoneInput, {
            initialCountry: "in",
            separateDialCode: true,
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js",
            preferredCountries: ['in', 'us', 'gb', 'ae', 'sg', 'ca', 'au']
        });
        var existingPhone = "{{ Auth::user()->phone }}";
        if (existingPhone) {
            iti.setNumber(existingPhone);
        }

        function clearPhoneError() {
            $('#phone').removeClass('has-error');
            $('#phone_error').text('');
        }
        function validatePhone() {
            if (!iti.isValidNumber()) {
                $('#phone').addClass('has-error');
                $('#phone_error').text('❌ Please enter a valid phone number for the selected country.');
                return false;
            } else {
                clearPhoneError();
                return true;
            }
        }
        phoneInput.addEventListener('blur', validatePhone);
        phoneInput.addEventListener('countrychange', validatePhone);
        phoneInput.addEventListener('input', function() { clearPhoneError(); });

        // Clear other field errors
        $('#first_name, #last_name, #email, #profile_image').on('input change', function() {
            $(this).removeClass('has-error');
            $('#' + $(this).attr('id') + '_error').text('');
        });

        // ------------------- IMAGE CROPPER LOGIC -------------------
        let cropper = null;
        let selectedFile = null;        // store original file object
        let croppedBlob = null;          // store final cropped blob to upload

        // When user selects a file, show modal & initialize cropper
        $('#profile_image').on('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;
            // Validate file type
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
            if (!allowedTypes.includes(file.type)) {
                $('#image_error').text('Only JPEG, PNG, JPG, WEBP images are allowed.');
                $('#profile_image').val(''); // clear input
                return;
            }
            $('#image_error').text(''); // clear previous error
            selectedFile = file;

            const reader = new FileReader();
            reader.onload = function(event) {
                const imgUrl = event.target.result;
                $('#cropperImage').attr('src', imgUrl);
                // show modal
                $('#cropperModal').css('display', 'flex');
                // Initialize cropper after image loads
                if (cropper) cropper.destroy();
                const imageElement = document.getElementById('cropperImage');
                cropper = new Cropper(imageElement, {
                    aspectRatio: 1,          // 1:1 for profile picture
                    viewMode: 1,
                    dragMode: 'move',
                    autoCropArea: 0.9,
                    restore: false,
                    guides: true,
                    center: true,
                    highlight: false,
                    cropBoxMovable: true,
                    cropBoxResizable: true,
                    toggleDragModeOnDblclick: false,
                    background: false,
                });
            };
            reader.readAsDataURL(file);
        });

        // Cancel crop: close modal, clear file input and reset cropper
        $('#cancelCrop').on('click', function() {
            $('#cropperModal').css('display', 'none');
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
            $('#profile_image').val('');  // clear file input
            selectedFile = null;
            croppedBlob = null;
        });

        // Save cropped image
        $('#saveCrop').on('click', function() {
            if (!cropper) return;
            // Get cropped canvas
            const canvas = cropper.getCroppedCanvas({
                width: 300,          // desired output width (adjust as needed)
                height: 300,
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high',
            });
            if (!canvas) {
                toastr.error('Could not crop image. Please try again.', 'Error');
                return;
            }
            // Convert canvas to blob
            canvas.toBlob(function(blob) {
                croppedBlob = blob;
                // Update preview on main form
                const previewUrl = URL.createObjectURL(blob);
                $('#profilePreview').attr('src', previewUrl);
                // close modal
                $('#cropperModal').css('display', 'none');
                if (cropper) {
                    cropper.destroy();
                    cropper = null;
                }
                toastr.success('Image cropped successfully!', 'Success');
            }, 'image/jpeg', 0.9);  // output as JPEG 90% quality
        });

        // Override form submission: replace file input with cropped blob if available
        $('#userProfile').submit(function(e) {
            e.preventDefault();

            // Phone validation first
            if (!validatePhone()) {
                toastr.error('Please correct the phone number: it must be valid for the selected country.', 'Validation Error');
                return;
            }
            // Update phone value
            $('#phone').val(iti.getNumber());

            let formData = new FormData(this);
            // If user cropped an image, replace the uploaded file with cropped blob
            if (croppedBlob) {
                // "user_image" is the field name expected by backend
                formData.set('user_image', croppedBlob, 'cropped_profile.jpg');
            } else if (selectedFile && !croppedBlob) {
                // user selected a file but never cropped? still send original?
                // We'll send original only if no crop happened but file exists (edge case)
                // But our flow forces cropping, so this shouldn't happen. For safety:
                if (selectedFile) {
                    formData.set('user_image', selectedFile);
                }
            }

            let btn = $(this).find('button');
            let originalText = btn.html();
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-pulse"></i> Updating...');

            $.ajax({
                url: "{{ route('submit-profile-update') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(response) {
                    toastr.success(response.message, 'Success');
                    if (response.profile_image_url) {
                        $('#profilePreview').attr('src', response.profile_image_url);
                    }
                    // reset crop state
                    $('#profile_image').val('');
                    selectedFile = null;
                    croppedBlob = null;
                },
                error: function(xhr) {
                    if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                        let errors = xhr.responseJSON.errors;
                        $('.error-text').text('');
                        $('.input-field, .file-input, #phone').removeClass('has-error');
                        $.each(errors, function(key, messages) {
                            let errorDivId = key === 'image' ? 'image_error' : key + '_error';
                            let inputField = (key === 'phone') ? $('#phone') : $('#' + key);
                            let errorDiv = $('#' + errorDivId);
                            if (inputField.length && errorDiv.length) {
                                errorDiv.text(messages[0]);
                                inputField.addClass('has-error');
                            } else {
                                toastr.error(messages[0]);
                            }
                        });
                        toastr.error('Please correct the highlighted fields.', 'Validation Error');
                    } else {
                        toastr.error(xhr.responseJSON?.message || 'Something went wrong.', 'Error');
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
