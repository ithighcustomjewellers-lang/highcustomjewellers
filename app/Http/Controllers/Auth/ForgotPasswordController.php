<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class ForgotPasswordController extends Controller
{
    // Show forgot form
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    // Send reset link
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        // ✅ Generate token
        $token = Str::random(64);

        // ✅ Delete old tokens
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // ✅ Insert new token
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => bcrypt($token),
            'created_at' => now()
        ]);

        // ✅ Send email
        $user = User::where('email', $request->email)->first();
        $user->sendPasswordResetNotification($token);

        return response()->json([
            'status' => true,
            'message' => 'Reset link successfully sent to your email!'
        ]);
    }

    // Show reset form
    public function showResetForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    // Reset password
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, $password) {
                $user->update([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ]);
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'status' => true,
                'message' => 'Password reset successful. Please login.'
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => __($status)
        ], 400);
    }
}
