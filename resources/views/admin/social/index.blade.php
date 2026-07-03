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
        /* ===== RESET & BASE ===== */
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
            margin: 75px auto;
            padding: 20px;
        }

        /* ===== HEADER ===== */
        .header {
            background: white;
            border-radius: 20px;
            padding: 20px 30px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            flex-wrap: wrap;
            gap: 15px;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
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
            flex-shrink: 0;
        }

        .company-name h1 {
            font-size: 24px;
            font-weight: 800;
            color: #1a1a1a;
            line-height: 1.2;
        }

        .company-name p {
            font-size: 12px;
            color: #666;
            margin-top: 4px;
        }

        .team-member {
            text-align: right;
            flex-shrink: 0;
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

        /* ===== DASHBOARD GRID ===== */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 30px;
        }

        .left-panel {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            min-width: 0;
        }

        .right-panel {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
        }

        /* ===== CARD ===== */
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
            max-width: 100%;
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

        /* ===== STATS ===== */
        .stats {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            margin-top: 15px;
            flex-wrap: wrap;
        }

        .stat-box {
            flex: 1;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 12px;
            text-align: center;
            min-width: 80px;
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

        /* ===== BUTTONS ===== */
        .btn-add {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            transition: transform 0.2s;
            white-space: nowrap;
        }

        .btn-add:hover {
            transform: translateY(-2px);
        }

        .btn-add:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
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

        /* ===== APP LINKS (Editable rows) ===== */
        .app-link {
            background: #f8f9fa;
            padding: 12px 15px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 15px;
            transition: all 0.3s;
            flex-wrap: wrap;
        }

        .app-link:hover {
            background: #e9ecef;
        }

        .app-link i {
            width: 30px;
            font-size: 20px;
            flex-shrink: 0;
            text-align: center;
        }

        .app-link .platform-icon {
            width: 30px;
            flex-shrink: 0;
            text-align: center;
        }

        .app-link .platform-icon i {
            font-size: 20px;
            width: auto;
        }

        .app-link .platform-icon img {
            width: 24px;
            height: 24px;
            object-fit: contain;
        }

        .app-link strong {
            min-width: 80px;
            flex-shrink: 0;
            font-size: 14px;
        }

        .app-link .link-input {
            flex: 1;
            min-width: 150px;
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

        .app-link .platform-name-input {
            max-width: 180px;
            min-width: 100px;
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
            white-space: nowrap;
        }

        .app-link .save-btn-sm:hover {
            background: #218838;
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
            white-space: nowrap;
        }

        .app-link .copy-btn-sm:hover {
            background: #5a67d8;
        }

        /* ===== CHECKBOX ===== */
        .qr-platform-checkbox {
            width: 18px;
            height: 18px;
            cursor: pointer;
            flex-shrink: 0;
            accent-color: #667eea;
        }

        /* ===== BOTTOM ACTIONS ===== */
        .bottom-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #f0f0f0;
            gap: 20px;
            flex-wrap: wrap;
        }

        .add-platform {
            display: flex;
            gap: 10px;
            flex: 1;
            flex-wrap: wrap;
        }

        .add-platform input {
            flex: 1;
            min-width: 150px;
            padding: 10px 15px;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            font-size: 14px;
        }

        .add-platform input:focus {
            outline: none;
            border-color: #667eea;
        }

        /* ===== ACCORDION ===== */
        .accordion-item {
            border: 1px solid #e0e0e0;
            border-radius: 12px !important;
            margin-bottom: 10px;
            overflow: hidden;
        }

        .accordion-button {
            background: white;
            color: #1a1a1a;
            padding: 15px 20px;
            font-weight: 600;
        }

        .accordion-button:not(.collapsed) {
            background: #f8f9fa;
            color: #667eea;
            box-shadow: none;
        }

        .accordion-button:focus {
            box-shadow: none;
            border-color: #667eea;
        }

        .accordion-button .badge {
            background: #667eea !important;
            font-size: 12px;
            padding: 4px 10px;
        }

        .accordion-body {
            padding: 15px 20px;
            background: #fafafa;
        }

        .accordion-body .app-link {
            background: white;
            padding: 10px 15px;
            border-radius: 10px;
            margin-bottom: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .accordion-body .app-link:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        /* ===== QR NAME INPUT ===== */
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

        /* ===== TOAST ===== */
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
            max-width: 90%;
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

        /* ===== LOADING SPINNER ===== */
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

        /* ===== MODAL ===== */
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

        .platform-cell {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
        }

        .platform-cell .platform-icon {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .platform-cell .platform-icon i {
            font-size: 20px;
        }

        .platform-cell .platform-icon img {
            width: 24px;
            height: 24px;
            object-fit: contain;
        }

        .btn-action {
            width: 100%;
            height: 42px;
            border: none;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all .3s ease;
            font-size: 14px;
        }

        .btn-remove {
            background: #fee2e2;
            color: #dc2626;
        }

        .btn-remove:hover {
            background: #dc2626;
            color: #fff;
        }

        .btn-copy {
            background: #e0e7ff;
            color: #4f46e5;
        }

        .btn-copy:hover {
            background: #4f46e5;
            color: #fff;
        }

        .modal-backdrop.show {
            opacity: 0.25 !important;
        }

        /* ===== SHARE BUTTONS ===== */
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

        /* ============================================================ */
        /* ===== RESPONSIVE BREAKPOINTS ===== */
        /* ============================================================ */

        /* Large screens (1025px - 1400px) */
        @media (max-width: 1200px) {
            .dashboard-grid {
                grid-template-columns: 1fr 320px;
                gap: 25px;
            }
            .main-container {
                padding: 15px;
            }
            .left-panel {
                padding: 25px;
            }
        }

        /* Tablet (769px - 1024px) */
        @media (max-width: 1024px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
                gap: 25px;
            }

            .right-panel {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 20px;
            }

            .header {
                padding: 18px 22px;
            }

            .company-name h1 {
                font-size: 20px;
            }

            .left-panel {
                padding: 22px;
            }
        }

        /* Small tablet / large phone (577px - 768px) */
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                text-align: center;
                padding: 18px 20px;
                gap: 12px;
            }

            .logo-section {
                justify-content: center;
                flex-direction: column;
                text-align: center;
            }

            .team-member {
                text-align: center;
                width: 100%;
            }

            .team-member .name {
                font-size: 16px;
            }

            .right-panel {
                grid-template-columns: 1fr;
                gap: 18px;
            }

            .left-panel {
                padding: 18px 16px;
            }

            .bottom-actions {
                flex-direction: column;
                align-items: stretch;
            }

            .add-platform {
                flex-direction: column;
                width: 100%;
            }

            .add-platform input {
                min-width: unset;
                width: 100%;
            }

            .add-platform .btn-add {
                width: 100%;
                justify-content: center;
            }

            .app-link {
                flex-direction: column;
                align-items: stretch;
                gap: 8px;
                padding: 12px;
            }

            .app-link .link-input {
                min-width: unset;
                width: 100%;
            }

            .app-link .platform-name-input {
                max-width: 100%;
                min-width: unset;
            }

            .app-link strong {
                min-width: unset;
                font-size: 13px;
            }

            .app-link .save-btn-sm,
            .app-link .copy-btn-sm {
                width: 100%;
                justify-content: center;
                padding: 8px;
            }

            .app-link .platform-icon {
                width: auto;
            }

            .app-link .qr-platform-checkbox {
                align-self: flex-start;
            }

            .stats {
                gap: 10px;
            }

            .stat-box {
                padding: 12px 8px;
                min-width: 60px;
            }

            .stat-number {
                font-size: 22px;
            }

            .qr-code svg,
            .qr-code img {
                width: 140px;
                height: 140px;
            }

            .QR_name {
                width: 100%;
                max-width: 100%;
                font-size: 14px;
            }

            .accordion-button {
                padding: 12px 16px;
                font-size: 14px;
            }

            .accordion-body {
                padding: 12px 14px;
            }

            .accordion-body .app-link {
                flex-direction: column;
                align-items: stretch;
            }

            .btn-add {
                padding: 10px 16px;
                font-size: 13px;
            }

            .print-btn {
                font-size: 13px;
                padding: 10px;
            }

            /* Modal responsive */
            .modal-dialog {
                margin: 10px;
            }

            .modal-body {
                padding: 15px;
            }

            .add-platform-section .row {
                flex-direction: column;
            }

            .add-platform-section .col-md-4,
            .add-platform-section .col-md-3,
            .add-platform-section .col-md-2 {
                width: 100%;
                margin-bottom: 10px;
            }

            .add-platform-section .col-md-2 .btn {
                width: 100%;
            }

            .platform-row {
                flex-direction: column;
                align-items: stretch !important;
            }

            .platform-row .col-md-4,
            .platform-row .col-md-6,
            .platform-row .col-md-1 {
                width: 100%;
                margin-bottom: 8px;
            }

            .platform-row .col-md-1 {
                display: flex;
                gap: 8px;
            }

            .platform-row .col-md-1 .btn-action {
                flex: 1;
            }

            .platform-cell {
                flex-wrap: wrap;
            }

            .platform-cell .edit-platform-name {
                flex: 1;
                min-width: 120px;
            }

            .modal-footer {
                flex-direction: column;
                gap: 10px;
            }

            .modal-footer .btn {
                width: 100%;
            }

            /* QR container in right panel */
            #multipleQrContainer>div {
                padding-bottom: 20px !important;
                margin-bottom: 25px !important;
            }

            #multipleQrContainer .stats {
                flex-direction: row;
                flex-wrap: wrap;
            }

            #multipleQrContainer .stats .stat-box {
                flex: 1 1 45%;
                min-width: 80px;
            }

            #multipleQrContainer .btn-add {
                font-size: 12px;
                padding: 6px 12px;
            }

            /* Toast */
            .toast {
                bottom: 10px;
                right: 10px;
                left: 10px;
                max-width: unset;
                text-align: center;
                font-size: 14px;
                padding: 10px 16px;
            }
        }

        /* Phone (up to 576px) */
        @media (max-width: 576px) {
            .main-container {
                padding: 10px;
                margin: 60px auto 20px;
            }

            .header {
                padding: 14px 16px;
                border-radius: 16px;
            }

            .logo-icon {
                width: 48px;
                height: 48px;
                font-size: 20px;
            }

            .company-name h1 {
                font-size: 18px;
            }

            .company-name p {
                font-size: 11px;
            }

            .team-member .name {
                font-size: 14px;
            }

            .left-panel {
                padding: 14px 12px;
                border-radius: 16px;
            }

            .card {
                padding: 18px 14px;
                border-radius: 16px;
            }

            .section-title {
                font-size: 16px;
                margin-bottom: 14px;
            }

            .app-link {
                padding: 10px 12px;
                gap: 6px;
            }

            .app-link .link-input {
                padding: 6px 10px;
                font-size: 12px;
            }

            .app-link .save-btn-sm,
            .app-link .copy-btn-sm {
                padding: 6px 10px;
                font-size: 11px;
            }

            .app-link i {
                font-size: 18px;
                width: 24px;
            }

            .app-link .platform-icon i {
                font-size: 18px;
            }

            .app-link .platform-icon img {
                width: 20px;
                height: 20px;
            }

            .app-link strong {
                font-size: 12px;
                min-width: 60px;
            }

            .bottom-actions {
                padding-top: 14px;
                gap: 12px;
            }

            .add-platform input {
                padding: 8px 12px;
                font-size: 13px;
            }

            .btn-add {
                padding: 8px 14px;
                font-size: 12px;
                border-radius: 8px;
            }

            .print-btn {
                font-size: 12px;
                padding: 10px;
                border-radius: 10px;
            }

            .qr-code svg,
            .qr-code img {
                width: 120px;
                height: 120px;
            }

            .QR_name {
                font-size: 13px;
                padding: 8px 12px;
            }

            .stat-number {
                font-size: 20px;
            }

            .stat-label {
                font-size: 10px;
            }

            .stat-box {
                padding: 10px 6px;
                min-width: 50px;
            }

            .accordion-button {
                padding: 10px 14px;
                font-size: 13px;
            }

            .accordion-body {
                padding: 10px 12px;
            }

            .accordion-body .app-link {
                padding: 8px 10px;
            }

            #multipleQrContainer>div {
                padding-bottom: 15px !important;
                margin-bottom: 20px !important;
            }

            #multipleQrContainer .stats .stat-box {
                flex: 1 1 100%;
                min-width: unset;
            }

            /* Modal */
            .modal-header {
                padding: 14px 16px;
            }

            .modal-header h5 {
                font-size: 16px;
            }

            .modal-body {
                padding: 12px;
            }

            .modal-footer {
                padding: 12px 16px;
            }

            .platform-row {
                padding: 8px;
            }

            .platform-row .col-md-4,
            .platform-row .col-md-6,
            .platform-row .col-md-1 {
                margin-bottom: 6px;
            }

            .platform-cell .edit-platform-name {
                min-width: 80px;
                font-size: 13px;
            }

            .platform-cell .platform-icon {
                width: 28px;
                height: 28px;
                font-size: 16px;
            }

            .platform-cell .platform-icon i {
                font-size: 16px;
            }

            .btn-action {
                height: 36px;
                font-size: 12px;
                border-radius: 8px;
            }

            .add-platform-section {
                padding: 12px;
            }

            .add-platform-section .form-label {
                font-size: 12px;
            }

            .add-platform-section .form-control,
            .add-platform-section .form-select {
                font-size: 13px;
                padding: 6px 10px;
            }

            .toast {
                font-size: 13px;
                padding: 8px 14px;
                bottom: 8px;
                right: 8px;
                left: 8px;
                border-radius: 8px;
            }

            /* Fix for tiny screens */
            .app-link .qr-platform-checkbox {
                width: 16px;
                height: 16px;
            }

            .modal-dialog.modal-fullscreen-sm-down {
                margin: 5px;
            }

            .modal-dialog.modal-fullscreen-sm-down .modal-content {
                border-radius: 16px;
            }

            .share-btn {
                width: 38px;
                height: 38px;
                font-size: 16px;
            }
        }

        /* Extra small (up to 400px) */
        @media (max-width: 400px) {
            .main-container {
                padding: 6px;
                margin: 50px auto 10px;
            }

            .header {
                padding: 10px 12px;
                border-radius: 12px;
            }

            .logo-icon {
                width: 40px;
                height: 40px;
                font-size: 16px;
            }

            .company-name h1 {
                font-size: 16px;
            }

            .left-panel {
                padding: 10px 8px;
                border-radius: 12px;
            }

            .card {
                padding: 14px 10px;
                border-radius: 12px;
            }

            .app-link {
                padding: 8px 8px;
                gap: 4px;
            }

            .app-link .link-input {
                padding: 5px 8px;
                font-size: 11px;
            }

            .app-link .save-btn-sm,
            .app-link .copy-btn-sm {
                padding: 5px 8px;
                font-size: 10px;
            }

            .app-link i {
                font-size: 16px;
                width: 20px;
            }

            .app-link strong {
                font-size: 11px;
                min-width: 50px;
            }

            .btn-add {
                padding: 6px 12px;
                font-size: 11px;
            }

            .print-btn {
                font-size: 11px;
                padding: 8px;
            }

            .stat-number {
                font-size: 18px;
            }

            .QR_name {
                font-size: 12px;
                padding: 6px 10px;
            }

            .accordion-button {
                padding: 8px 12px;
                font-size: 12px;
            }

            .accordion-body {
                padding: 8px 10px;
            }

            .modal-header {
                padding: 10px 12px;
            }

            .modal-header h5 {
                font-size: 14px;
            }

            .modal-body {
                padding: 8px;
            }

            .modal-footer {
                padding: 8px 12px;
            }

            .platform-cell .edit-platform-name {
                font-size: 12px;
                min-width: 60px;
            }

            .btn-action {
                height: 32px;
                font-size: 11px;
            }

            .add-platform input {
                padding: 6px 10px;
                font-size: 12px;
            }
        }

        /* ===== UTILITY FIXES ===== */
        .text-truncate-mobile {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        @media (max-width: 576px) {
            .text-truncate-mobile {
                white-space: normal;
                word-break: break-all;
            }
        }

        /* Fix for QR code container overflow */
        #multipleQrContainer {
            overflow: hidden;
        }

        #multipleQrContainer>div {
            max-width: 100%;
            overflow: hidden;
        }

        /* Fix for platform rows in modal on small screens */
        .platform-row .row.g-2 {
            --bs-gutter-y: 0.5rem;
        }

        @media (max-width: 768px) {
            .platform-row .row.g-2>* {
                padding-left: 0;
                padding-right: 0;
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

    <!-- ==================== ACCORDION 1: SOCIAL MEDIA LINKS ==================== -->
    <div class="accordion" id="socialAccordion">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#socialCollapse" aria-expanded="false">
                    <i class="fas fa-share-alt me-2" style="color: #667eea;"></i>
                    <strong>Social Media Links</strong>
                    <span class="badge bg-primary ms-2"></span>
                </button>
            </h2>
            <div id="socialCollapse" class="accordion-collapse collapse" data-bs-parent="#socialAccordion">
                <div class="accordion-body">

                    {{-- WhatsApp --}}
                    @php
                        $user = Auth::user();
                        $message = "Hi {$user->name},\n"
                            . "I came across your jewelry collection and I'm interested in learning more about it. "
                            . "Could you please share some details about your products and offerings?\n"
                            . "Thank you.";
                        $defaultWhatsappUrl = 'https://wa.me/' . preg_replace('/\D/', '', $user->mobile) . '?text=' . urlencode($message);
                    @endphp

                    <div class="app-link quick-link-item">
                        <input type="checkbox" class="qr-platform-checkbox" data-platform="WhatsApp"
                            data-input="whatsapp_url">
                        <i class="fab fa-whatsapp" style="color: #25D366;"></i>
                        <strong>WhatsApp:</strong>
                        <input type="url" id="whatsapp_url" placeholder="https://wa.me/yournumber"
                            class="link-input" value="{{ $quickLinks['whatsapp_url'] ?? $defaultWhatsappUrl }}">
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
                        <input type="url" id="telegram_url" placeholder="https://t.me/username"
                            class="link-input" value="{{ $quickLinks['telegram_url'] ?? '' }}">
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
                    <div class="app-link quick-link-item">
                        <input type="checkbox" class="qr-platform-checkbox" data-platform="YouTube"
                            data-input="youtube_url">
                        <i class="fab fa-youtube" style="color: #ff0000;"></i>
                        <strong>YouTube:</strong>
                        <input type="url" id="youtube_url" placeholder="https://youtube.com/@username"
                            class="link-input" value="{{ $quickLinks['youtube_url'] ?? '' }}">
                        <button onclick="saveQuickLink('youtube_url', 'YouTube')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('youtube_url').value)"
                            class="copy-btn-sm">
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
                    <div class="app-link quick-link-item">
                        <input type="checkbox" class="qr-platform-checkbox" data-platform="Instagram"
                            data-input="instagram_url">
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
                    <div class="app-link quick-link-item">
                        <input type="checkbox" class="qr-platform-checkbox" data-platform="X"
                            data-input="x_url">
                        <i class="fab fa-x-twitter" style="color: #000;"></i>
                        <strong>X:</strong>
                        <input type="url" id="x_url" placeholder="https://x.com/username"
                            class="link-input" value="{{ $quickLinks['x_url'] ?? '' }}">
                        <button onclick="saveQuickLink('x_url', 'X')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('x_url').value)"
                            class="copy-btn-sm">
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
                    <div class="app-link quick-link-item">
                        <input type="checkbox" class="qr-platform-checkbox" data-platform="Snapchat"
                            data-input="snapchat_url">
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

                    {{-- Messenger --}}
                    <div class="app-link quick-link-item">
                        <input type="checkbox" class="qr-platform-checkbox" data-platform="Messenger"
                            data-input="messenger_url">
                        <i class="fab fa-facebook-messenger" style="color: #0084FF;"></i>
                        <strong>Messenger:</strong>
                        <input type="url" id="messenger_url" placeholder="https://m.me/username"
                            class="link-input" value="{{ $quickLinks['messenger_url'] ?? '' }}">
                        <button onclick="saveQuickLink('messenger_url', 'Messenger')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('messenger_url').value)"
                            class="copy-btn-sm">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <br>

    <!-- ==================== ACCORDION 2: ECOMMERCE LINKS ==================== -->
    <div class="accordion" id="ecommerceAccordion">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#ecommerceCollapse" aria-expanded="false">
                    <i class="fas fa-shopping-cart me-2" style="color: #667eea;"></i>
                    <strong>Ecommerce Links</strong>
                    <span class="badge bg-primary ms-2"></span>
                </button>
            </h2>
            <div id="ecommerceCollapse" class="accordion-collapse collapse" data-bs-parent="#ecommerceAccordion">
                <div class="accordion-body">

                    {{-- eBay --}}
                    <div class="app-link quick-link-item">
                        <input type="checkbox" class="qr-platform-checkbox" data-platform="eBay"
                            data-input="ebay_url">
                        <i class="fab fa-ebay" style="color: #e53238; font-size: 20px; width: 30px;"></i>
                        <strong style="min-width: 80px;">eBay:</strong>
                        <input type="url" id="ebay_url" placeholder="https://www.ebay.com/usr/username"
                            class="link-input" value="{{ $quickLinks['ebay_url'] ?? '' }}">
                        <button onclick="saveQuickLink('ebay_url','eBay')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('ebay_url').value)"
                            class="copy-btn-sm">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>

                    {{-- Alibaba --}}
                    <div class="app-link quick-link-item">
                        <input type="checkbox" class="qr-platform-checkbox" data-platform="Alibaba"
                            data-input="alibaba_url">
                        <i class="fas fa-globe-asia" style="color: #FF6A00; font-size: 20px; width: 30px;"></i>
                        <strong style="min-width: 80px;">Alibaba:</strong>
                        <input type="url" id="alibaba_url"
                            placeholder="https://www.alibaba.com/member/username.html" class="link-input"
                            value="{{ $quickLinks['alibaba_url'] ?? '' }}">
                        <button onclick="saveQuickLink('alibaba_url','Alibaba')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('alibaba_url').value)"
                            class="copy-btn-sm">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>

                    {{-- IndiaMart --}}
                    <div class="app-link quick-link-item">
                        <input type="checkbox" class="qr-platform-checkbox" data-platform="IndiaMart"
                            data-input="indiamart_url">
                        <i class="fas fa-building" style="color: #E65100; font-size: 20px; width: 30px;"></i>
                        <strong style="min-width: 80px;">IndiaMart:</strong>
                        <input type="url" id="indiamart_url" placeholder="https://www.indiamart.com/username"
                            class="link-input" value="{{ $quickLinks['indiamart_url'] ?? '' }}">
                        <button onclick="saveQuickLink('indiamart_url','IndiaMart')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('indiamart_url').value)"
                            class="copy-btn-sm">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>

                    {{-- Etsy --}}
                    <div class="app-link quick-link-item">
                        <input type="checkbox" class="qr-platform-checkbox" data-platform="Etsy"
                            data-input="etsy_url">
                        <i class="fab fa-etsy" style="color: #f16521; font-size: 20px; width: 30px;"></i>
                        <strong style="min-width: 80px;">Etsy:</strong>
                        <input type="url" id="etsy_url" placeholder="https://www.etsy.com/shop/username"
                            class="link-input" value="{{ $quickLinks['etsy_url'] ?? '' }}">
                        <button onclick="saveQuickLink('etsy_url','Etsy')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('etsy_url').value)"
                            class="copy-btn-sm">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>

                    {{-- TradeIndia --}}
                    <div class="app-link quick-link-item">
                        <input type="checkbox" class="qr-platform-checkbox" data-platform="TradeIndia"
                            data-input="tradeindia_url">
                        <i class="fas fa-handshake" style="color: #FF6A00; font-size: 20px; width: 30px;"></i>
                        <strong style="min-width: 80px;">TradeIndia:</strong>
                        <input type="url" id="tradeindia_url" placeholder="https://www.tradeindia.com/username"
                            class="link-input" value="{{ $quickLinks['tradeindia_url'] ?? '' }}">
                        <button onclick="saveQuickLink('tradeindia_url','TradeIndia')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('tradeindia_url').value)"
                            class="copy-btn-sm">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>

                    {{-- Amazon --}}
                    <div class="app-link quick-link-item">
                        <input type="checkbox" class="qr-platform-checkbox" data-platform="Amazon"
                            data-input="amazon_url">
                        <i class="fab fa-amazon" style="color: #ff9900; font-size: 20px; width: 30px;"></i>
                        <strong style="min-width: 80px;">Amazon:</strong>
                        <input type="url" id="amazon_url" placeholder="https://www.amazon.com/shop/username"
                            class="link-input" value="{{ $quickLinks['amazon_url'] ?? '' }}">
                        <button onclick="saveQuickLink('amazon_url','Amazon')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('amazon_url').value)"
                            class="copy-btn-sm">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>

                    {{-- Flipkart --}}
                    <div class="app-link quick-link-item">
                        <input type="checkbox" class="qr-platform-checkbox" data-platform="Flipkart"
                            data-input="flipkart_url">
                        <i class="fas fa-store" style="color: #2874f0; font-size: 20px; width: 30px;"></i>
                        <strong style="min-width: 80px;">Flipkart:</strong>
                        <input type="url" id="flipkart_url" placeholder="https://www.flipkart.com/username"
                            class="link-input" value="{{ $quickLinks['flipkart_url'] ?? '' }}">
                        <button onclick="saveQuickLink('flipkart_url','Flipkart')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('flipkart_url').value)"
                            class="copy-btn-sm">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>

                    {{-- Shopify --}}
                    <div class="app-link quick-link-item">
                        <input type="checkbox" class="qr-platform-checkbox" data-platform="Shopify"
                            data-input="shopify_url">
                        <i class="fab fa-shopify" style="color: #7ab55c; font-size: 20px; width: 30px;"></i>
                        <strong style="min-width: 80px;">Shopify:</strong>
                        <input type="url" id="shopify_url" placeholder="https://username.myshopify.com"
                            class="link-input" value="{{ $quickLinks['shopify_url'] ?? '' }}">
                        <button onclick="saveQuickLink('shopify_url','Shopify')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('shopify_url').value)"
                            class="copy-btn-sm">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <br>

    <!-- ==================== ACCORDION 3: PAYMENT GATEWAYS ==================== -->
    <div class="accordion" id="paymentAccordion">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#paymentCollapse" aria-expanded="false">
                    <i class="fas fa-credit-card me-2" style="color: #667eea;"></i>
                    <strong>Payment Gateways</strong>
                    <span class="badge bg-primary ms-2"></span>
                </button>
            </h2>
            <div id="paymentCollapse" class="accordion-collapse collapse" data-bs-parent="#paymentAccordion">
                <div class="accordion-body">

                    {{-- PayPal --}}
                    <div class="app-link quick-link-item">
                        <input type="checkbox" class="qr-platform-checkbox" data-platform="PayPal"
                            data-input="paypal_url">
                        <i class="fab fa-paypal" style="color: #003087; font-size: 20px; width: 30px;"></i>
                        <strong style="min-width: 80px;">PayPal:</strong>
                        <input type="url" id="paypal_url" placeholder="https://paypal.me/username"
                            class="link-input" value="{{ $quickLinks['paypal_url'] ?? '' }}">
                        <button onclick="saveQuickLink('paypal_url','PayPal')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('paypal_url').value)"
                            class="copy-btn-sm">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>

                    {{-- Stripe --}}
                    <div class="app-link quick-link-item">
                        <input type="checkbox" class="qr-platform-checkbox" data-platform="Stripe"
                            data-input="stripe_url">
                        <i class="fab fa-stripe" style="color: #008CDD; font-size: 20px; width: 30px;"></i>
                        <strong style="min-width: 80px;">Stripe:</strong>
                        <input type="url" id="stripe_url" placeholder="https://stripe.com/username"
                            class="link-input" value="{{ $quickLinks['stripe_url'] ?? '' }}">
                        <button onclick="saveQuickLink('stripe_url','Stripe')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('stripe_url').value)"
                            class="copy-btn-sm">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>

                    {{-- Razorpay --}}
                    <div class="app-link quick-link-item">
                        <input type="checkbox" class="qr-platform-checkbox" data-platform="Razorpay"
                            data-input="razorpay_url">
                        <i class="fas fa-credit-card" style="color: #0C4A6E; font-size: 20px; width: 30px;"></i>
                        <strong style="min-width: 80px;">Razorpay:</strong>
                        <input type="url" id="razorpay_url" placeholder="https://razorpay.com/username"
                            class="link-input" value="{{ $quickLinks['razorpay_url'] ?? '' }}">
                        <button onclick="saveQuickLink('razorpay_url','Razorpay')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('razorpay_url').value)"
                            class="copy-btn-sm">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>

                    {{-- Payoneer --}}
                    <div class="app-link quick-link-item">
                        <input type="checkbox" class="qr-platform-checkbox" data-platform="Payoneer"
                            data-input="payoneer_url">
                        <i class="fas fa-globe" style="color: #FF4800; font-size: 20px; width: 30px;"></i>
                        <strong style="min-width: 80px;">Payoneer:</strong>
                        <input type="url" id="payoneer_url" placeholder="https://payoneer.com/username"
                            class="link-input" value="{{ $quickLinks['payoneer_url'] ?? '' }}">
                        <button onclick="saveQuickLink('payoneer_url','Payoneer')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('payoneer_url').value)"
                            class="copy-btn-sm">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>

                    {{-- Wise --}}
                    <div class="app-link quick-link-item">
                        <input type="checkbox" class="qr-platform-checkbox" data-platform="Wise"
                            data-input="wise_url">
                        <i class="fas fa-exchange-alt" style="color: #00BFA5; font-size: 20px; width: 30px;"></i>
                        <strong style="min-width: 80px;">Wise:</strong>
                        <input type="url" id="wise_url" placeholder="https://wise.com/username"
                            class="link-input" value="{{ $quickLinks['wise_url'] ?? '' }}">
                        <button onclick="saveQuickLink('wise_url','Wise')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('wise_url').value)"
                            class="copy-btn-sm">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>

                    {{-- Airwallex --}}
                    <div class="app-link quick-link-item">
                        <input type="checkbox" class="qr-platform-checkbox" data-platform="Airwallex"
                            data-input="airwallex_url">
                        <i class="fas fa-plane" style="color: #0055FF; font-size: 20px; width: 30px;"></i>
                        <strong style="min-width: 80px;">Airwallex:</strong>
                        <input type="url" id="airwallex_url" placeholder="https://airwallex.com/username"
                            class="link-input" value="{{ $quickLinks['airwallex_url'] ?? '' }}">
                        <button onclick="saveQuickLink('airwallex_url','Airwallex')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('airwallex_url').value)"
                            class="copy-btn-sm">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>

                    {{-- Skydo --}}
                    <div class="app-link quick-link-item">
                        <input type="checkbox" class="qr-platform-checkbox" data-platform="Skydo"
                            data-input="skydo_url">
                        <i class="fas fa-cloud-sun" style="color: #3B82F6; font-size: 20px; width: 30px;"></i>
                        <strong style="min-width: 80px;">Skydo:</strong>
                        <input type="url" id="skydo_url" placeholder="https://skydo.com/username"
                            class="link-input" value="{{ $quickLinks['skydo_url'] ?? '' }}">
                        <button onclick="saveQuickLink('skydo_url','Skydo')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('skydo_url').value)"
                            class="copy-btn-sm">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>

                    {{-- Cashfree --}}
                    <div class="app-link quick-link-item">
                        <input type="checkbox" class="qr-platform-checkbox" data-platform="Cashfree"
                            data-input="cashfree_url">
                        <i class="fas fa-coins" style="color: #00A859; font-size: 20px; width: 30px;"></i>
                        <strong style="min-width: 80px;">Cashfree:</strong>
                        <input type="url" id="cashfree_url" placeholder="https://cashfree.com/username"
                            class="link-input" value="{{ $quickLinks['cashfree_url'] ?? '' }}">
                        <button onclick="saveQuickLink('cashfree_url','Cashfree')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('cashfree_url').value)"
                            class="copy-btn-sm">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>

                    {{-- Instamojo --}}
                    <div class="app-link quick-link-item">
                        <input type="checkbox" class="qr-platform-checkbox" data-platform="Instamojo"
                            data-input="instamojo_url">
                        <i class="fas fa-hand-holding-heart" style="color: #FF6A00; font-size: 20px; width: 30px;"></i>
                        <strong style="min-width: 80px;">Instamojo:</strong>
                        <input type="url" id="instamojo_url" placeholder="https://instamojo.com/username"
                            class="link-input" value="{{ $quickLinks['instamojo_url'] ?? '' }}">
                        <button onclick="saveQuickLink('instamojo_url','Instamojo')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('instamojo_url').value)"
                            class="copy-btn-sm">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>

                    {{-- PayU India --}}
                    <div class="app-link quick-link-item">
                        <input type="checkbox" class="qr-platform-checkbox" data-platform="PayU"
                            data-input="payu_url">
                        <i class="fas fa-university" style="color: #0057B8; font-size: 20px; width: 30px;"></i>
                        <strong style="min-width: 80px;">PayU India:</strong>
                        <input type="url" id="payu_url" placeholder="https://payu.in/username"
                            class="link-input" value="{{ $quickLinks['payu_url'] ?? '' }}">
                        <button onclick="saveQuickLink('payu_url','PayU')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('payu_url').value)"
                            class="copy-btn-sm">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>

                    {{-- Western Union --}}
                    <div class="app-link quick-link-item">
                        <input type="checkbox" class="qr-platform-checkbox" data-platform="WesternUnion"
                            data-input="westernunion_url">
                        <i class="fas fa-hand-holding-usd" style="color: #FFD700; font-size: 20px; width: 30px;"></i>
                        <strong style="min-width: 80px;">Western Union:</strong>
                        <input type="url" id="westernunion_url" placeholder="https://westernunion.com/username"
                            class="link-input" value="{{ $quickLinks['westernunion_url'] ?? '' }}">
                        <button onclick="saveQuickLink('westernunion_url','WesternUnion')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('westernunion_url').value)"
                            class="copy-btn-sm">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>

                    {{-- Google Pay --}}
                    <div class="app-link quick-link-item">
                        <input type="checkbox" class="qr-platform-checkbox" data-platform="GooglePay"
                            data-input="googlepay_url">
                        <i class="fab fa-google-pay" style="color: #4285F4; font-size: 20px; width: 30px;"></i>
                        <strong style="min-width: 80px;">Google Pay:</strong>
                        <input type="url" id="googlepay_url" placeholder="Enter Google Pay UPI"
                            class="link-input" value="{{ $quickLinks['googlepay_url'] ?? '' }}">
                        <button onclick="saveQuickLink('googlepay_url','GooglePay')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('googlepay_url').value)"
                            class="copy-btn-sm">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>

                    {{-- UPI --}}
                    <div class="app-link quick-link-item">
                        <input type="checkbox" class="qr-platform-checkbox" data-platform="UPI"
                            data-input="upi_url">
                        <i class="fas fa-mobile-alt" style="color: #00BFA5; font-size: 20px; width: 30px;"></i>
                        <strong style="min-width: 80px;">UPI:</strong>
                        <input type="text" id="upi_url" placeholder="example@upi"
                            class="link-input" value="{{ $quickLinks['upi_url'] ?? '' }}">
                        <button onclick="saveQuickLink('upi_url','UPI')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('upi_url').value)"
                            class="copy-btn-sm">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>

                    {{-- Zelle --}}
                    <div class="app-link quick-link-item">
                        <input type="checkbox" class="qr-platform-checkbox" data-platform="Zelle"
                            data-input="zelle_url">
                        <i class="fas fa-exchange-alt" style="color: #6C1D45; font-size: 20px; width: 30px;"></i>
                        <strong style="min-width: 80px;">Zelle:</strong>
                        <input type="text" id="zelle_url" placeholder="Enter Zelle email/phone"
                            class="link-input" value="{{ $quickLinks['zelle_url'] ?? '' }}">
                        <button onclick="saveQuickLink('zelle_url','Zelle')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('zelle_url').value)"
                            class="copy-btn-sm">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>

                    {{-- Crypto BTC --}}
                    <div class="app-link quick-link-item">
                        <input type="checkbox" class="qr-platform-checkbox" data-platform="CryptoBTC"
                            data-input="crypto_btc_url">
                        <i class="fab fa-bitcoin" style="color: #F7931A; font-size: 20px; width: 30px;"></i>
                        <strong style="min-width: 80px;">Crypto (BTC):</strong>
                        <input type="text" id="crypto_btc_url" placeholder="Enter BTC Address"
                            class="link-input" value="{{ $quickLinks['crypto_btc_url'] ?? '' }}">
                        <button onclick="saveQuickLink('crypto_btc_url','CryptoBTC')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('crypto_btc_url').value)"
                            class="copy-btn-sm">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>

                    {{-- Crypto USDT --}}
                    <div class="app-link quick-link-item">
                        <input type="checkbox" class="qr-platform-checkbox" data-platform="CryptoUSDT"
                            data-input="crypto_usdt_url">
                        <i class="fas fa-coins" style="color: #26A17B; font-size: 20px; width: 30px;"></i>
                        <strong style="min-width: 80px;">Crypto (USDT):</strong>
                        <input type="text" id="crypto_usdt_url" placeholder="Enter USDT Address"
                            class="link-input" value="{{ $quickLinks['crypto_usdt_url'] ?? '' }}">
                        <button onclick="saveQuickLink('crypto_usdt_url','CryptoUSDT')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('crypto_usdt_url').value)"
                            class="copy-btn-sm">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>

                    {{-- Bank Details --}}
                    <div class="app-link quick-link-item">
                        <input type="checkbox" class="qr-platform-checkbox" data-platform="Bank"
                            data-input="bank_url">
                        <i class="fas fa-university" style="color: #1a237e; font-size: 20px; width: 30px;"></i>
                        <strong style="min-width: 80px;">Bank Details:</strong>
                        <input type="text" id="bank_url" placeholder="Enter Bank Account Details"
                            class="link-input" value="{{ $quickLinks['bank_url'] ?? '' }}">
                        <button onclick="saveQuickLink('bank_url','Bank')" class="save-btn-sm">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button onclick="copyToClipboard(document.getElementById('bank_url').value)"
                            class="copy-btn-sm">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>

 <div style="margin-top: 20px;">
                        <div id="customLinksContainer">
                            @php
                                $allPredefined = [
                                    // Social
                                    'whatsapp', 'telegram', 'facebook', 'youtube', 'linkedin', 'instagram',  'x',
                                    'threads','snapchat', 'messenger', 'reddit', 'discord','pinterest', 'twitch', 'quora', 'rumble','viber', 'tiktok', 'twitter','skype',
                                    'slack','medium', 'tumblr','flickr', 'soundcloud', 'vimeo','spotify','github', 'stackoverflow', 'behance', 'dribbble',
                                    // Ecommerce
                                    'ebay','amazon', 'alibaba', 'indiamart',
                                    'tradeindia','etsy', 'flipkart', 'shopify', 'walmart',
                                    'aliexpress','meesho', 'nykaa', 'myntra', 'snapdeal', 'ajio',
                                    // Payment
                                    'paypal','stripe','razorpay','payoneer',
                                    'wise', 'airwallex','skydo', 'cashfree', 'instamojo', 'payu','westernunion',
                                    'googlepay', 'applepay', 'samsungpay', 'phonepe', 'paytm', 'amazonpay', 'upi', 'zelle',
                                    'venmo', 'cryptobtc','cryptousdt', 'crypto_eth', 'bank',
                                ];
                                $customLinks = $socialLinks->filter(
                                    fn($link) => !in_array(strtolower($link->platform_name), $allPredefined),
                                );
                            @endphp

                            @foreach ($customLinks as $link)
                                <div class="app-link quick-link-item" id="link-row-{{ $link->id }}"
                                    data-icon-type="{{ $link->icon_type }}">
                                    <input type="checkbox" class="qr-platform-checkbox"
                                        data-platform="{{ $link->platform_name }}" data-input="url_{{ $link->id }}">

                                    <div class="platform-icon">
                                        {!! getPlatformIconHtml($link) !!}
                                    </div>

                                    <input type="text" id="platform_name_{{ $link->id }}"
                                        class="link-input platform-name-input" value="{{ $link->platform_name }}"
                                        placeholder="Platform Name" style="max-width:180px;">
                                    <input type="url" id="url_{{ $link->id }}" class="link-input"
                                        value="{{ $link->platform_url }}" placeholder="https://...">
                                    <button onclick="saveCustomLink({{ $link->id }}, '{{ $link->platform_name }}')"
                                        class="save-btn-sm">
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

                <!-- Custom Social Links Section - Dynamic Links -->
                <!--<div style="margin-top: 20px;">-->
                <!--    <div id="customLinksContainer">-->
                <!--        @foreach ($socialLinks as $link)-->
                <!--            <div class="app-link quick-link-item"-->
                <!--                id="link-row-{{ $link->id }}"-->
                <!--                data-icon-type="{{ $link->icon_type }}">-->

                <!--                <input type="checkbox"-->
                <!--                    class="qr-platform-checkbox"-->
                <!--                    data-platform="{{ $link->platform_name }}"-->
                <!--                    data-input="url_{{ $link->id }}">-->

                <!--                <div class="platform-icon">-->
                <!--                    {!! getPlatformIconHtml($link) !!}-->
                <!--                </div>-->

                <!--                {{-- Platform Name Editable --}}-->
                <!--                <input type="text"-->
                <!--                    id="platform_name_{{ $link->id }}"-->
                <!--                    class="link-input platform-name-input"-->
                <!--                    value="{{ $link->platform_name }}"-->
                <!--                    placeholder="Platform Name"-->
                <!--                    style="max-width:180px;">-->

                <!--                {{-- URL Editable --}}-->
                <!--                <input type="url"-->
                <!--                    id="url_{{ $link->id }}"-->
                <!--                    class="link-input"-->
                <!--                    value="{{ $link->platform_url }}"-->
                <!--                    placeholder="https://...">-->

                <!--                <button onclick="saveCustomLink({{ $link->id }})"-->
                <!--                        class="save-btn-sm">-->
                <!--                    <i class="fas fa-save"></i> Save-->
                <!--                </button>-->

                <!--                <button onclick="copyToClipboard(document.getElementById('url_{{ $link->id }}').value)"-->
                <!--                        class="copy-btn-sm">-->
                <!--                    <i class="fas fa-copy"></i> Copy-->
                <!--                </button>-->

                <!--                <button onclick="deleteCustomLink({{ $link->id }})"-->
                <!--                        class="save-btn-sm"-->
                <!--                        style="background:#dc3545;">-->
                <!--                    <i class="fas fa-trash"></i> Delete-->
                <!--                </button>-->

                <!--            </div>-->
                <!--            @endforeach-->
                <!--    </div>-->
                <!--</div>-->
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

             <!-- Right Panel -->
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
                                   <!-- ===== SOCIAL MEDIA PLATFORMS ===== -->
                                    <optgroup label="Social Media">
                                        <option value="WhatsApp">🟢 WhatsApp</option>
                                        <option value="Instagram">📸 Instagram</option>
                                        <option value="Facebook">🔵 Facebook</option>
                                        <option value="Telegram">✈️ Telegram</option>
                                        <option value="LinkedIn">💼 LinkedIn</option>
                                        <option value="YouTube">▶️ YouTube</option>
                                        <option value="Twitter/X">𝕏 Twitter/X</option>
                                        <option value="Threads">🧵 Threads</option>
                                        <option value="TikTok">🎵 TikTok</option>
                                        <option value="Snapchat">👻 Snapchat</option>
                                        <option value="Messenger">💬 Messenger</option>
                                        <option value="Pinterest">📌 Pinterest</option>
                                        <option value="Reddit">🤖 Reddit</option>
                                        <option value="Discord">🎮 Discord</option>
                                        <option value="Twitch">🎮 Twitch</option>
                                        <option value="Quora">❓ Quora</option>
                                        <option value="Medium">✍️ Medium</option>
                                        <option value="Tumblr">📝 Tumblr</option>
                                        <option value="Viber">📱 Viber</option>
                                        <option value="Skype">💬 Skype</option>
                                        <option value="Slack">💼 Slack</option>
                                        <option value="Rumble">📹 Rumble</option>
                                        <option value="Flickr">📷 Flickr</option>
                                        <option value="SoundCloud">🎵 SoundCloud</option>
                                        <option value="Vimeo">🎬 Vimeo</option>
                                        <option value="Spotify">🎶 Spotify</option>
                                        <option value="GitHub">💻 GitHub</option>
                                        <option value="Stack Overflow">📚 Stack Overflow</option>
                                        <option value="Behance">🎨 Behance</option>
                                        <option value="Dribbble">🏀 Dribbble</option>
                                    </optgroup>

                                    <!-- ===== ECOMMERCE PLATFORMS ===== -->
                                    <optgroup label="Ecommerce">
                                        <option value="eBay">🛒 eBay</option>
                                        <option value="Amazon">📦 Amazon</option>
                                        <option value="Alibaba">🌐 Alibaba</option>
                                        <option value="IndiaMart">🇮🇳 IndiaMart</option>
                                        <option value="TradeIndia">🇮🇳 TradeIndia</option>
                                        <option value="Etsy">🎨 Etsy</option>
                                        <option value="Flipkart">🛍️ Flipkart</option>
                                        <option value="Shopify">🛒 Shopify</option>
                                        <option value="Walmart">🛒 Walmart</option>
                                        <option value="AliExpress">📦 AliExpress</option>
                                        <option value="Meesho">🛍️ Meesho</option>
                                        <option value="Nykaa">💄 Nykaa</option>
                                        <option value="Myntra">👗 Myntra</option>
                                        <option value="Snapdeal">🛒 Snapdeal</option>
                                        <option value="Ajio">👕 Ajio</option>
                                    </optgroup>

                                    <!-- ===== PAYMENT GATEWAYS ===== -->
                                    <optgroup label="Payment Gateways">
                                        <option value="PayPal">💳 PayPal</option>
                                        <option value="Stripe">💳 Stripe</option>
                                        <option value="Payoneer">💳 Payoneer</option>
                                        <option value="Wise">💳 Wise</option>
                                        <option value="Airwallex">💳 Airwallex</option>
                                        <option value="Skydo">💳 Skydo</option>
                                        <option value="Cashfree">💳 Cashfree</option>
                                        <option value="Instamojo">💳 Instamojo</option>
                                        <option value="PayU">💳 PayU India</option>
                                        <option value="WesternUnion">💳 Western Union</option>
                                        <option value="GooglePay">📱 Google Pay</option>
                                        <option value="ApplePay">📱 Apple Pay</option>
                                        <option value="SamsungPay">📱 Samsung Pay</option>
                                        <option value="PhonePe">📱 PhonePe</option>
                                        <option value="Paytm">📱 Paytm</option>
                                        <option value="AmazonPay">📱 Amazon Pay</option>
                                        <option value="UPI">📱 UPI</option>
                                        <option value="Zelle">📱 Zelle</option>
                                        <option value="Venmo">📱 Venmo</option>
                                        <option value="CryptoBTC">₿ Crypto (BTC)</option>
                                        <option value="CryptoUSDT">₿ Crypto (USDT)</option>
                                        <option value="Bank">🏦 Bank Details</option>
                                    </optgroup>
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
                                <button type="button"
                                    class="btn btn-primary rounded-pill w-100 shadow-sm"
                                    onclick="addNewPlatformRow()">
                                    <i class="fas fa-plus me-2"></i>Add Link
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
                    <button type="button"
                        class="btn btn-success rounded-pill shadow-sm btn-save" onclick="saveEditedQR()">
                        <i class="fas fa-save me-2"></i>Save Changes
                    </button>
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

        function escapeHtml(str) {
            if (!str) return '';
            return str.replace(/[&<>]/g, function(m) {
                if (m === '&') return '&amp;';
                if (m === '<') return '&lt;';
                if (m === '>') return '&gt;';
                return m;
            });
        }

        function printBusinessCard() {
            window.open('{{ route('social.print') }}', '_blank');
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

            fetch('{{ route('admin.social.links.store') }}', {
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

            fetch('{{ route('admin.social.quick.update') }}', {
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

            fetch('{{ route('admin.save.multiple.qr') }}', {
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
            threads: 'fab fa-threads',

             // ===== ECOMMERCE =====
            ebay: 'fab fa-ebay',
            amazon: 'fab fa-amazon',
            etsy: 'fab fa-etsy',
            shopify: 'fab fa-shopify',
            flipkart: 'fas fa-store',
            walmart: 'fas fa-store',
            aliexpress: 'fas fa-globe',
            meesho: 'fas fa-shopping-bag',
            nykaa: 'fas fa-paint-brush',
            myntra: 'fas fa-shopping-bag',
            snapdeal: 'fas fa-shopping-cart',
            ajio: 'fas fa-tshirt',
            alibaba: 'fas fa-globe-asia',
            indiamart: 'fas fa-building',
            tradeindia: 'fas fa-handshake',

            // ===== PAYMENT GATEWAYS =====
            paypal: 'fab fa-paypal',
            stripe: 'fab fa-stripe',
            applepay: 'fab fa-apple-pay',
            amazonpay: 'fab fa-amazon-pay',
            googlepay: 'fab fa-google-pay',
            upi: 'fas fa-mobile-alt',
            zelle: 'fas fa-exchange-alt',
            venmo: 'fas fa-hand-holding-usd',
            cryptobtc: 'fab fa-bitcoin',
            crypto_eth: 'fab fa-ethereum',
            cryptousdt: 'fas fa-coins',
            bank: 'fas fa-university',
            razorpay: 'fas fa-credit-card',
            payoneer: 'fas fa-globe',
            wise: 'fas fa-exchange-alt',
            airwallex: 'fas fa-plane',
            skydo: 'fas fa-cloud-sun',
            cashfree: 'fas fa-coins',
            instamojo: 'fas fa-hand-holding-heart',
            payu: 'fas fa-university',
            westernunion: 'fas fa-hand-holding-usd',
            samsungpay: 'fas fa-mobile-alt',
            phonepe: 'fas fa-phone',
            paytm: 'fas fa-mobile-alt'
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
                rumble: '<i class="fas fa-video"></i>',

                // ===== ECOMMERCE =====
                ebay: '<i class="fab fa-ebay"></i>',
                amazon: '<i class="fab fa-amazon"></i>',
                etsy: '<i class="fab fa-etsy"></i>',
                shopify: '<i class="fab fa-shopify"></i>',
                flipkart: '<i class="fas fa-store"></i>',
                walmart: '<i class="fas fa-store"></i>',
                aliexpress: '<i class="fas fa-globe"></i>',
                meesho: '<i class="fas fa-shopping-bag"></i>',
                nykaa: '<i class="fas fa-paint-brush"></i>',
                myntra: '<i class="fas fa-shopping-bag"></i>',
                snapdeal: '<i class="fas fa-shopping-cart"></i>',
                ajio: '<i class="fas fa-tshirt"></i>',
                alibaba: '<i class="fas fa-globe-asia"></i>',
                indiamart: '<i class="fas fa-building"></i>',
                tradeindia: '<i class="fas fa-handshake"></i>',

                // ===== PAYMENT GATEWAYS =====
                paypal: '<i class="fab fa-paypal"></i>',
                stripe: '<i class="fab fa-stripe"></i>',
                applepay: '<i class="fab fa-apple-pay"></i>',
                amazonpay: '<i class="fab fa-amazon-pay"></i>',
                googlepay: '<i class="fab fa-google-pay"></i>',
                upi: '<i class="fas fa-mobile-alt"></i>',
                zelle: '<i class="fas fa-exchange-alt"></i>',
                venmo: '<i class="fas fa-hand-holding-usd"></i>',
                cryptobtc: '<i class="fab fa-bitcoin"></i>',
                crypto_eth: '<i class="fab fa-ethereum"></i>',
                cryptousdt: '<i class="fas fa-coins"></i>',
                bank: '<i class="fas fa-university"></i>',
                razorpay: '<i class="fas fa-credit-card"></i>',
                payoneer: '<i class="fas fa-globe"></i>',
                wise: '<i class="fas fa-exchange-alt"></i>',
                airwallex: '<i class="fas fa-plane-departure"></i>',
                skydo: '<i class="fas fa-cloud-sun"></i>',
                cashfree: '<i class="fas fa-coins"></i>',
                instamojo: '<i class="fas fa-hand-holding-heart"></i>',
                payu: '<i class="fas fa-university"></i>',
                westernunion: '<i class="fas fa-hand-holding-usd"></i>',
                samsungpay: '<i class="fas fa-mobile-alt"></i>',
                phonepe: '<i class="fas fa-phone"></i>',
                paytm: '<i class="fas fa-mobile-alt"></i>',
            };
            return icons[platform] || '<img src="/images/cm_logo.png" style="width:22px;height:22px;">';
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

            fetch('{{ route('admin-update-multi-qr') }}', {
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
                    }, 10);
                })
                .catch(() => {
                    showToast('Failed to copy link', 'error');
                });
        }

        function loadUserQRs() {
            $.ajax({
                url: "{{ route('admin-get-multi-qr-codes') }}",
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
                        url: "{{ route('admin-multi-qr-destroy', ':id') }}".replace(':id', qrId),
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

        function updateQRTitle(qrId, title) {
            fetch('{{ route('admin-update-multi-qr-title') }}', {
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

        function addNewLinkToContainer(link) {
            const container = document.getElementById('customLinksContainer');
            const html = `
                <div class="app-link quick-link-item"
                     id="link-row-${link.id}">

                    <input type="checkbox"
                        class="qr-platform-checkbox"
                        data-platform="${link.platform_name}"
                        data-input="url_${link.id}">

                    <div class="platform-icon">
                        <img src="/images/cm_logo.png"
                             style="width:24px;height:24px;">
                    </div>

                    <input type="text"
                        id="platform_name_${link.id}"
                        class="link-input platform-name-input"
                        value="${link.platform_name}">

                    <input type="url"
                        id="url_${link.id}"
                        class="link-input"
                        value="${link.platform_url}">

                    <button onclick="saveCustomLink(${link.id})"
                            class="save-btn-sm">
                        <i class="fas fa-save"></i> Save
                    </button>

                    <button onclick="copyToClipboard(document.getElementById('url_${link.id}').value)"
                            class="copy-btn-sm">
                        <i class="fas fa-copy"></i> Copy
                    </button>

                    <button onclick="deleteCustomLink(${link.id})"
                            class="save-btn-sm"
                            style="background:#dc3545;">
                        <i class="fas fa-trash"></i> Delete
                    </button>

                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
        }

        function saveCustomLink(linkId)
        {
            console.log('id=', linkId);

            let platformElement =
                document.getElementById('platform_name_' + linkId);

            let urlElement =
                document.getElementById('url_' + linkId);

            if (!platformElement) {
                console.error(
                    'Platform input not found:',
                    'platform_name_' + linkId
                );
                return;
            }

            if (!urlElement) {
                console.error(
                    'URL input not found:',
                    'url_' + linkId
                );
                return;
            }

            let platformName = platformElement.value;
            let platformUrl = urlElement.value;

            fetch('{{ route('admin-social-links-update-secondary') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    id: linkId,
                    platform_name: platformName,
                    platform_url: platformUrl
                })
            })
            .then(response => response.json())
            .then(data => {

                if (data.success) {
                    showToast('Updated Successfully');
                } else {
                    showToast(data.message || 'Update Failed');
                }

            })
            .catch(error => {
                console.error(error);
                showToast('Something went wrong');
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
                        url: "{{ route('admin-social-links-destroy', ':id') }}".replace(':id', linkId),
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


