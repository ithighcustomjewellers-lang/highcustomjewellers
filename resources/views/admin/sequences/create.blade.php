@extends('admin.layouts.layout')

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

                        <!-- Toolbar for Text Formatting -->
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

                        <input type="number" name="gap_days" class="form-control mb-2" placeholder="Gap Days">

                        <input type="text" name="variant" class="form-control mb-2" placeholder="Variant (A/B)">

                        <select name="type" class="form-control mb-2">
                            <option value="B2B">B2B</option>
                            <option value="B2C">B2C</option>
                        </select>

                        <!-- Hero Image Upload -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Hero Image (Will show at TOP of email)</label>
                            <input type="file" name="hero_image" class="form-control" id="heroImage" accept="image/*"
                                onchange="previewHeroImage(this)">
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

                        <!-- Social Links -->
                        <div class="card mb-2 p-2">
                            <h6>Social Media Buttons (Will appear as buttons in email)</h6>
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
                    <h4>Live Email Preview
                        <small class="text-muted">(Click eye icon to view full email)</small>
                    </h4>

                    <div class="preview-container">
                        <div class="email-preview-header">
                            <strong>Subject:</strong> <span id="previewSubject">No subject</span>
                            <button id="togglePreview" class="btn btn-sm btn-info" style="float: right;">
                                👁️ Hide Preview <!-- 👈 text will be updated by JS -->
                            </button>
                        </div>
                        <hr>

                        <!-- Email Preview Area - now visible by default -->
                        <div id="emailPreview" class="email-preview"
                            style="border: 1px solid #e0e0e0; padding: 20px; min-height: 400px; background: #fff;">
                            <!-- Preview content will appear here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Your existing styles remain unchanged */
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

        .modal-preview {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            animation: fadeIn 0.3s;
        }

        .modal-preview-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 800px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            animation: slideDown 0.3s;
        }

        .close-preview {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close-preview:hover {
            color: #000;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideDown {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .email-preview-modal {
            max-height: 70vh;
            overflow-y: auto;
            padding: 20px;
        }
    </style>

    <script>
        let currentEditor = document.getElementById('emailEditor');
        let previewVisible = true; // 👈 changed to true so preview is visible by default
        let currentHeroImage = null;
        let currentAttachment = null;

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

        function updatePreview() {
            const subject = document.getElementById('subject').value;
            let content = currentEditor.innerHTML;
            let previewHtml = '';

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

            const whatsapp = document.getElementById('whatsappLink').value;
            const telegram = document.getElementById('telegramLink').value;
            const business = document.getElementById('businessLink').value;

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
                previewHtml += buttonsHtml;
            }

            document.getElementById('previewSubject').textContent = subject || 'No subject';
            document.getElementById('emailPreview').innerHTML = previewHtml;
            document.getElementById('message').value = currentEditor.innerHTML;
        }

        function toggleInlinePreview() {
            const previewDiv = document.getElementById('emailPreview');
            const toggleBtn = document.getElementById('togglePreview');

            if (previewVisible) {
                previewDiv.style.display = 'none';
                toggleBtn.innerHTML = '👁️ View Email';
                toggleBtn.classList.remove('btn-secondary');
                toggleBtn.classList.add('btn-info');
                previewVisible = false;
            } else {
                updatePreview();
                previewDiv.style.display = 'block';
                toggleBtn.innerHTML = '👁️ Hide Preview';
                toggleBtn.classList.remove('btn-info');
                toggleBtn.classList.add('btn-secondary');
                previewVisible = true;
            }
        }

        // Event listeners
        document.getElementById('togglePreview').addEventListener('click', toggleInlinePreview);
        document.getElementById('subject').addEventListener('input', updatePreview);
        document.getElementById('whatsappLink').addEventListener('input', updatePreview);
        document.getElementById('telegramLink').addEventListener('input', updatePreview);
        document.getElementById('businessLink').addEventListener('input', updatePreview);
        currentEditor.addEventListener('input', updatePreview);
        currentEditor.addEventListener('keyup', updatePreview);
        currentEditor.addEventListener('paste', function(e) {
            e.preventDefault();
            const text = (e.originalEvent || e).clipboardData.getData('text/plain');
            document.execCommand('insertText', false, text);
            updatePreview();
        });

        // Make preview visible and updated on page load
        window.addEventListener('DOMContentLoaded', function() {
            updatePreview();
            // ensure preview is shown and toggle button matches
            const previewDiv = document.getElementById('emailPreview');
            const toggleBtn = document.getElementById('togglePreview');
            previewDiv.style.display = 'block';
            toggleBtn.innerHTML = '👁️ Hide Preview';
            toggleBtn.classList.remove('btn-info');
            toggleBtn.classList.add('btn-secondary');
            previewVisible = true;
        });
    </script>

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script>
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
                    url: '{{ route('admin-sequences-store') }}',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        toastr.success(response.message);

                        // Reset the entire form (including text inputs, file inputs, etc.)
                        $('#emailForm')[0].reset();

                        // Reset editor content
                        $('#emailEditor').html('<p>Start typing your email here...</p>');

                        // Reset hero image preview and variable
                        currentHeroImage = null;
                        $('#heroImage').val('');
                        $('#heroImagePreview').hide();
                        $('#heroImagePreviewImg').attr('src', '');

                        // Reset attachment preview and variable
                        currentAttachment = null;
                        $('#attachmentsImage').val('');
                        $('#attachmentPreview').hide();
                        $('#attachmentName').html('');

                        // Reset social links (already done by reset(), but we also trigger update)
                        // Also ensure any manually added JS variables are cleared
                        // Update preview to reflect the cleared state
                        updatePreview();

                        // Optionally focus on first field
                        $('#step').focus();
                    },
                    // error: function(xhr) {
                    //     toastr.error('errors');

                    //     if (xhr.status === 422) {
                    //         let errors = xhr.responseJSON.errors;
                    //         $.each(errors, function(key, value) {
                    //             toastr.error(value[0]);
                    //         });
                    //     } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    //         toastr.error(xhr.responseJSON.message);
                    //     } else {
                    //         toastr.error('Something went wrong ❌');
                    //     }
                    // }

                    error: function(xhr) {

                        if (xhr.status === 422) {

                            let errors = xhr.responseJSON.errors;

                            // ✅ if errors is string
                            if (typeof errors === 'string') {
                                toastr.error(errors);
                            }

                            // ✅ if errors is object (validation)
                            else if (typeof errors === 'object') {
                                $.each(errors, function(key, value) {
                                    toastr.error(value[0]);
                                });
                            }
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
