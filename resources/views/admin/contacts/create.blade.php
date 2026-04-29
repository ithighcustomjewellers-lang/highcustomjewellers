@extends('admin.layouts.layout')

@section('title','Add Contact')

@section('content')

<div class="container mt-4">
    <div class="card p-4">
        <h4>Add Contact</h4>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="POST" id="contactForm" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name" class="form-control" placeholder="Please enter name">
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" placeholder="Please enter email">
            </div>

            <div class="mb-3">
                <label>Company Name</label>
                <input type="text" name="company_name" class="form-control" placeholder="Please enter company name">
            </div>

            <div class="mb-3">
                <label>Type</label>
                <select name="type" class="form-control">
                    <option value="">-- Select Type --</option>
                    <option value="b2b">B2B</option>
                    <option value="b2c">B2C</option>
                </select>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-success">Save Contact</button>
                <a href="{{ route('admin-contacts-index') }}" class="btn btn-secondary">
                    Back
                </a>
            </div>

        </form>
    </div>
</div>

@endsection

<script src="{{ asset('js/jquery.min.js') }}"></script>

<script>
$(document).ready(function() {
    $('#contactForm').submit(function(e) {
        e.preventDefault();

        // 👉 FormData use
        var formData = new FormData(this);

        $.ajax({
            url: '{{ route('admin-contacts-store') }}',
            method: 'POST',
            data: formData,

            // 🔥 IMPORTANT (FormData ke liye MUST)
            processData: false,
            contentType: false,

            success: function(response) {
                toastr.success(response.message);
                $('#contactForm')[0].reset();
            },

            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;

                    $.each(errors, function(key, value) {
                        toastr.error(value[0]);
                    });
                } else {
                    toastr.error('Something went wrong ❌');
                }
            }
        });
    });
});
</script>

