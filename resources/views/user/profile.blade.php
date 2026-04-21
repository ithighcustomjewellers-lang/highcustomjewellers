<style>
    body {
        background: #f5f7fb;
        font-family: Arial, sans-serif;
    }

    /* Container center */
    .profile-wrapper {
        max-width: 900px;
        margin: 50px auto;
    }

    /* Top gradient */
    .profile-header {
        height: 140px;
        background: linear-gradient(90deg, #8ec5fc, #e0c3fc);
        border-radius: 12px 12px 0 0;
    }

    /* Card */
    .profile-card {
        background: #fff;
        border-radius: 12px;
        padding: 25px;
        margin-top: -70px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    }

    /* Top section */
    .profile-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .profile-user {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    /* Avatar */
    .profile-img {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        object-fit: cover;
    }

    /* Inputs grid */
    .profile-form {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    /* Full width */
    .profile-form .full {
        grid-column: span 2;
    }

    /* Inputs */
    .profile-form input,
    .profile-form select {
        width: 100%;
        padding: 12px;
        border-radius: 8px;
        border: 1px solid #ddd;
        outline: none;
        background: #f9f9f9;
    }

    /* Button */
    .btn-edit {
        background: #4e73df;
        color: #fff;
        border: none;
        border-radius: 6px;
        padding: 8px 20px;
        cursor: pointer;
    }

    .btn-primary {
        background: #4e73df;
        border: none;
        padding: 12px;
        border-radius: 8px;
        color: #fff;
        cursor: pointer;
    }

    .btn-primary:hover {
        background: #375ad3;
    }

    /* Responsive */
    @media(max-width: 768px) {
        .profile-form {
            grid-template-columns: 1fr;
        }
    }

    .btn-group {
        display: flex;
        gap: 10px;
    }

    .btn-secondary {
        background: #6c757d;
        color: #fff;
        border: none;
        padding: 12px;
        border-radius: 8px;
        text-decoration: none;
        text-align: center;
        display: inline-block;
    }

    .btn-secondary:hover {
        background: #5a6268;
    }
</style>

<div class="profile-wrapper">
    <div class="profile-header"></div>
    <div class="profile-card">
        <div class="profile-top">
            <div class="profile-user">
                <img src="{{ asset($user->user_image ?? 'images/user-icon.jpg') }}" class="profile-img">
                <div>
                    <h5>{{ $user->name }}</h5>
                </div>
            </div>
        </div>

        <form action="" id="userProfile" method="POST" enctype="multipart/form-data" class="profile-form">
            @csrf
            <div>
                <label>First Name</label>
                <input type="text" name="name" value="{{ $user->name }}">
            </div>
            <div>
                <label>Last Name</label>
                <input type="text" name="lastname" value="{{ $user->lastname ?? '' }}">
            </div>
            <div>
                <label>Email</label>
                <input type="email" name="email" value="{{ $user->email }}">
            </div>
            <div>
                <label>Phone</label>
                <input type="text" name="phone" value="{{ $user->mobile ?? '' }}">
            </div>
            <div class="full">
                <label>Profile Image</label>
                <input type="file" name="image">
            </div>
            <div class="full btn-group">
                <button type="submit" class="btn-primary">Update Profile</button>
                <a href="{{ route('dashboard') }}" class="btn-secondary">Back</a>
            </div>
        </form>

    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    $(document).ready(function() {

        $('#userProfile').submit(function(e) {
            e.preventDefault();

            let formData = new FormData(this); // ✅ IMPORTANT
            let btn = $(this).find('button');

            btn.prop('disabled', true).text('Updating...');

            $.ajax({
                url: "{{ route('submit-profile-update') }}", // ✅ FIXED
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,

                success: function(response) {
                    toastr.success(response.message);
                },

                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;

                        $.each(errors, function(key, val) {
                            toastr.error(val[0]);
                        });
                    } else {
                        toastr.error("Something went wrong");
                    }
                },

                complete: function() {
                    btn.prop('disabled', false).text('Update Profile');
                }
            });

        });

    });
</script>
