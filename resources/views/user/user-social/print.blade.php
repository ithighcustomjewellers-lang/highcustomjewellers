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

                // ===== COMPLETE QUICK LINKS MAPPING (including ecommerce & payment) =====
                $quickMapping = [
                    // Social Media
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
                    'tiktok_url' => 'TikTok',
                    'twitter_url' => 'Twitter',
                    'skype_url' => 'Skype',
                    'slack_url' => 'Slack',
                    'medium_url' => 'Medium',
                    'tumblr_url' => 'Tumblr',
                    'flickr_url' => 'Flickr',
                    'soundcloud_url' => 'SoundCloud',
                    'vimeo_url' => 'Vimeo',
                    'spotify_url' => 'Spotify',
                    'github_url' => 'GitHub',
                    'stackoverflow_url' => 'Stack Overflow',
                    'behance_url' => 'Behance',
                    'dribbble_url' => 'Dribbble',

                    // Ecommerce
                    'ebay_url' => 'eBay',
                    'amazon_url' => 'Amazon',
                    'alibaba_url' => 'Alibaba',
                    'indiamart_url' => 'IndiaMart',
                    'tradeindia_url' => 'TradeIndia',
                    'etsy_url' => 'Etsy',
                    'flipkart_url' => 'Flipkart',
                    'shopify_url' => 'Shopify',
                    'walmart_url' => 'Walmart',
                    'aliexpress_url' => 'AliExpress',
                    'meesho_url' => 'Meesho',
                    'nykaa_url' => 'Nykaa',
                    'myntra_url' => 'Myntra',
                    'snapdeal_url' => 'Snapdeal',
                    'ajio_url' => 'Ajio',

                    // Payment Gateways
                    'paypal_url' => 'PayPal',
                    'stripe_url' => 'Stripe',
                    'razorpay_url' => 'Razorpay',
                    'payoneer_url' => 'Payoneer',
                    'wise_url' => 'Wise',
                    'airwallex_url' => 'Airwallex',
                    'skydo_url' => 'Skydo',
                    'cashfree_url' => 'Cashfree',
                    'instamojo_url' => 'Instamojo',
                    'payu_url' => 'PayU India',
                    'westernunion_url' => 'Western Union',
                    'googlepay_url' => 'Google Pay',
                    'applepay_url' => 'Apple Pay',
                    'samsungpay_url' => 'Samsung Pay',
                    'phonepe_url' => 'PhonePe',
                    'paytm_url' => 'Paytm',
                    'amazonpay_url' => 'Amazon Pay',
                    'upi_url' => 'UPI',
                    'zelle_url' => 'Zelle',
                    'venmo_url' => 'Venmo',
                    'crypto_btc_url' => 'Crypto BTC',
                    'crypto_usdt_url' => 'Crypto USDT',
                    'crypto_eth_url' => 'Crypto ETH',
                    'bank_url' => 'Bank Details',

                    // Other
                    'website_url' => 'Website',
                    'blog_url' => 'Blog',
                    'portfolio_url' => 'Portfolio',
                    'podcast_url' => 'Podcast',
                    'newsletter_url' => 'Newsletter',
                    'linktree_url' => 'LinkTree',
                    'beacons_url' => 'Beacons'
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

                // ===== COMPLETE ICON MAPPING (Social + Ecommerce + Payment) =====
                $iconMap = [
                    // Social Media
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
                    'skype' => 'fab fa-skype',
                    'slack' => 'fab fa-slack',
                    'tumblr' => 'fab fa-tumblr',
                    'flickr' => 'fab fa-flickr',
                    'soundcloud' => 'fab fa-soundcloud',
                    'vimeo' => 'fab fa-vimeo',
                    'behance' => 'fab fa-behance',
                    'dribbble' => 'fab fa-dribbble',
                    'stackoverflow' => 'fab fa-stack-overflow',

                    // Ecommerce
                    'ebay' => 'fab fa-ebay',
                    'amazon' => 'fab fa-amazon',
                    'etsy' => 'fab fa-etsy',
                    'shopify' => 'fab fa-shopify',
                    'flipkart' => 'fas fa-store',
                    'walmart' => 'fas fa-store',
                    'aliexpress' => 'fas fa-globe',
                    'meesho' => 'fas fa-shopping-bag',
                    'nykaa' => 'fas fa-paint-brush',
                    'myntra' => 'fas fa-shopping-bag',
                    'snapdeal' => 'fas fa-shopping-cart',
                    'ajio' => 'fas fa-tshirt',
                    // For platforms without dedicated icons, use generic
                    'alibaba' => 'fas fa-globe-asia',
                    'indiamart' => 'fas fa-building',
                    'tradeindia' => 'fas fa-handshake',

                    // Payment Gateways
                    'paypal' => 'fab fa-paypal',
                    'stripe' => 'fab fa-stripe',
                    'applepay' => 'fab fa-apple-pay',
                    'amazonpay' => 'fab fa-amazon-pay',
                    'googlepay' => 'fab fa-google-pay',
                    'upi' => 'fas fa-mobile-alt',
                    'zelle' => 'fas fa-exchange-alt',
                    'venmo' => 'fas fa-hand-holding-usd',
                    'crypto_btc' => 'fab fa-bitcoin',
                    'crypto_eth' => 'fab fa-ethereum',
                    'crypto_usdt' => 'fas fa-coins',
                    'bank' => 'fas fa-university',
                    'razorpay' => 'fas fa-credit-card',
                    'payoneer' => 'fas fa-globe',
                    'wise' => 'fas fa-exchange-alt',
                    'airwallex' => 'fas fa-plane-departure',
                    'skydo' => 'fas fa-cloud-sun',
                    'cashfree' => 'fas fa-coins',
                    'instamojo' => 'fas fa-hand-holding-heart',
                    'payu' => 'fas fa-university',
                    'westernunion' => 'fas fa-hand-holding-usd',
                    'samsungpay' => 'fas fa-mobile-alt',
                    'phonepe' => 'fas fa-phone',
                    'paytm' => 'fas fa-mobile-alt',

                    // Other
                    'website' => 'fas fa-globe',
                    'blog' => 'fas fa-blog',
                    'portfolio' => 'fas fa-briefcase',
                    'podcast' => 'fas fa-podcast',
                    'newsletter' => 'fas fa-envelope',
                    'linktree' => 'fas fa-tree',
                    'beacons' => 'fas fa-flag'
                ];

                // Helper: normalize platform name for icon lookup
                function getIconClass($name, $iconMap) {
                    $key = str_replace(' ', '_', strtolower($name));
                    return $iconMap[$key] ?? null;
                }
            @endphp

            @foreach($allLinks as $platform)
            <div class="social-item">
                <div class="social-icon">
                    @if($platform['icon_type'] === 'custom')
                        <img src="{{ asset('images/cm_logo.png') }}" alt="{{ $platform['name'] }}">
                    @else
                        @php
                            $iconClass = getIconClass($platform['name'], $iconMap) ?? 'fas fa-link';
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
</script>

</body>
</html>
