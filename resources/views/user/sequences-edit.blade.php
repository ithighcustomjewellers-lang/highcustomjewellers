@extends('user.dashboard')

@section('content')
<div class="container-fluid mt-4 px-4">
    <div class="row g-4">
        <!-- Email Designer Form -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                    <h3 class="h4 mb-0 fw-bold text-gradient">✏️ Edit Email Sequence</h3>
                    <p class="text-muted small mt-1">Modify your email content and settings</p>
                </div>
                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form id="emailForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        <!-- Basic Info Row -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold small text-uppercase text-muted">Step Order</label>
                                <input type="number" name="step" id="step" class="form-control form-control-lg rounded-3" min="1" value="{{ old('step', $sequence->step) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold small text-uppercase text-muted">Gap Days</label>
                                <input type="number" name="gap_days" id="gapDays" class="form-control form-control-lg rounded-3" min="0" value="{{ old('gap_days', $sequence->gap_days) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold small text-uppercase text-muted">Variant (A/B/C)</label>
                                <input type="text" name="variant" id="variant" class="form-control form-control-lg rounded-3 text-uppercase" value="{{ old('variant', $sequence->variant) }}" maxlength="1">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold small text-uppercase text-muted">Business Type</label>
                                <select name="type" id="typeSelect" class="form-select form-control rounded-3 form-control-lg">
                                    <option value="B2B" {{ old('type', $sequence->type) == 'B2B' ? 'selected' : '' }}>B2B</option>
                                    <option value="B2C" {{ old('type', $sequence->type) == 'B2C' ? 'selected' : '' }}>B2C</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold small text-uppercase text-muted">Email Subject</label>
                            <input type="text" name="subject" id="subject" class="form-control form-control-lg rounded-3" value="{{ old('subject', $sequence->subject) }}">
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
                                        <input type="file" name="company_logo" id="companyLogoInput" class="form-control" accept="image/*">
                                        <input type="hidden" name="existing_company_logo" id="existingCompanyLogo" value="{{ $sequence->existing_company_logo }}">
                                        <input type="hidden" name="image_type" id="imageType" value="ass{{ $sequence->image_type ?? 'logo' }}">
                                    </div>
                                    <div class="col-md-5">
                                        <div id="companyLogoPreview" class="mt-2" style="{{ $sequence->existing_company_logo ? 'display:block' : 'display:none' }}">
                                            <img id="companyLogoPreviewImg" class="img-fluid rounded-3 border" style="max-height: 60px" src="{{ $sequence->existing_company_logo ? asset($sequence->existing_company_logo) : '' }}">
                                        </div>
                                        <label class="form-label fw-semibold">Logo Position</label>
                                        <select name="logo_position" id="logoPosition" class="form-select">
                                            <option value="left" {{ old('logo_position', $sequence->logo_position) == 'left' ? 'selected' : '' }}>Left</option>
                                            <option value="center" {{ old('logo_position', $sequence->logo_position) == 'center' ? 'selected' : '' }}>Center</option>
                                            <option value="right" {{ old('logo_position', $sequence->logo_position) == 'right' ? 'selected' : '' }}>Right</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Hero Image Upload -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold small text-uppercase text-muted">Hero Image (Top Banner)</label>
                            <div class="border rounded-3 p-3 bg-light">
                                <input type="file" name="hero_image" id="heroImage" class="form-control" accept="image/*" onchange="previewHeroImage(this)">
                                @if($sequence->hero_image)
                                    <div id="heroImagePreview" class="mt-2" style="display:block;">
                                        <div class="d-flex align-items-center gap-3">
                                            <img id="heroImagePreviewImg" class="rounded-3 border" style="max-width: 80px; max-height: 80px;" src="{{ asset($sequence->hero_image) }}">
                                            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill" onclick="removeHeroImage()">Remove</button>
                                        </div>
                                    </div>
                                @else
                                    <div id="heroImagePreview" class="mt-2" style="display:none;"></div>
                                @endif
                                <small class="text-muted">Recommended: 1200 x 400px. Max 2MB</small>
                            </div>
                        </div>

                        <!-- WYSIWYG Toolbar (same as create form) -->
                        <label class="form-label fw-semibold small text-uppercase text-muted">Email Content</label>
                        <div class="toolbar mb-2 p-2 bg-white rounded-3 border d-flex flex-wrap gap-1 align-items-center">
                            <div class="btn-group btn-group-sm me-1">
                                <button type="button" onclick="formatText('bold')" class="btn btn-outline-secondary fw-bold">B</button>
                                <button type="button" onclick="formatText('italic')" class="btn btn-outline-secondary fst-italic">I</button>
                                <button type="button" onclick="formatText('underline')" class="btn btn-outline-secondary text-decoration-underline">U</button>
                            </div>
                            <select id="fontFamilySelect" onchange="formatText('fontName', this.value)" class="form-select form-select-sm w-auto">
                                <option value="Arial">Arial</option>
                                <option value="Verdana">Verdana</option>
                                <option value="Georgia">Georgia</option>
                                <option value="Times New Roman">Times New Roman</option>
                            </select>
                            <select id="fontSizeSelect" onchange="changeFontSize(this.value)" class="form-select form-select-sm w-auto">
                                <option value="12px">12px</option>
                                <option value="14px">14px</option>
                                <option value="16px" selected>16px</option>
                                <option value="18px">18px</option>
                                <option value="24px">24px</option>
                                <option value="32px">32px</option>
                            </select>
                            <select onchange="formatText('foreColor', this.value)" class="form-control-sm">
                                <option value="#000000">Black</option>
                                <option value="#FF0000">Red</option>
                                <option value="#00FF00">Green</option>
                                <option value="#0000FF">Blue</option>
                                <option value="#FFA500">Orange</option>
                            </select>
                            <div class="btn-group btn-group-sm">
                                <button type="button" onclick="formatText('insertUnorderedList')" class="btn btn-outline-secondary">• List</button>
                                <button type="button" onclick="formatText('insertOrderedList')" class="btn btn-outline-secondary">1. List</button>
                            </div>
                            <div class="btn-group btn-group-sm">
                                <button type="button" onclick="formatText('justifyLeft')" class="btn btn-outline-secondary">⍇</button>
                                <button type="button" onclick="formatText('justifyCenter')" class="btn btn-outline-secondary">⍌</button>
                                <button type="button" onclick="formatText('justifyRight')" class="btn btn-outline-secondary">⍄</button>
                            </div>
                            <button type="button" onclick="addHorizontalLine()" class="btn btn-outline-secondary">─</button>
                        </div>

                        <div id="emailEditor" class="form-control mb-3" contenteditable="true"
                            style="min-height: 280px; border: 1px solid #dee2e6; border-radius: 12px; padding: 16px; overflow-y: auto; background: white; font-size: 16px;">
                            {!! old('message', $sequence->message) !!}
                        </div>
                        <textarea name="message" id="message" style="display:none;">{{ old('message', $sequence->message) }}</textarea>

                        <!-- Attachment -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold small text-uppercase text-muted">Attachment (Download Link)</label>
                            <div class="border rounded-3 p-3 bg-light">
                                <input type="file" name="attachments_image" class="form-control" id="attachmentsImage" accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.zip" onchange="previewAttachment(this)">
                                <div id="attachmentPreview" class="mt-2" style="{{ $sequence->attachments_image ? 'display:block' : 'display:none' }}">
                                    <div class="alert alert-info d-flex justify-content-between align-items-center py-2 px-3 mb-0">
                                        <span><i class="bi bi-paperclip me-2"></i><span id="attachmentName"></span></span>
                                        <button type="button" class="btn-close" onclick="removeAttachment()"></button>
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
                                    <div class="col-md-4">
                                        <input type="text" name="whatsapp_link" class="form-control" id="whatsappLink" value="{{ old('whatsapp_link', $sequence->whatsapp_link) }}" placeholder="WhatsApp URL">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" name="telegram_link" class="form-control" id="telegramLink" value="{{ old('telegram_link', $sequence->telegram_link) }}" placeholder="Telegram URL">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" name="business_link" class="form-control" id="businessLink" value="{{ old('business_link', $sequence->business_link) }}" placeholder="Business Website">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-3 mt-4 flex-wrap">
                            <button type="submit" class="btn btn-primary btn-lg px-5 rounded-pill shadow-sm">💾 Update Sequence</button>
                            <a href="{{ route('master-data-list') }}" class="btn btn-light btn-lg px-5 rounded-pill shadow-sm back-btn">
                                <i class="fa fa-arrow-left me-2"></i> Back
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Live Email Preview (same as create form) -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-lg rounded-4" style="top: 20px;">
                <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h3 class="h4 fw-bold mb-0">📱 Live Preview</h3>
                    <div class="btn-group" role="group">
                        <button class="btn btn-sm btn-outline-primary active-preview rounded-start-pill" data-preview="mobile">
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
                        <div id="mobilePreview" class="email-preview mobile-preview active" style="width: 100%; max-width: 360px; background: white; border-radius: 32px; box-shadow: 0 20px 35px -12px rgba(0,0,0,0.3); overflow: hidden;">
                            <div class="bg-dark text-white px-3 py-2 small d-flex justify-content-between">
                                <span>12:00</span>
                                <span>📶 🔋</span>
                            </div>
                            <div class="p-3" style="min-height: 520px;">
                                <div class="email-preview-header bg-light p-2 rounded mb-2">
                                    <strong>Subject:</strong> <span id="previewSubject">{{ $sequence->subject }}</span>
                                </div>
                                <div id="mobilePreviewContent"></div>
                            </div>
                        </div>

                        <!-- Desktop Frame -->
                        <div id="desktopPreview" class="email-preview desktop-preview" style="display: none; width: 100%; max-width: 680px; background: white; border-radius: 24px; box-shadow: 0 20px 35px -12px rgba(0,0,0,0.2); overflow: hidden;">
                            <div class="bg-light px-4 py-2 border-bottom d-flex gap-2 align-items-center">
                                <i class="bi bi-envelope-fill text-primary"></i>
                                <strong>Email Preview</strong>
                                <span class="ms-auto"><span id="previewSubjectDesktop">{{ $sequence->subject }}</span></span>
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

