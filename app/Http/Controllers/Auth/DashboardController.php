<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function Dashboard()
    {
        $user = Auth::user();
        return view('user.dashboard', compact('user'));
    }

    public function UserProfile()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    public function submitProfileUpdate(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|unique:users,email,' . Auth::id(),
            'phone'  => 'required|digits:10',
            'image'  => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $user = Auth::user();

        // ✅ Basic update
        $user->name   = $request->name;
        $user->lastname = $request->lastname;
        $user->email  = $request->email;
        $user->mobile = $request->phone;

        // ✅ Password update
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // ✅ Image upload
        if ($request->hasFile('image')) {

            // delete old image (optional)
            if ($user->image && file_exists(public_path($user->image))) {
                unlink(public_path($user->image));
            }

            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();

            $file->move(public_path('uploads/users'), $filename);

            $user->user_image = 'uploads/users/' . $filename;
        }

        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Profile updated successfully'
        ]);
    }
}
