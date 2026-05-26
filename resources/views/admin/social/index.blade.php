@extends('admin.layouts.layout')

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
            gap: 10px;
            flex: 1;
        }

        .add-platform input {
            flex: 1;
            padding: 10px 15px;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            font-size: 14px;
        }

        .add-platform input:focus {
            outline: none;
            border-color: #667eea;
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

        /* .share-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin: 20px 0;
        } */

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
                <div class="section-title">
                    <i class="fas fa-link"></i> MY SOCIAL LINKS MANAGER
                </div>

                <!-- Social Links Table -->
                <div style="overflow-x: auto;">
                    <table class="links-table" id="linksTable">
                        <thead>
                            <tr>
                                <th>PLATFORM</th>
                                <th>ACTIVE LINK</th>
                                <th>ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody id="linksTableBody">
                            @foreach ($socialLinks as $link)
                                <tr id="link-row-{{ $link->id }}" data-icon-type="{{ $link->icon_type }}">
                                    <td>
                                        <div class="platform-cell">
                                            <div class="platform-icon">
                                                {!! getPlatformIconHtml($link) !!}
                                            </div>
                                            <span class="platform-name">{{ $link->platform_name }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="url" id="url_{{ $link->id }}" class="link-url-input"
                                            value="{{ $link->platform_url }}">
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button
                                                onclick="copyToClipboard(document.getElementById('url_{{ $link->id }}').value)"
                                                class="copy-btn" title="Copy Link">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                            <button onclick="saveLink(this, {{ $link->id }})" class="save-btn"
                                                data-url="{{ route('social-links-update', $link->id) }}"
                                                title="Save Changes">
                                                <i class="fas fa-save"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Editable App Links Section -->
                <div style="margin-top: 20px;">
                    <div class="section-title">
                        <i class="fas fa-star"></i> QUICK LINKS
                    </div>

                    {{-- WhatsApp --}}
                    <div class="app-link">
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
                    <div class="app-link">
                        <i class="fab fa-telegram" style="color: #0088cc;"></i>
                        <strong>Telegram:</strong>
                        <input type="url" id="telegram_url" placeholder="https://t.me/username" class="link-input"
                            value="{{ $quickLinks['telegram_url'] ?? '' }}">
                        <button onclick="saveQuickLink('telegram_url', 'Telegram')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('telegram_url').value)"
                            class="copy-btn-sm">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>

                    {{-- Facebook --}}
                    <div class="app-link">
                        <i class="fab fa-facebook" style="color: #1877f2;"></i>
                        <strong>Facebook:</strong>
                        <input type="url" id="facebook_url" placeholder="https://facebook.com/username"
                            class="link-input" value="{{ $quickLinks['facebook_url'] ?? '' }}">
                        <button onclick="saveQuickLink('facebook_url', 'Facebook')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('facebook_url').value)"
                            class="copy-btn-sm">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>

                    {{-- YouTube --}}
                    <div class="app-link">
                        <i class="fab fa-youtube" style="color: #ff0000;"></i>
                        <strong>YouTube:</strong>
                        <input type="url" id="youtube_url" placeholder="https://youtube.com/@username"
                            class="link-input" value="{{ $quickLinks['youtube_url'] ?? '' }}">
                        <button onclick="saveQuickLink('youtube_url', 'YouTube')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('youtube_url').value)" class="copy-btn-sm">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>

                    {{-- LinkedIn --}}
                    <div class="app-link">
                        <i class="fab fa-linkedin" style="color: #0077b5;"></i>
                        <strong>LinkedIn:</strong>
                        <input type="url" id="linkedin_url" placeholder="https://linkedin.com/in/username"
                            class="link-input" value="{{ $quickLinks['linkedin_url'] ?? '' }}">
                        <button onclick="saveQuickLink('linkedin_url', 'LinkedIn')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('linkedin_url').value)"
                            class="copy-btn-sm">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>

                    {{-- Instagram --}}
                    <div class="app-link">
                        <i class="fab fa-instagram" style="color: #E4405F;"></i>
                        <strong>Instagram:</strong>
                        <input type="url" id="instagram_url" placeholder="https://instagram.com/username"
                            class="link-input" value="{{ $quickLinks['instagram_url'] ?? '' }}">
                        <button onclick="saveQuickLink('instagram_url', 'Instagram')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('instagram_url').value)"
                            class="copy-btn-sm">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>

                    {{-- X --}}
                    <div class="app-link">
                        <i class="fab fa-x-twitter" style="color: #000;"></i>
                        <strong>X:</strong>
                        <input type="url" id="x_url" placeholder="https://x.com/username" class="link-input"
                            value="{{ $quickLinks['x_url'] ?? '' }}">
                        <button onclick="saveQuickLink('x_url', 'X')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('x_url').value)" class="copy-btn-sm">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>

                    {{-- Threads --}}
                    <div class="app-link">
                        <i class="fab fa-threads" style="color: #000;"></i>
                        <strong>Threads:</strong>
                        <input type="url" id="threads_url" placeholder="https://threads.net/@username"
                            class="link-input" value="{{ $quickLinks['threads_url'] ?? '' }}">
                        <button onclick="saveQuickLink('threads_url', 'Threads')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('threads_url').value)"
                            class="copy-btn-sm">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>

                    {{-- Snapchat --}}
                    <div class="app-link">
                        <i class="fab fa-snapchat" style="color: #FFFC00;"></i>
                        <strong>Snapchat:</strong>
                        <input type="url" id="snapchat_url" placeholder="https://snapchat.com/add/username"
                            class="link-input" value="{{ $quickLinks['snapchat_url'] ?? '' }}">
                        <button onclick="saveQuickLink('snapchat_url', 'Snapchat')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('snapchat_url').value)"
                            class="copy-btn-sm">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>

                    {{-- Reddit --}}
                    <div class="app-link">
                        <i class="fab fa-reddit" style="color: #FF4500;"></i>
                        <strong>Reddit:</strong>
                        <input type="url" id="reddit_url" placeholder="https://reddit.com/user/username"
                            class="link-input" value="{{ $quickLinks['reddit_url'] ?? '' }}">
                        <button onclick="saveQuickLink('reddit_url', 'Reddit')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('reddit_url').value)"
                            class="copy-btn-sm">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>

                    {{-- Discord --}}
                    <div class="app-link">
                        <i class="fab fa-discord" style="color: #5865F2;"></i>
                        <strong>Discord:</strong>
                        <input type="url" id="discord_url" placeholder="https://discord.gg/yourserver"
                            class="link-input" value="{{ $quickLinks['discord_url'] ?? '' }}">
                        <button onclick="saveQuickLink('discord_url', 'Discord')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('discord_url').value)"
                            class="copy-btn-sm">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>

                    {{-- Pinterest --}}
                    <div class="app-link">
                        <i class="fab fa-pinterest" style="color: #E60023;"></i>
                        <strong>Pinterest:</strong>
                        <input type="url" id="pinterest_url" placeholder="https://pinterest.com/username"
                            class="link-input" value="{{ $quickLinks['pinterest_url'] ?? '' }}">
                        <button onclick="saveQuickLink('pinterest_url', 'Pinterest')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('pinterest_url').value)"
                            class="copy-btn-sm">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>

                    {{-- Quora --}}
                    <div class="app-link">
                        <i class="fab fa-quora" style="color: #B92B27;"></i>
                        <strong>Quora:</strong>
                        <input type="url" id="quora_url" placeholder="https://quora.com/profile/username"
                            class="link-input" value="{{ $quickLinks['quora_url'] ?? '' }}">
                        <button onclick="saveQuickLink('quora_url', 'Quora')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('quora_url').value)" class="copy-btn-sm">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>

                    {{-- Messenger --}}
                    <div class="app-link">
                        <i class="fab fa-facebook-messenger" style="color: #0084FF;"></i>
                        <strong>Messenger:</strong>
                        <input type="url" id="messenger_url" placeholder="https://m.me/username" class="link-input"
                            value="{{ $quickLinks['messenger_url'] ?? '' }}">
                        <button onclick="saveQuickLink('messenger_url', 'Messenger')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('messenger_url').value)"
                            class="copy-btn-sm">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>

                    {{-- Twitch --}}
                    <div class="app-link">
                        <i class="fab fa-twitch" style="color: #9146FF;"></i>
                        <strong>Twitch:</strong>
                        <input type="url" id="twitch_url" placeholder="https://twitch.tv/username"
                            class="link-input" value="{{ $quickLinks['twitch_url'] ?? '' }}">
                        <button onclick="saveQuickLink('twitch_url', 'Twitch')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('twitch_url').value)"
                            class="copy-btn-sm">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>

                    {{-- Rumble --}}
                    <div class="app-link">
                        <i class="fas fa-video" style="color: #85C742;"></i>
                        <strong>Rumble:</strong>
                        <input type="url" id="rumble_url" placeholder="https://rumble.com/user/username"
                            class="link-input" value="{{ $quickLinks['rumble_url'] ?? '' }}">
                        <button onclick="saveQuickLink('rumble_url', 'Rumble')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('rumble_url').value)"
                            class="copy-btn-sm">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>

                    {{-- Viber --}}
                    <div class="app-link">
                        <i class="fab fa-viber" style="color: #7360F2;"></i>
                        <strong>Viber:</strong>
                        <input type="url" id="viber_url" placeholder="https://invite.viber.com/?g=group"
                            class="link-input" value="{{ $quickLinks['viber_url'] ?? '' }}">
                        <button onclick="saveQuickLink('viber_url', 'Viber')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('viber_url').value)" class="copy-btn-sm">
                            <i class="fas fa-copy"></i> Copy
                        </button>
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
                <!-- QR Card -->
                <div class="card qr-card">
                    <div class="qr-title">
                        <i class="fas fa-qrcode"></i> DYNAMIC QR CODE
                    </div>
                    <div class="qr-code" id="qrCodeContainer">
                        <div id="qrPlaceholder" style="color: #999;">Loading QR Code...</div>
                    </div>
                    <div class="profile-url" id="profileUrl">
                        <i class="fas fa-link"></i> {{ $profileUrl }}
                    </div>
                    <div class="qr-title">
                        <i class="fas fa-share-alt"></i> SHARE DIGITALLY
                    </div>
                    {{-- <div class="share-buttons">
                        <a href="https://wa.me/?text={{ urlencode($profileUrl) }}" target="_blank"
                            class="share-btn whatsapp-share">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        <a href="https://t.me/share/url?url={{ urlencode($profileUrl) }}" target="_blank"
                            class="share-btn telegram-share">
                            <i class="fab fa-telegram"></i>
                        </a>
                        <a href="mailto:?subject=My Digital Business Card&body={{ urlencode($profileUrl) }}"
                            class="share-btn email-share">
                            <i class="fas fa-envelope"></i>
                        </a>
                    </div> --}}
                </div>

                <!-- Analytics -->
                <div class="card">
                    <div class="qr-title">
                        <i class="fas fa-chart-line"></i> ANALYTICS
                    </div>
                    <div class="stats">
                        <div class="stat-box">
                            <div class="stat-number" id="qrScans">{{ $qrScans ?? 0 }}</div>
                            <div class="stat-label">QR Scans (30 days)</div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-number" id="btnClicks">{{ $btnClicks ?? 0 }}</div>
                            <div class="stat-label">Button Clicks</div>
                        </div>
                    </div>
                    <div class="stat-box" style="margin-top: 15px;">
                        <div class="stat-number" id="totalLinks">{{ $socialLinks->count() }}</div>
                        <div class="stat-label">Total Platforms</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="toast"></div>

    <!-- QR Code Library -->
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
        let currentQRCode = null;

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

        function generateDynamicQRCode(profileUrl) {
            const qrContainer = document.getElementById('qrCodeContainer');
            if (!qrContainer) return;
            qrContainer.innerHTML = '';
            if (!profileUrl) {
                const profileUrlElement = document.getElementById('profileUrl');
                if (profileUrlElement) {
                    let text = profileUrlElement.innerText;
                    profileUrl = text.replace('🔗', '').replace('fa-link', '').trim();
                }
            }
            if (!profileUrl) {
                profileUrl = window.location.origin + '/profile';
            }
            try {
                currentQRCode = new QRCode(qrContainer, {
                    text: profileUrl,
                    width: 180,
                    height: 180,
                    colorDark: "#000000",
                    colorLight: "#ffffff",
                    correctLevel: QRCode.CorrectLevel.H
                });
            } catch (error) {
                console.error('QR Code generation error:', error);
                qrContainer.innerHTML = '<div style="color: red;">Error generating QR Code</div>';
            }
        }

        // Function to get platform icon based on stored icon_type
        function getPlatformIconFromType(platformName, iconType, platformId = null) {
            const iconMap = {
                'whatsapp': 'fab fa-whatsapp',
                'telegram': 'fab fa-telegram',
                'instagram': 'fab fa-instagram',
                'facebook': 'fab fa-facebook',
                'youtube': 'fab fa-youtube',
                'linkedin': 'fab fa-linkedin',
                'twitter': 'fab fa-twitter',
                'x': 'fab fa-x-twitter',
                'tiktok': 'fab fa-tiktok',
                'snapchat': 'fab fa-snapchat',
                'reddit': 'fab fa-reddit',
                'discord': 'fab fa-discord',
                'pinterest': 'fab fa-pinterest',
                'twitch': 'fab fa-twitch',
                'quora': 'fab fa-quora',
                'github': 'fab fa-github',
                'spotify': 'fab fa-spotify',
                'medium': 'fab fa-medium',
                'stackoverflow': 'fab fa-stack-overflow',
                'behance': 'fab fa-behance',
                'dribbble': 'fab fa-dribbble',
                'flickr': 'fab fa-flickr',
                'soundcloud': 'fab fa-soundcloud',
                'vimeo': 'fab fa-vimeo',
                'vk': 'fab fa-vk',
                'weibo': 'fab fa-weibo',
                'tumblr': 'fab fa-tumblr',
                'foursquare': 'fab fa-foursquare',
                'slack': 'fab fa-slack',
                'skype': 'fab fa-skype',
                'viber': 'fab fa-viber',
                'messenger': 'fab fa-facebook-messenger',
                'threads': 'fab fa-threads',
                'rumble': 'fas fa-video'
            };
            const platformLower = platformName.toLowerCase();
            if (iconType === 'custom') {
                return `<img src="/images/cm_logo.png" alt="${escapeHtml(platformName)}" style="width: 24px; height: 24px; object-fit: contain;">`;
            }
            if (iconMap[platformLower]) {
                return `<i class="${iconMap[platformLower]}"></i>`;
            }
            return `<img src="/images/cm_logo.png" alt="${escapeHtml(platformName)}" style="width: 24px; height: 24px; object-fit: contain;">`;
        }

        function escapeHtml(str) {
            if (!str) return '';
            return str.replace(/[&<>]/g, function(m) {
                if (m === '&') return '&amp;';
                if (m === '<') return '&lt;';
                if (m === '>') return '&gt;';
                return m;
            });
        }

        function saveLink(btn, id) {
            let url = document.getElementById('url_' + id).value;
            if (!url) {
                showToast('Please enter a valid URL', 'error');
                return;
            }

            // Server-side se bana-banaya clean URL uthayein
            let fetchUrl = btn.getAttribute('data-url');

            let originalHtml = btn.innerHTML;
            btn.innerHTML = '<div class="loading-spinner"></div>';
            btn.disabled = true;

            // Ab fetchUrl bina kisi replace hack ke chalega
            fetch(fetchUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        platform_url: url
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        // Agar server se 404, 500 ya koi aur error aaye toh handle karein
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        showToast('Link saved successfully!');
                        if (data.totalLinks) document.getElementById('totalLinks').innerText = data.totalLinks;
                        if (data.profile_url) {
                            document.getElementById('profileUrl').innerHTML = '<i class="fas fa-link"></i> ' + data
                                .profile_url;
                            generateDynamicQRCode(data.profile_url);
                            updateShareButtons(data.profile_url);
                        }
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
            fetch('{{ route('social.quick.update') }}', {
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
                        location.reload();
                        if (data.totalLinks) document.getElementById('totalLinks').innerText = data.totalLinks;
                        if (data.profile_url) {
                            document.getElementById('profileUrl').innerHTML = '<i class="fas fa-link"></i> ' + data
                                .profile_url;
                            generateDynamicQRCode(data.profile_url);
                            updateShareButtons(data.profile_url);
                        }
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
            fetch('{{ route('social.links.store') }}', {
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
                        addLinkRowToTable(data.link);
                        if (data.totalLinks) document.getElementById('totalLinks').innerText = data.totalLinks;
                        if (data.profile_url) {
                            document.getElementById('profileUrl').innerHTML = '<i class="fas fa-link"></i> ' + data
                                .profile_url;
                            generateDynamicQRCode(data.profile_url);
                            updateShareButtons(data.profile_url);
                        }
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

        function addLinkRowToTable(link) {
            const tbody = document.getElementById('linksTableBody');
            const newRow = document.createElement('tr');
            newRow.id = `link-row-${link.id}`;
            newRow.setAttribute('data-icon-type', link.icon_type || 'custom');
            const platformIconHtml = getPlatformIconFromType(link.platform_name, link.icon_type || 'custom', link.id);
            newRow.innerHTML = `
            <td>
                <div class="platform-cell">
                    <div class="platform-icon">
                        ${platformIconHtml}
                    </div>
                    <span class="platform-name">${escapeHtml(link.platform_name)}</span>
                </div>
            </td>
            <td>
                <input type="url" id="url_${link.id}" class="link-url-input" value="${escapeHtml(link.platform_url)}">
            </td>
            <td>
                <div class="action-buttons">
                    <button onclick="copyToClipboard(document.getElementById('url_${link.id}').value)" class="copy-btn" title="Copy Link">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
            </td>
        `;
            tbody.appendChild(newRow);
        }



        function updateShareButtons(profileUrl) {
            const encodedUrl = encodeURIComponent(profileUrl);
            const whatsappBtn = document.querySelector('.whatsapp-share');
            const telegramBtn = document.querySelector('.telegram-share');
            const emailBtn = document.querySelector('.email-share');
            if (whatsappBtn) whatsappBtn.href = `https://wa.me/?text=${encodedUrl}`;
            if (telegramBtn) telegramBtn.href = `https://t.me/share/url?url=${encodedUrl}`;
            if (emailBtn) emailBtn.href = `mailto:?subject=My Digital Business Card&body=${encodedUrl}`;
        }

        function printBusinessCard() {
            window.open('{{ route('social.print') }}', '_blank');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const profileUrlElement = document.getElementById('profileUrl');
            let initialProfileUrl = profileUrlElement ? profileUrlElement.innerText.replace(/[^\x00-\x7F]/g, '')
                .trim() : window.location.origin + '/profile';
            initialProfileUrl = initialProfileUrl.replace('🔗', '').trim();
            if (initialProfileUrl) {
                generateDynamicQRCode(initialProfileUrl);
            }
        });
    </script>
@endsection


