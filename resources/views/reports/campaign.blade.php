@extends('user.dashboard')

<style>
.dataTables_wrapper .dataTables_paginate {
    margin-top: 24px;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 4px;
}

/* Base Pagination Buttons */
.dataTables_wrapper .dataTables_paginate .paginate_button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 40px;
    height: 40px;
    padding: 0 16px;
    margin: 2;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    background: #ffffff;
    color: #374151;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s ease-in-out;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

/* Button Hover State */
.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background: #f3f4f6;
    color: #111827;
    border-color: #d1d5db;
    transform: translateY(-1px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

/* Active / Current Page Button */
.dataTables_wrapper .dataTables_paginate .paginate_button.current,
.dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
    background: #2563eb;
    color: #ffffff;
    border-color: #2563eb;
    box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3);
}

/* Previous & Next Special Buttons */
.dataTables_wrapper .dataTables_paginate .paginate_button.previous,
.dataTables_wrapper .dataTables_paginate .paginate_button.next {
    background: #111827;
    color: #ffffff;
    border-color: #111827;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.previous:hover,
.dataTables_wrapper .dataTables_paginate .paginate_button.next:hover {
    background: #1f2937;
    color: #ffffff;
    border-color: #1f2937;
}

/* Disabled State (e.g., Prev button on page 1) */
.dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
.dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover {
    background: #f3f4f6;
    color: #9ca3af;
    border-color: #e5e7eb;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
    opacity: 0.6;
}
</style>
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2>📊 Email Tracking Report</h2>
                </div>
            </div>
        </div>
    </div>
    <!-- DataTable -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="campaignTable" class="table table-bordered table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th width="5%">#</th>
                            <th width="12%">Lead Name</th>
                            <th width="15%">Lead Email</th>
                            <th width="5%">Step</th>
                            <th width="18%">Subject</th>
                            <th width="8%">Status</th>
                            <th width="10%">Scheduled At</th>
                            <th width="10%">Sent At</th>
                            <th width="10%">Seen At</th>
                            <th width="10%">WhatsApp</th>
                            <th width="10%">Instagram</th>
                            <th width="10%">Facebook Messenger</th>
                            <th width="10%">Threads</th>
                            <th width="10%">Telegram</th>
                            <th width="10%">Snapchat</th>
                            <th width="10%">X</th>
                            <th width="10%">linkedin</th>
                            <th width="10%">Other</th>
                            <th width="10%">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be populated by DataTable -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">📧 Email Campaign Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailsContent">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary"></div>
                    <p class="mt-2">Loading...</p>
                </div>
            </div>
        </div>
    </div>
</div>
    <link rel="stylesheet" href="{{ asset('css/colReorder.dataTables.min.css') }}">
    <script src="{{ asset('js/dataTables.colReorder.min.js') }}"></script>

<script>

$(document).ready(function () {
    let table = $('#campaignTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,

        // Column Reorder
        colReorder: true,
        // Save column order, search, page etc.
        stateSave: true,
        stateDuration: -1,
        ajax: {
            url: "{{ route('report.campaign.data') }}",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        },

        columns: [
            {data: null, name: 'sr_no', orderable: false, searchable: false,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;}
            },
            { data: 'lead_name', name: 'leads.name' },
            { data: 'lead_email', name: 'leads.email' },
            { data: 'step', name: 'sequences.step' },
            { data: 'subject', name: 'sequences.subject' },
            { data: 'status_badge', name: 'campaign_logs.status', orderable: true },
            { data: 'scheduled_at', name: 'campaign_logs.scheduled_at' },
            { data: 'sent_at', name: 'campaign_logs.sent_at' },
            { data: 'seen_at', name: 'campaign_logs.seen_at' },
            { data: 'whatsapp_clicks', orderable: false, searchable: false },
            { data: 'instagram_clicks', orderable: false, searchable: false },
            { data: 'facebook_messenger_clicks', orderable: false, searchable: false },
            { data: 'threads_clicks', orderable: false, searchable: false },
            { data: 'telegram_clicks', orderable: false, searchable: false },
            { data: 'snapchat_clicks', orderable: false, searchable: false },
            { data: 'x_clicks', orderable: false, searchable: false },
            { data: 'linkedin_clicks', orderable: false, searchable: false },
            { data: 'other_clicks', orderable: false, searchable: false },
            { data: 'total_clicks', orderable: false, searchable: false }
        ],


        order: [
            [0, 'desc']
        ],
        pageLength: 10,
        language: {
            paginate: {
                previous: '← Previous',
                next: 'Next →'
            },
            search: "🔍 Search:",
            searchPlaceholder: "Search leads, emails, subjects..."
        },
        drawCallback: function () {
            $('[data-bs-toggle="tooltip"]').tooltip();
        }
    });
});

</script>
@endsection
