@extends('user.dashboard')
<!-- Additional Styles -->
<style>
    @import url('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css');

    body {
        background: #f3f6fc;
    }

    .text-gradient {
        background: linear-gradient(135deg, #2b3b4e, #1a4d8c);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    .toolbar button,
    .toolbar select {
        cursor: pointer;
        transition: all 0.1s ease;
    }

    .toolbar button:hover {
        background-color: #e9ecef;
        transform: scale(0.96);
    }

    .email-preview img {
        max-width: 100%;
        height: auto;
        border-radius: 12px;
    }

    .email-preview .company-logo {
        max-width: 140px;
        max-height: 70px;
        object-fit: contain;
    }

    .logo-left {
        text-align: left;
    }

    .logo-center {
        text-align: center;
    }

    .logo-right {
        text-align: right;
    }

    .mobile-preview .btn,
    .desktop-preview .btn {
        display: inline-block;
        padding: 8px 18px;
        margin: 4px;
        border-radius: 40px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
    }

    .active-preview {
        background-color: #0d6efd !important;
        color: white !important;
    }

    #emailEditor {
        transition: box-shadow 0.2s;
        position: relative;
        /* ✅ FIX: placeholder को सही जगह दिखाने के लिए */
    }

    #emailEditor:focus {
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.25);
        outline: none;
    }

    #emailEditor {
        line-height: 1.4;
    }

    #emailEditor p,
    #emailEditor div {
        margin: 0;
    }

    #mobilePreviewContent {
        line-height: 1.4;
    }

    #mobilePreviewContent p,
    #mobilePreviewContent div {
        margin: 0;
    }


    #mobilePreviewContent {
        line-height: 1.4;
    }

    #mobilePreviewContent p,
    #mobilePreviewContent div {
        margin: 0;
    }


    #desktopPreviewContent {
        line-height: 1.4;
    }

    #desktopPreviewContent p,
    #desktopPreviewContent div {
        margin: 0;
    }

    #mobilePreviewContent,
    #desktopPreviewContent {
        height: 500px;
        overflow-y: auto;
        overflow-x: hidden;
        padding: 20px;
    }

    #mobilePreviewContent::-webkit-scrollbar,
    #desktopPreviewContent::-webkit-scrollbar {
        width: 8px;
    }

    #mobilePreviewContent::-webkit-scrollbar-thumb,
    #desktopPreviewContent::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 10px;
    }

    .form-control::placeholder {
        color: #adb5bd !important;
        opacity: 1;
        font-size: 0.95rem;
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        min-width: 130px;
        height: 42px;

        padding: 0 18px;

        border-radius: 50px;
        text-decoration: none;

        font-size: 14px;
        font-weight: 600;

        color: #fff;

        transition: all .25s ease;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        color: #fff;
    }

    .action-btn.whatsapp {
        /* background: #25D366;
         */
        background: linear-gradient(135deg, #2563eb, #4f46e5);
    }

    .action-btn.other {
        /* background: #2563eb; */
        background: linear-gradient(135deg, #2563eb, #4f46e5);
    }

    /* ─── PLACEHOLDER STYLES ─── */
    #emailEditor.placeholder::before {
        content: "Start typing your email here…";
        color: #000000;
        font-size: 16px;
        position: absolute;
        top: 16px;
        left: 16px;
        pointer-events: none;
        user-select: none;
        line-height: 1.4;
    }
</style>
@section('content')
    <div class="container-fluid mt-4 px-4">
        <div class="row g-4">
            <!-- Email Designer Form -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                        <h3 class="h4 mb-0 fw-bold text-gradient">✨ Create New Email Sequence</h3>
                        <p class="text-muted small mt-1">Design engaging emails with our drag & drop editor</p>
                    </div>
                    <div class="card-body p-4">
                        <form id="emailForm" method="POST" enctype="multipart/form-data">
                            @csrf
                            <!-- Basic Info Row -->
                            <div class="row g-4 mb-4">
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold small text-uppercase text-muted">Step</label>
                                    <input type="number" name="step" id="step"
                                        class="form-control form-control-lg rounded-3" min="1" placeholder="e.g., 1">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold small text-uppercase text-muted">Gap Days</label>
                                    <input type="number" name="gap_days" id="gapDays"
                                        class="form-control form-control-lg rounded-3" placeholder="Day" min="0">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold small text-uppercase text-muted">Variant</label>
                                    <input type="text" name="variant" id="variant"
                                        class="form-control form-control-lg rounded-3 text-uppercase" placeholder="A/B/C"
                                        maxlength="1">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold small text-uppercase text-muted">Type</label>
                                    <select name="type" id="typeSelect"
                                        class="form-select form-control rounded-3 form-control-lg">
                                        <option value="B2B">B2B</option>
                                        <option value="B2C">B2C</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold small text-uppercase text-muted">Email Subject</label>
                                <input type="text" name="subject" id="subject"
                                    class="form-control form-control-lg rounded-3"
                                    placeholder="✉️ Write an engaging subject line...">
                            </div>

                            <!-- Branding Section (Logo/Banner) -->
                            <div class="card bg-light border-0 rounded-4 mb-4 overflow-hidden">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <i class="bi bi-building fs-5"></i>
                                        <h5 class="mb-0 fw-bold">Brand Identity</h5>
                                    </div>
                                    <div class="row g-3 align-items-end">
                                        <div class="col-md-7">
                                            <label class="form-label fw-semibold">Company Logo / Banner</label>
                                            <input type="file" name="company_logo" id="companyLogoInput"
                                                class="form-control" accept="image/*">
                                            <input type="hidden" name="existing_company_logo" id="existingCompanyLogo">
                                            <input type="hidden" name="image_type" id="imageType" value="logo">
                                            <small class="text-muted d-block mt-2">
                                                <strong>Recommended Company Logo / Banner</strong><br>
                                                📌 <strong>Banner:</strong> 500 × 200 px or 800 × 300 px<br>
                                                📌 <strong>Logo:</strong> 300 × 300 px or 450 × 120 px
                                            </small>
                                        </div>
                                        <div class="col-md-5">
                                            <div id="companyLogoPreview" class="mt-2" style="display:none;">
                                                <div class="d-flex align-items-center gap-3">
                                                    <img id="companyLogoPreviewImg" class="img-fluid rounded-3 border"
                                                        style="max-height: 30px">
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-danger rounded-pill"
                                                        onclick="removeCompanyLogo()">
                                                        <i class="bi bi-trash"></i> Remove
                                                    </button>
                                                </div>
                                            </div>
                                            <label class="form-label fw-semibold">Logo Position</label>
                                            <select name="logo_position" id="logoPosition" class="form-select">
                                                <option value="left">Left</option>
                                                <option value="center" selected>Center</option>
                                                <option value="right">Right</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Hero Image Upload --}}
                            <div class="mb-4">
                                <label class="form-label fw-semibold small text-uppercase text-muted">Hero Image (Top
                                    Banner)</label>
                                <div class="border rounded-3 p-3 bg-light">
                                    <input type="file" name="hero_image" id="heroImage" class="form-control"
                                        accept="image/*" onchange="previewHeroImage(this)">
                                    <div id="heroImagePreview" class="mt-2" style="display:none;">
                                        <div class="d-flex align-items-center gap-3">
                                            <img id="heroImagePreviewImg" class="rounded-3 border"
                                                style="max-width: 80px; max-height: 80px;">
                                            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill"
                                                onclick="removeHeroImage()">Remove</button>
                                        </div>

                                    </div>
                                    <small class="text-muted">Recommended: 1200 x 400px. Max 2MB</small>
                                    <div class="mt-2">
                                        <label class="form-label fw-semibold small text-muted">Hero Image Link
                                            (Optional)</label>
                                        <input type="url" name="hero_image_link" id="heroImageLink"
                                            class="form-control" placeholder="https://example.com/landing-page"
                                            value="{{ old('hero_image_link', $sequence->hero_image_link ?? '') }}">
                                        <small class="text-muted">If provided, the hero image will become
                                            clickable.</small>
                                    </div>
                                </div>
                            </div>

                            <!-- WYSIWYG Toolbar -->
                            <label class="form-label fw-semibold small text-uppercase text-muted">Email Content</label>
                            <div
                                class="toolbar mb-2 p-2 bg-white rounded-3 border d-flex flex-wrap gap-1 align-items-center">
                                <div class="btn-group btn-group-sm me-1">
                                    <button type="button" onclick="formatText('bold')"
                                        class="btn btn-outline-secondary fw-bold">B</button>
                                    <button type="button" onclick="formatText('italic')"
                                        class="btn btn-outline-secondary fst-italic">I</button>
                                    <button type="button" onclick="formatText('underline')"
                                        class="btn btn-outline-secondary text-decoration-underline">U</button>
                                </div>
                                <select id="fontFamilySelect" onchange="formatText('fontName', this.value)"
                                    class="form-select form-select-sm w-auto">
                                    <option value="Arial">Arial</option>
                                    <option value="Verdana">Verdana</option>
                                    <option value="Georgia">Georgia</option>
                                    <option value="Times New Roman">Times New Roman</option>
                                </select>
                                <select id="fontSizeSelect" onchange="changeFontSize(this.value)"
                                    class="form-select form-select-sm w-auto">
                                    <option value="12px">12px</option>
                                    <option value="14px">14px</option>
                                    <option value="16px" selected>16px</option>
                                    <option value="18px">18px</option>
                                    <option value="24px">24px</option>
                                    <option value="32px">32px</option>
                                </select>
                                <select onchange="formatText('foreColor', this.value)" class="form-control-sm">
                                    <option value="#000000">⚫ Black</option>
                                    <option value="#808080">⚪ Gray</option>
                                    <option value="#FF0000">🔴 Red</option>
                                    <option value="#FFA500">🟠 Orange</option>
                                    <option value="#FFFF00">🟡 Yellow</option>
                                    <option value="#00FF00">🟢 Green</option>
                                    <option value="#008000">🟩 Dark Green</option>
                                    <option value="#00FFFF">🔵 Cyan</option>
                                    <option value="#0000FF">🔷 Blue</option>
                                    <option value="#800080">🟣 Purple</option>
                                    <option value="#FF00FF">🩷 Magenta</option>
                                    <option value="#8B4513">🟤 Brown</option>
                                </select>
                                <button type="button" onclick="addHorizontalLine()"
                                    class="btn btn-outline-secondary">─</button>
                            </div>

                            <div id="emailEditor" class="form-control mb-3" contenteditable="true"
                                style="min-height: 280px; border: 1px solid #dee2e6; border-radius: 12px; padding: 16px; overflow-y: auto; background: white; font-size: 16px;">
                            </div>
                            <textarea name="message" id="message" style="display:none;"></textarea>

                            <!-- Attachment -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold small text-uppercase text-muted">Attachment (Download
                                    Link)</label>
                                <div class="border rounded-3 p-3 bg-light">
                                    <input type="file" name="attachments_image" class="form-control"
                                        id="attachmentsImage" accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.zip"
                                        onchange="previewAttachment(this)">
                                    <div id="attachmentPreview" class="mt-2" style="display:none;">
                                        <div
                                            class="alert alert-info d-flex justify-content-between align-items-center py-2 px-3 mb-0">
                                            <span><i class="bi bi-paperclip me-2"></i><span
                                                    id="attachmentName"></span></span>
                                            <button type="button" class="btn-close"
                                                onclick="removeAttachment()"></button>
                                        </div>
                                    </div>
                                    <small class="text-muted">Max 5MB. PDF, DOC, XLS, ZIP, Images</small>
                                </div>
                            </div>

                            <!-- Social & Business Links -->
                            <div class="card bg-white border rounded-4 mb-4">
                                <div class="card-body p-3">
                                    <label class="form-label fw-semibold small mb-2">🔗 Action Links (CTA Buttons)</label>
                                    <div class="row g-2">

                                        <!-- Social & Business Links -->
                                        <div class="card bg-white border rounded-4 mb-4">
                                            <div class="card-body p-3">
                                                <label class="form-label fw-semibold small mb-2">whatsapp</label>
                                                <div class="row g-2">
                                                    <div class="col-md-4">
                                                        <input type="text" name="whatsapp_link" class="form-control"
                                                            id="whatsappLink" placeholder="WhatsApp URL">
                                                    </div>
                                                    <div class="row g-2 mt-2" id="dynamicActionLinks"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex gap-3 mt-4">
                                <button type="submit" class="btn btn-primary btn-lg px-5 rounded-pill shadow-sm">💾 Save
                                    Sequence</button>
                                <a href="{{ route('master-data-list') }}"
                                    class="btn btn-outline-secondary btn-lg px-4 rounded-pill">← Back</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Live Email Preview -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-lg rounded-4" style="top: 20px;">
                    <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                        <h3 class="h4 fw-bold mb-0">📱 Live Preview</h3>
                        <div class="btn-group" role="group">
                            <button class="btn btn-sm btn-outline-primary active-preview rounded-start-pill"
                                data-preview="mobile">
                                <i class="bi bi-phone"></i> Mobile
                            </button>
                            <button class="btn btn-sm btn-outline-primary rounded-end-pill" data-preview="desktop">
                                <i class="bi bi-display"></i> Desktop
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-4 bg-light">
                        <div class="preview-container d-flex justify-content-center">
                            <!-- Mobile Frame -->
                            <div id="mobilePreview" class="email-preview mobile-preview active"
                                style="width: 100%; max-width: 360px; background: white; border-radius: 32px; box-shadow: 0 20px 35px -12px rgba(0,0,0,0.3); overflow: hidden; transition: all 0.2s;">
                                <div class="bg-dark text-white px-3 py-2 small d-flex justify-content-between">
                                    <span>12:00</span>
                                    <span>📶 🔋</span>
                                </div>
                                <div class="p-3" style="min-height: 520px;">
                                    <div class="email-preview-header bg-light p-2 rounded mb-2">
                                        <strong>Subject:</strong> <span id="previewSubject">No subject</span>
                                    </div>
                                    <div id="mobilePreviewContent"></div>
                                </div>

                            </div>

                            <!-- Desktop Frame -->
                            <div id="desktopPreview" class="email-preview desktop-preview"
                                style="display: none; width: 100%; max-width: 680px; background: white; border-radius: 24px; box-shadow: 0 20px 35px -12px rgba(0,0,0,0.2); overflow: hidden;">
                                <div class="bg-light px-4 py-2 border-bottom d-flex gap-2 align-items-center">
                                    <i class="bi bi-envelope-fill text-primary"></i>
                                    <strong>Email Preview</strong>
                                    <span class="ms-auto"><span id="previewSubjectDesktop">No subject</span></span>
                                </div>
                                <div class="p-4" style="min-height: 500px;">
                                    <div id="desktopPreviewContent"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script>
        // DOM Elements
        let currentEditor = document.getElementById('emailEditor');
        let currentHeroImage = null;
        let currentAttachment = null;
        let currentCompanyLogo = null;
        let currentCompanyLogoFile = null;
        let currentImageType = 'logo'; // ✅ FIX: missing closing quote

        // ─── PLACEHOLDER HELPER ───
        function updatePlaceholder() {
            const el = currentEditor;
            const text = el.textContent;
            const html = el.innerHTML.trim();
            const isEmpty = !text || text === '\u00A0' || text.trim() === '' ||
                html === '<br>' || html === '<p><br></p>' || html === '<p>&nbsp;</p>' ||
                html === '<div><br></div>' || html === '<div>&nbsp;</div>';
            el.classList.toggle('placeholder', isEmpty);
            if (isEmpty && (html === '' || html === '<br>' || html === '<div></div>' || html === '<p></p>')) {
                el.innerHTML = '<p><br></p>';
            }
        }

        // Load initial data
        window.addEventListener('DOMContentLoaded', () => {

            loadBusinessLinks();
            updatePreview();
            updatePlaceholder();
        });

        function loadBusinessLinks() {
            fetch('{{ route('user-business-links') }}')
                .then(response => response.json())
                .then(data => {
                    if (data.company_logo) {
                        currentCompanyLogo = data.company_logo;
                        currentImageType = data.image_type || 'logo';
                        document.getElementById('companyLogoPreviewImg').src = data.company_logo;
                        document.getElementById('companyLogoPreview').style.display = 'block';
                        document.getElementById('existingCompanyLogo').value = data.company_logo;
                        document.getElementById('imageType').value = currentImageType;
                    }
                    document.getElementById('whatsappLink').value = data.whatsapp_link || '';

                    window.selectedActionLinks = data.action_links || [];
                    renderActionLinks(window.selectedActionLinks);
                    document.getElementById('logoPosition').value = data.logo_position || 'center';
                    updatePreview();
                })
                .catch(err => console.error(err));
        }

        function renderActionLinks(links) {
            let html = '';
            links.forEach(function(link, index) {
                html += `
                <div class="col-md-4 action-link-item mb-2">
                    <label class="small fw-semibold mb-1 d-block">${link.platform_name}</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="action_links[${index}][platform_url]" value="${link.platform_url}">
                        <button type="button" class="btn btn-danger remove-action-link" data-id="${link.id}">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <input type="hidden" name="action_links[${index}][platform_name]" value="${link.platform_name}">
                    <input type="hidden" name="action_links[${index}][id]" value="${link.id}">
                </div>
            `;
            });
            $('#dynamicActionLinks').html(html);
        }

        $(document).on('click', '.remove-action-link', function() {
            const linkId = $(this).data('id');
            window.selectedActionLinks = window.selectedActionLinks.filter(function(link) {
                return link.id != linkId;
            });
            renderActionLinks(window.selectedActionLinks);
            updatePreview(); // <-- Refresh the live preview
            console.log(window.selectedActionLinks);
        });

        // Logo upload with dimension detection
        document.getElementById('companyLogoInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                currentCompanyLogoFile = file;
                const reader = new FileReader();
                reader.onload = function(ev) {
                    const img = new Image();
                    img.onload = () => {
                        const aspectRatio = img.width / img.height;
                        currentImageType = (img.width > 400 || aspectRatio > 2) ? 'banner' : 'logo';
                        document.getElementById('imageType').value = currentImageType;
                        currentCompanyLogo = ev.target.result;
                        document.getElementById('companyLogoPreviewImg').src = currentCompanyLogo;
                        document.getElementById('companyLogoPreview').style.display = 'block';
                        document.getElementById('existingCompanyLogo').value = '';
                        updatePreview();
                    };
                    img.src = ev.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        // Text formatting
        function formatText(command, value = null) {
            document.execCommand(command, false, value);
            updatePreview();
            updatePlaceholder();
            currentEditor.focus();
        }

        function changeFontSize(size) {
            document.execCommand('fontSize', false, '7');
            document.querySelectorAll('#emailEditor font[size="7"]').forEach(el => {
                el.removeAttribute('size');
                el.style.fontSize = size;
            });
            updatePreview();
            updatePlaceholder();
        }

        function addHorizontalLine() {
            document.execCommand('insertHorizontalRule', false, null);
            updatePreview();
            updatePlaceholder();
        }

        // Hero image handlers
        function previewHeroImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = (e) => {
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

        // ========== Remove Company Logo ==========
        function removeCompanyLogo() {
            currentCompanyLogo = null;
            currentCompanyLogoFile = null;
            currentImageType = 'logo';

            // Clear file input
            document.getElementById('companyLogoInput').value = '';

            // Clear hidden fields
            document.getElementById('existingCompanyLogo').value = '';
            document.getElementById('imageType').value = 'logo';

            // Hide preview
            document.getElementById('companyLogoPreview').style.display = 'none';
            document.getElementById('companyLogoPreviewImg').src = '';

            // Update preview
            updatePreview();

            toastr.info('Company logo/banner removed');
        }

        // Attachment handlers
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

        // Live Preview generator
        function generatePreviewHtml() {
            let content = currentEditor.innerHTML;
            let logoHtml = '';
            if (currentCompanyLogo) {
                if (currentImageType === 'banner') {
                    logoHtml =
                        `<img src="${currentCompanyLogo}" alt="Banner" style="width: 100%; border-radius: 16px; margin-bottom: 20px;">`;
                } else {
                    const position = document.getElementById('logoPosition').value;
                    const align = position === 'left' ? 'text-start' : (position === 'center' ? 'text-center' : 'text-end');
                    logoHtml =
                        `<div class="${align} my-3"><img src="${currentCompanyLogo}" class="company-logo" style="max-width: 140px; border-radius: 12px;"></div>`;
                }
            }
            let heroHtml = currentHeroImage ?
                `<img src="${currentHeroImage.data}" style="width:100%; border-radius: 16px; margin-bottom: 20px;">` : '';
            let attachmentHtml = '';
            if (currentAttachment) {
                attachmentHtml =
                    `<div class="alert alert-secondary mt-3 p-2 rounded-3"><i class="bi bi-paperclip"></i> <strong>Attachment:</strong> ${currentAttachment.name} (${currentAttachment.size})<br><a href="#" class="small">Download file →</a></div>`;
            }


            let linksHtml = `<div class="d-flex flex-wrap gap-3 justify-content-center mt-4">`;
            const whatsapp = document.getElementById('whatsappLink').value;
            if (whatsapp) {
                linksHtml += `<a href="${whatsapp}" class="action-btn whatsapp" target="_blank"> WhatsApp </a>`;
            }

            if (window.selectedActionLinks) {
                window.selectedActionLinks.forEach(function(link) {
                    let url = link.platform_url || '';
                    if (!url) return;
                    linksHtml += `
                    <a href="${url}"
                    class="action-btn other"
                    target="_blank">
                        ${link.platform_name}
                    </a>
                `;
                });
            }

            linksHtml += `</div>`;
            return logoHtml + heroHtml + content + attachmentHtml + linksHtml;
        }

        function updatePreview() {
            const subjectText = document.getElementById('subject').value || 'No subject';

            document.getElementById('previewSubject').innerText = subjectText;
            document.getElementById('previewSubjectDesktop').innerText = subjectText;

            const fullHtml = generatePreviewHtml();

            document.getElementById('mobilePreviewContent').innerHTML = fullHtml;
            document.getElementById('desktopPreviewContent').innerHTML = fullHtml;
        }

        // Event Listeners for live sync
        document.getElementById('subject').addEventListener('input', updatePreview);
        document.getElementById('whatsappLink').addEventListener('input', updatePreview);
        document.getElementById('logoPosition').addEventListener('change', updatePreview);

        currentEditor.addEventListener('input', function() {
            updatePreview();
            updatePlaceholder();
        });
        currentEditor.addEventListener('keyup', function() {
            updatePreview();
            updatePlaceholder();
        });
        currentEditor.addEventListener('paste', function(e) {
            e.preventDefault();
            const text = (e.clipboardData || window.clipboardData).getData('text/plain');
            document.execCommand('insertText', false, text);
            updatePreview();
            updatePlaceholder();
        });

        // Preview toggle
        document.querySelectorAll('[data-preview]').forEach(btn => {
            btn.addEventListener('click', function() {
                const type = this.dataset.preview;
                document.querySelectorAll('[data-preview]').forEach(b => b.classList.remove(
                    'active-preview'));
                this.classList.add('active-preview');
                if (type === 'mobile') {
                    document.getElementById('mobilePreview').style.display = 'block';
                    document.getElementById('desktopPreview').style.display = 'none';
                } else {
                    document.getElementById('mobilePreview').style.display = 'none';
                    document.getElementById('desktopPreview').style.display = 'block';
                }
            });
        });

        // Variant: only single uppercase letter
        $('#variant').on('input', function() {
            let val = $(this).val().replace(/[^A-Za-z]/g, '').toUpperCase().substring(0, 1);
            $(this).val(val);
        });

        // AJAX Submit
        $(document).ready(function() {
            $('#emailForm').submit(function(e) {
                e.preventDefault();

                let html = currentEditor.innerHTML;

                // tags ke beech extra newlines remove
                html = html.replace(/>\s+\</g, '><');

                // empty paragraph normalize
                html = html.replace(
                    /<p>(?:\s|&nbsp;|<br>)*<\/p>/gi,
                    '<p>&nbsp;</p>'
                );

                // last ke blank paragraphs remove
                html = html.replace(
                    /(?:<p>&nbsp;<\/p>\s*)+$/i,
                    ''
                );

                // max 2 blank lines
                html = html.replace(
                    /(<p>&nbsp;<\/p>\s*){3,}/gi,
                    '<p>&nbsp;</p><p>&nbsp;</p>'
                );

                document.getElementById('message').value = html;

                if (!$('#step').val()) {
                    toastr.error('Step is required');
                    return;
                }
                if (!$('#subject').val()) {
                    toastr.error('Subject is required');
                    return;
                }
                let editorEmpty = !currentEditor.innerText.trim() || currentEditor.innerHTML ===
                    '<p><br></p>';
                if (editorEmpty) {
                    toastr.error('Message content is required');
                    return;
                }
                if (!$('input[name="gap_days"]').val()) {
                    toastr.error('Gap days required');
                    return;
                }
                if (!$('#variant').val()) {
                    toastr.error('Variant required');
                    return;
                }

                const formData = new FormData(this);
                $.ajax({
                    url: '{{ route('user-sequences-store') }}',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(resp) {
                        toastr.success(resp.message);
                        $('#emailForm')[0].reset();
                        currentEditor.innerHTML = '<p><br></p>';
                        updatePlaceholder();
                        currentHeroImage = null;
                        currentAttachment = null;
                        $('#heroImage').val('');
                        $('#heroImagePreview').hide();
                        $('#attachmentsImage').val('');
                        $('#attachmentPreview').hide();
                        loadBusinessLinks();
                        updatePreview();
                    },
                    error: function(xhr) {
                        if (xhr.status === 422 && xhr.responseJSON.errors) {
                            Object.values(xhr.responseJSON.errors).forEach(err => toastr.error(
                                err[0]));
                        } else {
                            toastr.error(xhr.responseJSON?.message || 'Something went wrong');
                        }
                    }
                });
            });
        });
    </script>
@endsection
