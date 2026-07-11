@extends('user.dashboard')
<style>
    .dashboard-stats {
        display: grid;
        gap: 18px;
        grid-template-columns: repeat(7, 1fr);
    }

    .dashboard-card {
        border-radius: 14px;
        transition: .3s;
        height: 100%;
    }

    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 .75rem 1.5rem rgba(0, 0, 0, .12) !important;
    }

    .card-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .dashboard-card h6 {
        font-size: 15px;
        margin-bottom: 6px;
        font-weight: 600;
    }

    .dashboard-card h2 {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .dashboard-card small {
        color: #6c757d;
        font-size: 13px;
    }

    .dashboard-icon {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 20px;
        flex-shrink: 0;
    }

    /* Colors */
    .bg-mail {
        background: #0d6efd;
    }

    .bg-pending {
        background: #ffc107;
    }

    .bg-sent {
        background: #198754;
    }

    .bg-seen {
        background: #6f42c1;
    }

    .bg-fail {
        background: #dc3545;
    }

    .bg-interest {
        background: #20c997;
    }

    .bg-not-interest {
        background: #fd7e14;
    }

    /* Responsive */

    @media (max-width:1600px) {
        .dashboard-stats {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    @media (max-width:992px) {
        .dashboard-stats {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width:768px) {
        .dashboard-stats {
            grid-template-columns: repeat(2, 1fr);
        }

        .dashboard-card h2 {
            font-size: 22px;
        }

        .dashboard-icon {
            width: 45px;
            height: 45px;
            font-size: 18px;
        }
    }

    @media (max-width:576px) {
        .dashboard-stats {
            grid-template-columns: 1fr;
        }
    }
</style>

{{-- problem a hai ki dashboard me date ka filter add kiya hua hai
for example mene sent mail 09/07/2026 kiya tha or mene filter kiya 10/07/2026 means today ok, so aaj se recode me 0 aayenga but
fir mene sent pe click kiya so wo purana means 09/07/2026 ka mail show hua to a problem kaise solve kare  --}}

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Dashboard</h2>
            <p class="text-muted mb-0">Welcome back! Here's what's happening.</p>
        </div>

        <div class="dropdown">
            <button class="btn btn-outline-secondary dropdown-toggle shadow-sm" type="button" data-bs-toggle="dropdown"
                data-bs-auto-close="outside">

                <i class="fas fa-calendar-alt me-2"></i>
                {{ ucfirst(request('filter', 'Today')) }}
            </button>

            <div class="dropdown-menu dropdown-menu-end p-3 shadow" style="min-width:220px;">

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
    </div>
    <div class="row g-4">



        <div class="dashboard-stats">
            {{-- Total Mail --}}
            <a href="{{ route('report.campaign', [
                'filter' => request('filter', 'today'),
                'from' => 'dashboard',
                'start_date' => request('start_date'),
                'end_date' => request('end_date'),
            ]) }}"
                class="text-decoration-none text-dark">
                <div class="card dashboard-card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="card-info">
                            <div>
                                <h6 class="text-primary">Total Mail</h6>
                                <h2>{{ number_format($stats['total_mail']) }}</h2>
                                <small>Total emails</small>
                            </div>

                            <div class="dashboard-icon bg-mail">
                                <i class="fas fa-envelope"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>

            {{-- Pending --}}
            <a href="{{ route('report.campaign', [
                'status' => 'pending',
                'filter' => request('filter', 'today'),
                'from' => 'dashboard',
                'start_date' => request('start_date'),
                'end_date' => request('end_date'),
            ]) }}"
                class="text-decoration-none text-dark">
                <div class="card dashboard-card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="card-info">
                            <div>
                                <h6 class="text-warning">Pending</h6>
                                <h2>{{ number_format($stats['pending']) }}</h2>
                                <small>Pending emails</small>
                            </div>

                            <div class="dashboard-icon bg-pending">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>

            {{-- Sent --}}
            <a href="{{ route('report.campaign', [
                'status' => 'send',
                'filter' => request('filter', 'today'),
                'from' => 'dashboard',
                'start_date' => request('start_date'),
                'end_date' => request('end_date'),
            ]) }}"
                class="text-decoration-none text-dark">
                <div class="card dashboard-card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="card-info">
                            <div>
                                <h6 class="text-success">Sent</h6>
                                <h2>{{ number_format($stats['sent']) }}</h2>
                                <small>Successfully sent</small>
                            </div>

                            <div class="dashboard-icon bg-sent">
                                <i class="fas fa-paper-plane"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>

            {{-- Seen --}}
            <a href="{{ route('report.campaign', [
                'status' => 'seen',
                'filter' => request('filter', 'today'),
                'from' => 'dashboard',
                'start_date' => request('start_date'),
                'end_date' => request('end_date'),
            ]) }}"
                class="text-decoration-none text-dark">
                <div class="card dashboard-card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="card-info">
                            <div>
                                <h6 class="text-purple">Seen</h6>
                                <h2>{{ number_format($stats['seen']) }}</h2>
                                <small>Opened emails</small>
                            </div>

                            <div class="dashboard-icon bg-seen">
                                <i class="fas fa-eye"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>

            {{-- Failed --}}
            <a href="{{ route('report.campaign', [
                'status' => 'failed',
                'filter' => request('filter', 'today'),
                'from' => 'dashboard',
                'start_date' => request('start_date'),
                'end_date' => request('end_date'),
            ]) }}"
                class="text-decoration-none text-dark">
                <div class="card dashboard-card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="card-info">
                            <div>
                                <h6 class="text-danger">Failed</h6>
                                <h2>{{ number_format($stats['fail']) }}</h2>
                                <small>Failed emails</small>
                            </div>

                            <div class="dashboard-icon bg-fail">
                                <i class="fas fa-times-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>

            {{-- Interested --}}
            <a href="{{ route('report.campaign', [
                'status' => 'interested',
                'filter' => request('filter', 'today'),
                'from' => 'dashboard',
                'start_date' => request('start_date'),
                'end_date' => request('end_date'),
            ]) }}"
                class="text-decoration-none text-dark">
                <div class="card dashboard-card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="card-info">
                            <div>
                                <h6 class="text-success">Interested</h6>
                                <h2>{{ number_format($stats['interested']) }}</h2>
                                <small>Interested leads</small>
                            </div>

                            <div class="dashboard-icon bg-interest">
                                <i class="fas fa-thumbs-up"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>

            {{-- Not Interested --}}
            <a href="{{ route('report.campaign', [
                'status' => 'not_interested',
                'filter' => request('filter', 'today'),
                'from' => 'dashboard',
                'start_date' => request('start_date'),
                'end_date' => request('end_date'),
            ]) }}"
                class="text-decoration-none text-dark">
                <div class="card dashboard-card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="card-info">
                            <div>
                                <h6 class="text-danger">Not Interested</h6>
                                <h2>{{ number_format($stats['not_interested']) }}</h2>
                                <small>Not interested leads</small>
                            </div>

                            <div class="dashboard-icon bg-not-interest">
                                <i class="fas fa-thumbs-down"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>

        </div>

        <div class="row mt-4">
            <!-- Campaign Status Overview -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Campaign Status Overview</h5>
                    </div>
                    <div class="card-body">
                        <div id="campaignStatusChart"></div>
                    </div>
                </div>
            </div>
            <!-- Platform Click Tracking -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Platform Click Tracking</h5>
                    </div>
                    <div class="card-body">
                        <div id="platformClickChart"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <!-- QR Scans -->
            <div class="col-md-6 mb-3">
                <div class="card dashboard-card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h6 class="text-info">
                            <i class="fas fa-qrcode"></i> QR Scans
                        </h6>
                        <h2>{{ number_format($stats['qr_scans']) }}</h2>
                        <small class="text-muted">
                            Total QR code scans
                        </small>
                    </div>
                </div>
            </div>

            <!-- Button Clicks -->
            <div class="col-md-6 mb-3">
                <div class="card dashboard-card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h6 class="text-success">
                            <i class="fas fa-mouse-pointer"></i> Button Clicks
                        </h6>
                        <h2>{{ number_format($stats['button_clicks']) }}</h2>
                        <small class="text-muted">
                            Total tracked button clicks
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <script src="{{ asset('js/apexcharts.js') }}"></script>
        <script>
            let campaignChart;
            $(document).ready(function() {

                $.ajax({
                    url: "{{ route('dashboard-chart-data') }}",
                    type: "GET",
                    data: {
                        filter: "{{ request('filter', 'today') }}",
                        start_date: "{{ request('start_date') }}",
                        end_date: "{{ request('end_date') }}"
                    },

                    success: function(response) {

                        if (campaignChart) {
                            campaignChart.destroy();
                        }

                        var options = {
                            series: [
                                response.pending,
                                response.sent,
                                response.seen,
                                response.fail,
                                response.interested,
                                response.not_interested
                            ],

                            colors: [
                                '#f59e0b', // Pending - Orange
                                '#10b981', // Sent - Green
                                '#8b5cf6', // Seen - Purple
                                '#ef4444', // Fail - Red
                                '#06b6d4', // Interested - Cyan
                                '#6b7280' // Not Interested - Gray
                            ],
                            chart: {
                                type: 'donut',
                                height: 350
                            },
                            labels: [
                                'Pending',
                                'Sent',
                                'Seen',
                                'Fail',
                                'Interested',
                                'Not Interested'
                            ],

                            legend: {
                                position: 'right'
                            },
                            plotOptions: {
                                pie: {
                                    donut: {
                                        size: '60%',
                                        labels: {
                                            show: true,
                                            total: {
                                                show: true,
                                                showAlways: true,
                                                label: 'Total Mail',
                                                formatter: function() {
                                                    return response.total_mail;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        };

                        campaignChart = new ApexCharts(
                            document.querySelector("#campaignStatusChart"),
                            options
                        );

                        campaignChart.render();
                    }
                });

            });

            let platformChart;

            $(document).ready(function() {

                $.ajax({
                    url: "{{ route('dashboard-platform-click-chart') }}",
                    type: "GET",
                    data: {
                        filter: "{{ request('filter', 'today') }}",
                        start_date: "{{ request('start_date') }}",
                        end_date: "{{ request('end_date') }}"
                    },
                    success: function(response) {

                        if (platformChart) {
                            platformChart.destroy();
                        }

                        var options = {
                            series: response.series,

                            colors: [
                                '#25D366', // WhatsApp
                                '#E4405F', // Instagram
                                '#1877F2', // Facebook Messenger
                                '#229ED9', // Telegram
                                '#0A66C2', // LinkedIn
                                '#000000', // X
                                '#7C3AED', // Threads
                                '#6B7280' // Other
                            ],
                            chart: {
                                type: 'donut',
                                height: 350
                            },
                            labels: response.labels,

                            legend: {
                                position: 'right'
                            },

                            plotOptions: {
                                pie: {
                                    donut: {
                                        size: '60%',
                                        labels: {
                                            show: true,
                                            total: {
                                                show: true,
                                                showAlways: true,
                                                label: 'Total Clicks',
                                                formatter: function() {
                                                    return response.total;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        };

                        platformChart = new ApexCharts(
                            document.querySelector("#platformClickChart"),
                            options
                        );

                        platformChart.render();
                    }
                });

            });
        </script>
    @endsection
