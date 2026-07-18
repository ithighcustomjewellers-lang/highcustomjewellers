<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* ---------- RESET & BASE ---------- */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
            background: #f5f7fb;
            overflow-x: hidden;
        }

        /* ---------- SIDEBAR (dark theme) ---------- */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 260px;
            height: 100%;
            background: #0f172a;
            color: #e2e8f0;
            z-index: 1050;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 1.2rem 0.8rem 2rem;
            transition: transform 0.3s ease-in-out;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
        }

        .sidebar-brand {
            font-size: 1.4rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            padding: 0 0.75rem 1.8rem 0.75rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            margin-bottom: 1.2rem;
            color: #f1f5f9;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .sidebar-brand i {
            color: #3b82f6;
            font-size: 1.8rem;
        }

        .nav-menu {
            list-style: none;
            padding: 0;
            margin: 0;
            flex: 1;
        }

        .nav-item {
            margin-bottom: 0.2rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 0.7rem 0.9rem;
            border-radius: 10px;
            color: #cbd5e1;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            cursor: pointer;
            position: relative;
        }

        .nav-link i {
            width: 1.5rem;
            font-size: 1.15rem;
            text-align: center;
            flex-shrink: 0;
        }

        .nav-link .menu-text {
            flex: 1;
        }

        .nav-link .arrow {
            transition: transform 0.3s ease;
            font-size: 0.8rem;
            margin-left: auto;
        }

        .nav-link .arrow.open {
            transform: rotate(180deg);
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.06);
            color: #f1f5f9;
        }

        .nav-link.active {
            background: #0d6efd !important;
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
        }

        .nav-link.active .arrow {
            color: #fff;
        }

        .nav-link.active-indicator {
            border-left: 4px solid #0d6efd;
            background: rgba(13, 110, 253, 0.08);
            color: #f1f5f9;
        }

        .nav-link.active-indicator i {
            color: #0d6efd;
        }

        .sub-menu {
            list-style: none;
            padding: 0;
            margin: 0.2rem 0 0.2rem 1.8rem;
            overflow: hidden;
            max-height: 0;
            transition: max-height 0.35s ease, opacity 0.25s ease;
            opacity: 0;
        }

        .sub-menu.open {
            max-height: 400px;
            opacity: 1;
        }

        .sub-menu .nav-link {
            padding: 0.5rem 0.9rem 0.5rem 1.2rem;
            font-size: 0.9rem;
            border-radius: 8px;
        }

        .sub-menu .nav-link i {
            font-size: 0.9rem;
            width: 1.3rem;
        }

        .sub-menu .nav-link.active {
            background: #0d6efd !important;
            color: #ffffff !important;
        }

        .sidebar-divider {
            height: 1px;
            background: rgba(255, 255, 255, 0.06);
            margin: 0.8rem 0.5rem;
        }

        /* ---------- TOP NAVBAR ---------- */
        .top-navbar {
            position: fixed;
            top: 0;
            right: 0;
            left: 260px;
            height: 70px;
            background: #ffffff;
            z-index: 1040;
            padding: 0 2rem;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 1.5rem;
            transition: left 0.3s ease;
        }

        .profile-img {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #e9edf4;
        }

        .hc-user-code {
            color: #fff;
            border-radius: 18px;
            padding: 8px 20px;
            background: linear-gradient(135deg, #6793d6 0%, #413b3b 40%, #0939a0 60%);
            background-size: 400%;
            font-weight: 600;
            animation: Gradient 5s ease infinite;
        }

        @keyframes Gradient {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

        /* ---------- MAIN CONTENT ---------- */
        .main-content {
            margin-left: 260px;
            margin-top: 70px;
            padding: 20px;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        /* ---------- SIDEBAR TOGGLE BUTTON (mobile) ---------- */
        .sidebar-toggle {
            display: none;
            background: transparent;
            border: none;
            color: #1e293b;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.2rem 0.6rem;
            transition: color 0.2s;
        }

        .sidebar-toggle:hover {
            color: #0d6efd;
        }

        /* ---------- OVERLAY (mobile) ---------- */
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            z-index: 1045;
        }

        .overlay.show {
            display: block;
        }

        /* ---------- RESPONSIVE DESIGN ---------- */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
            }

            .sidebar.show {
                transform: translateX(0);
                height: 100%;
            }

            .sidebar-toggle {
                display: block;
            }

            .top-navbar {
                left: 0;
                padding: 0 1.2rem;
            }

            .main-content {
                margin-left: 0;
                padding: 15px;
            }
        }

        @media (max-width: 576px) {
            .top-navbar {
                flex-wrap: wrap;
                height: auto;
                min-height: 60px;
                padding: 0.6rem 1rem;
                gap: 0.5rem;
            }

            .hc-user-code {
                font-size: 0.75rem;
                padding: 5px 12px;
            }

            .profile-img {
                width: 32px;
                height: 32px;
            }

            .main-content {
                margin-top: 60px;
                padding: 12px;
            }

            .dropdown-menu {
                min-width: 200px !important;
            }
        }

        /* ---------- ACCESSIBILITY ---------- */
        .nav-link:focus-visible {
            outline: 2px solid #0d6efd;
            outline-offset: 2px;
        }

        /* Scrollbar styling */
        .sidebar::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 10px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.25);
        }
    </style>
