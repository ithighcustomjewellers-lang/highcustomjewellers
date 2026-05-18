<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')
            ->scopes(['https://www.googleapis.com/auth/gmail.send'])
            ->with([
                'access_type' => 'offline', // 🔥 MUST
                'prompt' => 'consent'       // 🔥 MUST
            ])
            ->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        /** @var User $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'User not logged in');
        }

        $user->gmail_token = $googleUser->token;
        $user->gmail_refresh_token = $googleUser->refreshToken;

        $user->save();

        return redirect()->route('admin-dashboard')
            ->with('success', 'Gmail connected successfully');
    }
}
