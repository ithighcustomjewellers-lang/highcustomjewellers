@extends('admin.layouts.layout')
@section('title', 'User List')
@section('content')

    <div class="container mt-4">
        <div class="card p-4">
            <h4 class="mb-3">User List</h4>
            <table id="usersTable" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>

    </div>

@endsection
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">

<style>
    .card {
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    }

    table.dataTable thead {
        background: #4e73df;
        color: #fff;
    }

    table.dataTable tbody tr:hover {
        background: #f1f5ff;
    }
</style>

<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/dataTables.bootstrap5.min.js') }}"></script>

<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
            columns: [{
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
                    data: 'created_at'
                },
                {
                    data: 'action',
                    orderable: false,
                    searchable: false
                }
            ],

            order: [
                [0, 'desc']
            ],
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],

            language: {
                search: "🔍 Search:",
                lengthMenu: "Show _MENU_ entries",
                paginate: {
                    next: "→",
                    previous: "←"
                }
            }
        });
    });

function deleteuserList(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel!',
    }).then((result) => {

        if (result.isConfirmed) {
            // Proceed with delete
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
                    Swal.fire({
                        title: 'Deleted!',
                        text: response.message,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    });

                    $('#usersTable').DataTable().ajax.reload();
                },
                error: function(xhr) {
                    console.log(xhr.responseText);

                    Swal.fire({
                        title: 'Error!',
                        text: 'Failed to delete user.',
                        icon: 'error'
                    });
                }
            });

        } else {
            // Cancel clicked
            Swal.fire({
                title: 'Cancelled',
                text: 'User is safe 🙂',
                icon: 'info',
                timer: 1500,
                showConfirmButton: false
            });
        }

    });
}
</script>
