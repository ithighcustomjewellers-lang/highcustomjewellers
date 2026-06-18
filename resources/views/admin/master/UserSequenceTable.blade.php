@extends('admin.layouts.layout')

<style>
    .content-wrapper,
    .main-content,
    .page-content {
        overflow-x: hidden;
    }

    .table-wrapper {
        overflow-x: auto;
    }

    .main-content {
        margin-left: 250px;
        width: calc(100% - 250px);
        min-height: 100vh;
    }

    .page-content {
        margin-top: 70px;
        padding: 20px;
    }

    .table-responsive {
        overflow-x: auto;
    }

    #UserSequenceTable {
        width: 100% !important;
        min-width: 2200px;
    }

    /* Individual column search styling */
    .column-search-input {
        width: 100%;
        padding: 4px 6px;
        font-size: 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        background: #fff;
        transition: all 0.3s ease;
    }

    .column-search-input:focus {
        border-color: #80bdff;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .column-search-input::placeholder {
        color: #999;
        font-size: 11px;
    }

    .search-header {
        background: #f8f9fa !important;
        padding: 8px 5px !important;
    }

    .dataTables_filter {
        display: none !important;
    }

     /* Custom filter bar styling */
 .filter-bar {
    background: #f8f9fa;
    padding: 15px 20px;
    border-radius: 10px;
    border: 1px solid #dee2e6;
    margin-bottom: 20px;

    display: flex;
    justify-content: flex-end; /* Right End */
    align-items: flex-end;
    gap: 15px;
    flex-wrap: wrap;

    width: fit-content;
    margin-left: auto; /* Push to right */
}

.filter-group {
    min-width: 220px;
}

.filter-group label {
    display: block;
    margin-bottom: 6px;
    font-size: 13px;
    font-weight: 600;
    color: #495057;
}

.filter-group .form-select {
    height: 40px;
    min-width: 220px;
}

.filter-buttons .btn {
    height: 40px;
    min-width: 90px;
}

@media (max-width: 768px) {
    .filter-bar {
        width: 100%;
        margin-left: 0;
        justify-content: stretch;
    }

    .filter-group,
    .filter-buttons {
        width: 100%;
    }

    .filter-group .form-select,
    .filter-buttons .btn {
        width: 100%;
    }
}

