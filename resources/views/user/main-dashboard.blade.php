@extends('user.dashboard')
<style>
   .dashboard-card {
    border-radius: 15px;
    transition: all 0.3s ease;
    overflow: hidden;
}

.dashboard-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.12) !important;
}

.dashboard-icon {
    width: 55px;
    height: 55px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: #fff;
}

.bg-mail { background: #0d6efd; }
.bg-pending { background: #ffc107; }
.bg-sent { background: #198754; }
.bg-seen { background: #6f42c1; }
.bg-fail { background: #dc3545; }
.bg-interest { background: #20c997; }
.bg-not-interest { background: #fd7e14; }
</style>

@section('content')
    <div class="row g-4">
   {{-- <a href="{{ route('report.campaign', ['status' => 'not_interested']) }}" class="text-decoration-none"> --}}
   {{-- <a href="{{ route('report.campaign', ['status' => 'interested']) }}" class="text-decoration-none"> --}}
{{-- <a href="{{ route('report.campaign', ['status' => 'failed']) }}" class="text-decoration-none"> --}}
    <!-- Total Mail -->
    <div class="col-lg-2 col-md-6">
        <div class="card dashboard-card shadow-sm border-0">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-primary mb-1">Total Mail</h6>
                    <h2>{{ number_format($stats['total_mail']) }}</h2>
                    <small class="text-muted">Total emails</small>
                </div>
                <div class="dashboard-icon bg-mail">
                    <i class="fas fa-envelope"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Sent -->
    <div class="col-lg-2 col-md-6">
        <div class="card dashboard-card shadow-sm border-0">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-success mb-1">Sent</h6>
                    <h2>{{ number_format($stats['sent']) }}</h2>
                    <small class="text-muted">Successfully sent</small>
                </div>
                <div class="dashboard-icon bg-sent">
                    <i class="fas fa-paper-plane"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Seen -->
    <div class="col-lg-2 col-md-6">
        <div class="card dashboard-card shadow-sm border-0">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-purple mb-1">Seen</h6>
                    <h2>{{ number_format($stats['seen']) }}</h2>
                    <small class="text-muted">Opened emails</small>
                </div>
                <div class="dashboard-icon bg-seen">
                    <i class="fas fa-eye"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Fail -->
    <div class="col-lg-2 col-md-6">
        <div class="card dashboard-card shadow-sm border-0">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-danger mb-1">Fail</h6>
                    <h2>{{ number_format($stats['fail']) }}</h2>
                    <small class="text-muted">Failed emails</small>
                </div>
                <div class="dashboard-icon bg-fail">
                    <i class="fas fa-times-circle"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Interested -->
    <div class="col-lg-2 col-md-6">
        <div class="card dashboard-card shadow-sm border-0">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-success mb-1">Interested</h6>
                    <h2>{{ number_format($stats['interested']) }}</h2>
                    <small class="text-muted">Interested leads</small>
                </div>
                <div class="dashboard-icon bg-interest">
                    <i class="fas fa-thumbs-up"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Not Interested -->
    <div class="col-lg-2 col-md-6">
        <div class="card dashboard-card shadow-sm border-0">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-danger mb-1">Not Interested</h6>
                    <h2>{{ number_format($stats['not_interested']) }}</h2>
                    <small class="text-muted">Not interested leads</small>
                </div>
                <div class="dashboard-icon bg-not-interest">
                    <i class="fas fa-thumbs-down"></i>
                </div>
            </div>
        </div>
    </div>
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
                        chart: {
                            type: 'donut',
                            height: 450
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
                success: function(response) {

                    if (platformChart) {
                        platformChart.destroy();
                    }

                    var options = {
                        series: response.series,
                        chart: {
                            type: 'donut',
                            height: 450
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
