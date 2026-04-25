<style>
    /* Background */
    body {
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
        background: linear-gradient(135deg, #4facfe, #00f2fe);
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    /* Login Card */
    .login-container {
        background: #e6e6e6;
        padding: 40px;
        width: 400px;
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    /* Title */
    .login-container h2 {
        margin-bottom: 20px;
        font-weight: bold;
        color: #333;
    }

    /* Inputs */
    .login-container input {
        width: 100%;
        padding: 12px;
        margin-bottom: 15px;
        border-radius: 6px;
        border: 1px solid #ccc;
        outline: none;
        font-size: 14px;
    }

    /* Input focus */
    .login-container input:focus {
        border-color: #4facfe;
        box-shadow: 0 0 5px rgba(79, 172, 254, 0.5);
    }

    /* Forgot password */
    .forgot-password {
        font-size: 14px;
        margin-bottom: 15px;
    }

    .forgot-password a {
        text-decoration: none;
        color: #007bff;
    }

    .forgot-password a:hover {
        text-decoration: underline;
    }

    /* Button */
    .login-btn {
        width: 100%;
        padding: 12px;
        background: #2f6fed;
        color: #fff;
        border: none;
        border-radius: 6px;
        font-size: 16px;
        cursor: pointer;
        transition: 0.3s;
    }

    .login-btn:hover {
        background: #1d4ed8;
    }

    /* Register text */
    .register-text {
        margin-top: 15px;
        font-size: 14px;
    }

    .register-text a {
        color: #007bff;
        text-decoration: none;
    }

    .register-text a:hover {
        text-decoration: underline;
    }
</style>

<div class="login-container">
    <h2>Forgot Password</h2>

    <form id="forgotForm">
        @csrf

        <input type="email" name="email" class="form-control mb-3" placeholder="Enter your email">

        <button type="submit" class="btn btn-primary w-100 login-btn">
            Send Reset Link
        </button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    $(document).ready(function() {

        $('#forgotForm').submit(function(e) {
            e.preventDefault();

            let btn = $(this).find('button');
            btn.prop('disabled', true).text('Sending...');

            $.ajax({
                url: "{{ route('admin.password.email') }}",
                type: "POST",
                data: $(this).serialize(),

                success: function(res) {

                    // ✅ Save message for next page
                    localStorage.setItem('success_msg', res.message);

                    // ✅ Redirect after 1.5 sec
                    setTimeout(function() {
                        window.location.href = "{{ route('login-data') }}";
                    }, 1500);
                },

                error: function(xhr) {
                    toastr.error("Email not found");
                },

                complete: function() {
                    btn.prop('disabled', false).text('Send Reset Link');
                }
            });
        });

    });
</script>
