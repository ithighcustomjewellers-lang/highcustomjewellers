
<head>
      @include('profile.partials.social-grid')
</head>
@php

    /*
    |--------------------------------------------------------------------------
    | MULTI QR LINKS -> SAME FORMAT AS SHOW PAGE
    |--------------------------------------------------------------------------
    */

    $allPlatforms = [];

    foreach($links as $link)
    {
        $allPlatforms[] = [

            'name' => $link['platform'],

            'url' => route('track-multi-qr-click') .
                '?url=' . urlencode($link['url']) .
                '&slug=' . ($selectedQr['tracking_slug'] ?? $selectedQr['id']) .
                '&platform=' . urlencode($link['platform']),

            'icon_type' => 'fa'
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | ICON FUNCTION
    |--------------------------------------------------------------------------
    */

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

             // ===== ECOMMERCE =====
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
            'alibaba' => 'fas fa-globe-asia',
            'indiamart' => 'fas fa-building',
            'tradeindia' => 'fas fa-handshake',

            // ===== PAYMENT GATEWAYS =====
            'paypal' => 'fab fa-paypal',
            'stripe' => 'fab fa-stripe',
            'applepay' => 'fab fa-apple-pay',
            'amazonpay' => 'fab fa-amazon-pay',
            'googlepay' => 'fab fa-google-pay',
            'upi' => 'fas fa-mobile-alt',
            'zelle' => 'fas fa-exchange-alt',
            'venmo' => 'fas fa-hand-holding-usd',
            'cryptobtc' => 'fab fa-bitcoin',
            'crypto_eth' => 'fab fa-ethereum',
            'cryptousdt' => 'fas fa-coins',
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
        ];

        $platformName = strtolower($platform['name']);
        if (($platform['icon_type'] ?? 'fa') === 'custom')
        {
            return '<img src="' . asset('images/cm_logo.png') . '">';
        }
        if (isset($iconMap[$platformName]))
        {
            return '<i class="' . $iconMap[$platformName] . '"></i>';
        }
        return '<img src="' . asset('images/cm_logo.png') . '">';
    }

@endphp

<div class="profile-card">
    <div class="profile-header">
        <img src="{{ asset('images/live_QR_Back_logo.png') }}" width="100%" alt="Header Logo">
    </div>

    {{-- SOCIAL LINKS --}}
    <div class="social-links">
        <div class="social-links-grid">
            @foreach ($allPlatforms as $platform)
                <a href="{{ $platform['url'] }}"
                   class="social-link"
                   target="_blank"
                   rel="noopener noreferrer"
                   onclick="trackClick('{{ addslashes($platform['name']) }}')">
                    <div class="social-icon">
                        {!! getIconHtml($platform) !!}
                    </div>
                    <div class="social-name">
                        {{ $platform['name'] }}
                    </div>
                    <div class="social-arrow">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </a>
            @endforeach

            @if(count($allPlatforms) == 0)
                <div class="text-center text-muted py-4"
                     style="
                        grid-column: span 2;
                        background: rgba(255,255,255,0.9);
                        border-radius:16px;
                     ">
                    <i class="fas fa-link fa-2x mb-2"></i>
                    <p>No links available.</p>
                </div>
            @endif
        </div>
    </div>
</div>
<script>

    function trackClick(platform)
    {
        console.log(
            'Platform clicked:',
            platform
        );
    }

</script>

</body>
</html>
