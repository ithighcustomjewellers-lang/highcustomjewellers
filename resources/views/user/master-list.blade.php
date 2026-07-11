{{-- resources/views/user/sequences/index.blade.php --}}

@extends('user.dashboard')
@section('content')
<div class="container-fluid py-4">

    <!-- ======================= -->
    <!-- PAGE HEADER -->
    <!-- ======================= -->
    <div class="sequence-header">
        <div>
            <h2 class="main-title">
                Sequence List
            </h2>
            <p class="sub-title mb-0">
                Manage your automated email campaign sequences
            </p>
        </div>

        <a href="{{ route('master-view-page') }}" class="btn add-sequence-btn">
            <i class="fa fa-plus-circle me-2"></i>
            Add New Sequence
        </a>
    </div>

    <!-- ======================= -->
    <!-- MAIN CARD -->
    <!-- ======================= -->
    <div class="sequence-card">
        <!-- ======================= -->
        <!-- TABLE -->
        <!-- ======================= -->
        <div class="table-responsive">
            <table id="sequencesTable" class="table align-middle sequence-table">
                <thead>
                    <tr>
                        <th>Edit</th>
                        <th>Step</th>
                        <th>Gap Days</th>
                        <th>Variant</th>
                        <th>Message</th>
                        <th>Subject</th>
                        <th>Type</th>
                        <th>Whatsapp</th>
                        <th>Created</th>
                        <th>Updated</th>
                        <th>Delete</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection

<style>
body{
    background:#f4f7fb;
}

/* ======================= */
/* HEADER */
/* ======================= */

.sequence-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
    flex-wrap:wrap;
    gap:15px;
}

.main-title{
    font-size:30px;
    font-weight:800;
    color:#111827;
    margin-bottom:5px;
}

.sub-title{
    font-size:14px;
    color:#6b7280;
}

/* ======================= */
/* BUTTON */
/* ======================= */

