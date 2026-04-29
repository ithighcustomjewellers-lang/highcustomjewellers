<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    public function Register()
    {
        return view('auth.register');
    }

    public function SubmitRegister(Request $request)
    {
        // 1. VALIDATE FIRST
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|max:50',
            'lastname'  => 'required|string|max:50',
            'email'     => 'required|email|unique:users,email',
            'phone'     => 'required|digits:10',
            'password'  => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // 2. SAVE USER (ONLY AFTER VALIDATION PASSES)
        $user = User::create([
            'name' => $request->firstname,
            'lastname'  => $request->lastname,
            'email'     => $request->email,
            'mobile'     => $request->phone,
            'password'  => Hash::make($request->password),
            'user_code' => 'HC' . now()->format('mYHis'),
        ]);

        // 3. SUCCESS RESPONSE
        return response()->json([
            'status' => true,
            'message' => 'Registration successful',
            'data' => $user
        ]);
    }

    public function Login()
    {
        return view('auth.login');
    }

    public function SubmitLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {

            $request->session()->regenerate();

            $user = Auth::user();

            return response()->json([
                'status' => true,
                'role' => $user->is_admin ? 'admin' : 'user'
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Invalid credentials'
        ], 401);
    }

    public function logout(Request $request)
    {
        Auth::logout(); // logout user

        $request->session()->invalidate(); // session destroy
        $request->session()->regenerateToken(); // CSRF refresh

        return response()->json([
            'status' => true,
            'message' => 'Logout successful'
        ]);
    }


}
