
<head>
    @include('profile.partials.social-grid')
</head>

<body>
    <div class="profile-card">
        <div class="profile-header">
            <img src="{{ asset('images/live_QR_Back_logo.png') }}" width="100%" alt="Header Logo">
        </div>

        <div class="social-links">
            @php
                // Merge and deduplicate links
                $allPlatforms = [];

                // Add all social links from database (priority)
                foreach ($socialLinks as $link) {
                    $key = strtolower(trim($link->platform_name));
                    $allPlatforms[$key] = [
                        'name' => $link->platform_name,
                        'url' => $link->platform_url,
                        'icon_type' => $link->icon_type,
                        'source' => 'social',
                        'original' => $link,
                    ];
                }

                // Define quick links mapping
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
                    'viber_url' => 'Viber',
                ];

                // Add quick links only if platform doesn't exist already
                foreach ($quickMapping as $key => $name) {
                    $platformKey = strtolower($name);
                    if (isset($quickLinks[$key]) && !empty($quickLinks[$key]) && !isset($allPlatforms[$platformKey])) {
                        $allPlatforms[$platformKey] = [
                            'name' => $name,
                            'url' => $quickLinks[$key],
                            'icon_type' => 'fa',
                            'source' => 'quick',
                            'original' => null,
                        ];
                    }
                }

                // Helper function to get icon HTML
                function getIconHtml($platform)
                {
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
                        'rumble' => 'fas fa-video',
                    ];

                    $platformName = strtolower($platform['name']);

                    if ($platform['icon_type'] === 'custom') {
                        return '<img src="' . asset('images/cm_logo.png') . '" alt="' . e($platform['name']) . '">';
                    }

                    if (isset($iconMap[$platformName])) {
                        return '<i class="' . $iconMap[$platformName] . '"></i>';
                    }

                    return '<img src="' . asset('images/cm_logo.png') . '" alt="' . e($platform['name']) . '">';
                }
            @endphp

            {{-- 2-Column Grid Layout - One row me do box --}}
            <div class="social-links-grid">
                @foreach ($allPlatforms as $platform)
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
                @if (count($allPlatforms) == 0)
                    <div class="text-center text-muted py-4"
                        style="grid-column: span 2; background: rgba(255,255,255,0.9); border-radius: 16px;">
                        <i class="fas fa-link fa-2x mb-2"></i>
                        <p>No social links added yet.</p>
                    </div>
                @endif
            </div>
        </div>

        <script>
            const slug = '{{ $slug }}';
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

            function trackClick(platform) {
                fetch(`/profile/${slug}/track-click`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        platform: platform
                    })
                }).catch(err => console.error('Tracking error:', err));
            }

            document.addEventListener('DOMContentLoaded', function() {
                console.log('Profile loaded:', window.location.href);
            });
        </script>
</body>

</html>
