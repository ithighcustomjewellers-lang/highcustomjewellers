@extends('user.dashboard')
@section('content')
    <style>
        body {
            background: #f4f7fb;
        }

        .business-wrapper {
            max-width: 800px;
            margin: auto;
        }

        .business-card {
            background: #ffffff;
            border-radius: 30px;
            padding: 45px;
            position: relative;
            overflow: hidden;
            box-shadow:
                0 10px 40px rgba(15, 23, 42, .08),
                0 2px 8px rgba(15, 23, 42, .04);
        }

        .business-card::before {
            content: '';
            position: absolute;
            top: -120px;
            right: -120px;
            width: 280px;
            height: 280px;
            background: linear-gradient(135deg, #2563eb, #7c3aed);
            opacity: .08;
            border-radius: 50%;
        }

        .business-card::after {
            content: '';
            position: absolute;
            bottom: -100px;
            left: -100px;
            width: 240px;
            height: 240px;
            background: linear-gradient(135deg, #06b6d4, #2563eb);
            opacity: .08;
            border-radius: 50%;
        }

        .main-title {
            font-size: 32px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 10px;
            letter-spacing: -1px;
        }

        .sub-title {
            color: #64748b;
            font-size: 15px;
            margin-bottom: 35px;
        }

        .upload-box {
            border: 2px dashed #dbe3ef;
            border-radius: 18px;
            padding: 15px;
            background: linear-gradient(to bottom, #f8fbff, #ffffff);
            transition: .3s;
        }

        .upload-box:hover {
            border-color: #2563eb;
            transform: translateY(-2px);
        }

        .preview-image {
            width: 100%;
            height: 130px;
            object-fit: contain;
            border-radius: 22px;
            background: #fff;
            padding: 10px;
            border: 1px solid #edf2f7;
            box-shadow: 0 10px 25px rgba(15, 23, 42, .06);
        }

        .custom-input {
            height: 30px;
            border-radius: 18px;
            border: 1px solid #dbe3ef;
            padding-left: 58px;
            font-size: 15px;
            transition: .25s;
            background: #fff;
        }

        .custom-input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 5px rgba(37, 99, 235, .10);
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 20px;
            z-index: 5;
        }

        .save-btn {
            height: 60px;
            border: none;
            border-radius: 18px;
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            font-size: 17px;
            font-weight: 700;
            letter-spacing: .3px;
            transition: .3s;
            box-shadow: 0 10px 25px rgba(37, 99, 235, .25);
        }

        .save-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 35px rgba(37, 99, 235, .35);
        }

        .upload-label {
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 14px;
        }

        .section-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #eff6ff;
            color: #2563eb;
            padding: 8px 16px;
            border-radius: 40px;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 18px;
        }

        .text-danger {
            font-size: 13px;
            margin-top: 6px;
            display: block;
        }

        @media(max-width:768px) {

            .business-card {
                padding: 25px;
                border-radius: 24px;
            }

            .main-title {
                font-size: 28px;
            }

            .preview-image {
                height: 200px;
            }

        }

        .custom-dropdown {
            position: relative;
            width: 100%;
        }

        .dropdown-header {
            height: 60px;
            border: 1px solid #dbe3ef;
            border-radius: 18px;
            background: #fff;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            font-weight: 600;
        }

        .dropdown-content {
            display: none;
            /* position: absolute; */
            top: 68px;
            left: 0;
            width: 100%;
            max-height: 300px;
            overflow-y: auto;
            background: #fff;
            border-radius: 18px;
            border: 1px solid #dbe3ef;
            box-shadow: 0 15px 35px rgba(0,0,0,.08);
            z-index: 999;
            padding: 10px;
        }

        .dropdown-content.active {
            display: block;
        }

        .dropdown-item-custom {
            display: flex;
            gap: 12px;
            align-items: flex-start;
            padding: 12px;
            border-radius: 12px;
            cursor: pointer;
            transition: .2s;
        }

        .dropdown-item-custom:hover {
            background: #f8fafc;
        }

        .dropdown-item-custom input {
            margin-top: 5px;
        }

        .link-info {
            flex: 1;
        }

        .platform-name {
            font-weight: 700;
            color: #0f172a;
        }

        .platform-url {
            font-size: 12px;
            color: #64748b;
            word-break: break-all;
        }
    </style>


    <div class="business-wrapper">
        <div class="business-card">
            <div class="text-center">
                <div class="section-badge">
                    <i class="fas fa-layer-group"></i>
                    Business Branding
                </div>
                <h1 class="main-title">
                    Business Links
                </h1>
                <p class="sub-title">
                    Upload your company image and manage all business social links professionally.
                </p>
            </div>


            <form id="businessForm" enctype="multipart/form-data">
                @csrf
                <!-- IMAGE -->
                <div class="upload-box mb-4">
                    <label class="upload-label">
                        Company Logo
                    </label>
                    <div class="text-center">
                        {{-- <img src="{{ asset('images/company-logo.jpg') }}" class="preview-image" id="imagePreview"> --}}
                        <img src="{{ isset($business->company_logo) ? asset($business->company_logo) : asset('images/company-logo.jpg') }}"
                            class="preview-image" id="imagePreview">
                    </div>
                    <div class="mt-4">
                        <input type="file" name="company_logo" id="companyLogo" class="form-control custom-input">
                        <small class="text-danger company_logo_error"></small>
                    </div>
                </div>
                @php
                    $user = Auth::user();

                    $message = "Hi {$user->name},\n"
                        . "I received your email and I'm interested in your jewelry collection. "
                        . "I'm reaching out through WhatsApp to learn more about your products and pricing. "
                        . "Could you please share more details?\n"
                        . "Thank you.";

                    $defaultWhatsappUrl = 'https://wa.me/' . preg_replace('/\D/', '', $user->mobile) . '?text=' . urlencode($message);
                @endphp

                <!-- WHATSAPP -->
                <div class="mb-4">
                    <label class="upload-label">
                        WhatsApp Link
                    </label>
                    <div class="input-wrapper">
                        <i class="bi bi-whatsapp input-icon text-success"></i>
                        <input type="text" name="whatsapp_link"  class="form-control custom-input" value="{{ $defaultWhatsappUrl }}" placeholder="https://wa.me/919999999999">
                    </div>
                    <small class="text-danger whatsapp_link_error"></small>
                </div>

                <div class="mb-4">
                    <label class="upload-label">Select Action Links</label>

                    <div class="custom-dropdown">
                        <div class="dropdown-header" id="dropdownHeader">
                            <span id="selectedCount">Select Links</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>

                        <div class="dropdown-content" id="dropdownContent">
                            @foreach($sociallinks as $link)
                                <label class="dropdown-item-custom">
                                    <input type="checkbox"
                                        name="social_link_ids[]"
                                        value="{{ $link->id }}"
                                        class="action-link-checkbox">

                                    <div class="link-info">
                                        <div class="platform-name">
                                            {{ $link->platform_name }}
                                        </div>

                                        <div class="platform-url">
                                            {{ Str::limit($link->platform_url, 50) }}
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- BUTTON -->
                <button type="submit" id="saveBusinessBtn" class="btn btn-primary w-100 save-btn">
                    <i class="fas fa-save me-2"></i>
                    Save Business Details
                </button>
            </form>
        </div>
    </div>
    <script>
        // IMAGE PREVIEW
        $('#companyLogo').change(function() {
            let file = this.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('#imagePreview').attr('src', e.target.result);
                }
                reader.readAsDataURL(file);
            }
        });

        // FORM SUBMIT
        $('#businessForm').submit(function(e) {
            e.preventDefault();
            $('.text-danger').text('');
            let formData = new FormData(this);
            $.ajax({
                url: "{{ route('submit-business-links') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('#saveBusinessBtn').html('<i class="fas fa-spinner fa-spin me-2"></i> Saving...').prop('disabled', true);
                },
                success: function(response) {
                    $('#saveBusinessBtn').html('<i class="fas fa-check me-2"></i> Saved Successfully').prop('disabled', false);
                    toastr.success(response.message);
                    $('#businessForm')[0].reset();
                    location.reload();
                },
                error: function(xhr) {
                    $('#saveBusinessBtn')
                        .html('<i class="fas fa-save me-2"></i> Save Business Details')
                        .prop('disabled', false);
                    if (xhr.status == 422) {
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            $('.' + key + '_error').text(value[0]);
                        });
                    } else {
                        toastr.error('Something went wrong');
                    }
                }
            });
        });

        $(document).ready(function () {
            // Open / Close Dropdown
            $('#dropdownHeader').on('click', function () {
                $('#dropdownContent').toggleClass('active');
            });

            // Close dropdown when click outside
            $(document).on('click', function (e) {
                if (!$(e.target).closest('.custom-dropdown').length) {
                    $('#dropdownContent').removeClass('active');
                }
            });

            // Update selected count
            $(document).on('change', '.action-link-checkbox', function () {
                let count = $('.action-link-checkbox:checked').length;
                if (count > 0) {
                    $('#selectedCount').text(count + ' Link(s) Selected');
                } else {
                    $('#selectedCount').text('Select Links');
                }
            });
        });
    </script>


@endsection