<!-- Styles and Scripts (copy exactly from your create blade) -->
<script src="{{ asset('js/jquery.min.js') }}"></script>
<style>
    body { background: #f3f6fc; }
    .text-gradient { background: linear-gradient(135deg, #2b3b4e, #1a4d8c); -webkit-background-clip: text; background-clip: text; color: transparent; }
    .toolbar button, .toolbar select { cursor: pointer; transition: all 0.1s ease; }
    .toolbar button:hover { background-color: #e9ecef; transform: scale(0.96); }
    .email-preview img { max-width: 100%; height: auto; border-radius: 12px; }
    .email-preview .company-logo { max-width: 140px; max-height: 70px; object-fit: contain; }
    .logo-left { text-align: left; }
    .logo-center { text-align: center; }
    .logo-right { text-align: right; }
    .mobile-preview .btn, .desktop-preview .btn { display: inline-block; padding: 8px 18px; margin: 4px; border-radius: 40px; text-decoration: none; font-size: 14px; font-weight: 500; }
    .active-preview { background-color: #0d6efd !important; color: white !important; }
    #emailEditor:focus { box-shadow: 0 0 0 3px rgba(13,110,253,0.25); outline: none; }
</style>

<script>
    let currentEditor = document.getElementById('emailEditor');
    let currentHeroImage = null;
    let currentAttachment = null;
    let currentCompanyLogo = null;
    let currentImageType = '{{ $sequence->image_type ?? "logo" }}';
    let existingCompanyLogo = '{{ $sequence->existing_company_logo }}';
    let existingHeroImage = '{{ $sequence->hero_image }}';
    let existingAttachment = '{{ $sequence->attachments_image }}';

    // Pre-populate preview on load
    window.addEventListener('DOMContentLoaded', () => {
    // Set existing company logo preview
    if (existingCompanyLogo) {
        currentCompanyLogo = '{{ asset($sequence->existing_company_logo) }}';
        document.getElementById('companyLogoPreviewImg').src = currentCompanyLogo;
        document.getElementById('companyLogoPreview').style.display = 'block';
    }
    // Set existing hero image
    if (existingHeroImage) {
        currentHeroImage = { data: '{{ asset($sequence->hero_image) }}', file: null, name: '' };
        document.getElementById('heroImagePreviewImg').src = currentHeroImage.data;
        document.getElementById('heroImagePreview').style.display = 'block';
    }
    // Set existing attachment
    if (existingAttachment) {
        currentAttachment = {
            name: '{{ $sequence->attachment_name ?? "Attachment" }}',
            size: '{{ round($sequence->attachment_size / 1024, 2) }} KB'
        };
        const attachmentNameSpan = document.getElementById('attachmentName');
        if (attachmentNameSpan) {
            attachmentNameSpan.innerHTML = `${currentAttachment.name} (${currentAttachment.size})`;
        }
        document.getElementById('attachmentPreview').style.display = 'block';
    }
    // Set existing links and logo position
    document.getElementById('whatsappLink').value = '{{ $sequence->whatsapp_link }}';
    document.getElementById('telegramLink').value = '{{ $sequence->telegram_link }}';
    document.getElementById('businessLink').value = '{{ $sequence->business_link }}';
    document.getElementById('logoPosition').value = '{{ $sequence->logo_position ?? "center" }}';
    updatePreview();
});

    // All the same helper functions as your create form (copy exactly)
    function formatText(command, value = null) {
        document.execCommand(command, false, value);
        updatePreview();
        currentEditor.focus();
    }
    function changeFontSize(size) {
        document.execCommand('fontSize', false, '7');
        document.querySelectorAll('#emailEditor font[size="7"]').forEach(el => {
            el.removeAttribute('size');
            el.style.fontSize = size;
        });
        updatePreview();
    }
    function addHorizontalLine() {
        document.execCommand('insertHorizontalRule', false, null);
        updatePreview();
    }
    function previewHeroImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = (e) => {
                currentHeroImage = { data: e.target.result, file: input.files[0], name: input.files[0].name };
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
        const attachmentNameSpan = document.getElementById('attachmentName');
        if (attachmentNameSpan) {
            attachmentNameSpan.innerHTML = `${currentAttachment.name} (${currentAttachment.size})`;
        }
        document.getElementById('attachmentPreview').style.display = 'block';
        updatePreview();
    }
}
    function removeAttachment() {
    currentAttachment = null;
    document.getElementById('attachmentsImage').value = '';
    document.getElementById('attachmentPreview').style.display = 'none';
    document.getElementById('attachmentName').innerHTML = ''; // clear
    updatePreview();
}
    document.getElementById('companyLogoInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
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
                    // If a new file is selected, we also need to unset the "remove_logo" checkbox logic
                    document.getElementById('removeLogoCheck') && (document.getElementById('removeLogoCheck').checked = false);
                    updatePreview();
                };
                img.src = ev.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
    function generatePreviewHtml() {
        let content = currentEditor.innerHTML;
        let logoHtml = '';
        if (currentCompanyLogo) {
            if (currentImageType === 'banner') {
                logoHtml = `<img src="${currentCompanyLogo}" alt="Banner" style="width: 100%; border-radius: 16px; margin-bottom: 20px;">`;
            } else {
                const position = document.getElementById('logoPosition').value;
                const align = position === 'left' ? 'text-start' : (position === 'center' ? 'text-center' : 'text-end');
                logoHtml = `<div class="${align} my-3"><img src="${currentCompanyLogo}" class="company-logo" style="max-width: 140px; border-radius: 12px;"></div>`;
            }
        }
        let heroHtml = currentHeroImage ? `<img src="${currentHeroImage.data}" style="width:100%; border-radius: 16px; margin-bottom: 20px;">` : '';
        let attachmentHtml = '';
        if (currentAttachment) {
            attachmentHtml = `<div class="alert alert-secondary mt-3 p-2 rounded-3"><i class="bi bi-paperclip"></i> <strong>Attachment:</strong> ${currentAttachment.name} (${currentAttachment.size})<br><a href="#" class="small">Download file →</a></div>`;
        }
        let linksHtml = '';
        const whatsapp = document.getElementById('whatsappLink').value;
        const telegram = document.getElementById('telegramLink').value;
        const business = document.getElementById('businessLink').value;
        if (whatsapp || telegram || business) {
            linksHtml = '<div class="d-flex flex-wrap gap-2 justify-content-center mt-4 pt-2">';
            if (whatsapp) linksHtml += `<a href="${whatsapp}" class="btn btn-success rounded-pill" target="_blank" style="background:#25D366;">📱 WhatsApp</a>`;
            if (telegram) linksHtml += `<a href="${telegram}" class="btn btn-info rounded-pill text-white" target="_blank" style="background:#0088cc;">📨 Telegram</a>`;
            if (business) linksHtml += `<a href="${business}" class="btn btn-primary rounded-pill" target="_blank">💼 Website</a>`;
            linksHtml += '</div>';
        }
        return logoHtml + heroHtml + content + attachmentHtml + linksHtml;
    }
    function updatePreview() {
        const subjectText = document.getElementById('subject').value || 'No subject';
        document.getElementById('previewSubject').innerText = subjectText;
        document.getElementById('previewSubjectDesktop').innerText = subjectText;
        const fullHtml = generatePreviewHtml();
        document.getElementById('mobilePreviewContent').innerHTML = fullHtml;
        document.getElementById('desktopPreviewContent').innerHTML = fullHtml;
        document.getElementById('message').value = currentEditor.innerHTML;
    }
    // Event listeners
    document.getElementById('subject').addEventListener('input', updatePreview);
    document.getElementById('whatsappLink').addEventListener('input', updatePreview);
    document.getElementById('telegramLink').addEventListener('input', updatePreview);
    document.getElementById('businessLink').addEventListener('input', updatePreview);
    document.getElementById('logoPosition').addEventListener('change', updatePreview);
    currentEditor.addEventListener('input', updatePreview);
    currentEditor.addEventListener('keyup', updatePreview);
    currentEditor.addEventListener('paste', (e) => {
        e.preventDefault();
        const text = (e.clipboardData || window.clipboardData).getData('text/plain');
        document.execCommand('insertText', false, text);
        updatePreview();
    });
    // Preview toggle
    document.querySelectorAll('[data-preview]').forEach(btn => {
        btn.addEventListener('click', function() {
            const type = this.dataset.preview;
            document.querySelectorAll('[data-preview]').forEach(b => b.classList.remove('active-preview'));
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
    // Variant limit to one uppercase letter
    $('#variant').on('input', function() {
        let val = $(this).val().replace(/[^A-Za-z]/g, '').toUpperCase().substring(0,1);
        $(this).val(val);
    });


$(document).ready(function() {
    $('#emailForm').on('submit', function(e) {
        e.preventDefault();
        $('#message').val(currentEditor.innerHTML);

        // Validations
        if (!$('#step').val()) { toastr.error('Step is required'); return; }
        if (!$('#subject').val()) { toastr.error('Subject is required'); return; }
        let editorEmpty = !currentEditor.innerText.trim() || currentEditor.innerHTML === '<p><br></p>';
        if (editorEmpty) { toastr.error('Message content is required'); return; }
        if (!$('input[name="gap_days"]').val()) { toastr.error('Gap days required'); return; }
        if (!$('#variant').val()) { toastr.error('Variant required'); return; }

        const formData = new FormData(this);
        if ($('#removeLogoCheck').length && $('#removeLogoCheck').is(':checked')) {
            formData.append('remove_logo', '1');
        }

        $.ajax({
            url: "{{ route('sequences-list-update', $sequence->id) }}",
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(resp) {
                toastr.success(resp.message || 'Updated successfully');
                setTimeout(() => location.reload(), 1500);
            },
            error: function(xhr) {
                if (xhr.status === 422 && xhr.responseJSON.errors) {
                    Object.values(xhr.responseJSON.errors).forEach(err => toastr.error(err[0]));
                } else {
                    toastr.error(xhr.responseJSON?.message || 'Update failed');
                }
            }
        });
    });
});
</script>
@endsection
