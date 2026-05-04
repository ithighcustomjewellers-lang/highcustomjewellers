<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;

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

        $user = auth()->user();

        // 🔍 Debug (ek baar check kar lo)
        // dd($googleUser);

        $user->gmail_token = $googleUser->token;
        $user->gmail_refresh_token = $googleUser->refreshToken;
        $user->save();

        return redirect()->route('admin-dashboard');
    }
}
