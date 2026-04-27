@extends('admin.layouts.layout')

@section('title', 'Edit User')

@section('content')

    <div class="container mt-4">
        <div class="card p-4">
            <h4 class="mb-3">Edit User</h4>
            <form action="#" id="updateUserData" method="POST">
                <input type="hidden" name="id" value="{{ $user->id }}">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3 input-field">
                        <label>Name</label>
                        <input type="text" name="name" id="name" value="{{ $user->name }}" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3 input-field">
                        <label>Last Name</label>
                        <input type="text" name="lastname" id="lastname" value="{{ $user->lastname }}" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3 input-field">
                        <label>Email</label>
                        <input type="email" name="email" id="email" value="{{ $user->email }}" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3 input-field">
                        <label>Mobile</label>
                        <input type="text" name="phone" id="phone" value="{{ $user->mobile }}" class="form-control">
                    </div>

                    <div class="col-md-12">
                        <button class="btn btn-primary">Update</button>
                        <a href="{{ route('admin-users-index') }}" class="btn btn-secondary">Back</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

<script src="{{ asset('js/jquery.min.js') }}"></script>
<script>
    $(document).ready(function() {

        $('#updateUserData').submit(function(e) {
            e.preventDefault();
            let formData = new FormData(this);

            $.ajax({
                url: "{{ route('admin-update-data') }}", // ✅ PASS ID HERE
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: formData,
                processData: false,
                contentType: false,

                success: function(response) {
                    Swal.fire({
                        title: 'Success!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                },

                error: function(xhr) {

                    let errors = xhr.responseJSON.errors;

                    $('.error-text').remove(); // remove old errors
                    $('.input-field').removeClass('has-error');

                    $.each(errors, function(key, value) {

                        let input = $('#' + key);

                        // add red border
                        input.addClass('is-invalid');

                        // show error text
                        input.closest('.input-field').append('<span class="error-text">' + value[0] + '</span>');

                    });
                }

            });
        });

    });
</script>
