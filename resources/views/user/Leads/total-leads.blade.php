@extends('user.dashboard')

<style>
    /* ==============================
   DataTable Wrapper
=================================*/
.dataTables_wrapper {
    font-size: 14px;
    color: #495057;
}

/* ==============================
   Top Controls
=================================*/
/* Top Controls (Show Entries + Search) */
.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter {
    display: inline-flex;
    align-items: center;
    margin-bottom: 15px;
}

.dataTables_wrapper .dataTables_filter {
    float: right;
}

.dataTables_wrapper .dataTables_length {
    float: left;
}

/* Parent Row */
.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter {
    width: auto;
}

.dataTables_wrapper .dataTables_filter input {
    margin-left: 8px;
    border-radius: 8px;
    border: 1px solid #ddd;
    padding: 6px 10px;
}

.dataTables_wrapper .dataTables_length select {
    margin: 0 8px;
    border-radius: 8px;
    border: 1px solid #ddd;
    padding: 4px 8px;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter {
        float: none;
        display: flex;
        justify-content: space-between;
        width: 100%;
        margin-bottom: 10px;
    }

    .dataTables_wrapper .dataTables_filter input {
        width: 180px;
    }
}

/* ==============================
   Table
=================================*/
#totalLeadsTable {
    border-collapse: separate !important;
    border-spacing: 0;
    width: 100% !important;
}

#totalLeadsTable thead th {
    background: linear-gradient(90deg,#0d6efd,#0056d6);
    color: #fff;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 13px;
    letter-spacing: .5px;
    padding: 14px;
    border: none;
    white-space: nowrap;
}

#totalLeadsTable thead th:first-child {
    border-top-left-radius: 10px;
}

#totalLeadsTable thead th:last-child {
    border-top-right-radius: 10px;
}

#totalLeadsTable tbody td {
    padding: 14px;
    vertical-align: middle;
    border-bottom: 1px solid #edf2f7;
    font-size: 14px;
}

#totalLeadsTable tbody tr {
    transition: .25s;
}

#totalLeadsTable tbody tr:hover {
    background: #f8fbff;
}

/* Zebra */
#totalLeadsTable tbody tr:nth-child(even) {
    background: #fcfcfc;
}

/* ==============================
   Sorting Icons
=================================*/
table.dataTable thead .sorting,
table.dataTable thead .sorting_asc,
table.dataTable thead .sorting_desc {
    background-position: center right 10px;
}

/* ==============================
   Pagination
=================================*/
.dataTables_paginate {
    margin-top: 20px !important;
}

.dataTables_paginate .paginate_button {
    border-radius: 8px !important;
    border: none !important;
    margin: 0 3px;
    padding: 8px 14px !important;
    background: #f1f3f5 !important;
    color: #495057 !important;
    transition: .3s;
}

.dataTables_paginate .paginate_button:hover {
    background: #0d6efd !important;
    color: #fff !important;
}

.dataTables_paginate .paginate_button.current {
    background: #0d6efd !important;
    color: #fff !important;
    font-weight: bold;
}

/* ==============================
   Info Text
=================================*/
.dataTables_info {
    padding-top: 15px !important;
    color: #6c757d;
}

/* ==============================
   Processing
=================================*/
.dataTables_processing {
    background: #fff !important;
    border-radius: 10px;
    padding: 15px !important;
    color: #0d6efd !important;
    font-weight: bold;
}

/* ==============================
   Card
=================================*/
.card {
    border: none;
    border-radius: 15px;
    overflow: hidden;
}

.card-header {
    border: none;
    padding: 18px 25px;
    font-size: 18px;
    font-weight: 600;
}

/* ==============================
   Responsive
=================================*/
@media (max-width:768px){

    .dataTables_filter{
        text-align:left !important;
        margin-top:15px;
    }

    .dataTables_filter input{
        width:100% !important;
        min-width:100%;
    }

    .dataTables_length{
        text-align:left !important;
    }

    #totalLeadsTable th,
    #totalLeadsTable td{
        white-space:nowrap;
        font-size:13px;
    }

}
</style>

@section('content')

<h1 class="h3 mb-4 text-gray-800">Total Leads</h1>

    <div class="container-fluid mt-4">
        <div class="card shadow">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="totalLeadsTable" class="table table-bordered table-striped w-100">
                        <thead class="table-dark">
                        <tr>
                            <th>Email</th>
                            <th>Name</th>
                            <th>Last Name</th>
                            <th>Company</th>
                            <th>Type</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

<script src="{{ asset('js/jquery.min.js') }}"></script>
<script>

$(function () {
    $('#totalLeadsTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        autoWidth: false,
        ajax: {
            url: "{{ route('total.leads.data.list') }}",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },

            error: function (xhr) {
                console.log(xhr.responseText);
            }
        },

        columns: [
            {
                data: 'email',
                name: 'email'
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'lastname',
                name: 'lastname'
            },
            {
                data: 'company_name',
                name: 'company_name'
            },
            {
                data: 'type',
                name: 'type'
            },
            {
                data: 'created_at',
                name: 'created_at'
            },
            {
                data: 'updated_at',
                name: 'updated_at'
            }

        ],

        order: [[5,'desc']],
        pageLength:10,
        lengthMenu:[
            [10,25,50,100],
            [10,25,50,100]
        ],

        language:{
            search:"Search :",
            searchPlaceholder:"Search Leads",
            zeroRecords:"No Leads Found",
            processing:"Loading...",
            paginate:{
                previous:"← Previous",
                next:"Next →"
            }
        }
    });
});
</script>
