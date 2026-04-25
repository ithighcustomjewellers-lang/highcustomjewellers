<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Admin;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;

class LoginController extends Controller
{
    public function AdminLogin()
    {
        return view('admin.auth.login');
    }

    public function AdminSubmitLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (auth()->guard('admin')->attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => true,
                'redirect' => route('admin-dashboard')
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid credentials'
        ]);
    }

    public function AdminLogout()
    {
        auth()->guard('admin')->logout();
        return redirect()->route('admin-login');
    }

    public function adminShowForgotForm()
    {
        return view('admin.auth.forgot-password');
    }

    public function adminSendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:admins,email'
        ]);

        $token = Str::random(64);

        DB::table('password_reset_tokens_admins')
            ->where('email', $request->email)
            ->delete();

        DB::table('password_reset_tokens_admins')->insert([
            'email' => $request->email,
            'token' => bcrypt($token),
            'created_at' => now()
        ]);

        $admin = Admin::where('email', $request->email)->first();
        $admin->adminSendPasswordResetNotification($token);

        return response()->json([
            'status' => true,
            'message' => 'Admin reset link sent!'
        ]);
    }

    public function adminShowResetForm($token)
    {
        return view('admin.auth.reset-password', ['token' => $token]);
    }

    public function adminResetPassword(Request $request)
    {

    dd($request);
    die();
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

        // ✅ Token record find karo
        $record = DB::table('password_reset_tokens_admins')
            ->where('email', $request->email)
            ->first();

        // ❌ Invalid token
        if (!$record || !Hash::check($request->token, $record->token)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid or expired token'
            ], 400);
        }

        // ✅ Admin find karo
        $admin = Admin::where('email', $request->email)->first();

        if (!$admin) {
            return response()->json([
                'status' => false,
                'message' => 'Admin not found'
            ], 404);
        }

        // ✅ Password update
        $admin->update([
            'password' => Hash::make($request->password),
            'remember_token' => Str::random(60),
        ]);

        // ✅ Token delete karo
        DB::table('password_reset_tokens_admins')
            ->where('email', $request->email)
            ->delete();

        return response()->json([
            'status' => true,
            'message' => 'Password reset successful. Please login.'
        ]);
    }
}
