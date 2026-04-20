<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function Register()
    {
        return view('auth.register');
    }

    public function SubmitRegister(Request $request)
    {
        // ✅ 1. VALIDATE FIRST
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

        // ✅ 2. SAVE USER (ONLY AFTER VALIDATION PASSES)
        $user = User::create([
            'name' => $request->firstname,
            'lastname'  => $request->lastname,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'password'  => Hash::make($request->password),
            'user_code' => 'CHJ' . now()->format('mYHis'),

        ]);

        // ✅ 3. SUCCESS RESPONSE
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
        // ✅ 1. VALIDATE FIRST
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // ✅ 2. CHECK USER CREDENTIALS
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid email or password'
            ], 401);
        }

        // ✅ 3. SUCCESS RESPONSE (You can also generate a token here if using API authentication)
        return response()->json([
            'status' => true,
            'message' => 'Login successful',
            'data' => $user
        ]);
    }
}