.add-sequence-btn{
    background:linear-gradient(135deg,#2563eb,#4f46e5);
    color:#fff;
    border:none;
    border-radius:14px;
    padding:12px 22px;
    font-weight:700;
    font-size:14px;
    transition:0.3s;
    box-shadow:
        0 8px 20px rgba(37,99,235,0.25);
}

.add-sequence-btn:hover{
    transform:translateY(-2px);
    color:#fff;
    box-shadow:
        0 12px 30px rgba(37,99,235,0.35);
}

/* ======================= */
/* CARD */
/* ======================= */

.sequence-card{
    background:#fff;
    border-radius:24px;
    padding:22px;
    box-shadow:
        0 10px 40px rgba(15,23,42,0.06);
}

/* ======================= */
/* TABLE */
/* ======================= */

.sequence-table{
    border-collapse:separate;
    border-spacing:0 12px;
    width:100% !important;
}

.sequence-table thead th{
    background:#111827;
    color:#fff;
    border:none !important;
    padding:16px 14px;
    font-size:13px;
    font-weight:700;
    text-transform:uppercase;
    letter-spacing:0.4px;
    white-space:nowrap;
}

.sequence-table thead th:first-child{
    border-top-left-radius:14px;
    border-bottom-left-radius:14px;
}

.sequence-table thead th:last-child{
    border-top-right-radius:14px;
    border-bottom-right-radius:14px;
}

.sequence-table tbody tr{
    background:#fff;
    transition:0.25s;
    border-radius:18px;
    overflow:hidden;
    box-shadow:
        0 3px 10px rgba(0,0,0,0.04);
}

.sequence-table tbody tr:hover{
    transform:translateY(-2px);
    box-shadow:
        0 10px 25px rgba(0,0,0,0.08);
}

.sequence-table tbody td{
    padding:16px 14px;
    border-top:none !important;
    border-bottom:none !important;
    vertical-align:middle;
    color:#374151;
    font-size:14px;
}

/* ======================= */
/* INLINE EDIT */
/* ======================= */

.editable-cell{
    cursor:pointer;
}

.editable-cell:hover{
    background:#f8fafc;
}

.editable-input{
    width:100%;
    border:2px solid #2563eb;
    border-radius:10px;
    padding:8px 10px;
    outline:none;
    font-size:14px;
    font-weight:600;
    background:#fff;
}

/* ======================= */
/* BADGES */
/* ======================= */

.step-badge{
    background:#ecfeff;
    color:#0f766e;
}

.gap-badge{
    background:#fff7ed;
    color:#c2410c;
}

.variant-badge{
    background:#eef2ff;
    color:#4338ca;
}

.step-badge,
.gap-badge,
.variant-badge{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    min-width:70px;
    padding:8px 14px;
    border-radius:999px;
    font-size:13px;
    font-weight:700;
}

/* ======================= */
/* EDIT BUTTON */
/* ======================= */

.edit-btn{
    border:none;
    border-radius:12px;
    padding:9px 16px;
    background:#111827;
    color:#fff;
    font-size:13px;
    font-weight:600;
    transition:0.2s;
}

.edit-btn:hover{
    background:#2563eb;
    color:#fff;
    transform:translateY(-1px);
}

/* ======================= */
/* LINKS */
/* ======================= */

.table-link{
    color:#2563eb;
    font-weight:600;
    text-decoration:none;
}

.table-link:hover{
    text-decoration:underline;
}

/* ======================= */
/* DATATABLE SEARCH */
/* ======================= */

.dataTables_filter{
    margin-bottom:20px;
}

.dataTables_filter input{
    border:1px solid #d1d5db !important;
    border-radius:14px !important;
    padding:10px 14px !important;
    min-width:260px;
    background:#fff;
    outline:none !important;
}

.dataTables_filter input:focus{
    border-color:#2563eb !important;
    box-shadow:none !important;
}

/* ======================= */
/* SHOW ENTRIES */
/* ======================= */

.dataTables_length select{
    border-radius:12px !important;
    padding:6px 10px !important;
    border:1px solid #d1d5db !important;
}

/* ======================= */
/* PAGINATION */
/* ======================= */

.dataTables_paginate{
    margin-top:25px !important;
    cursor: pointer;
}

.paginate_button{
    border:none !important;
    background:#fff !important;
    color:#374151 !important;
    border-radius:12px !important;
    padding:10px 16px !important;
    margin:0 4px !important;
    font-weight:600;
    transition:0.25s;
    box-shadow: 0 3px 10px rgba(0,0,0,0.05);
}

.paginate_button:hover{
    background:#2563eb !important;
    color:#fff !important;
    transform:translateY(-1px);
}

.paginate_button.current{
    background:#2563eb !important;
    color:#fff !important;
    box-shadow:
    0 8px 18px rgba(37,99,235,0.25);
}

/* ======================= */
/* PREVIOUS / NEXT */
/* ======================= */

.paginate_button.previous,
.paginate_button.next{
    background:#111827 !important;
    color:#fff !important;
}

/* ======================= */
/* INFO */
/* ======================= */

.dataTables_info{
    color:#6b7280;
    margin-top:20px;
    font-weight:500;
}

/* ======================= */
/* MOBILE */
/* ======================= */

@media(max-width:768px){
    .main-title{
        font-size:24px;
    }
    .sequence-card{
        padding:14px;
    }
    .dataTables_filter input{
        min-width:100%;
    }
}


.first-updated-row td {
    background-color: #A6A6A6 !important;
    color: #000 !important;
}

/* =======================
/* ADMIN UPDATE HIGHLIGHTING */
/* ======================= */

/* .admin-updated-row {
    background-color: #fff3cd !important;
    border-left: 4px solid #ffc107 !important;
    animation: highlightPulse 2s ease-in-out;
    transition: all 0.3s ease;
}

.admin-updated-row:hover {
    background-color: #ffe69c !important;
}

.first-updated-row {
    background-color: #f8d7da !important;
    border-left: 4px solid #dc3545 !important;
    font-weight: 600;
    animation: highlightPulse 1.5s ease-in-out 3;
}

.first-updated-row:hover {
    background-color: #f5c6cb !important;
} */

/* @keyframes highlightPulse {
    0% {
        background-color: #ffeeba;
        transform: scale(1);
    }
    50% {
        background-color: #ffc107;
        transform: scale(1.01);
    }
    100% {
        background-color: #fff3cd;
        transform: scale(1);
    }
}

.new-badge {
    font-size: 10px;
    padding: 4px 8px;
    border-radius: 4px;
    animation: pulseBadge 0.8s ease-in-out infinite;
}

@keyframes pulseBadge {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

.editable-input {
    width: 100% !important;
    border: 1px solid #ffc107 !important;
    background-color: #fff !important;
    padding: 4px 8px !important;
    border-radius: 4px !important;
    box-shadow: 0 0 5px rgba(255, 193, 7, 0.3) !important;
}

.editable-cell {
    cursor: pointer;
    position: relative;
}

.editable-cell:hover {
    background-color: #f8f9fa !important;
}

.editable-cell:hover::after {
    content: '✏️';
    position: absolute;
    right: 5px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 12px;
    opacity: 0.5; */
/* }  */
</style>

<script src="{{ asset('js/jquery.min.js') }}"></script>
<script>
$(document).ready(function() {

    var table = $('#sequencesTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: "{{ route('getSequences-data') }}",
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            dataSrc: function(json) {
                // Store admin updated IDs for highlighting
                if (json.data && json.data.length > 0) {
                    window.adminUpdatedIds = [];
                    json.data.forEach(function(row) {
                        if (row.is_admin_updated == 1) {
                            window.adminUpdatedIds.push(row.id);
                        }
                    });
                }
                return json.data;
            }
        },
        columns: [
            {
                data: 'edit',
                name: 'edit',
                orderable: false,
                searchable: false
            },
            {
                data: 'step',
                name: 'step'
            },
            {
                data: 'gap_days',
                name: 'gap_days'
            },
            {
                data: 'variant',
                name: 'variant'
            },
            {
                data: 'message',
                name: 'message'
            },
            {
                data: 'subject',
                name: 'subject'
            },
            {
                data: 'type',
                name: 'type'
            },
            {
                data: 'whatsapp_link',
                name: 'whatsapp_link',
                orderable: false,
                searchable: false
            },
            {
                data: 'created_at',
                name: 'created_at'
            },
            {
                data: 'updated_at',
                name: 'updated_at'
            },
            {
                data: 'delete',
                orderable: false,
                searchable: false
            }
        ],
        order: [
            [1, 'asc']  // Default order by Step descending
        ],
        pageLength: 10,
        language: {
            paginate: {
                previous: '← Previous',
                next: 'Next →'
            },
            searchPlaceholder: "Search sequence..."
        },
        drawCallback: function(settings) {
            applyAdminUpdateHighlighting();
        }
    });

    // =======================
    // ADMIN UPDATE HIGHLIGHTING
    // =======================

    function applyAdminUpdateHighlighting() {
        var rows = $('#sequencesTable tbody tr');
        var firstUpdatedRow = null;

        rows.each(function(index) {
            var row = $(this);
            var rowData = table.row(row).data();

            row.removeClass('admin-updated-row first-updated-row');

            if (rowData && rowData.is_admin_updated == 1) {
                row.addClass('admin-updated-row');

                if (!firstUpdatedRow) {
                    firstUpdatedRow = row;
                }
            }
        });

        if (firstUpdatedRow) {
            firstUpdatedRow.addClass('first-updated-row');

            var stepCell = firstUpdatedRow.find('td:eq(4)');
            if (stepCell.length && !stepCell.find('.new-badge').length) {
                stepCell.prepend('<span class="badge bg-danger new-badge me-1">🔥 NEW</span>');
            }
        }
    }

    // =======================
    // INLINE EDIT
    // =======================

    $('#sequencesTable tbody').on('click', 'td.editable-cell', function() {
        if ($(this).find('input').length) return;

        let td = $(this);
        let currentValue = td.text().trim();
        currentValue = currentValue.replace(/🔥/g, '').trim();
        let field = td.data('field');
        let id = td.data('id');

        let input = $('<input>', {
            type: 'text',
            value: currentValue,
            class: 'editable-input form-control form-control-sm'
        });

        td.html(input);
        input.focus();

        input.on('input', function() {
            if (field === 'variant') {
                let start = this.selectionStart;
                let end = this.selectionEnd;
                let oldValue = $(this).val();
                let newValue = oldValue.toUpperCase();
                if (oldValue !== newValue) {
                    $(this).val(newValue);
                    this.setSelectionRange(start, end);
                }
            }
        });

        input.on('blur', function() {
            let newValue = input.val().trim();
            if (field === 'variant') {
                newValue = newValue.toUpperCase();
            }
            saveInlineEdit(td, id, field, newValue);
        }).on('keypress', function(e) {
            if (e.which === 13) {
                input.blur();
            }
        });
    });

    function saveInlineEdit(td, id, field, newValue) {
        $.ajax({
            url: "{{ route('master-list-sequences-inlineUpdate') }}",
            type: "POST",
            data: {
                _token: '{{ csrf_token() }}',
                id: id,
                field: field,
                value: newValue
            },
            success: function(response) {
                td.text(newValue);
                table.ajax.reload(null, false);
            },
            error: function(xhr) {
                alert(xhr.responseJSON?.message || 'Validation error');
                table.ajax.reload(null, false);
            }
        });
    }

    // =======================
    // POLLING FOR ADMIN UPDATES (Every 30 seconds)
    // =======================

    setInterval(function() {
        $.ajax({
            url: "{{ route('check-admin-sequences-updates') }}",
            type: "GET",
            success: function(response) {
                if (response.has_updates) {
                    table.ajax.reload(null, false);
                }
            }
        });
    }, 30000);

});

// =======================
// DELETE FUNCTION
// =======================

function deleteList(id) {
    Swal.fire({
        title: 'Delete Sequence?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes Delete'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ route('sequence-delete') }}",
                type: "POST",
                data: {
                    id: id,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire(
                            'Deleted!',
                            response.message,
                            'success'
                        );
                        $('#sequencesTable')
                            .DataTable()
                            .ajax
                            .reload(null, false);
                    } else {
                        Swal.fire(
                            'Error',
                            response.message,
                            'error'
                        );
                    }
                },
                error: function() {
                    Swal.fire(
                        'Error',
                        'Something went wrong',
                        'error'
                    );
                }
            });
        }
    });
}
</script>
