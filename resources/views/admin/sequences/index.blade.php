@extends('admin.layouts.layout')

@section('title', 'Sequence List')

@section('content')
<div class="container mt-4">
    <div class="card p-4">
        <h4 class="mb-3">Sequence List</h4>
        <div class="mb-2">
            <a href="{{ route('admin-sequences-create') }}" class="btn btn-success">+ Add New Sequence</a>
        </div>
        <table id="sequencesTable" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>View</th>
                    <th>Edit</th>
                    <th>ID</th>
                    <th>Step</th>
                    <th>Subject</th>
                    <th>Type</th>
                    <th>Gap Days</th>
                    <th>Variant</th>
                    <th>Created</th>
                    <th>Updated</th>
                    <th>Delete</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

<script src="{{ asset('js/jquery.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#sequencesTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                url: '{{ route("admin-sequences-data") }}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },
            columnDefs: [{
                targets: 2, // ID column (we still show but keep index)
                orderable: false,
                searchable: false,
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            }],
            columns: [
                { data: 'view', orderable: false, searchable: false },
                { data: 'edit', orderable: false, searchable: false },
                { data: 'id' },
                { data: 'step' },
                { data: 'subject' },
                { data: 'type' },
                { data: 'gap_days' },
                { data: 'variant' },
                { data: 'created_at' },
                { data: 'updated_at' },
                { data: 'delete', orderable: false, searchable: false }
            ],
            order: [[3, 'desc']], // order by step or ID? adjust as needed
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100]
        });
    });




</script>