</style>

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="page-title">
                    📊 All Tracking Report
                </h2>
            </div>
        </div>

       <!-- Filter Bar with Side-by-Side Layout -->
       <div class="filter-bar">
            <!-- User Name Filter -->
            <div class="filter-group">
                <label for="user_name_filter">
                    👤 Filter by User Name
                </label>
                <select id="user_name_filter" class="form-select">
                    <option value="">All Users</option>
                    @foreach($users as $user)
                        <option value="{{ $user->name }}">
                            {{ $user->name }} {{ $user->lastname }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- User Email Filter -->
            <div class="filter-group">
                <label for="user_email_filter">
                    📧 Filter by User Email
                </label>
                <select id="user_email_filter" class="form-select">
                    <option value="">All Emails</option>
                    @foreach($users as $user)
                        <option value="{{ $user->email }}">
                            {{ $user->email }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Clear Button -->
            <div class="filter-buttons">
                <button class="btn btn-secondary" id="clear_filters">
                    <i class="fas fa-times"></i> Clear
                </button>
            </div>

        </div>

        <div class="report-card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="UserSequenceTable" class="table table-bordered table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Lead Name</th>
                                <th>Lead Email</th>
                                <th>Step</th>
                                <th>Subject</th>
                                <th>Scheduled At</th>
                                <th>Status</th>
                                <th>Sent At</th>
                                <th>Seen At</th>
                                <th>WhatsApp</th>
                                <th>Instagram</th>
                                <th>Facebook Messenger</th>
                                <th>Threads</th>
                                <th>Telegram</th>
                                <th>Youtube</th>
                                <th>X</th>
                                <th>LinkedIn</th>
                                <th>Other</th>
                                <th>Total</th>
                                <th>Full Name</th>
                                <th>User Email</th>
                            </tr>
                            <!-- Individual Column Search Row -->
                            <tr class="search-header">
                                <th></th>
                                <th><input type="text" class="column-search-input" placeholder="Search Lead" data-column="1"></th>
                                <th><input type="text" class="column-search-input" placeholder="Search Email" data-column="2"></th>
                                <th><input type="text" class="column-search-input" placeholder="Search Step" data-column="3"></th>
                                <th><input type="text" class="column-search-input" placeholder="Search Subject" data-column="4"></th>
                                <th><input type="text" class="column-search-input" placeholder="Search Date" data-column="5"></th>
                                <th><input type="text" class="column-search-input" placeholder="Search Status" data-column="6"></th>
                                <th><input type="text" class="column-search-input" placeholder="Search Date" data-column="7"></th>
                                <th><input type="text" class="column-search-input" placeholder="Search Date" data-column="8"></th>
                                {{-- <th><input type="text" class="column-search-input" placeholder="Search" data-column="9" disabled></th>
                                <th><input type="text" class="column-search-input" placeholder="Search" data-column="10" disabled></th>
                                <th><input type="text" class="column-search-input" placeholder="Search" data-column="11" disabled></th>
                                <th><input type="text" class="column-search-input" placeholder="Search" data-column="12" disabled></th>
                                <th><input type="text" class="column-search-input" placeholder="Search" data-column="13" disabled></th>
                                <th><input type="text" class="column-search-input" placeholder="Search" data-column="14" disabled></th>
                                <th><input type="text" class="column-search-input" placeholder="Search" data-column="15" disabled></th>
                                <th><input type="text" class="column-search-input" placeholder="Search" data-column="16" disabled></th>
                                <th><input type="text" class="column-search-input" placeholder="Search" data-column="17" disabled></th>
                                <th><input type="text" class="column-search-input" placeholder="Search" data-column="18" disabled></th> --}}

                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th><input type="text" class="column-search-input" placeholder="Search Name" data-column="19"></th>
                                <th><input type="text" class="column-search-input" placeholder="Search Email" data-column="20"></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="{{ asset('css/colReorder.dataTables.min.css') }}">
    <script src="{{ asset('js/dataTables.colReorder.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            var table = $('#UserSequenceTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                scrollX: true,
                pageLength: 25,
                lengthMenu: [
                    [10, 25, 50, 100],
                    [10, 25, 50, 100]
                ],
                ajax: {
                    url: "{{ route('user-sequence-data-list') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: function(d) {
                        // Add individual column search values
                        $('.column-search-input').each(function() {
                            var column = $(this).data('column');
                            var value = $(this).val();
                            if (value && value.trim() !== '') {
                                d['columns[' + column + '][search][value]'] = value.trim();
                            }
                        });

                        // Add user name and email filters
                        d['user_name'] = $('#user_name_filter').val();
                        d['user_email'] = $('#user_email_filter').val();
                    }
                },
                columns: [
                   {
                        data: null,
                        name: 'sr_no',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    { data: 'lead_name', orderable: false, searchable: false },
                    { data: 'lead_email', orderable: false, searchable: false },
                    { data: 'step', orderable: false, searchable: false },
                    { data: 'subject', orderable: false, searchable: false },
                    { data: 'scheduled_at', orderable: false, searchable: false },
                    { data: 'status_badge', orderable: false, searchable: false},
                    { data: 'sent_at',orderable: false, searchable: false},
                    { data: 'seen_at', orderable: false, searchable: false },
                    { data: 'whatsapp_clicks', orderable: false, searchable: false },
                    { data: 'instagram_clicks', orderable: false, searchable: false },
                    { data: 'facebook_messenger_clicks', orderable: false, searchable: false },
                    { data: 'threads_clicks', orderable: false, searchable: false },
                    { data: 'telegram_clicks', orderable: false, searchable: false },
                    { data: 'snapchat_clicks', orderable: false, searchable: false },
                    { data: 'x_clicks', orderable: false, searchable: false },
                    { data: 'linkedin_clicks', orderable: false, searchable: false },
                    { data: 'other_clicks', orderable: false, searchable: false },
                    { data: 'total_clicks', orderable: false, searchable: false },
                    { data: 'full_name', orderable: false, searchable: false },
                    { data: 'email', orderable: false, searchable: false }
                ],
                 ordering: true,
                    order: [],
                pageLength: 10,
                language: {
                    paginate: {
                        previous: '← Previous',
                        next: 'Next →'
                    },
                    search: "🔍 Search:",
                    searchPlaceholder: "Search leads, emails, subjects..."
                },
                drawCallback: function() {
                    $('[data-bs-toggle="tooltip"]').tooltip();
                }
            });

            // Apply column search on keyup
            $('.column-search-input').on('keyup change', function() {
                var column = $(this).data('column');
                var value = $(this).val();

                // If column is searchable
                if ($(this).prop('disabled') !== true) {
                    table.column(column).search(value).draw();
                }
            });

            // Debounce search for better performance
            var searchTimeout;
            $('.column-search-input').on('keyup', function() {
                clearTimeout(searchTimeout);
                var input = $(this);
                searchTimeout = setTimeout(function() {
                    var column = input.data('column');
                    var value = input.val();
                    if (input.prop('disabled') !== true) {
                        table.column(column).search(value).draw();
                    }
                }, 300);
            });

            // Clear filters button
            $('#clear_filters').on('click', function() {
                $('#user_name_filter').val('');
                $('#user_email_filter').val('');
                // Also clear column searches
                $('.column-search-input').each(function() {
                    if ($(this).prop('disabled') !== true) {
                        $(this).val('');
                    }
                });
                table.search('').columns().search('').draw();
            });

            // Auto-apply filters on change (optional)
            $('#user_name_filter, #user_email_filter').on('change', function() {
                table.draw();
            });
        });
    </script>
@endsection
