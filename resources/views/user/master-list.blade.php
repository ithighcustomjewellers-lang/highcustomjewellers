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
                        <th>Business Type</th>
                        <th>Whatsapp</th>
                        <th>Telegram</th>
                        <th>Business</th>
                        <th>Created</th>
                        <th>Updated</th>
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

</style>
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script>
$(document).ready(function() {

    var table = $('#sequencesTable').DataTable({
        processing:true,
        serverSide:true,
        responsive:true,
        ajax:{
            url:"{{ route('getSequences-data') }}",
            type:'POST',
            headers:{
                'X-CSRF-TOKEN':'{{ csrf_token() }}'
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
                data:'telegram_link',
                name:'telegram_link'
            },

            {
                data:'business_link',
                name:'business_link'
            },

            {
                data:'created_at',
                name:'created_at'
            },

            {
                data:'updated_at',
                name:'updated_at'
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


    // =======================
    // INLINE EDIT
    // =======================

    $('#sequencesTable tbody').on('click', 'td.editable-cell', function() {
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
            url:"{{ route('master-list-sequences-inlineUpdate') }}",
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
        $('#sequencesTable tbody tr').each(function(){
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

</script>
