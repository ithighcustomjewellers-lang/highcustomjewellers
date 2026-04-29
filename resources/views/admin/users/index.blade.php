@extends('admin.layouts.layout')

@section('title', 'User List')

@section('content')
    <div class="container mt-4">
        <div class="card p-4">
            <h4 class="mb-3">User List</h4>
            <table id="usersTable" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Edit</th>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Role</th>
                        <th>User Code</th>
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

        $('#usersTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,

            ajax: {
                url: '{{ route('admin-users-data') }}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },

            columnDefs: [{
                targets: 1, // serial column (ID replace)
                orderable: false,
                searchable: false,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            }],

            columns: [{
                    data: 'edit',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'id'
                },
                {
                    data: 'name'
                },
                {
                    data: 'lastname'
                },
                {
                    data: 'email'
                },
                {
                    data: 'mobile'
                },
                {
                    data: 'is_admin'
                },
                {
                    data: 'user_code'
                },
                {
                    data: 'created_at'
                },
                {
                    data: 'updated_at'
                },
                {
                    data: 'delete',
                    orderable: false,
                    searchable: false
                }
            ],

            order: [
                [2, 'desc']
            ],
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100]
        });

    });

    function deleteuserList(id) {

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {

            if (result.isConfirmed) {

                $.ajax({
                    url: "{{ route('admin-users-destroy') }}",
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: {
                        id: id
                    },

                    success: function(response) {

                        Swal.fire('Deleted!', response.message, 'success');

                        $('#usersTable').DataTable().ajax.reload();
                    },

                    error: function() {
                        Swal.fire('Error!', 'Delete failed', 'error');
                    }
                });

            }

        });
    }
</script>