</head>

<body>

    <!-- ========== OVERLAY (mobile) ========== -->
    <div class="overlay" id="sidebarOverlay"></div>

    <!-- ========== SIDEBAR ========== -->
    <aside class="sidebar" id="sidebar" role="navigation" aria-label="Main sidebar">
        <div class="sidebar-brand">
            <i class="fas fa-cube"></i>
            <span>My Dashboard</span>
        </div>

        <ul class="nav-menu">
            <!-- Dashboard -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                    href="{{ route('dashboard') }}"
                    aria-current="{{ request()->routeIs('dashboard') ? 'page' : 'false' }}">
                    <i class="fas fa-chart-pie"></i>
                    <span class="menu-text">Dashboard</span>
                </a>
            </li>

            <!-- Social Links -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('user-social-links') ? 'active' : '' }}"
                    href="{{ route('user-social-links') }}">
                    <i class="fas fa-share-alt"></i>
                    <span class="menu-text">Social Links</span>
                </a>
            </li>

            <!-- Mail Automations (dropdown) -->
            @php
                $isMailActive = request()->routeIs('master-data-list') ||
                                request()->routeIs('master-link-document') ||
                                request()->routeIs('report.campaign');
            @endphp
            <li class="nav-item">
                <a class="nav-link {{ $isMailActive ? 'active-indicator' : '' }}"
                    role="button"
                    aria-expanded="{{ $isMailActive ? 'true' : 'false' }}"
                    aria-controls="submenu-mail"
                    id="mailParentLink"
                    onclick="toggleSubmenu(event)">
                    <i class="fas fa-envelope"></i>
                    <span class="menu-text">Mail Automations</span>
                    <i class="fas fa-chevron-down arrow {{ $isMailActive ? 'open' : '' }}"></i>
                </a>
                <ul class="sub-menu {{ $isMailActive ? 'open' : '' }}" id="submenu-mail" role="menu">
                    <li class="nav-item" role="none">
                        <a class="nav-link {{ request()->routeIs('master-link-document') ? 'active' : '' }}"
                            href="{{ route('master-link-document') }}" role="menuitem">
                            <i class="fas fa-link"></i> Link
                        </a>
                    </li>
                    <li class="nav-item" role="none">
                        <a class="nav-link {{ request()->routeIs('master-data-list') ? 'active' : '' }}"
                            href="{{ route('master-data-list') }}" role="menuitem">
                            <i class="fas fa-database"></i> Master
                        </a>
                    </li>
                    <li class="nav-item" role="none">
                        <a class="nav-link {{ request()->routeIs('report.campaign') ? 'active' : '' }}"
                            href="{{ route('report.campaign') }}" role="menuitem">
                            <i class="fas fa-chart-line"></i> Tracking Report
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Leads -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('leads-index') ? 'active' : '' }}"
                    href="{{ route('leads-index') }}">
                    <i class="fas fa-users"></i>
                    <span class="menu-text">Leads</span>
                </a>
            </li>


            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('privacyPolicy') ? 'active' : '' }}"
                    href="{{ route('privacyPolicy') }}">
                    <i class="fas fa-shield-alt"></i>
                    <span class="menu-text">Privacy Policy</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('terms') ? 'active' : '' }}"
                    href="{{ route('terms') }}">
                    <i class="fas fa-file-contract"></i>
                    <span class="menu-text">Terms & Conditions</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('landingPage') ? 'active' : '' }}"
                    href="{{ route('landingPage') }}">
                    <i class="fas fa-home"></i>
                    <span class="menu-text">Landing Page</span>
                </a>
            </li>

        </ul>
    </aside>

    <!-- ========== TOP NAVBAR ========== -->
    <nav class="top-navbar" id="topNavbar">
        <!-- Mobile toggle button -->
        <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar" aria-expanded="false">
            <i class="fas fa-bars"></i>
        </button>

        <div style="display:flex; align-items:center; gap:1rem; flex-wrap:wrap; margin-left:auto;">
            <!-- User Code -->
            <div class="hc-user-code">
                <strong>{{ auth()->user()->user_code ?? 'ADMIN' }}</strong>
            </div>

            <!-- User Dropdown -->
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle"
                    id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ asset(auth()->user()->user_image ?? 'images/user-icon.jpg') }}"
                        class="profile-img me-2" alt="User avatar">
                    <strong class="d-none d-sm-inline">{{ auth()->user()->name ?? 'User' }} {{ auth()->user()->lastname ?? '' }}</strong>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownUser">
                    <li>
                        <a class="dropdown-item" href="{{ route('user-profile') }}">
                            <i class="fas fa-user me-2"></i>Profile
                        </a>
                    </li>
                     <li>
                        <a href="{{ url('connect-gmail') }}" class="gmail-connect-btn">
                            <i class="fas fa-envelope me-2"></i>Connect Gmail
                        </a>

                         <!--@if(!auth()->user()->gmail_token)-->
                        <!--    <a href="{{ url('connect-gmail') }}" class="gmail-connect-btn">-->
                        <!--        <i class="fas fa-envelope me-2"></i>Connect Gmail-->
                        <!--    </a>-->
                        <!--@else-->
                        <!--    <span class="gmail-connected">-->
                        <!--        <i class="fas fa-envelope me-2"></i>Gmail Connected-->
                        <!--    </span>-->
                        <!--@endif-->
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a class="dropdown-item" href="#" id="logoutBtn">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- ========== MAIN CONTENT ========== -->
    <div class="main-content" id="mainContent">
        @yield('content')
    </div>

    <!-- ========== SCRIPTS ========== -->
    <script>
        $(document).ready(function() {
            // Logout functionality
            $('#logoutBtn').click(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('logout') }}",
                    type: "POST",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        toastr.success("Logged out successfully");
                        setTimeout(function() {
                            window.location.href = "{{ route('login-data') }}";
                        }, 1000);
                    },
                    error: function() {
                        toastr.error("Logout failed");
                    }
                });
            });
        });

        // ========== SIDEBAR TOGGLE (Custom JavaScript) ==========
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const toggleBtn = document.getElementById('sidebarToggle');

            // Toggle sidebar on button click
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const isOpen = sidebar.classList.contains('show');
                    sidebar.classList.toggle('show');
                    overlay.classList.toggle('show');
                    toggleBtn.setAttribute('aria-expanded', !isOpen);
                    // Prevent body scroll when sidebar is open
                    document.body.style.overflow = !isOpen ? 'hidden' : '';
                });
            }

            // Close sidebar on overlay click
            if (overlay) {
                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show');
                    if (toggleBtn) toggleBtn.setAttribute('aria-expanded', 'false');
                    document.body.style.overflow = '';
                });
            }

            // Close sidebar on Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && sidebar.classList.contains('show')) {
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show');
                    if (toggleBtn) toggleBtn.setAttribute('aria-expanded', 'false');
                    document.body.style.overflow = '';
                }
            });

            // ========== SUBMENU TOGGLE (Custom, no Bootstrap) ==========
            window.toggleSubmenu = function(event) {
                const parentLink = event.currentTarget;
                const submenu = document.getElementById('submenu-mail');
                const arrow = parentLink.querySelector('.arrow');
                const isOpen = submenu.classList.contains('open');

                // Toggle
                submenu.classList.toggle('open');
                arrow.classList.toggle('open');
                parentLink.setAttribute('aria-expanded', !isOpen);

                // Maintain active-indicator
                if (!isOpen) {
                    parentLink.classList.add('active-indicator');
                } else {
                    const hasActiveChild = submenu.querySelector('.nav-link.active') !== null;
                    if (!hasActiveChild) {
                        parentLink.classList.remove('active-indicator');
                    }
                }
                event.preventDefault();
            };

            // Ensure parent stays highlighted if child is active (on page load)
            const mailParent = document.getElementById('mailParentLink');
            const submenuMail = document.getElementById('submenu-mail');
            if (submenuMail && submenuMail.classList.contains('open')) {
                mailParent.classList.add('active-indicator');
                mailParent.setAttribute('aria-expanded', 'true');
            }

            // ========== KEYBOARD NAVIGATION ==========
            document.querySelectorAll('.nav-link[role="button"]').forEach(link => {
                link.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        this.click();
                    }
                });
            });

            // Handle window resize - close sidebar on large screens
            window.addEventListener('resize', function() {
                if (window.innerWidth > 991.98 && sidebar.classList.contains('show')) {
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show');
                    if (toggleBtn) toggleBtn.setAttribute('aria-expanded', 'false');
                    document.body.style.overflow = '';
                }
            });
        });
    </script>
</body>

</html>
