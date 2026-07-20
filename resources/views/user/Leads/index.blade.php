@extends('user.dashboard')

@section('content')
    <style>
        /* ----- GLOBAL RESET & FONTS ----- */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: radial-gradient(circle at 10% 20%, #ffffff, #ffffff);
            font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', sans-serif;
            color: #eef2ff;
        }

        /* custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #1a1e2a;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: #f6d58b;
            border-radius: 10px;
        }

        /* ----- GLASS CARD (enhanced) ----- */
        .glass-card {
            background: rgba(18, 22, 32, 0.7);
            backdrop-filter: blur(14px);
            border: 1px solid rgba(246, 213, 139, 0.2);
            border-radius: 28px;
            box-shadow: 0 20px 35px -12px rgba(0, 0, 0, 0.5), 0 0 0 0.5px rgba(246, 213, 139, 0.1) inset;
            transition: all 0.2s ease;
        }

        .glass-card:hover {
            border-color: rgba(246, 213, 139, 0.4);
            box-shadow: 0 25px 40px -14px rgba(0, 0, 0, 0.6);
        }

        /* ----- TYPOGRAPHY & GOLD ACCENTS ----- */
        .gold-title {
            font-weight: 700;
            font-size: 1.0rem;
            background: linear-gradient(135deg, #f9e0a0, #e4b363);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            letter-spacing: -0.3px;
            border-left: 4px solid #f6d58b;
            padding-left: 1rem;
        }

        /* ----- FORM CONTROLS (glossy dark) ----- */
        .custom-input,
        .form-select.custom-input {
            background: #0f121c;
            border: 1px solid #2a2e3f;
            color: #f0f3fa;
            height: 52px;
            border-radius: 18px;
            padding: 0 1rem;
            font-size: 0.95rem;
            transition: all 0.2s;
        }

        .custom-input:focus,
        .form-select.custom-input:focus {
            border-color: #f6d58b;
            box-shadow: 0 0 0 3px rgba(246, 213, 139, 0.2);
            outline: none;
            background: #0b0e16;
        }

        .custom-input::placeholder {
            color: #5a6077;
            font-weight: 400;
        }

        /* date inputs specific */
        .date-input {
            background: #7c88ad;
            border: 1px solid #2a2e3f;
            border-radius: 18px;
            height: 52px;
            padding: 0 1rem;
            color: #f0f3fa;
            width: 100%;
        }

        .date-input:focus {
            border-color: #f6d58b;
            outline: none;
            box-shadow: 0 0 0 2px rgba(246, 213, 139, 0.3);
        }

        /* buttons */
        .btn-gold {
            background: linear-gradient(105deg, #f6d58b, #e7b651);
            border: none;
            color: #0a0c12;
            font-weight: 700;
            padding: 0 1.5rem;
            height: 52px;
            border-radius: 18px;
            transition: all 0.2s;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .btn-gold:hover {
            transform: translateY(-2px);
            filter: brightness(1.03);
            box-shadow: 0 12px 20px -8px rgba(230, 182, 70, 0.4);
        }

        .btn-gold:active {
            transform: translateY(1px);
        }

        .btn-outline-gold {
            background: transparent;
            border: 1px solid #f6d58b;
            color: #f6d58b;
            border-radius: 18px;
            height: 52px;
            font-weight: 500;
            transition: 0.2s;
        }

        .btn-outline-gold:hover {
            background: rgba(246, 213, 139, 0.1);
            border-color: #ffdf8c;
            transform: translateY(-1px);
        }

        /* ----- BADGES (B2B / B2C) ----- */
        .badge-b2b {
            background: #1a3350;
            color: #9bc5ff;
            padding: 6px 16px;
            border-radius: 40px;
            font-weight: 600;
            font-size: 0.8rem;
            letter-spacing: 0.3px;
            display: inline-block;
        }

        .badge-b2c {
            background: #5e3d12;
            color: #ffda99;
            padding: 6px 16px;
            border-radius: 40px;
            font-weight: 600;
            font-size: 0.8rem;
        }

        /* ----- TABLE (modern, clean) ----- */
        .lead-table {
            border-radius: 24px;
            overflow-x: auto;
        }

        .lead-table table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .lead-table th {
            background: #0e111b;
            color: #f6d58b;
            font-weight: 600;
            padding: 1rem 1rem;
            border-bottom: 1px solid #262b3a;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .lead-table td {
            background: #11141f;
            padding: 1rem;
            border-bottom: 1px solid #1e2332;
            color: #eef2ff;
            vertical-align: middle;
        }

        .lead-table tr:hover td {
            background: #171c2b;
            transition: 0.1s;
        }

        /* today badge */
        .today-badge {
            background: linear-gradient(125deg, #f6d58b, #e4b05a);
            color: #0a0c12;
            padding: 8px 22px;
            border-radius: 60px;
            font-weight: 700;
            font-size: 0.9rem;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        /* filter section active indicator */
        .filter-active {
            background: rgba(246, 213, 139, 0.1);
            border-radius: 20px;
            padding: 6px 14px;
            font-size: 0.8rem;
            color: #f6d58b;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        /* pagination refined */
        .pagination-nav {
            display: flex;
            justify-content: center;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 28px;
        }

        .page-btn {
            background: #0f121c;
            border: 1px solid #2d3245;
            color: #f6d58b;
            padding: 8px 18px;
            border-radius: 40px;
            font-weight: 500;
            transition: all 0.2s;
            cursor: pointer;
        }

        .page-btn:hover:not(.disabled) {
            background: #1e2538;
            border-color: #f6d58b;
            transform: translateY(-2px);
        }

        .page-btn.active {
            background: #f6d58b;
            color: #0a0c12;
            border-color: #f6d58b;
            font-weight: 700;
        }

        .page-btn.disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }

        /* loader skeleton */
        .skeleton-row td {
            height: 68px;
            background: linear-gradient(90deg, #151a28 25%, #1f2538 50%, #151a28 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }

        .btn-delete {
            width: 38px;
            height: 38px;
            border: none;
            border-radius: 10px;
            background: #fff1f2;
            color: #dc3545;
            cursor: pointer;
            transition: all .3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-delete:hover {
            background: #dc3545;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(220, 53, 69, .25);
        }

        .btn-delete:active {
            transform: scale(.95);
        }

        .btn-delete i {
            font-size: 14px;
        }

        .delete-popup {
            border-radius: 18px !important;
        }

        @keyframes shimmer {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }

        /* responsive */
        @media (max-width: 768px) {
            .gold-title {
                font-size: 1.3rem;
            }

            .btn-gold,
            .btn-outline-gold,
            .custom-input {
                height: 46px;
            }

            .date-input {
                height: 46px;
            }

            .lead-table th,
            .lead-table td {
                padding: 0.75rem;
                font-size: 0.8rem;
            }

            .today-badge {
                font-size: 0.75rem;
                padding: 5px 16px;
            }
        }

        /* today badge */
        .today-badge {
            background: linear-gradient(125deg, #f6d58b, #e4b05a);
            color: #0a0c12;
            padding: 10px 22px;
            border-radius: 60px;
            font-weight: 700;
            font-size: 0.9rem;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            white-space: nowrap;
        }

        /* download demo */
        .download-box {
            height: 46px;
            padding: 0 18px;
            border-radius: 60px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(246, 213, 139, 0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.25s ease;
            backdrop-filter: blur(10px);
        }

        .download-box:hover {
            background: rgba(246, 213, 139, 0.12);
            border-color: #f6d58b;
            transform: translateY(-2px);
        }

        .upload-box {
            width: 150px;
            height: 48px;
            border-radius: 14px;
            border: 1px dashed rgba(246, 213, 139, 0.5);
            background: rgba(255, 255, 255, 0.04);
            cursor: pointer;
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .upload-box:hover {
            background: rgba(246, 213, 139, 0.08);
            border-color: #f6d58b;
            transform: translateY(-2px);
        }

        .upload-content {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #f6d58b;
            font-weight: 600;
            font-size: 14px;
        }

        .upload-icon {
            font-size: 18px;
        }

        .download-box {
            height: 48px;
            border-radius: 14px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            background: rgba(255, 255, 255, 0.05);
            cursor: pointer;
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .download-box:hover {
            background: rgba(255, 255, 255, 0.08);
            transform: translateY(-2px);
            border-color: #f6d58b;
        }

        .container {
            max-width: 100%;
        }

        .btn-delete {
            background: white;
        }
    </style>

    <div class="container py-4">
        <!-- NEW LEAD ENTRY CARD -->
        <div class="glass-card p-4 p-md-3 mb-3">
            <h3 class="gold-title mb-4">✨ New Lead Entry</h3>
            <form id="leadForm">
                @csrf
                <div class="row g-3 align-items-end">
                    <div class="col-md-2 col-sm-6">
                        <input type="email" name="email" id="email" class="custom-input w-100" placeholder="Email ID *"
                            autocomplete="off">
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <input type="text" name="name" id="name" class="custom-input w-100"
                            placeholder="First Name *">
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <input type="text" name="lastname" id="lastname" class="custom-input w-100"
                            placeholder="Last Name *">
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <input type="text" name="company_name" id="company_name" class="custom-input w-100"
                            placeholder="Company Name *">
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex gap-2">
                            <select name="type" id="leadType" class="form-select custom-input">
                                <option value="">Select Type</option>
                                <option value="B2B">B2B</option>
                                <option value="B2C">B2C</option>
                            </select>
                            <button type="button" class="btn-gold px-4" onclick="submitLeadFromButton()">Add Lead</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- LIVE LEAD LIST CARD -->
        <div class="glass-card p-4 p-md-3">
            {{-- <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <!-- Left Title -->
                <h3 class="gold-title mb-0">
                    📋 Live Lead List
                </h3>
                <!-- Right Side -->
                <div class="d-flex align-items-center gap-3 flex-wrap">

                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle shadow-sm" type="button"
                            data-bs-toggle="dropdown" data-bs-auto-close="outside">

                            <i class="fas fa-calendar-alt me-2"></i>
                            {{ ucfirst(request('filter', 'Today')) }}
                        </button>

                        <div class="dropdown-menu dropdown-menu-end p-3 shadow" style="min-width:250px;">

                            <a class="dropdown-item" href="?filter=today">
                                <i class="fas fa-calendar-day me-2"></i> Today
                            </a>

                            <a class="dropdown-item" href="?filter=weekly">
                                <i class="fas fa-calendar-week me-2"></i> Weekly
                            </a>

                            <a class="dropdown-item" href="?filter=monthly">
                                <i class="fas fa-calendar-alt me-2"></i> Monthly
                            </a>

                            <a class="dropdown-item" href="?filter=yearly">
                                <i class="fas fa-calendar me-2"></i> Yearly
                            </a>

                            <hr>

                            <form method="GET" action="{{ route('dashboard') }}">
                                <input type="hidden" name="filter" value="custom">

                                <div class="mb-2">
                                    <label class="form-label small fw-semibold">Start Date</label>
                                    <input type="date" name="start_date" class="form-control form-control-sm"
                                        value="{{ request('start_date') }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">End Date</label>
                                    <input type="date" name="end_date" class="form-control form-control-sm"
                                        value="{{ request('end_date') }}">
                                </div>

                                <button type="submit" class="btn btn-primary btn-sm w-100">
                                    <i class="fas fa-search me-1"></i>
                                    Apply Filter
                                </button>
                            </form>

                        </div>
                    </div>

                    <form action="{{ route('bulk-leads-upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <label class="upload-box mb-0">
                            <input type="file" name="excel_file" accept=".xlsx,.xls,.csv" hidden
                                onchange="this.form.submit()">

                            <div class="upload-content">
                                <i class="fa fa-file-excel-o upload-icon"></i>
                                <span>Excel Upload</span>
                            </div>
                        </label>
                    </form>
                    <!-- Download Demo -->
                    <a href="{{ route('download-leads-demo') }}" class="download-box text-decoration-none">
                        <div class="upload-content">
                            <i class="fa fa-download upload-icon"></i>
                            <span>Download Excel File</span>
                        </div>
                    </a>
                    <!-- Today Badge - dynamically updated -->
                    <div class="today-badge">
                        📅 <span id="periodLabel">Today</span> added:
                        <span id="todayCount" class="fw-bold">0</span>
                    </div>
                </div>
            </div> --}}

            <div class="d-flex flex-wrap align-items-center gap-3 mb-4">

                {{-- Success/Error Messages --}}
                @if (Session::has('success'))
                    <script>
                        toastr.success(@json(Session::get('success')));
                    </script>
                @endif

                @if (Session::has('error'))
                    <script>
                        toastr.error(@json(Session::get('error')));
                    </script>
                @endif

                {{-- Download Sample --}}
                <a href="{{ route('download-leads-demo') }}" class="download-box text-decoration-none">
                    <div class="upload-content">
                        <i class="fa fa-download upload-icon"></i>
                        <span>Download Excel File</span>
                    </div>
                </a>

                {{-- Excel Upload --}}
                <form action="{{ route('bulk-leads-upload') }}" method="POST" enctype="multipart/form-data" class="mb-0">
                    @csrf
                    <label class="upload-box mb-0">
                        <input type="file" name="excel_file" accept=".xlsx,.xls,.csv" hidden
                            onchange="this.form.submit()">

                        <div class="upload-content">
                            <i class="fa fa-file-excel-o upload-icon"></i>
                            <span>Excel Upload</span>
                        </div>
                    </label>
                </form>

                {{-- Lead Count --}}
                <div class="today-badge">
                    📅
                    <span id="periodLabel">
                        {{ ucfirst(request('filter', 'Today')) }}
                    </span>
                    Added :
                    <span id="todayCount" class="fw-bold">
                        0
                    </span>
                </div>

                {{-- Date Filter --}}
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle shadow-sm" type="button"
                        data-bs-toggle="dropdown" data-bs-auto-close="outside"
                        style="background: #6c757d; color: #fff; border-color: #6c757d;">
                        <i class="fas fa-calendar-alt me-2"></i>
                        {{ ucfirst(request('filter', 'Today')) }}
                    </button>

                    <div class="dropdown-menu dropdown-menu-end p-3 shadow" style="min-width:280px;">

                        <a class="dropdown-item" href="?filter=today">
                            <i class="fas fa-calendar-day me-2"></i>
                            Today
                        </a>

                        <a class="dropdown-item" href="?filter=weekly">
                            <i class="fas fa-calendar-week me-2"></i>
                            Weekly
                        </a>

                        <a class="dropdown-item" href="?filter=monthly">
                            <i class="fas fa-calendar-alt me-2"></i>
                            Monthly
                        </a>

                        <a class="dropdown-item" href="?filter=yearly">
                            <i class="fas fa-calendar me-2"></i>
                            Yearly
                        </a>

                        <hr>

                        {{-- Change this route if your page route is different --}}
                        <form method="GET" action="{{ url()->current() }}">
                            <input type="hidden" name="filter" value="custom">

                            <div class="mb-2">
                                <label class="form-label fw-semibold small">
                                    Start Date
                                </label>

                                <input type="date" name="start_date" class="form-control form-control-sm"
                                    value="{{ request('start_date') }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold small">
                                    End Date
                                </label>

                                <input type="date" name="end_date" class="form-control form-control-sm"
                                    value="{{ request('end_date') }}">
                            </div>

                            <button class="btn btn-primary btn-sm w-100">
                                <i class="fas fa-search me-1"></i>
                                Apply Filter
                            </button>
                        </form>
                    </div>
                </div>
            </div>


            {{-- <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                <div>
                    @if (Session::has('success'))
                        <script>
                            toastr.success(@json(Session::get('success')));
                        </script>
                    @endif

                    @if (Session::has('error'))
                        <script>
                            toastr.error(@json(Session::get('error')));
                        </script>
                    @endif
                </div>
            </div> --}}

            <!-- TABLE -->
            <div class="table-responsive lead-table">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Email</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Company</th>
                            <th>Type</th>
                            <th>Tracking</th>
                            <th>Added Date</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody id="leadTableBody">
                        <!-- skeleton loading injected by js -->
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            <div id="paginationContainer" class="pagination-nav mt-4"></div>
        </div>
    </div>

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script>
        // =====================================================================
        // 1. GLOBAL STATE
        // =====================================================================
        let currentPage = 1;
        let currentFilter = 'today';
        let currentStartDate = '';
        let currentEndDate = '';
        let isLoading = false;
        let refreshInterval = null;

        // =====================================================================
        // 2. HELPER FUNCTIONS (globally defined)
        // =====================================================================
        function formatDateToDMY(dateInput) {
            if (!dateInput) return '';
            if (typeof dateInput === 'string' && dateInput.match(/^\d{4}-\d{2}-\d{2}$/)) {
                let parts = dateInput.split('-');
                return `${parts[2]}-${parts[1]}-${parts[0]}`;
            }
            let date = new Date(dateInput);
            if (isNaN(date.getTime())) return '';
            let day = String(date.getDate()).padStart(2, '0');
            let month = String(date.getMonth() + 1).padStart(2, '0');
            let year = date.getFullYear();
            return `${day}-${month}-${year}`;
        }

        function showSkeleton() {
            let $tbody = $('#leadTableBody');
            if (!$tbody.length) return;
            $tbody.empty();
            for (let i = 0; i < 5; i++) {
                $tbody.append(
                    '<tr class="skeleton-row"><td colspan="8" style="padding:0"><div style="height:68px"></div></tr>'
                );
            }
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

        // =====================================================================
        // 3. RENDER FUNCTIONS
        // =====================================================================
        function renderTable(data) {
            if (!data.length) {
                $('#leadTableBody').html(
                    '<tr><td colspan="8" class="text-center py-5">✨ No leads found for this period</td></tr>'
                );
                return;
            }
            let html = '';
            data.forEach(function(item) {
                let formattedDate = formatDateToDMY(item.created_at);
                html += `<tr data-id="${item.id}">
                        <td class="editable-cell" data-field="email">${escapeHtml(item.email)}</td>
                        <td class="editable-cell" data-field="name">${escapeHtml(item.name)}</td>
                        <td class="editable-cell" data-field="lastname">${escapeHtml(item.lastname)}</td>
                        <td data-field="company_name">${escapeHtml(item.company_name)}</td>
                        <td class="editable-cell" data-field="type">${item.type === 'B2B' ? '<span class="badge-b2b">B2B</span>' : '<span class="badge-b2c">B2C</span>'}</td>
                        <td data-field="tracking">${item.tracking}</td>
                        <td>${formattedDate}</td>
                        <td>
                            <button class="btn-delete" onclick="deleteLead(${item.id})" title="Delete Lead">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>`;
            });
            $('#leadTableBody').html(html);
        }

        function renderPagination(pagination) {
            if (pagination.total === 0) {
                $('#paginationContainer').html('');
                return;
            }
            let html = '';
            html += `<button class="page-btn ${pagination.current_page <= 1 ? 'disabled' : ''}"
                    onclick="loadLeads(${pagination.current_page - 1})" ${pagination.current_page <= 1 ? 'disabled' : ''}>
                    ← Prev
                </button>`;

            let start = Math.max(1, pagination.current_page - 2);
            let end = Math.min(pagination.last_page, pagination.current_page + 2);

            if (start > 1) {
                html += `<button class="page-btn" onclick="loadLeads(1)">1</button>`;
                if (start > 2) html += `<span class="mx-1 text-muted">⋯</span>`;
            }

            for (let i = start; i <= end; i++) {
                html +=
                    `<button class="page-btn ${i === pagination.current_page ? 'active' : ''}" onclick="loadLeads(${i})">${i}</button>`;
            }

            if (end < pagination.last_page) {
                if (end < pagination.last_page - 1) html += `<span class="mx-1 text-muted">⋯</span>`;
                html +=
                    `<button class="page-btn" onclick="loadLeads(${pagination.last_page})">${pagination.last_page}</button>`;
            }

            html += `<button class="page-btn ${pagination.current_page >= pagination.last_page ? 'disabled' : ''}"
                    onclick="loadLeads(${pagination.current_page + 1})" ${pagination.current_page >= pagination.last_page ? 'disabled' : ''}>
                    Next →
                </button>`;
            $('#paginationContainer').html(html);
        }

        // =====================================================================
        // 4. MAIN loadLeads() – with showSkeleton flag
        // =====================================================================
        function loadLeads(page = 1, filter = null, start_date = null, end_date = null, showLoader = true) {
            if (isLoading) return;
            isLoading = true;

            if (filter === null) {
                filter = currentFilter;
                start_date = currentStartDate;
                end_date = currentEndDate;
            } else {
                currentFilter = filter;
                currentStartDate = start_date || '';
                currentEndDate = end_date || '';
            }

            currentPage = page;

            let params = {
                page: page,
                filter: filter
            };
            if (filter === 'custom' && start_date && end_date) {
                params.start_date = start_date;
                params.end_date = end_date;
            }

            // Show skeleton only when explicitly requested
            if (showLoader) {
                showSkeleton();
            }

            $.ajax({
                url: '{{ route('lead-list') }}',
                method: 'GET',
                data: params,
                success: function(response) {
                    renderTable(response.data);
                    renderPagination(response.pagination);
                    $('#todayCount').text(response.today_count);
                    updateFilterUI(filter, start_date, end_date);
                },
                error: function() {
                    if (showLoader) {
                        toastr.error('Failed to load leads');
                        $('#leadTableBody').html(
                            '<tr><td colspan="8" class="text-center py-5 text-muted">⚠️ Could not fetch leads</td></tr>'
                        );
                    }
                },
                complete: function() {
                    isLoading = false;
                }
            });
        }

        // =====================================================================
        // 5. AUTO-REFRESH (every 3 sec, no skeleton)
        // =====================================================================
        function refreshLeads() {
            loadLeads(currentPage, currentFilter, currentStartDate, currentEndDate, false);
        }

        // =====================================================================
        // 6. UI UPDATE HELPER
        // =====================================================================
        function updateFilterUI(filter, start_date, end_date) {
            let label = '';
            switch (filter) {
                case 'today':
                    label = 'Today';
                    break;
                case 'weekly':
                    label = 'Weekly';
                    break;
                case 'monthly':
                    label = 'Monthly';
                    break;
                case 'yearly':
                    label = 'Yearly';
                    break;
                case 'custom':
                    if (start_date && end_date) {
                        label = start_date + ' → ' + end_date;
                    } else {
                        label = 'Custom';
                    }
                    break;
                default:
                    label = 'Today';
            }
            $('#filterLabel').text(label);
            $('#periodLabel').text(label);
        }

        // =====================================================================
        // 7. FILTER CLICK HANDLERS
        // =====================================================================
        $(document).on('click', '.dropdown-item[data-filter]', function(e) {
            e.preventDefault();
            let filter = $(this).data('filter');
            loadLeads(1, filter, null, null);
        });

        // =====================================================================
        // 8. CUSTOM DATE RANGE
        // =====================================================================
        function applyCustomDate(e) {
            e.preventDefault();
            let start = $('#customStartDate').val();
            let end = $('#customEndDate').val();
            if (!start || !end) {
                toastr.warning('Please select both start and end dates.');
                return;
            }
            if (start > end) {
                toastr.warning('Start date cannot be after end date.');
                return;
            }
            loadLeads(1, 'custom', start, end);
            $('.dropdown-toggle').dropdown('toggle');
        }

        // =====================================================================
        // 9. SUBMIT LEAD
        // =====================================================================
        function submitLeadFromButton() {
            let type = $('#leadType').val();
            if (!type) {
                toastr.error('Please select B2B or B2C type');
                return;
            }
            submitLead(type);
        }

        function submitLead(type) {
            const form = document.getElementById('leadForm');
            const formData = new FormData(form);
            formData.append('type', type);

            let email = $('#email').val().trim();

            if (!email) {
                toastr.error('Please Enter Email');
                return;
            }

            $.ajax({
                url: '{{ route('lead-store') }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    toastr.success(response.message);
                    $('#leadForm')[0].reset();
                    $('#leadType').val('');
                    loadLeads(currentPage, currentFilter, currentStartDate, currentEndDate);
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let firstError = Object.values(errors)[0][0];
                        toastr.error(firstError);
                    } else {
                        toastr.error('Server error. Please try again.');
                    }
                }
            });
        }

        // =====================================================================
        // 10. INLINE EDITING
        // =====================================================================
        $(document).on('click', '#leadTableBody td.editable-cell', function(e) {
            if ($(this).find('input, select').length) return;

            let td = $(this);
            let currentValue = td.text().trim();
            let field = td.data('field');
            let id = td.closest('tr').data('id');
            let input;

            if (field === 'type') {
                input = $('<select>', {
                    class: 'editable-input form-select',
                    style: 'background:#0f121c; color:#fff; border-radius:12px; padding:6px;'
                });
                input.append('<option value="B2B">B2B</option><option value="B2C">B2C</option>');
                if (currentValue.includes('B2B')) input.val('B2B');
                else if (currentValue.includes('B2C')) input.val('B2C');
            } else {
                input = $('<input>', {
                    type: field === 'email' ? 'email' : 'text',
                    value: currentValue,
                    class: 'editable-input',
                    style: 'background:#0f121c; color:#fff; border:1px solid #f6d58b; border-radius:12px; padding:8px; width:100%;'
                });
            }

            td.html(input);
            input.focus();

            input.on('blur', function() {
                let newValue = input.val().trim();
                if (field === 'type') newValue = input.val();
                saveInlineEdit(td, id, field, newValue);
            }).on('keypress', function(e) {
                if (e.which === 13 && field !== 'type') {
                    input.blur();
                }
            });
        });

        function saveInlineEdit(td, id, field, newValue) {
            if (field !== 'type' && field !== 'company_name' && newValue === '') {
                toastr.warning('Value cannot be empty');
                return;
            }

            let originalHtml = td.html();
            td.html('<span class="text-muted"><i class="fas fa-spinner fa-spin"></i> saving...</span>');

            $.ajax({
                url: '{{ route('leads-update', ':id') }}'.replace(':id', id),
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}'
                },
                data: {
                    field: field,
                    value: newValue
                },
                success: function(response) {
                    if (response.success) {
                        if (field === 'type') {
                            let badge = newValue === 'B2B' ? '<span class="badge-b2b">B2B</span>' :
                                '<span class="badge-b2c">B2C</span>';
                            td.html(badge);
                        } else {
                            td.html(escapeHtml(newValue));
                        }
                    } else {
                        td.html(originalHtml);
                        toastr.error(response.message || 'Update failed');
                    }
                },
                error: function(xhr) {
                    td.html(originalHtml);
                    let msg = xhr.responseJSON?.message || 'Server error';
                    toastr.error(msg);
                }
            });
        }

        // =====================================================================
        // 11. DELETE LEAD
        // =====================================================================
        function deleteLead(id) {
            Swal.fire({
                title: 'Delete Lead?',
                text: "This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-trash"></i> Yes, Delete',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                customClass: {
                    popup: 'delete-popup'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('leads-destroy', ':id') }}'.replace(':id', id),
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: response.message || 'Lead deleted successfully.',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            $(`tr[data-id="${id}"]`).fadeOut(300, function() {
                                $(this).remove();
                                if ($('#leadTableBody tr').length === 0) {
                                    $('#leadTableBody').html(`
                                        <tr>
                                            <td colspan="8" class="text-center py-5">
                                                ✨ No leads found
                                            </td>
                                        </tr>
                                    `);
                                }
                            });
                            loadLeads(currentPage, currentFilter, currentStartDate, currentEndDate);
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: xhr.responseJSON?.message || 'Failed to delete lead'
                            });
                        }
                    });
                }
            });
        }

        // =====================================================================
        // 12. INITIAL LOAD & AUTO-REFRESH
        // =====================================================================
        $(document).ready(function() {
            const urlParams = new URLSearchParams(window.location.search);
            let filter = urlParams.get('filter') || 'today';
            let start = urlParams.get('start_date') || '';
            let end = urlParams.get('end_date') || '';
            if (filter === 'custom' && (!start || !end)) {
                filter = 'today';
                start = '';
                end = '';
            }
            currentFilter = filter;
            currentStartDate = start;
            currentEndDate = end;
            updateFilterUI(filter, start, end);

            // Initial load (with skeleton)
            loadLeads(1, filter, start, end, true);

            // Start auto-refresh every 2 seconds (no skeleton)
            if (refreshInterval) clearInterval(refreshInterval);
            refreshInterval = setInterval(refreshLeads, 2000);
        });

        // Pause auto-refresh while editing
        $(document).on('focus', '.editable-input', function() {
            clearInterval(refreshInterval);
        });

        $(document).on('blur', '.editable-input', function() {
            setTimeout(() => {
                if (refreshInterval) clearInterval(refreshInterval);
                refreshInterval = setInterval(refreshLeads, 2000);
            }, 1000);
        });

        // Clean up interval on page unload
        $(window).on('beforeunload', function() {
            if (refreshInterval) clearInterval(refreshInterval);
        });
    </script>
@endsection
