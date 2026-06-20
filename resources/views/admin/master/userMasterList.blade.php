@extends('admin.layouts.layout')

<style>
body{
    background:#f4f7fb;
}

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

.sequence-card{
    background:#fff;
    border-radius:24px;
    padding:22px;
    box-shadow:
        0 10px 40px rgba(15,23,42,0.06);
}

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

.table-link{
    color:#2563eb;
    font-weight:600;
    text-decoration:none;
}

.table-link:hover{
    text-decoration:underline;
}

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

/* .dataTables_length select{
    border-radius:12px !important;
    padding:6px 10px !important;
    border:1px solid #d1d5db !important;
} */

.dataTables_info{
    color:#6b7280;
    margin-top:20px;
    font-weight:500;
}

/* Filter bar at the bottom (end) */
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
<div class="container-fluid py-4">
    <div class="sequence-header">
        <div>
            <h2 class="main-title">
                User Master
            </h2>
        </div>
        {{-- <a href="{{ route('master-view-page') }}" class="btn add-sequence-btn">
            <i class="fa fa-plus-circle me-2"></i>
            Add New Sequence
        </a> --}}
    </div>

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

    <div class="sequence-card">
        <div class="table-responsive">
            <table id="userMasterList" class="table align-middle sequence-table">
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
                        <th>User Name</th>
                        <th>User Email</th>
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



<script src="{{ asset('js/jquery.min.js') }}"></script>
<script>
$(document).ready(function() {
    var table = $('#userMasterList').DataTable({
        processing:true,
        serverSide:true,
        responsive:true,
        ajax:{
            url:"{{ route('admin-master-data-list') }}",
            type:'POST',
            headers:{
                'X-CSRF-TOKEN':'{{ csrf_token() }}'
            },
            data: function(d) {
                // Add user name and email filters
                d['user_name'] = $('#user_name_filter').val();
                d['user_email'] = $('#user_email_filter').val();
            }
        },
        columns:[
            {
                data:'edit',
                name:'edit',
                orderable:false,
                searchable:false
            },
            {
                data:'step',
                name:'step'
            },
            {
                data:'gap_days',
                name:'gap_days'
            },
            {
                data:'variant',
                name:'variant'
            },
            {
                data:'message',
                name:'message'
            },
            {
                data:'subject',
                name:'subject'
            },
            {
                data:'type',
                name:'type'
            },
            {
                data:'whatsapp_link',
                name:'whatsapp_link'
            },
            {
                data:'user_name',
                name:'user_name'
            },
            {
                data:'user_email',
                name:'user_email'
            },
            {
                data:'created_at',
                name:'created_at'
            },
            {
                data:'updated_at',
                name:'updated_at'
            },
            {
                data:'delete',
                orderable:false,
                searchable:false
            }

        ],
        order:[[1,'asc']],
        pageLength:10,
        language:{
            paginate:{
                previous:'← Previous',
                next:'Next →'
            },
            searchPlaceholder:"Search sequence..."
        }
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

    // Auto-apply filters on change
    $('#user_name_filter, #user_email_filter').on('change', function() {
        table.draw();
    });

    $('#userMasterList tbody').on('click', 'td.editable-cell', function() {
        if ($(this).find('input').length) return;

        let td = $(this);
        let currentValue = td.text().trim();
        let field = td.data('field');
        let id = td.data('id');

        let input = $('<input>', {
            type: 'text',
            value: currentValue,
            class: 'editable-input'
        });

        td.html(input);
        input.focus();

        // Optional: force uppercase while typing
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


    function saveInlineEdit(td,id,field,newValue){
        $.ajax({
            url:"{{ route('user-master-list-sequences-inlineUpdate') }}",
            type:"POST",
            data:{
                _token:'{{ csrf_token() }}',
                id:id,
                field:field,
                value:newValue
            },
            success:function(response){
                td.text(newValue);
            },
            error:function(xhr){
                alert(xhr.responseJSON?.message || 'Validation error');
                table.ajax.reload(null,false);
            }
        });
    }


    // =======================
    // EDITABLE CELLS
    // =======================

    table.on('draw',function(){
        $('#userMasterList tbody tr').each(function(){
            let rowData = table.row(this).data();
            if(rowData && rowData.id){
                $(this).find('td:eq(1)')
                    .addClass('editable-cell')
                    .attr('data-field','step')
                    .attr('data-id',rowData.id);
                $(this).find('td:eq(2)')
                    .addClass('editable-cell')
                    .attr('data-field','gap_days')
                    .attr('data-id',rowData.id);
                $(this).find('td:eq(3)')
                    .addClass('editable-cell')
                    .attr('data-field','variant')
                    .attr('data-id',rowData.id);
            }
        });
    });

});

function userMasterDeleteList(id)
{
    Swal.fire({
        title: 'Delete Sequence?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes Delete'
    }).then((result) => {

        if(result.isConfirmed)
        {
            $.ajax({
                url: "{{ route('user-master-sequence-delete') }}",
                type: "POST",
                data: {
                    id: id,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response)
                {
                    if(response.success)
                    {
                        Swal.fire(
                            'Deleted!',
                            response.message,
                            'success'
                        );

                        $('#userMasterList')
                            .DataTable()
                            .ajax
                            .reload(null,false);
                    }
                    else {
                        Swal.fire(
                            'Error',
                            response.message,
                            'error'
                        );
                    }
                },
                error: function()
                {
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
