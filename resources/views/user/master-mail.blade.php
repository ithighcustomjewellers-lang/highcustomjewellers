@extends('user.dashboard')

@section('content')
    <div class="container mt-4">
        <div class="row">
            <!-- Email Designer Form -->
            <div class="col-md-6">
                <div class="card p-4">
                    <h4>Add Sequence - Custom Email Designer</h4>

                    <form id="emailForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="number" name="step" id="step" class="form-control mb-2"
                            placeholder="Step (1,2,3)">
                        <input type="text" name="subject" class="form-control mb-2" id="subject" placeholder="Subject">

                        <!-- Business Links & Logo Section -->
                        <div class="card mb-3 p-3" id="businessLinksSection">
                            <div class="row align-items-end">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Company Logo / Banner</label>
                                    <input type="file" name="company_logo" id="companyLogoInput" class="form-control"
                                        accept="image/*">
                                    <!-- Hidden fields -->
                                    <input type="hidden" name="existing_company_logo" id="existingCompanyLogo"
                                        value="">
                                    <input type="hidden" name="image_type" id="imageType" value="logo">
                                </div>

                                <div class="col-md-6">
                                    <div id="companyLogoPreview" class="mb-2" style="display:none;">
                                        <img id="companyLogoPreviewImg"
                                            style="max-width: 100px; max-height: 50px; border-radius: 5px;">
                                    </div>
                                    <small>Logo Position:</small>
                                    <select name="logo_position" id="logoPosition" class="form-control form-control-sm">
                                        <option value="left">Left</option>
                                        <option value="center" selected>Center</option>
                                        <option value="right">Right</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Toolbar -->
                        <div class="toolbar mb-2">
                            <button type="button" onclick="formatText('bold')"
                                class="btn btn-sm btn-outline-secondary"><b>B</b></button>
                            <button type="button" onclick="formatText('italic')"
                                class="btn btn-sm btn-outline-secondary"><i>I</i></button>
                            <button type="button" onclick="formatText('underline')"
                                class="btn btn-sm btn-outline-secondary"><u>U</u></button>
                            <select id="fontFamilySelect" onchange="formatText('fontName', this.value)"
                                class="form-control-sm">
                                <option value="Arial">Arial</option>
                                <option value="Verdana">Verdana</option>
                                <option value="Georgia">Georgia</option>
                                <option value="Times New Roman">Times New Roman</option>
                                <option value="Courier New">Courier New</option>
                            </select>
                            <select id="fontSizeSelect" onchange="changeFontSize(this.value)" class="form-control-sm">
                                <option value="10px">10px</option>
                                <option value="12px">12px</option>
                                <option value="14px">14px</option>
                                <option value="16px" selected>16px</option>
                                <option value="18px">18px</option>
                                <option value="20px">20px</option>
                                <option value="24px">24px</option>
                                <option value="28px">28px</option>
                                <option value="32px">32px</option>
                                <option value="34px">34px</option>
                                <option value="36px">36px</option>
                            </select>
                            <select onchange="formatText('foreColor', this.value)" class="form-control-sm">
                                <option value="#000000">Black</option>
                                <option value="#FF0000">Red</option>
                                <option value="#00FF00">Green</option>
                                <option value="#0000FF">Blue</option>
                                <option value="#FFA500">Orange</option>
                                <option value="#800080">Purple</option>
                            </select>
                            <button type="button" onclick="formatText('insertUnorderedList')"
                                class="btn btn-sm btn-outline-secondary">• List</button>
                            <button type="button" onclick="formatText('insertOrderedList')"
                                class="btn btn-sm btn-outline-secondary">1. List</button>
                            <button type="button" onclick="formatText('justifyLeft')"
                                class="btn btn-sm btn-outline-secondary">⍇</button>
                            <button type="button" onclick="formatText('justifyCenter')"
                                class="btn btn-sm btn-outline-secondary">⍌</button>
                            <button type="button" onclick="formatText('justifyRight')"
                                class="btn btn-sm btn-outline-secondary">⍄</button>
                            <button type="button" onclick="addHorizontalLine()"
                                class="btn btn-sm btn-outline-secondary">─</button>
                        </div>

                        <!-- Email Content Editor -->
                        <div id="emailEditor" class="form-control mb-2" contenteditable="true"
                            style="min-height: 300px; border: 1px solid #ddd; padding: 10px; overflow-y: auto; font-size: 16px; font-family: Arial;">
                            <p>Start typing your email here...</p>
                        </div>

                        <textarea name="message" id="message" style="display:none;"></textarea>
                        <input type="number" name="gap_days" id="gapDays" class="form-control mb-2" placeholder="Gap Days" min="0" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        <input type="text" name="variant" id="variant" class="form-control mb-2" placeholder="Variant (A/B/C)" maxlength="1" style="text-transform: uppercase;">
                        <select name="type" class="form-control mb-2">
                            <option value="B2B">B2B</option>
                            <option value="B2C">B2C</option>
                        </select>

                        <!-- Hero Image Upload -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Hero Image (Will show at TOP of email)</label>
                            <input type="file" name="hero_image" class="form-control" id="heroImage"
                                accept="image/*" onchange="previewHeroImage(this)">
                            <small class="text-muted">Recommended size: 600x300px. Max 2MB</small>
                            <div id="heroImagePreview" class="mt-2" style="display:none;">
                                <img id="heroImagePreviewImg" src=""
                                    style="max-width: 100%; max-height: 150px; border-radius: 5px;">
                                <button type="button" class="btn btn-sm btn-danger mt-1"
                                    onclick="removeHeroImage()">Remove Hero Image</button>
                            </div>
                        </div>

                        <!-- Attachments Upload -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Attachment File (Will show as DOWNLOAD LINK in email)</label>
                            <input type="file" name="attachments_image" class="form-control" id="attachmentsImage"
                                accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.zip" onchange="previewAttachment(this)">
                            <small class="text-muted">Max 5MB. Supported: Images, PDF, DOC, XLS, ZIP</small>
                            <div id="attachmentPreview" class="mt-2" style="display:none;">
                                <div class="alert alert-info">
                                    📎 <span id="attachmentName"></span>
                                    <button type="button" class="btn btn-sm btn-danger float-end"
                                        onclick="removeAttachment()">Remove</button>
                                </div>
                            </div>
                        </div>

                        <div class="mt-2">
                            <input type="text" name="whatsapp_link" class="form-control mb-2" id="whatsappLink"
                                placeholder="WhatsApp Link (https://wa.me/...)">
                            <input type="text" name="telegram_link" class="form-control mb-2" id="telegramLink"
                                placeholder="Telegram Link (https://t.me/...)">
                            <input type="text" name="business_link" class="form-control mb-2" id="businessLink"
                                placeholder="Business Link (https://...)">
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-success">Save Sequence</button>
                            <a href="{{ route('admin-sequences-index') }}" class="btn btn-secondary">Back</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Live Email Preview -->
            <div class="col-md-6">
                <div class="card p-4">
                    <h4>Live Email Preview</h4>
                    <div class="mb-3">
                        <button class="btn btn-sm btn-outline-primary active-preview" data-preview="mobile">📱
                            Mobile</button>
                        <button class="btn btn-sm btn-outline-primary" data-preview="desktop">💻 Desktop</button>
                    </div>
                    <div class="preview-container">
                        <div class="email-preview-header">
                            <strong>Subject:</strong> <span id="previewSubject">No subject</span>
                        </div>
                        <hr>
                        <div id="mobilePreview" class="email-preview mobile-preview active"
                            style="border: 1px solid #e0e0e0; padding: 20px; min-height: 400px; background: #fff; max-width: 375px; margin: 0 auto; box-shadow: 0 0 20px rgba(0,0,0,0.1); border-radius: 20px;">
                        </div>
                        <div id="desktopPreview" class="email-preview desktop-preview"
                            style="border: 1px solid #e0e0e0; padding: 20px; min-height: 400px; background: #fff; display: none; max-width: 600px; margin: 0 auto; box-shadow: 0 0 20px rgba(0,0,0,0.1); border-radius: 10px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .email-preview {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }

        .email-preview-header {
            margin-bottom: 10px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 5px;
        }

        .toolbar {
            padding: 8px;
            background: #f8f9fa;
            border-radius: 25px;
            border: 1px solid #ddd;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .toolbar button,
        .toolbar select {
            margin: 0 2px;
        }

        #emailEditor {
            background: white;
            transition: all 0.3s ease;
        }

        #emailEditor:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, .25);
        }

        .active-preview {
            background-color: #007bff !important;
            color: white !important;
        }

        .mobile-preview {
            width: 375px !important;
            max-width: 375px !important;
            margin: 0 auto !important;
        }

        .mobile-preview img {
            max-width: 100% !important;
            height: auto !important;
        }

        .mobile-preview .company-logo {
            max-width: 150px !important;
        }

        .mobile-preview .btn {
            padding: 8px 16px !important;
            font-size: 14px !important;
        }

        .desktop-preview img {
            max-width: 100% !important;
            height: auto !important;
        }

        .desktop-preview .company-logo {
            max-width: 120px !important;
            max-height: 60px !important;
        }

        .logo-left {
            text-align: left !important;
        }

        .logo-center {
            text-align: center !important;
        }

        .logo-right {
            text-align: right !important;
        }
    </style>

    <script>
        let currentEditor = document.getElementById('emailEditor');
        let currentHeroImage = null;
        let currentAttachment = null;
        let currentCompanyLogo = null;
        let currentCompanyLogoFile = null;
        let businessLinksData = null;
        let currentImageType = 'logo';

        window.addEventListener('DOMContentLoaded', function() {
            loadBusinessLinks();
            updatePreview();
        });

        function loadBusinessLinks() {
            fetch('{{ route('user-business-links') }}')
                .then(response => response.json())
                .then(data => {
                    businessLinksData = data;
                    if (data.company_logo) {
                        currentCompanyLogo = data.company_logo;
                        currentImageType = data.image_type || 'logo';
                        document.getElementById('companyLogoPreviewImg').src = data.company_logo;
                        document.getElementById('companyLogoPreview').style.display = 'block';
                        document.getElementById('existingCompanyLogo').value = data.company_logo;
                        document.getElementById('imageType').value = currentImageType;
                        console.log('Loaded existing logo, type:', currentImageType);
                    } else {
                        currentCompanyLogo = null;
                        document.getElementById('companyLogoPreview').style.display = 'none';
                        document.getElementById('existingCompanyLogo').value = '';
                        document.getElementById('imageType').value = 'logo';
                    }
                    document.getElementById('whatsappLink').value = data.whatsapp_link || '';
                    document.getElementById('telegramLink').value = data.telegram_link || '';
                    document.getElementById('businessLink').value = data.business_link || '';
                    document.getElementById('logoPosition').value = data.logo_position || 'center';
                    updatePreview();
                })
                .catch(error => console.error('Error loading business links:', error));
        }

        // File selection with dimension detection
        document.getElementById('companyLogoInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                currentCompanyLogoFile = file;
                const reader = new FileReader();
                reader.onload = function(ev) {
                    const img = new Image();
                    img.onload = function() {
                        const width = img.width;
                        const height = img.height;
                        console.log('Image dimensions:', width, 'x', height);
                        const aspectRatio = width / height;
                        console.log('Aspect ratio:', aspectRatio);
                        const isBanner = (width > 400) || (aspectRatio > 2);
                        console.log('Is banner?', isBanner);
                        currentImageType = isBanner ? 'banner' : 'logo';
                        document.getElementById('imageType').value = currentImageType;
                        currentCompanyLogo = ev.target.result;
                        document.getElementById('companyLogoPreviewImg').src = currentCompanyLogo;
                        document.getElementById('companyLogoPreview').style.display = 'block';
                        document.getElementById('existingCompanyLogo').value = '';
                        updatePreview();
                    };
                    img.onerror = function() {
                        console.error('Failed to load image dimensions – using fallback as logo');
                        currentImageType = 'logo';
                        document.getElementById('imageType').value = 'logo';
                        currentCompanyLogo = ev.target.result;
                        document.getElementById('companyLogoPreviewImg').src = currentCompanyLogo;
                        document.getElementById('companyLogoPreview').style.display = 'block';
                        document.getElementById('existingCompanyLogo').value = '';
                        updatePreview();
                    };
                    img.src = ev.target.result;
                };
                reader.onerror = function() {
                    console.error('FileReader error');
                };
                reader.readAsDataURL(file);
            }
        });


        // Helper functions
        function changeFontSize(size) {
            document.execCommand('fontSize', false, '7');
            const fontElements = document.querySelectorAll('#emailEditor font[size="7"]');
            fontElements.forEach(el => {
                el.removeAttribute('size');
                el.style.fontSize = size;
            });
            updatePreview();
        }

        function formatText(command, value = null) {
            document.execCommand(command, false, value);
            updatePreview();
            currentEditor.focus();
        }

        function addHorizontalLine() {
            document.execCommand('insertHorizontalRule', false, null);
            updatePreview();
        }

        function previewHeroImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    currentHeroImage = {
                        data: e.target.result,
                        file: input.files[0],
                        name: input.files[0].name
                    };
                    document.getElementById('heroImagePreviewImg').src = e.target.result;
                    document.getElementById('heroImagePreview').style.display = 'block';
                    updatePreview();
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function removeHeroImage() {
            currentHeroImage = null;
            document.getElementById('heroImage').value = '';
            document.getElementById('heroImagePreview').style.display = 'none';
            updatePreview();
        }

        function previewAttachment(input) {
            if (input.files && input.files[0]) {
                currentAttachment = {
                    file: input.files[0],
                    name: input.files[0].name,
                    size: (input.files[0].size / 1024).toFixed(2) + ' KB'
                };
                document.getElementById('attachmentName').innerHTML =
                    `${currentAttachment.name} (${currentAttachment.size})`;
                document.getElementById('attachmentPreview').style.display = 'block';
                updatePreview();
            }
        }

        function removeAttachment() {
            currentAttachment = null;
            document.getElementById('attachmentsImage').value = '';
            document.getElementById('attachmentPreview').style.display = 'none';
            updatePreview();
        }

        // Preview toggle
        document.querySelectorAll('[data-preview]').forEach(btn => {
            btn.addEventListener('click', function() {
                const previewType = this.dataset.preview;
                document.querySelectorAll('[data-preview]').forEach(b => b.classList.remove(
                    'active-preview'));
                this.classList.add('active-preview');
                if (previewType === 'mobile') {
                    document.getElementById('mobilePreview').style.display = 'block';
                    document.getElementById('desktopPreview').style.display = 'none';
                } else {
                    document.getElementById('desktopPreview').style.display = 'block';
                    document.getElementById('mobilePreview').style.display = 'none';
                }
                updatePreview();
            });
        });

        // Update preview
        function updatePreview() {
            const subject = document.getElementById('subject').value;
            let content = currentEditor.innerHTML;
            const logoPosition = document.getElementById('logoPosition').value;
            const whatsapp = document.getElementById('whatsappLink').value;
            const telegram = document.getElementById('telegramLink').value;
            const business = document.getElementById('businessLink').value;

            function generatePreviewHtml() {
                let previewHtml = '';
                if (currentCompanyLogo) {
                    if (currentImageType === 'banner') {
                        previewHtml +=
                            `<img src="${currentCompanyLogo}" alt="Banner" style="width: 100%; border-radius: 8px; margin-bottom: 15px;">`;
                    } else {
                        const logoClass = `logo-${logoPosition}`;
                        previewHtml += `<div class="${logoClass}" style="margin-bottom: 15px;">
                        <img src="${currentCompanyLogo}" alt="Company Logo" class="company-logo" style="border-radius: 5px; max-width: 150px;">
                    </div>`;
                    }
                }
                if (currentHeroImage) {
                    previewHtml +=
                        `<img src="${currentHeroImage.data}" alt="Hero Image" style="max-width: 100%; border-radius: 8px; margin-bottom: 20px;"><br>`;
                }
                previewHtml += content;
                if (currentAttachment) {
                    previewHtml += `<div style="margin-top: 20px; padding: 10px; background: #f0f0f0; border-radius: 5px; border-left: 3px solid #007bff;">
                        📎 <strong>Attachment:</strong> ${currentAttachment.name} (${currentAttachment.size})<br>
                        <small><a href="#" style="color: #007bff;">📥 Download Attachment</a></small>
                        </div>`;
                }
                let buttonsHtml = '';
                if (whatsapp || telegram || business) {
                    buttonsHtml =
                        '<div style="margin-top: 20px; padding: 15px; text-align: center; background: #f8f9fa; border-radius: 8px;">';
                    if (whatsapp) buttonsHtml +=
                        `<a href="${whatsapp}" class="btn btn-success" style="background-color:#25D366; color:white; padding:10px 20px; margin:5px; text-decoration:none; border-radius:5px; display:inline-block;" target="_blank">📱 WhatsApp</a>`;
                    if (telegram) buttonsHtml +=
                        `<a href="${telegram}" class="btn btn-info" style="background-color:#0088cc; color:white; padding:10px 20px; margin:5px; text-decoration:none; border-radius:5px; display:inline-block;" target="_blank">📨 Telegram</a>`;
                    if (business) buttonsHtml +=
                        `<a href="${business}" class="btn btn-primary" style="background-color:#007bff; color:white; padding:10px 20px; margin:5px; text-decoration:none; border-radius:5px; display:inline-block;" target="_blank">💼 Business</a>`;
                    buttonsHtml += '</div>';
                }
                previewHtml += buttonsHtml;
                return previewHtml;
            }

            const previewHtml = generatePreviewHtml();
            document.getElementById('mobilePreview').innerHTML = previewHtml;
            document.getElementById('desktopPreview').innerHTML = previewHtml;
            document.getElementById('previewSubject').textContent = subject || 'No subject';
            document.getElementById('message').value = currentEditor.innerHTML;
        }

        // Live preview event listeners
        document.getElementById('subject').addEventListener('input', updatePreview);
        document.getElementById('whatsappLink').addEventListener('input', updatePreview);
        document.getElementById('telegramLink').addEventListener('input', updatePreview);
        document.getElementById('businessLink').addEventListener('input', updatePreview);
        document.getElementById('logoPosition').addEventListener('change', updatePreview);
        currentEditor.addEventListener('input', updatePreview);
        currentEditor.addEventListener('keyup', updatePreview);
        currentEditor.addEventListener('paste', function(e) {
            e.preventDefault();
            const text = (e.originalEvent || e).clipboardData.getData('text/plain');
            document.execCommand('insertText', false, text);
            updatePreview();
        });
    </script>

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script>

        $('#variant').on('input', function () {
            let value = $(this).val();

            // only A-Z allow
            value = value.replace(/[^A-Za-z]/g, '');

            // uppercase
            value = value.toUpperCase();

            // only single character
            value = value.substring(0, 1);

            $(this).val(value);
        });

        $(document).ready(function() {
            $('#emailForm').submit(function(e) {
                e.preventDefault();

                let editorContent = $('#emailEditor').html().trim();
                $('#message').val(editorContent);

                if ($('#step').val() == '') {
                    toastr.error('Step is required');
                    return;
                }
                if ($('#subject').val() == '') {
                    toastr.error('Subject is required');
                    return;
                }
                if (editorContent === '' || editorContent === '<p><br></p>') {
                    toastr.error('Message is required');
                    return;
                }
                if ($('input[name="gap_days"]').val() == '') {
                    toastr.error('Gap days is required');
                    return;
                }
                if ($('input[name="variant"]').val() == '') {
                    toastr.error('variant is required');
                    return;
                }

                var formData = new FormData(this);

                $.ajax({
                    url: '{{ route('user-sequences-store') }}',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        toastr.success(response.message);
                        $('#emailForm')[0].reset();
                        $('#emailEditor').html('<p>Start typing your email here...</p>');
                        currentHeroImage = null;
                        currentAttachment = null;
                        $('#heroImage').val('');
                        $('#heroImagePreview').hide();
                        $('#attachmentsImage').val('');
                        $('#attachmentPreview').hide();
                        currentCompanyLogo = null;
                        currentCompanyLogoFile = null;
                        $('#companyLogoInput').val('');
                        loadBusinessLinks();
                        updatePreview();
                        $('#step').focus();
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            if (typeof errors === 'string') toastr.error(errors);
                            else if (typeof errors === 'object') $.each(errors, function(key,
                                value) {
                                toastr.error(value[0]);
                            });
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            toastr.error(xhr.responseJSON.message);
                        } else {
                            toastr.error('Something went wrong ❌');
                        }
                    }
                });
            });
        });
    </script>
@endsection
