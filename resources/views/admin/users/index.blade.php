{{-- admin/users/index.blade.php --}}
@extends('admin.layouts.layout')

@section('title', 'User Management')


@section('content')
    <style>
        .rights-badge {
            background: #f0f2f5;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            margin: 2px;
            display: inline-block;
            white-space: nowrap;
        }

        .rights-container {
            min-width: 180px;
        }

        .status-toggle {
            cursor: pointer;
            width: 45px;
            height: 22px;
            background: #ccc;
            display: inline-block;
            border-radius: 30px;
            position: relative;
            transition: 0.3s;
        }

        .status-toggle.active {
            background: #28a745;
        }

        .status-toggle span {
            width: 18px;
            height: 18px;
            background: white;
            border-radius: 50%;
            position: absolute;
            top: 2px;
            left: 3px;
            transition: 0.3s;
        }

        .status-toggle.active span {
            left: 24px;
        }

        .action-btns .btn-sm {
            padding: 2px 8px;
            font-size: 12px;
            margin: 0 2px;
        }

        #usersTable th {
            background: #f8f9fc;
            font-weight: 600;
            font-size: 13px;
        }

        .card-header-custom {
            background: white;
            border-bottom: 1px solid #e9ecef;
            padding: 1rem 1.5rem;
        }

        .filter-section .form-control,
        .filter-section .btn {
            border-radius: 30px;
        }

        .total-users-badge {
            background: #eef2ff;
            color: #4e73df;
            padding: 8px 16px;
            border-radius: 40px;
            font-weight: 600;
        }

        .image-preview {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #ddd;
        }

        .profile-img-sm {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
        }

        .btn-outline-custom {
            border-radius: 30px;
            padding: 5px 18px;
            font-size: 13px;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 70px;
            height: 34px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        /* background (inactive = red) */
        .slider {
            position: absolute;
            cursor: pointer;
            background-color: #e74c3c;
            border-radius: 34px;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            transition: 0.4s;
        }

        /* circle */
        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: #fff;
            border-radius: 50%;
            transition: 0.4s;
        }

        /* ACTIVE = green */
        input:checked+.slider {
            background-color: #2ecc71;
        }

        /* move circle right */
        input:checked+.slider:before {
            transform: translateX(36px);
        }
    </style>

    <div class="container-fluid px-4 mt-3">
        <div class="card shadow-sm">
            <div class="card-header-custom d-flex flex-wrap justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <h5 class="mb-0 fw-bold text-dark">HIGHCUSTOM JEWELLERS</h5>
                    {{-- <button id="addUserBtn" class="btn btn-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#addEditUserModal">
                        <i class="fas fa-plus-circle"></i> Add New Team Member
                    </button> --}}
                </div>
                <div class="d-flex flex-wrap gap-2 mt-2 mt-sm-0">
                    <div class="input-group" style="width: 250px;">
                        <input type="text" id="globalSearch" class="form-control form-control-sm rounded-pill"
                            placeholder="Search All...">
                    </div>
                    <button id="filterInactiveBtn" class="btn btn-outline-danger btn-sm rounded-pill">De-Active
                        List</button>
                    <button id="showAllBtn" class="btn btn-outline-secondary btn-sm rounded-pill">All Users</button>
                    <!-- Removed the duplicate "Add New" button -->
                    <button class="btn btn-info btn-sm rounded-pill text-white" id="exportExcelBtn"><i
                            class="fas fa-file-excel"></i> Excel</button>
                    <button class="btn btn-secondary btn-sm rounded-pill" id="printCardBtn"><i class="fas fa-print"></i>
                        Print Card</button>
                    <span class="total-users-badge ms-2" id="totalUsersCount">Total Users: 0</span>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="usersTable" class="table table-bordered table-hover align-middle mb-0" style="width:100%">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">Edit</th>
                                <th width="8%">E.CODE</th>
                                <th width="12%">FULLNAME</th>
                                <th width="10%">MOBILE</th>
                                <th width="15%">EMAIL</th>
                                <th width="15%">ROLL</th>
                                <th width="18%">APP RIGHTS</th>
                                <th width="18%">ACCESS RIGHTS</th>
                                <th width="8%">STATUS</th>
                                <th width="6%">DELETE</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Add/Edit User Modal with Image Upload --}}
    <div class="modal fade" id="addEditUserModal" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                {{-- <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalTitle">Add New Team Member</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div> --}}
                <form id="userForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="user_id" id="userId">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4 text-center">
                                <div class="mb-3">
                                    <label class="form-label">Profile Image</label>
                                    <div id="imagePreviewContainer">
                                        <img id="imagePreview" src="{{ asset('images/default-avatar.png') }}"
                                            class="image-preview mb-2"
                                            style="width:100px; height:100px; border-radius:50%; object-fit:cover;">
                                    </div>
                                    <input type="file" class="form-control" name="profile_image" id="profileImage"
                                        accept="image/*">
                                    <small class="text-muted">Upload JPG, PNG</small>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <label>First Name *</label>
                                        <input type="text" name="name" id="name" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label>Last Name *</label>
                                        <input type="text" name="lastname" id="lastname" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label>Mobile Number *</label>
                                        <input type="text" name="mobile" id="mobile" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label>Email *</label>
                                        <input type="email" name="email" id="email" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label>User Code (E.CODE)</label>
                                        <input type="text" name="user_code" id="user_code" class="form-control"
                                            placeholder="Auto if empty">
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label>Password</label>
                                        <input type="password" name="password" id="password" class="form-control"
                                            placeholder="Leave blank to keep unchanged">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Member</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


<script src="{{ asset('js/jquery.min.js') }}"></script>
<script>
    $(document).ready(function() {
        let table;
        let filterInactiveActive = false;

        // Initialize DataTable
        table = $('#usersTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                url: '{{ route('admin-users-data') }}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: function(d) {
                    d.global_search = $('#globalSearch').val();
                    d.filter_inactive = filterInactiveActive ? 1 : 0;
                }
            },
            columns: [{
                    data: 'edit',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'user_code',
                    name: 'user_code'
                },
                {
                    data: 'fullname',
                    name: 'fullname'
                },
                {
                    data: 'mobile',
                    name: 'mobile'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'is_admin',
                    name: 'is_admin'
                },
                {
                    data: 'app_rights',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'access_rights',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'status',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'delete',
                    orderable: false,
                    searchable: false
                }
            ],
            order: [
                [1, 'asc']
            ],
            pageLength: 10,
            drawCallback: function() {
                updateTotalCount();
            }
        });

        function updateTotalCount() {
            $.ajax({
                url: '{{ route('admin-users-total') }}',
                type: 'GET',
                success: function(res) {
                    $('#totalUsersCount').text('Total Users: ' + res.total);
                }
            });
        }
        updateTotalCount();

            $('#globalSearch').on('keyup', function() { table.ajax.reload(); });
            $('#filterInactiveBtn').on('click', function() {
                filterInactiveActive = true;
                table.ajax.reload();
            });
            $('#showAllBtn').on('click', function() {
                filterInactiveActive = false;
                table.ajax.reload();
            });

        function resetModal() {
            $('#userForm')[0].reset();
            $('#userId').val('');
            $('#modalTitle').text('Add New Team Member');
            $('#imagePreview').attr('src', '{{ asset('images/default-avatar.png') }}');
            $('.app-right, .access-right').prop('checked', false);
            $('#statusSwitch').prop('checked', true);
            $('#password').removeAttr('required');
            $('#profileImage').val('');
        }

        $(document).on('click', '#addUserBtn', function() {
            resetModal();
        });

        // Image preview
        $('#profileImage').on('change', function(e) {
            const file = e.target.files[0];
            if (file) $('#imagePreview').attr('src', URL.createObjectURL(file));
        });

        // Submit form
        $('#userForm').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            let appRights = [];
            let accessRights = [];
            $('.app-right:checked').each(function() {
                appRights.push($(this).val());
            });
            $('.access-right:checked').each(function() {
                accessRights.push($(this).val());
            });
            formData.append('app_rights', JSON.stringify(appRights));
            formData.append('access_rights', JSON.stringify(accessRights));
            formData.append('is_active', $('#statusSwitch').is(':checked') ? 1 : 0);

            let url = '{{ route('admin-users-store') }}';
            let method = 'POST';
            if ($('#userId').val()) {
                formData.append('_method', 'PUT');
                url = '{{ route('admin-users-update') }}';
            }
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    Swal.fire('Success', res.message, 'success');
                    // $('#addEditUserModal').modal('hide');
                    table.ajax.reload();
                    updateTotalCount();
                },
                error: function(xhr) {
                    Swal.fire('Error', xhr.responseJSON?.message || 'Something went wrong',
                        'error');
                }
            });
        });
    });



    function editUser(id) {
        $.ajax({
            url: '{{ route('admin-users-edit') }}',
            type: 'GET',
            data: {
                id: id
            },
            success: function(user) {
                $('#userId').val(user.id);
                $('#name').val(user.name);
                $('#lastname').val(user.lastname);
                $('#mobile').val(user.mobile);
                $('#email').val(user.email);
                $('#user_code').val(user.user_code);
                if (user.profile_image) $('#imagePreview').attr('src', '/storage/' + user.profile_image);
                else $('#imagePreview').attr('src', '{{ asset('images/default-avatar.png') }}');
                $('#statusSwitch').prop('checked', user.is_active == 1);
                let appRights = user.app_rights ? JSON.parse(user.app_rights) : [];
                let accessRights = user.access_rights ? JSON.parse(user.access_rights) : [];
                $('.app-right').prop('checked', false);
                $('.access-right').prop('checked', false);
                appRights.forEach(r => {
                    $(`.app-right[value="${r}"]`).prop('checked', true);
                });
                accessRights.forEach(r => {
                    $(`.access-right[value="${r}"]`).prop('checked', true);
                });
                $('#modalTitle').text('Edit Team Member');
                // $('#addEditUserModal').modal('show');
            }
        });
    }

    function deleteUser(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route('admin-users-destroy') }}',
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
                        updateTotalCount();
                    },
                    error: function() {
                        Swal.fire('Error!', 'Delete failed', 'error');
                    }
                });
            }
        });
    }

    $('#exportExcelBtn').on('click', function() {
        window.location.href = '{{ route('admin-users-export') }}?global_search=' + $('#globalSearch').val() +
            '&filter_inactive=' + (window.filterInactiveActive ? 1 : 0);
    });
    $('#printCardBtn').on('click', function() {
        let printWindow = window.open('{{ route('admin-users-print') }}', '_blank');
        printWindow.focus();
    });


    $(document).on('click', '.status-toggle', function() {

        let btn = $(this);
        let userId = btn.data('id');
        let newStatus = btn.is(':checked') ? 'active' : 'inactive';

        $.ajax({
            url: '{{ route('admin-users-toggle-status') }}',
            type: 'POST',
            data: {
                id: userId,
                status: newStatus,
                _token: '{{ csrf_token() }}'
            },
            success: function(res) {
                // Swal.fire('Updated', res.message, 'success');
                // Update button appearance and data attribute
                btn.removeClass('btn-success btn-danger')
                    .addClass(newStatus === 'active' ? 'btn-success' : 'btn-danger')
                    .text(newStatus.toUpperCase());
                btn.data('status', newStatus);
                // Also update total count if needed
                // updateTotalCount();
            },
            error: function() {
                Swal.fire('Error', 'Could not update status', 'error');
            }
        });
    });
</script>
