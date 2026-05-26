<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $user->name }} - Digital Business Card</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            padding: 20px;
        }

        .profile-card {
            max-width: 500px;
            margin: 0 auto;
            background: white;
            border-radius: 30px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .profile-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 20px;
            text-align: center;
        }

        .profile-avatar {
            width: 100px;
            height: 100px;
            background: white;
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 50px;
            color: #667eea;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .profile-name {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .profile-bio {
            font-size: 14px;
            opacity: 0.9;
        }

        .social-links {
            padding: 30px;
        }

        .social-link {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            margin-bottom: 15px;
            background: #f8f9fa;
            border-radius: 15px;
            text-decoration: none;
            color: #333;
            transition: all 0.3s ease;
            animation: fadeInUp 0.6s ease-out;
            animation-fill-mode: both;
        }

        .social-link:hover {
            transform: translateX(8px) scale(1.02);
            background: #e9ecef;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .social-icon {
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-right: 15px;
            background: white;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .social-link:hover .social-icon {
            transform: scale(1.1);
        }

        .social-icon i {
            font-size: 24px;
        }

        .social-icon img {
            width: 28px;
            height: 28px;
            object-fit: contain;
        }

        .social-name {
            flex: 1;
            font-weight: 600;
            font-size: 16px;
        }

        .social-arrow {
            color: #999;
            transition: all 0.3s ease;
        }

        .social-link:hover .social-arrow {
            transform: translateX(5px);
            color: #667eea;
        }

        .footer {
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #eee;
        }

        /* Icon Colors */
        .fa-whatsapp { color: #25D366; }
        .fa-telegram { color: #0088cc; }
        .fa-instagram { color: #E4405F; }
        .fa-facebook { color: #1877f2; }
        .fa-youtube { color: #ff0000; }
        .fa-linkedin { color: #0077b5; }
        .fa-twitter { color: #1DA1F2; }
        .fa-x-twitter { color: #000000; }
        .fa-tiktok { color: #000000; }
        .fa-snapchat { color: #FFFC00; }
        .fa-reddit { color: #FF4500; }
        .fa-discord { color: #5865F2; }
        .fa-pinterest { color: #E60023; }
        .fa-twitch { color: #9146FF; }
        .fa-quora { color: #B92B27; }

        @media (max-width: 768px) {
            body {
                padding: 10px;
            }
            .profile-card {
                margin: 20px auto;
            }
            .social-link {
                padding: 12px 15px;
            }
            .social-name {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="profile-card">
        <div class="profile-header">
            <div class="profile-avatar">
                <i class="fas fa-user-circle"></i>
            </div>
            <div class="profile-name">{{ $user->name }}</div>
            <div class="profile-bio">Digital Business Card</div>
        </div>

        <div class="social-links">
            @php
                // Professional: Merge and deduplicate links
                $allPlatforms = [];

                // Step 1: Add all social links from database (priority)
                foreach ($socialLinks as $link) {
                    $key = strtolower(trim($link->platform_name));
                    $allPlatforms[$key] = [
                        'name' => $link->platform_name,
                        'url' => $link->platform_url,
                        'icon_type' => $link->icon_type,
                        'source' => 'social',
                        'original' => $link
                    ];
                }

                // Step 2: Define quick links mapping (only add if not already exists)
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

                // Step 3: Add quick links only if platform doesn't exist already
                foreach ($quickMapping as $key => $name) {
                    $platformKey = strtolower($name);
                    if (isset($quickLinks[$key]) && !empty($quickLinks[$key]) && !isset($allPlatforms[$platformKey])) {
                        $allPlatforms[$platformKey] = [
                            'name' => $name,
                            'url' => $quickLinks[$key],
                            'icon_type' => 'fa',
                            'source' => 'quick',
                            'original' => null
                        ];
                    }
                }

                // Helper function to get icon HTML
                function getIconHtml($platform) {
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

                    $platformName = strtolower($platform['name']);

                    // Custom platform (added by user) - show cm_logo.png
                    if ($platform['icon_type'] === 'custom') {
                        return '<img src="' . asset('images/cm_logo.png') . '" alt="' . e($platform['name']) . '">';
                    }

                    // Predefined platform - show Font Awesome icon
                    if (isset($iconMap[$platformName])) {
                        return '<i class="' . $iconMap[$platformName] . '"></i>';
                    }

                    // Fallback
                    return '<img src="' . asset('images/cm_logo.png') . '" alt="' . e($platform['name']) . '">';
                }
            @endphp

            {{-- Display all unique platforms (no duplicates) --}}
            @foreach($allPlatforms as $platform)
            <a href="{{ $platform['url'] }}" class="social-link" target="_blank" rel="noopener noreferrer"
               onclick="trackClick('{{ addslashes($platform['name']) }}')">
                <div class="social-icon">
                    {!! getIconHtml($platform) !!}
                </div>
                <div class="social-name">{{ $platform['name'] }}</div>
                <div class="social-arrow"><i class="fas fa-arrow-right"></i></div>
            </a>
            @endforeach

            {{-- Show message if no links available --}}
            @if(count($allPlatforms) == 0)
            <div class="text-center text-muted py-4">
                <i class="fas fa-link fa-2x mb-2"></i>
                <p>No social links added yet.</p>
            </div>
            @endif
        </div>

        <div class="footer">
            <i class="fas fa-qrcode"></i> Scan to connect with me
        </div>
    </div>

    <script>
        const slug = '{{ $slug }}';
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

        function trackClick(platform) {
            // Send tracking data asynchronously
            fetch(`/profile/${slug}/track-click`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ platform: platform })
            }).catch(err => console.error('Tracking error:', err));
        }

        // Optional: Track page view for analytics
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Profile loaded:', window.location.href);
        });
    </script>
</body>
</html>
