<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $user->name }} - Business Card</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #e0e0e0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        /* Business Card Container */
        .business-card {
            width: 350px;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            position: relative;
            page-break-after: avoid;
            break-inside: avoid;
        }

        /* Card Header with Gradient */
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px 20px;
            text-align: center;
            color: white;
            position: relative;
        }

        /* Avatar */
        .avatar {
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 50%;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            color: #667eea;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .name {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .title {
            font-size: 12px;
            opacity: 0.9;
            letter-spacing: 1px;
        }

        /* QR Code Section */
        .qr-section {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid #eee;
        }

        .qr-label {
            font-size: 10px;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 10px;
        }

        .qr-code {
            width: 120px;
            height: 120px;
            margin: 0 auto;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .qr-code svg {
            width: 100%;
            height: 100%;
        }

        .profile-url {
            font-size: 9px;
            color: #667eea;
            word-break: break-all;
            margin-top: 10px;
            padding: 5px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        /* Social Links Section */
        .social-section {
            padding: 20px;
        }

        .social-title {
            font-size: 11px;
            font-weight: 600;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
            text-align: center;
        }

        .social-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
        }

        .social-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 10px;
            background: #f8f9fa;
            border-radius: 10px;
            text-decoration: none;
            transition: all 0.3s;
            font-size: 12px;
        }

        .social-item:hover {
            background: #e9ecef;
            transform: translateX(3px);
        }

        .social-icon {
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            background: white;
            border-radius: 8px;
        }

        .social-icon i {
            font-size: 16px;
        }

        .social-icon img {
            width: 18px;
            height: 18px;
            object-fit: contain;
        }

        .social-name {
            flex: 1;
            font-size: 11px;
            font-weight: 500;
            color: #333;
        }

        /* Footer */
        .card-footer {
            padding: 15px;
            text-align: center;
            background: #f8f9fa;
            border-top: 1px solid #eee;
            font-size: 9px;
            color: #999;
        }

        /* Print Styles */
        @media print {
            body {
                background: white;
                padding: 0;
                margin: 0;
            }

            .business-card {
                box-shadow: none;
                margin: 0 auto;
                page-break-after: avoid;
                break-inside: avoid;
            }

            .social-item {
                break-inside: avoid;
            }

            .no-print {
                display: none;
            }

            @page {
                size: auto;
                margin: 0mm;
            }
        }

        /* Button Styles (Screen Only) */
        .print-btn-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
        }

        .print-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 50px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            transition: all 0.3s;
        }

        .print-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
        }

        @media print {
            .print-btn-container {
                display: none;
            }
        }

        /* Responsive */
        @media (max-width: 480px) {
            .business-card {
                width: 100%;
                max-width: 350px;
            }
        }
    </style>
</head>
<body>

<div class="business-card">
    <!-- Header Section -->
    <div class="card-header">
        <div class="avatar">
            <i class="fas fa-user-circle"></i>
        </div>
        <div class="name">{{ $user->name }}</div>
        <div class="title">Digital Business Card</div>
    </div>

    <!-- QR Code Section -->
    <div class="qr-section">
        <div class="qr-label">
            <i class="fas fa-qrcode"></i> SCAN TO CONNECT
        </div>
        <div class="qr-code" id="qrCodeContainer">
            <div style="color: #999;">Loading QR...</div>
        </div>
        <div class="profile-url" id="profileUrl">
            <i class="fas fa-link"></i> {{ $profileUrl }}
        </div>
    </div>

    <!-- Social Links Section -->
    <div class="social-section">
        <div class="social-title">
            <i class="fas fa-share-alt"></i> CONNECT WITH ME
        </div>
        <div class="social-grid" id="socialLinksContainer">
            @php
                // Merge and deduplicate social links
                $allLinks = [];

                // Add social links from database
                foreach ($socialLinks as $link) {
                    $key = strtolower($link->platform_name);
                    $allLinks[$key] = [
                        'name' => $link->platform_name,
                        'url' => $link->platform_url,
                        'icon_type' => $link->icon_type ?? 'fa'
                    ];
                }

                // Add quick links (only if not already exists)
                $quickMapping = [
                    'whatsapp_url' => 'WhatsApp',
                    'telegram_url' => 'Telegram',
                    'instagram_url' => 'Instagram',
                    'facebook_url' => 'Facebook',
                    'youtube_url' => 'YouTube',
                    'linkedin_url' => 'LinkedIn',
                    'x_url' => 'X',
                    'threads_url' => 'Threads',
                    'snapchat_url' => 'Snapchat',
                    'reddit_url' => 'Reddit',
                    'discord_url' => 'Discord',
                    'pinterest_url' => 'Pinterest',
                    'twitch_url' => 'Twitch',
                    'quora_url' => 'Quora',
                    'messenger_url' => 'Messenger',
                    'rumble_url' => 'Rumble',
                    'viber_url' => 'Viber'
                ];

                foreach ($quickMapping as $key => $name) {
                    $platformKey = strtolower($name);
                    if (isset($quickLinks[$key]) && !empty($quickLinks[$key]) && !isset($allLinks[$platformKey])) {
                        $allLinks[$platformKey] = [
                            'name' => $name,
                            'url' => $quickLinks[$key],
                            'icon_type' => 'fa'
                        ];
                    }
                }

                // Icon mapping
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
                    'viber' => 'fab fa-viber',
                    'messenger' => 'fab fa-facebook-messenger',
                    'threads' => 'fab fa-threads',
                    'rumble' => 'fas fa-video'
                ];
            @endphp

            @foreach($allLinks as $platform)
            <div class="social-item">
                <div class="social-icon">
                    @if($platform['icon_type'] === 'custom')
                        <img src="{{ asset('images/cm_logo.png') }}" alt="{{ $platform['name'] }}">
                    @else
                        @php
                            $iconClass = $iconMap[strtolower($platform['name'])] ?? 'fas fa-link';
                        @endphp
                        <i class="{{ $iconClass }}"></i>
                    @endif
                </div>
                <div class="social-name">{{ $platform['name'] }}</div>
                <div class="social-arrow" style="color: #999;">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Footer -->
    <div class="card-footer">
        <i class="fas fa-qrcode"></i> Scan QR code to save my contact
    </div>
</div>

<!-- Print Button (Screen Only) -->
<div class="print-btn-container no-print">
    <button class="print-btn" onclick="window.print()">
        <i class="fas fa-print me-2"></i> Print Business Card
    </button>
</div>

<!-- QR Code Library -->
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>

<script>
    // Generate QR Code
    const profileUrl = '{{ $profileUrl }}';
    const qrContainer = document.getElementById('qrCodeContainer');

    if (qrContainer && profileUrl) {
        qrContainer.innerHTML = '';
        new QRCode(qrContainer, {
            text: profileUrl,
            width: 120,
            height: 120,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });
    }

    // Optional: Auto-print when page loads (uncomment if needed)
    // window.addEventListener('load', function() {
    //     window.print();
    // });
</script>

</body>
</html>
