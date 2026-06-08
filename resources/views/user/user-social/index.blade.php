@extends('user.dashboard')

@section('title', 'Social Links Manager')
@section('content')

    <?php
    // Helper function to get platform icon HTML based on stored icon_type
    function getPlatformIconHtml($platform)
    {
        // Predefined platforms icon mapping (for Font Awesome)
        $iconMap = [
            'whatsapp' => 'fab fa-whatsapp',
            'telegram' => 'fab fa-telegram',
            'instagram' => 'fab fa-instagram',
            'facebook' => 'fab fa-facebook',
            'youtube' => 'fab fa-youtube',
            'linkedin' => 'fab fa-linkedin',
            'twitter' => 'fab fa-twitter',
            'x' => 'fab fa-x-twitter',
            'tiktok' => 'fab fa-tiktok',
            'snapchat' => 'fab fa-snapchat',
            'reddit' => 'fab fa-reddit',
            'discord' => 'fab fa-discord',
            'pinterest' => 'fab fa-pinterest',
            'twitch' => 'fab fa-twitch',
            'quora' => 'fab fa-quora',
            'github' => 'fab fa-github',
            'spotify' => 'fab fa-spotify',
            'medium' => 'fab fa-medium',
            'stackoverflow' => 'fab fa-stack-overflow',
            'behance' => 'fab fa-behance',
            'dribbble' => 'fab fa-dribbble',
            'flickr' => 'fab fa-flickr',
            'soundcloud' => 'fab fa-soundcloud',
            'vimeo' => 'fab fa-vimeo',
            'vk' => 'fab fa-vk',
            'weibo' => 'fab fa-weibo',
            'tumblr' => 'fab fa-tumblr',
            'foursquare' => 'fab fa-foursquare',
            'slack' => 'fab fa-slack',
            'skype' => 'fab fa-skype',
            'viber' => 'fab fa-viber',
            'messenger' => 'fab fa-facebook-messenger',
            'threads' => 'fab fa-threads',
            'rumble' => 'fas fa-video',
        ];

        $platformName = strtolower($platform->platform_name);

        // Check if should use custom image (cm_logo.png)
        if ($platform->icon_type === 'custom') {
            return '<img src="/images/cm_logo.png" alt="' . e($platform->platform_name) . '" style="width: 24px; height: 24px; object-fit: contain;">';
        }

        // Use Font Awesome icon if available
        if (isset($iconMap[$platformName])) {
            return '<i class="' . $iconMap[$platformName] . '"></i>';
        }

        // Fallback to custom image
        return '<img src="/images/cm_logo.png" alt="' . e($platform->platform_name) . '" style="width: 24px; height: 24px; object-fit: contain;">';
    }
    ?>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: #f5f7fb;
        }

        .main-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Header Styles */
        .header {
            background: white;
            border-radius: 20px;
            padding: 20px 30px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 24px;
        }

        .company-name h1 {
            font-size: 24px;
            font-weight: 800;
            color: #1a1a1a;
        }

        .company-name p {
            font-size: 12px;
            color: #666;
            margin-top: 4px;
        }

        .team-member {
            text-align: right;
        }

        .team-member .label {
            font-size: 12px;
            color: #666;
        }

        .team-member .name {
            font-size: 18px;
            font-weight: 600;
            color: #667eea;
        }

        /* Grid Layout */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 30px;
        }

        /* Left Panel */
        .left-panel {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
        }

        /* Table Styles */
        .links-table {
            width: 100%;
            margin-bottom: 30px;
        }

        .links-table thead tr {
            background: #f8f9fa;
            border-radius: 10px;
        }

        .links-table th {
            padding: 15px;
            text-align: left;
            font-size: 13px;
            font-weight: 600;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .links-table td {
            padding: 15px;
            border-bottom: 1px solid #f0f0f0;
        }

        .platform-cell {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
        }

        .platform-icon {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .platform-icon i {
            font-size: 20px;
        }

        .platform-icon img {
            width: 24px;
            height: 24px;
            object-fit: contain;
        }

        .platform-name {
            font-weight: 500;
        }

        .link-url-input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 13px;
            color: #667eea;
            background: #f8f9fa;
        }

        .link-url-input:focus {
            outline: none;
            border-color: #667eea;
            background: white;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }


        .copy-btn,
        .save-btn {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 13px;
            padding: 5px 10px;
            border-radius: 6px;
            transition: all 0.3s;
        }

        .copy-btn {
            color: #667eea;
        }

        .copy-btn:hover {
            background: #667eea10;
        }

        .save-btn {
            color: #28a745;
        }

        .save-btn:hover {
            background: #28a74510;
        }

        /* App Link Styles - Editable */
        .app-link {
            background: #f8f9fa;
            padding: 12px 15px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 15px;
            transition: all 0.3s;
        }

        .app-link:hover {
            background: #e9ecef;
        }

        .app-link i {
            width: 30px;
            font-size: 20px;
        }

        .app-link .link-input {
            flex: 1;
            padding: 8px 12px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 13px;
            background: white;
        }

        .app-link .link-input:focus {
            outline: none;
            border-color: #667eea;
        }

        .app-link .copy-btn-sm {
            background: #667eea;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.3s;
        }

        .app-link .copy-btn-sm:hover {
            background: #5a67d8;
        }

        .app-link .save-btn-sm {
            background: #28a745;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.3s;
            margin-right: 5px;
        }

        .app-link .save-btn-sm:hover {
            background: #218838;
        }

        /* Bottom Actions */
        .bottom-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #f0f0f0;
            gap: 20px;
        }

        .add-platform {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
        }

        .qr-platform-checkbox {
            width: 20px;
            height: 15px;
            min-width: 20px;
            cursor: pointer;
            margin: 0;
        }

        .add-platform input[type="text"],
        .add-platform input[type="url"] {
            flex: 1;
            height: 40px;
            padding: 0 15px;
            box-sizing: border-box;
        }


        .btn-add {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            transition: transform 0.2s;
        }

        .btn-add:hover {
            transform: translateY(-2px);
        }

        /* Right Panel */
        .right-panel {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .card {
            background: white;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .qr-card {
            text-align: center;
        }

        .qr-title {
            font-size: 14px;
            font-weight: 600;
            color: #666;
            margin-bottom: 15px;
            letter-spacing: 1px;
        }

        .qr-code {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }

        .qr-code svg,
        .qr-code img {
            width: 180px;
            height: 180px;
        }

        .profile-url {
            font-size: 11px;
            color: #999;
            word-break: break-all;
            margin: 15px 0;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .share-btn {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: white;
            font-size: 20px;
            transition: transform 0.2s;
        }

        .share-btn:hover {
            transform: translateY(-3px);
        }

        .whatsapp-share {
            background: #25D366;
        }

        .telegram-share {
            background: #0088cc;
        }

        .email-share {
            background: #ea4335;
        }

        .stats {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            margin-top: 15px;
        }

        .stat-box {
            flex: 1;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 12px;
            text-align: center;
        }

        .stat-number {
            font-size: 28px;
            font-weight: 800;
            color: #667eea;
        }

        .stat-label {
            font-size: 11px;
            color: #666;
            margin-top: 5px;
        }

        .print-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 12px;
            width: 100%;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
            transition: all 0.3s;
        }

        .print-btn:hover {
            transform: translateY(-2px);
        }

        /* Loading Spinner */
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        .QR_name {
            width: 120px;
            max-width: 90%;
            text-align: center;
            font-size: 16px;
            font-weight: 700;
            padding: 10px 15px;
            border: 2px solid #667eea;
            border-radius: 12px;
            outline: none;
            background: #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, .08);
        }

        .btn-add-platform {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 0.45rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            width: 100%;
        }

        .btn-add-platform:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .btn-save {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
        }

        /* Modal Styles */
        .platform-row {
            background: white;
            border-radius: 10px;
            padding: 10px;
            margin-bottom: 10px;
            transition: all 0.3s;
            border: 1px solid #e0e0e0;
        }

        .platform-row:hover {
            background: #f8f9fa;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .modal-content {
            border-radius: 20px;
            background: white;
        }

        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 20px 20px 0 0;
        }

        .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }

        .add-platform-section {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .btn-action{
            width:100%;
            height:42px;
            border:none;
            border-radius:10px;
            display:flex;
            align-items:center;
            justify-content:center;
            transition:all .3s ease;
            font-size:14px;
        }

        .btn-remove{
            background:#fee2e2;
            color:#dc2626;
        }

        .btn-remove:hover{
            background:#dc2626;
            color:#fff;
        }

        .btn-copy{
            background:#e0e7ff;
            color:#4f46e5;
        }

        .btn-copy:hover{
            background:#4f46e5;
            color:#fff;
        }

        .modal-backdrop.show {
            opacity: 0.25 !important;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        @media (max-width: 1024px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }

            .team-member {
                text-align: center;
            }

            .bottom-actions {
                flex-direction: column;
            }

            .add-platform {
                width: 100%;
                flex-direction: column;
            }
        }

        .toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #28a745;
            color: white;
            padding: 12px 24px;
            border-radius: 10px;
            display: none;
            z-index: 1000;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>

    <div class="main-container">
        <div class="header">
            <div class="logo-section">
                <div class="logo-icon">
                    <i class="fas fa-qrcode"></i>
                </div>
                <div class="company-name">
                    <h1>Digital Business Card</h1>
                    <p>Smart Social Links Manager</p>
                </div>
            </div>
            <div class="team-member">
                <div class="label">Welcome,</div>
                <div class="name">{{ Auth::user()->name }}</div>
            </div>
        </div>

        <!-- Dashboard Grid -->
        <div class="dashboard-grid">
            <!-- Left Panel -->
            <div class="left-panel">
                <div style="margin-top:20px; text-align:end;">
                    <button onclick="addMultipleQR()" class="btn-add">
                        <i class="fas fa-qrcode"></i>
                        Generate QR Code
                    </button>
                </div>

                <!-- Editable App Links Section -->
                <div style="margin-top: 20px;">
                    <div class="section-title">
                        <i class="fas fa-star"></i> QUICK LINKS
                    </div>

                    {{-- WhatsApp --}}
                    <div class="app-link quick-link-item">
                        <input type="checkbox" class="qr-platform-checkbox" data-platform="WhatsApp"
                            data-input="whatsapp_url">
                        <i class="fab fa-whatsapp" style="color: #25D366;"></i>
                        <strong>WhatsApp:</strong>
                        <input type="url" id="whatsapp_url" placeholder="https://wa.me/yournumber" class="link-input"
                            value="{{ $quickLinks['whatsapp_url'] ?? '' }}">
                        <button onclick="saveQuickLink('whatsapp_url', 'WhatsApp')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('whatsapp_url').value)"
                            class="copy-btn-sm">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>

                    {{-- Telegram --}}
                    <div class="app-link quick-link-item">
                        <input type="checkbox" class="qr-platform-checkbox" data-platform="Telegram"
                            data-input="telegram_url">
                        <i class="fab fa-telegram" style="color: #0088cc;"></i>
                        <strong>Telegram:</strong>
                        <input type="url" id="telegram_url" placeholder="https://t.me/username" class="link-input"
                            value="{{ $quickLinks['telegram_url'] ?? ($adminQuickLinks['telegram_url'] ?? '') }}">
                        <button onclick="saveQuickLink('telegram_url', 'Telegram')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('telegram_url').value)"
                            class="copy-btn-sm">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>

                    {{-- Facebook --}}
                    <div class="app-link quick-link-item">
                        <input type="checkbox" class="qr-platform-checkbox" data-platform="Facebook"
                            data-input="facebook_url">
                        <i class="fab fa-facebook" style="color: #1877f2;"></i>
                        <strong>Facebook:</strong>
                        <input type="url" id="facebook_url" placeholder="https://facebook.com/username"
                            class="link-input"
                            value="{{ $quickLinks['facebook_url'] ?? ($adminQuickLinks['facebook_url'] ?? '') }}">
                        <button onclick="saveQuickLink('facebook_url', 'Facebook')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('facebook_url').value)"
                            class="copy-btn-sm">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>

                    {{-- YouTube --}}
                    <div class="app-link quick-link-item">
                        <input type="checkbox" class="qr-platform-checkbox" data-platform="YouTube"
                            data-input="youtube_url">
                        <i class="fab fa-youtube" style="color: #ff0000;"></i>
                        <strong>YouTube:</strong>
                        <input type="url" id="youtube_url" placeholder="https://youtube.com/@username"
                            class="link-input"
                            value="{{ $quickLinks['youtube_url'] ?? ($adminQuickLinks['youtube_url'] ?? '') }}">
                        <button onclick="saveQuickLink('youtube_url', 'YouTube')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('youtube_url').value)" class="copy-btn-sm">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>

                    {{-- LinkedIn --}}
                    <div class="app-link quick-link-item">
                        <input type="checkbox" class="qr-platform-checkbox" data-platform="LinkedIn"
                            data-input="linkedin_url">
                        <i class="fab fa-linkedin" style="color: #0077b5;"></i>
                        <strong>LinkedIn:</strong>
                        <input type="url" id="linkedin_url" placeholder="https://linkedin.com/in/username"
                            class="link-input"
                            value="{{ $quickLinks['linkedin_url'] ?? ($adminQuickLinks['linkedin_url'] ?? '') }}">
                        <button onclick="saveQuickLink('linkedin_url', 'LinkedIn')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('linkedin_url').value)"
                            class="copy-btn-sm">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>

                    {{-- Instagram --}}
                    <div class="app-link quick-link-item">
                        <input type="checkbox" class="qr-platform-checkbox" data-platform="Instagram"
                            data-input="instagram_url">
                        <i class="fab fa-instagram" style="color: #E4405F;"></i>
                        <strong>Instagram:</strong>
                        <input type="url" id="instagram_url" placeholder="https://instagram.com/username"
                            class="link-input"
                            value="{{ $quickLinks['instagram_url'] ?? ($adminQuickLinks['instagram_url'] ?? '') }}">
                        <button onclick="saveQuickLink('instagram_url', 'Instagram')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('instagram_url').value)"
                            class="copy-btn-sm">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>

                    {{-- X --}}
                    <div class="app-link quick-link-item">
                        <input type="checkbox" class="qr-platform-checkbox" data-platform="X" data-input="x_url">
                        <i class="fab fa-x-twitter" style="color: #000;"></i>
                        <strong>X:</strong>
                        <input type="url" id="x_url" placeholder="https://x.com/username" class="link-input"
                            value="{{ $quickLinks['x_url'] ?? ($adminQuickLinks['x_url'] ?? '') }}">
                        <button onclick="saveQuickLink('x_url', 'X')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('x_url').value)" class="copy-btn-sm">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>

                    {{-- Threads --}}
                    <div class="app-link quick-link-item">
                        <input type="checkbox" class="qr-platform-checkbox" data-platform="Threads"
                            data-input="threads_url">
                        <i class="fab fa-threads" style="color: #000;"></i>
                        <strong>Threads:</strong>
                        <input type="url" id="threads_url" placeholder="https://threads.net/@username"
                            class="link-input"
                            value="{{ $quickLinks['threads_url'] ?? ($adminQuickLinks['threads_url'] ?? '') }}">
                        <button onclick="saveQuickLink('threads_url', 'Threads')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('threads_url').value)"
                            class="copy-btn-sm">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>

                    {{-- Snapchat --}}
                    <div class="app-link quick-link-item">
                        <input type="checkbox" class="qr-platform-checkbox" data-platform="Snapchat"
                            data-input="snapchat_url">
                        <i class="fab fa-snapchat" style="color: #FFFC00;"></i>
                        <strong>Snapchat:</strong>
                        <input type="url" id="snapchat_url" placeholder="https://snapchat.com/add/username"
                            class="link-input"
                            value="{{ $quickLinks['snapchat_url'] ?? ($adminQuickLinks['snapchat_url'] ?? '') }}">
                        <button onclick="saveQuickLink('snapchat_url', 'Snapchat')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('snapchat_url').value)"
                            class="copy-btn-sm">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>

                    {{-- Reddit --}}
                    {{-- <div class="app-link quick-link-item">
                        <input type="checkbox" class="qr-platform-checkbox" data-platform="Reddit"
                            data-input="reddit_url">
                        <i class="fab fa-reddit" style="color: #FF4500;"></i>
                        <strong>Reddit:</strong>
                        <input type="url" id="reddit_url" placeholder="https://reddit.com/user/username"
                            class="link-input"
                            value="{{ $quickLinks['reddit_url'] ?? ($adminQuickLinks['reddit_url'] ?? '') }}">
                        <button onclick="saveQuickLink('reddit_url', 'Reddit')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('reddit_url').value)"
                            class="copy-btn-sm">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div> --}}

                    {{-- Discord --}}
                    {{-- <div class="app-link quick-link-item">
                        <input type="checkbox" class="qr-platform-checkbox" data-platform="Discord"
                            data-input="discord_url">
                        <i class="fab fa-discord" style="color: #5865F2;"></i>
                        <strong>Discord:</strong>
                        <input type="url" id="discord_url" placeholder="https://discord.gg/yourserver"
                            class="link-input"
                            value="{{ $quickLinks['discord_url'] ?? ($adminQuickLinks['discord_url'] ?? '') }}">
                        <button onclick="saveQuickLink('discord_url', 'Discord')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('discord_url').value)"
                            class="copy-btn-sm">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div> --}}

                    {{-- Pinterest --}}
                    {{-- <div class="app-link quick-link-item">
                        <input type="checkbox" class="qr-platform-checkbox" data-platform="Pinterest"
                            data-input="pinterest_url">
                        <i class="fab fa-pinterest" style="color: #E60023;"></i>
                        <strong>Pinterest:</strong>
                        <input type="url" id="pinterest_url" placeholder="https://pinterest.com/username"
                            class="link-input"
                            value="{{ $quickLinks['pinterest_url'] ?? ($adminQuickLinks['pinterest_url'] ?? '') }}">
                        <button onclick="saveQuickLink('pinterest_url', 'Pinterest')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('pinterest_url').value)"
                            class="copy-btn-sm">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div> --}}

                    {{-- Quora --}}
                    {{-- <div class="app-link quick-link-item">
                        <input type="checkbox" class="qr-platform-checkbox" data-platform="Quora"
                            data-input="quora_url">
                        <i class="fab fa-quora" style="color: #B92B27;"></i>
                        <strong>Quora:</strong>
                        <input type="url" id="quora_url" placeholder="https://quora.com/profile/username"
                            class="link-input"
                            value="{{ $quickLinks['quora_url'] ?? ($adminQuickLinks['quora_url'] ?? '') }}">
                        <button onclick="saveQuickLink('quora_url', 'Quora')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('quora_url').value)" class="copy-btn-sm">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div> --}}

                    {{-- Messenger --}}
                    <div class="app-link quick-link-item">
                        <input type="checkbox" class="qr-platform-checkbox" data-platform="Messenger"
                            data-input="messenger_url">
                        <i class="fab fa-facebook-messenger" style="color: #0084FF;"></i>
                        <strong>Messenger:</strong>
                        <input type="url" id="messenger_url" placeholder="https://m.me/username" class="link-input"
                            value="{{ $quickLinks['messenger_url'] ?? ($adminQuickLinks['messenger_url'] ?? '') }}">
                        <button onclick="saveQuickLink('messenger_url', 'Messenger')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('messenger_url').value)"
                            class="copy-btn-sm">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>

                    {{-- Twitch --}}
                    {{-- <div class="app-link quick-link-item">
                        <input type="checkbox" class="qr-platform-checkbox" data-platform="Twitch"
                            data-input="twitch_url">
                        <i class="fab fa-twitch" style="color: #9146FF;"></i>
                        <strong>Twitch:</strong>
                        <input type="url" id="twitch_url" placeholder="https://twitch.tv/username"
                            class="link-input"
                            value="{{ $quickLinks['twitch_url'] ?? ($adminQuickLinks['twitch_url'] ?? '') }}">
                        <button onclick="saveQuickLink('twitch_url', 'Twitch')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('twitch_url').value)"
                            class="copy-btn-sm">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div> --}}

                    {{-- Rumble --}}
                    {{-- <div class="app-link quick-link-item">
                        <input type="checkbox" class="qr-platform-checkbox" data-platform="Rumble"
                            data-input="rumble_url">
                        <i class="fas fa-video" style="color: #85C742;"></i>
                        <strong>Rumble:</strong>
                        <input type="url" id="rumble_url" placeholder="https://rumble.com/user/username"
                            class="link-input"
                            value="{{ $quickLinks['rumble_url'] ?? ($adminQuickLinks['rumble_url'] ?? '') }}">
                        <button onclick="saveQuickLink('rumble_url', 'Rumble')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('rumble_url').value)"
                            class="copy-btn-sm">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div> --}}

                    {{-- Viber --}}
                    {{-- <div class="app-link quick-link-item">
                        <input type="checkbox" class="qr-platform-checkbox" data-platform="Viber"
                            data-input="viber_url">
                        <i class="fab fa-viber" style="color: #7360F2;"></i>
                        <strong>Viber:</strong>
                        <input type="url" id="viber_url" placeholder="https://invite.viber.com/?g=group"
                            class="link-input"
                            value="{{ $quickLinks['viber_url'] ?? ($adminQuickLinks['viber_url'] ?? '') }}">
                        <button onclick="saveQuickLink('viber_url', 'Viber')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('viber_url').value)" class="copy-btn-sm">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div> --}}
                </div>

                <!-- Custom Social Links Section - Dynamic Links -->
                <div style="margin-top: 20px;">
                    <div id="customLinksContainer">
                        @foreach ($socialLinks as $link)
                            <div class="app-link quick-link-item" id="link-row-{{ $link->id }}"
                                data-icon-type="{{ $link->icon_type }}">
                                <input type="checkbox" class="qr-platform-checkbox"
                                    data-platform="{{ $link->platform_name }}" data-input="url_{{ $link->id }}">
                                <div class="platform-icon">
                                    {!! getPlatformIconHtml($link) !!}
                                </div>
                                <strong>{{ $link->platform_name }}:</strong>
                                <input type="url" id="url_{{ $link->id }}" class="link-input" value="{{ $link->platform_url }}" placeholder="https://...">
                                <button onclick="saveCustomLink({{ $link->id }}, '{{ $link->platform_name }}')" class="save-btn-sm">
                                    <i class="fas fa-save"></i> Save
                                </button>
                                <button
                                    onclick="copyToClipboard(document.getElementById('url_{{ $link->id }}').value)"
                                    class="copy-btn-sm">
                                    <i class="fas fa-copy"></i> Copy
                                </button>
                                <button onclick="deleteCustomLink({{ $link->id }})" class="save-btn-sm"
                                    style="background:#dc3545;">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Bottom Actions -->
                <div class="bottom-actions">
                    <div class="add-platform">
                        <input type="text" id="newPlatformName" placeholder="Platform Name (e.g., TikTok)">
                        <input type="url" id="newPlatformUrl" placeholder="Platform URL (https://...)">
                        <button onclick="addCustomPlatform()" class="btn-add">
                            <i class="fas fa-plus"></i> ADD NEW
                        </button>
                    </div>
                </div>

                <button onclick="printBusinessCard()" class="print-btn">
                    <i class="fas fa-print"></i> PRINT BUSINESS CARD
                </button>
            </div>

            <!-- Right Panel -->
            <div class="right-panel">
                <div class="card">
                    <div class="qr-title">
                        <i class="fas fa-layer-group"></i>
                        MULTIPLE QR CODES
                    </div>
                    <div id="multipleQrContainer"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit QR Modal -->
    <div class="modal fade" id="editQrModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-xl modal-fullscreen-sm-down">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        <i class="fas fa-qrcode me-2"></i>
                        Edit QR Code Links
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="main-container">
                    <!-- Add New Platform Section -->
                    <div class="add-platform-section">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">Platform Dropdown</label>
                                <select class="form-select" id="platformDropdown">
                                    <option value="">-- Select Platform --</option>
                                    <option class="form-select" value="WhatsApp">🟢 WhatsApp</option>
                                    <option class="form-select" value="Instagram">📸 Instagram</option>
                                    <option class="form-select" value="Facebook">🔵 Facebook</option>
                                    <option class="form-select" value="Telegram">✈️ Telegram</option>
                                    <option class="form-select" value="LinkedIn">💼 LinkedIn</option>
                                    <option class="form-select" value="YouTube">▶️ YouTube</option>
                                    <option class="form-select" value="Website">🌐 Website</option>
                                    <option class="form-select" value="Twitter/X">𝕏 Twitter/X</option>
                                    <option class="form-select" value="Pinterest">📌 Pinterest</option>
                                    <option class="form-select" value="Snapchat">👻 Snapchat</option>
                                    <option class="form-select" value="Messenger">💬 Messenger</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-semibold">Custom Platform Name</label>
                                <input type="text" class="form-control" id="customPlatformName"
                                    placeholder="Custom Platform Name">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-semibold">Platform URL *</label>
                                <input type="url" class="form-control" id="platformUrl"
                                    placeholder="https://example.com">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-semibold">&nbsp;</label>
                                <button type="button" class="btn-add-platform w-100" onclick="addNewPlatformRow()">
                                    <i class="fas fa-plus me-2"></i> Add Link
                                </button>
                            </div>
                        </div>
                    </div>

                    <div id="editQrLinksContainer" class="qr-links-list">
                        <!-- Dynamic rows will appear here -->
                    </div>
                </div>

                <div class="modal-footer">
                    {{-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i> Cancel
                    </button> --}}
                    <button type="button" class="btn-save" onclick="saveEditedQR()">
                        <i class="fas fa-save me-2"></i> Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="toast"></div>

    <!-- QR Code Library -->
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
        let currentEditingQR = null;

        function showToast(message, type = 'success') {
            let toast = document.getElementById('toast');
            toast.textContent = message;
            toast.style.backgroundColor = type === 'success' ? '#28a745' : '#dc3545';
            toast.style.display = 'block';
            setTimeout(() => {
                toast.style.display = 'none';
            }, 3000);
        }

        function copyToClipboard(text) {
            if (!text) {
                showToast('Nothing to copy!', 'error');
                return;
            }
            navigator.clipboard.writeText(text).then(function() {
                showToast('Link copied to clipboard!');
            });
        }

        function escapeHtml(str) {
            if (!str) return '';
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        }

        function getPlatformIcon(platform) {
            platform = platform.toLowerCase();
            const icons = {
                whatsapp: '<i class="fab fa-whatsapp"></i>',
                telegram: '<i class="fab fa-telegram"></i>',
                instagram: '<i class="fab fa-instagram"></i>',
                facebook: '<i class="fab fa-facebook"></i>',
                youtube: '<i class="fab fa-youtube"></i>',
                linkedin: '<i class="fab fa-linkedin"></i>',
                twitter: '<i class="fab fa-twitter"></i>',
                x: '<i class="fab fa-x-twitter"></i>',
                tiktok: '<i class="fab fa-tiktok"></i>',
                snapchat: '<i class="fab fa-snapchat"></i>',
                reddit: '<i class="fab fa-reddit"></i>',
                discord: '<i class="fab fa-discord"></i>',
                pinterest: '<i class="fab fa-pinterest"></i>',
                twitch: '<i class="fab fa-twitch"></i>',
                quora: '<i class="fab fa-quora"></i>',
                github: '<i class="fab fa-github"></i>',
                spotify: '<i class="fab fa-spotify"></i>',
                medium: '<i class="fab fa-medium"></i>',
                stackoverflow: '<i class="fab fa-stack-overflow"></i>',
                behance: '<i class="fab fa-behance"></i>',
                dribbble: '<i class="fab fa-dribbble"></i>',
                flickr: '<i class="fab fa-flickr"></i>',
                soundcloud: '<i class="fab fa-soundcloud"></i>',
                vimeo: '<i class="fab fa-vimeo"></i>',
                vk: '<i class="fab fa-vk"></i>',
                weibo: '<i class="fab fa-weibo"></i>',
                tumblr: '<i class="fab fa-tumblr"></i>',
                foursquare: '<i class="fab fa-foursquare"></i>',
                slack: '<i class="fab fa-slack"></i>',
                skype: '<i class="fab fa-skype"></i>',
                viber: '<i class="fab fa-viber"></i>',
                messenger: '<i class="fab fa-facebook-messenger"></i>',
                threads: '<i class="fab fa-threads"></i>',
                rumble: '<i class="fas fa-video"></i>'
            };
            return icons[platform] || '<img src="/images/cm_logo.png" style="width:22px;height:22px;">';
        }

        function saveQuickLink(platformKey, platformName) {
            let urlInput = document.getElementById(platformKey);
            let platformUrl = urlInput.value;
            if (!platformUrl) {
                showToast('Please enter a valid URL for ' + platformName, 'error');
                return;
            }
            let btn = event.target.closest('button');
            let originalHtml = btn.innerHTML;
            btn.innerHTML = '<div class="loading-spinner"></div>';
            btn.disabled = true;

            fetch('{{ route('user.social.quick.update') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        platform_name: platformName,
                        platform_url: platformUrl,
                        platform_key: platformKey
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast(platformName + ' link saved successfully!');
                    } else {
                        showToast(data.message || 'Error saving link', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Error saving link', 'error');
                })
                .finally(() => {
                    btn.innerHTML = originalHtml;
                    btn.disabled = false;
                });
        }

        function addCustomPlatform() {
            let platformName = document.getElementById('newPlatformName').value.trim();
            let platformUrl = document.getElementById('newPlatformUrl').value.trim();

            if (!platformName || !platformUrl) {
                showToast('Please fill both fields!', 'error');
                return;
            }

            let btn = event.target;
            let originalHtml = btn.innerHTML;
            btn.innerHTML = '<div class="loading-spinner"></div>';
            btn.disabled = true;

            fetch('{{ route('user.social.links.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        platform_name: platformName,
                        platform_url: platformUrl
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('Platform added successfully!');
                        document.getElementById('newPlatformName').value = '';
                        document.getElementById('newPlatformUrl').value = '';
                        addNewLinkToContainer(data.link);
                    } else {
                        showToast(data.message || 'Error adding platform', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Error adding platform', 'error');
                })
                .finally(() => {
                    btn.innerHTML = originalHtml;
                    btn.disabled = false;
                });
        }

        function addNewLinkToContainer(link) {
            const container = document.getElementById('customLinksContainer');
            if (!container) return;

            const newLinkHtml = `
            <div class="app-link quick-link-item" id="link-row-${link.id}">
                <input type="checkbox" class="qr-platform-checkbox"
                       data-platform="${escapeHtml(link.platform_name)}"
                       data-input="url_${link.id}">
                <div class="platform-icon">
                    ${getPlatformIcon(link.platform_name)}
                </div>
                <strong>${escapeHtml(link.platform_name)}:</strong>
                <input type="url" id="url_${link.id}" class="link-input"
                       value="${escapeHtml(link.platform_url)}" placeholder="https://...">
                <button onclick="saveCustomLink(${link.id}, '${escapeHtml(link.platform_name)}')" class="save-btn-sm">
                    <i class="fas fa-save"></i> Save
                </button>
                <button onclick="copyToClipboard(document.getElementById('url_${link.id}').value)" class="copy-btn-sm">
                    <i class="fas fa-copy"></i> Copy
                </button>
                <button onclick="deleteCustomLink(${link.id})" class="save-btn-sm" style="background:#dc3545;">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </div>`;

            container.insertAdjacentHTML('beforeend', newLinkHtml);
        }

        function printBusinessCard() {
            window.open('{{ route('user.social.print') }}', '_blank');
        }

        document.addEventListener('DOMContentLoaded', function() {
            renderMultipleQRs(@json($multiQrs));
        });

        function addMultipleQR() {
            let checkboxes = document.querySelectorAll('.qr-platform-checkbox');
            let selectedLinks = [];
            checkboxes.forEach(function(box) {
                if (box.checked) {
                    let platform = box.getAttribute('data-platform');
                    let inputId = box.getAttribute('data-input');
                    let url = document.getElementById(inputId).value;
                    if (url) {
                        selectedLinks.push({
                            platform: platform,
                            url: url
                        });
                    }
                }
            });

            if (selectedLinks.length === 0) {
                showToast('Please select at least one platform', 'error');
                return;
            }

            let btn = event.target;
            let originalHtml = btn.innerHTML;
            btn.innerHTML = '<div class="loading-spinner"></div>';
            btn.disabled = true;

            fetch('{{ route('save.multiple.qr') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        links: selectedLinks
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('QR Code Generated Successfully!');
                        renderMultipleQRs(data.all_qrs);
                        checkboxes.forEach(function(box) {
                            box.checked = false;
                        });
                    } else {
                        showToast(data.message || 'Error generating QR', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Error generating QR', 'error');
                })
                .finally(() => {
                    btn.innerHTML = originalHtml;
                    btn.disabled = false;
                });
        }

        const qrPlatformIcons = {
            whatsapp: 'fab fa-whatsapp',
            telegram: 'fab fa-telegram',
            instagram: 'fab fa-instagram',
            facebook: 'fab fa-facebook',
            youtube: 'fab fa-youtube',
            linkedin: 'fab fa-linkedin',
            x: 'fab fa-x-twitter',
            twitter: 'fab fa-twitter',
            snapchat: 'fab fa-snapchat',
            reddit: 'fab fa-reddit',
            discord: 'fab fa-discord',
            pinterest: 'fab fa-pinterest',
            quora: 'fab fa-quora',
            messenger: 'fab fa-facebook-messenger',
            twitch: 'fab fa-twitch',
            rumble: 'fas fa-video',
            viber: 'fab fa-viber',
            threads: 'fab fa-threads'
        };
        const customPlatformLogo = '/images/cm_logo.png';

        function renderMultipleQRs(qrs) {
            let container = document.getElementById('multipleQrContainer');
            if (!container) return;
            container.innerHTML = '';
            if (!qrs || qrs.length === 0) {
                container.innerHTML = `
                <div style="text-align:center; color:#999; padding:20px;">
                    No QR codes generated yet. Select platforms and click "Generate QR Code" to create one.
                </div>
                `;
                return;
            }

            qrs.forEach(function(qr) {
                let platformIconsHtml = '';
                if (qr.links) {
                    qr.links.forEach(function(link) {
                        let platformName = link.platform.toLowerCase();
                        let iconClass = qrPlatformIcons[platformName];
                        if (iconClass) {
                            platformIconsHtml += `
                            <i class="${iconClass}"
                            title="${link.platform}"
                            style="font-size:22px; margin:5px; color:#667eea;">
                            </i>
                            `;
                        } else {
                            platformIconsHtml += `
                                <img src="${customPlatformLogo}"
                                    title="${link.platform}"
                                    style="width:24px;height:24px;object-fit:contain;margin:5px;">
                            `;
                        }
                    });
                }

                let qrBox = document.createElement('div');
                qrBox.style.marginBottom = '40px';
                qrBox.style.borderBottom = '1px solid #f0f0f0';
                qrBox.style.paddingBottom = '20px';

                let qrId = 'qr_' + qr.id;

                qrBox.innerHTML = `
                    <div style="text-align:center; margin-bottom:10px;">
                        <input type="text" class="QR_name"
                               value="${qr.title ?? ''}"
                               placeholder="QR Name"
                               onblur="updateQRTitle('${qr.id}', this.value)">
                    </div>
                    <div id="${qrId}" style="display:flex; justify-content:center; margin-bottom:15px;"></div>
                    <div style="text-align:center; margin-top:15px;">
                        <button onclick='openEditQRModal(${JSON.stringify(qr)})'
                                class="btn-add"
                                style="padding:8px 15px; font-size:13px; margin-right:8px;">
                            <i class="fas fa-edit"></i> Edit QR
                        </button>
                        <button onclick="deleteQR('${qr.id}')"
                                style="padding:8px 15px; font-size:13px; background:#dc3545; color:#fff; border:none; border-radius:5px; cursor:pointer;">
                            <i class="fas fa-trash"></i> Delete QR
                        </button>
                    </div>
                    <div style="text-align:center; margin-bottom:15px;">
                        ${platformIconsHtml}
                    </div>
                    <div onclick="copyQrLink(this)" data-link="${window.location.origin}/multi-qr/{{ Auth::id() }}/${qr.id}"
                        style="text-align:center; font-size:12px; word-break:break-all; margin-bottom:15px; background:#f8f9fa; padding:10px; border-radius:8px; cursor:pointer;">
                        <i class="fas fa-link"></i>
                        ${window.location.origin}/multi-qr/{{ Auth::id() }}/${qr.id}
                    </div>
                    <div class="stats">
                        <div class="stat-box">
                            <div class="stat-number">${qr.qr_scans ?? 0}</div>
                            <div class="stat-label">QR Scans</div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-number">${qr.button_clicks ?? 0}</div>
                            <div class="stat-label">Button Clicks</div>
                        </div>
                    </div>
                `;

                container.appendChild(qrBox);

                new QRCode(document.getElementById(qrId), {
                    text: `${window.location.origin}/multi-qr/{{ Auth::id() }}/${qr.id}`,
                    width: 180,
                    height: 180
                });
            });
        }

        function openEditQRModal(qr) {
            currentEditingQR = qr;
            let container = document.getElementById('editQrLinksContainer');
            if (!container) return;

            container.innerHTML = '';

            if (qr.links && qr.links.length > 0) {
                qr.links.forEach(function(link) {
                    addEditableRow(link.platform, link.url);
                });
            }

            let modal = new bootstrap.Modal(document.getElementById('editQrModal'));
            modal.show();
        }

        function addEditableRow(platform = '', url = '') {
            let container = document.getElementById('editQrLinksContainer');
            if (!container) return;

            let platformIcon = getPlatformIcon(platform);

            let rowDiv = document.createElement('div');
            rowDiv.className = 'row g-2 mb-3 platform-row align-items-center';

            rowDiv.innerHTML = `
                <div class="col-md-4">
                    <div class="platform-cell">
                        <div class="platform-icon">
                            ${platformIcon}
                        </div>
                        <input type="text"
                               class="form-control edit-platform-name"
                               value="${escapeHtml(platform)}"
                               placeholder="Platform Name"
                               style="margin-left: 10px;">
                    </div>
                </div>
                <div class="col-md-6">
                    <input type="url"
                        class="form-control edit-platform-url"
                        value="${escapeHtml(url)}"
                        placeholder="https://example.com">
                </div>

                <div class="col-md-1 col-6">
                    <button type="button"
                            class="btn-action btn-remove"
                            onclick="removePlatformRow(this)">
                        <i class="fas fa-trash me-1"></i>
                    </button>
                </div>

                <div class="col-md-1 col-6">
                    <button type="button"
                            class="btn-action btn-copy"
                            onclick="copyPlatformRow(this)">
                        <i class="fas fa-copy me-1"></i>
                    </button>
                </div>
            `;

            container.appendChild(rowDiv);
        }

        function addNewPlatformRow() {
            let dropdown = document.getElementById('platformDropdown').value;
            let customName = document.getElementById('customPlatformName').value.trim();
            let url = document.getElementById('platformUrl').value.trim();

            let platform = dropdown || customName;

            if (!platform) {
                showToast('Please select or enter a platform name', 'error');
                return;
            }

            if (!url) {
                showToast('Please enter a platform URL', 'error');
                return;
            }

            addEditableRow(platform, url);

            document.getElementById('platformDropdown').value = '';
            document.getElementById('customPlatformName').value = '';
            document.getElementById('platformUrl').value = '';

            showToast('Platform added to edit list', 'success');
        }

        function removePlatformRow(button) {
            if (button && button.closest) {
                let row = button.closest('.platform-row');
                if (row) {
                    row.remove();
                    showToast('Platform removed', 'success');
                }
            }
        }

        function copyPlatformRow(button) {
            if (button && button.closest) {
                let row = button.closest('.platform-row');
                if (row) {
                    let urlInput = row.querySelector('.edit-platform-url');
                    if (urlInput && urlInput.value) {
                        navigator.clipboard.writeText(urlInput.value)
                            .then(() => {
                                showToast('URL copied to clipboard!');
                            })
                            .catch(() => {
                                showToast('Failed to copy URL', 'error');
                            });
                    } else {
                        showToast('No URL to copy', 'error');
                    }
                }
            }
        }

        function saveEditedQR() {
            let platformNames = document.querySelectorAll('.edit-platform-name');
            let urls = document.querySelectorAll('.edit-platform-url');
            let updatedLinks = [];

            for (let i = 0; i < platformNames.length; i++) {
                let platform = platformNames[i].value.trim();
                let url = urls[i].value.trim();

                if (platform && url) {
                    updatedLinks.push({
                        platform: platform,
                        url: url
                    });
                } else if (platform && !url) {
                    showToast(`Please enter URL for ${platform}`, 'error');
                    return;
                }
            }

            if (updatedLinks.length === 0) {
                showToast('Please add at least one valid link', 'error');
                return;
            }

            let saveBtn = document.querySelector('#editQrModal .btn-save');
            let originalHtml = saveBtn.innerHTML;
            saveBtn.innerHTML = '<div class="loading-spinner"></div> Saving...';
            saveBtn.disabled = true;

            fetch('{{ route('update-multi-qr') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        qr_id: currentEditingQR.id,
                        links: updatedLinks
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('QR Updated Successfully!');
                        renderMultipleQRs(data.all_qrs);
                        let modal = bootstrap.Modal.getInstance(document.getElementById('editQrModal'));
                        if (modal) {
                            modal.hide();
                        }
                    } else {
                        showToast(data.message || 'Error updating QR', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Error updating QR code', 'error');
                })
                .finally(() => {
                    saveBtn.innerHTML = originalHtml;
                    saveBtn.disabled = false;
                });
        }

        function copyQrLink(element) {
            const link = element.dataset.link;
            navigator.clipboard.writeText(link)
                .then(() => {
                    showToast('Link copied successfully');
                    const original = element.innerHTML;
                    element.innerHTML = '<i class="fas fa-check text-success"></i> Copied!';
                    setTimeout(() => {
                        element.innerHTML = original;
                    }, 2000);
                })
                .catch(() => {
                    showToast('Failed to copy link', 'error');
                });
        }

        function updateQRTitle(qrId, title) {
            fetch('{{ route('update-multi-qr-title') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        qr_id: qrId,
                        title: title
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('QR Title Saved');
                    }
                })
                .catch(error => {
                    console.error(error);
                });
        }

        function deleteQR(qrId) {
            Swal.fire({
                title: 'Delete QR Code?',
                text: "This QR code will be permanently deleted.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('multi-qr-destroy', ':id') }}".replace(':id', qrId),
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: 'QR Code deleted successfully.',
                                timer: 1500,
                                showConfirmButton: false
                            });
                            loadUserQRs();
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Unable to delete QR code.'
                            });
                        }
                    });
                }
            });
        }

        function loadUserQRs() {
            $.ajax({
                url: "{{ route('get-multi-qr-codes') }}",
                type: "GET",
                success: function(response) {
                    if (response.success) {
                        renderMultipleQRs(response.qrs);
                    }
                },
                error: function(xhr) {
                    console.error('Failed to load QRs', xhr);
                }
            });
        }

        function deleteCustomLink(linkId) {
            Swal.fire({
                title: 'Delete Link?',
                text: "This link will be permanently deleted.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('user-social-links-destroy', ':id') }}".replace(':id', linkId),
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: 'Link deleted successfully.',
                                timer: 1500,
                                showConfirmButton: false
                            });
                            document.getElementById(`link-row-${linkId}`).remove();
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Unable to delete link.'
                            });
                        }
                    });
                }
            });
        }
    </script>
@endsection
