<?php

namespace App\Http\Controllers;

use App\Models\User;
use Google\Client;
use Google\Service\Gmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
class GoogleController extends Controller
{

// public function redirect()
    // {
    //     return Socialite::driver('google')
    //         ->scopes(['https://www.googleapis.com/auth/gmail.send']) // ✅ Required scope
    //         ->with([
    //             'access_type' => 'offline',   // Get refresh token
    //             'prompt'      => 'consent',   // Force consent screen (re‑grant permissions)
    //         ])
    //         ->redirect();
    // }

    // /**
    //  * Handle the Google callback and save tokens.
    //  */
    // public function callback()
    // {
    //     try {
    //         $googleUser = Socialite::driver('google')->stateless()->user();
    //     } catch (\Exception $e) {
    //         Log::error('Google OAuth callback error: ' . $e->getMessage());
    //         return redirect()->route('admin-dashboard')->with('error', 'Failed to authenticate with Google. Please try again.');
    //     }

    //     $user = auth()->user();
    //     if (!$user) {
    //         return redirect()->route('login')->with('error', 'You must be logged in to connect Gmail.');
    //     }

    //     // Save tokens
    //     $user->gmail_token = $googleUser->token;
    //     // Socialite returns refreshToken only if access_type=offline and prompt=consent
    //     $user->gmail_refresh_token = $googleUser->refreshToken;
    //     $user->save();

    //     // Optional: verify the token has the required scope
    //     Log::info('Gmail tokens saved for user ' . $user->id . ', refresh token present: ' . ($googleUser->refreshToken ? 'yes' : 'no'));

    //     return redirect()->route('admin-dashboard')->with('success', 'Gmail connected successfully! You can now send campaigns.');
    // }

    /**
     * Redirect user to Google OAuth
     */
    public function redirect()
    {
        return Socialite::driver('google')
            ->stateless()
            ->scopes([
                Gmail::GMAIL_SEND,
                'openid',
                'email',
                'profile',
            ])
            ->with([
                'access_type' => 'offline',
                'prompt' => 'consent',
                'include_granted_scopes' => 'true',
            ])
            ->redirect();
    }

    /**
     * Google OAuth Callback
     */
    public function callback()
    {
        try {

            $googleUser = Socialite::driver('google')
                ->stateless()
                ->user();

            /** @var User $user */
            $user = Auth::user();

            if (!$user) {
                return redirect()
                    ->route('login')
                    ->with('error', 'Please login first.');
            }

            $user->gmail_token = $googleUser->token;

            if (!empty($googleUser->refreshToken)) {
                $user->gmail_refresh_token = $googleUser->refreshToken;
            }

            if (!empty($googleUser->expiresIn)) {
                $user->gmail_token_expires_at = now()->addSeconds(
                    $googleUser->expiresIn
                );
            }

            $user->save();

            Log::info('Google Connected', [
                'user_id' => $user->id,
                'email' => $user->email,
                'refresh_token' => !empty($googleUser->refreshToken),
            ]);

            return redirect()
                ->route('admin-dashboard')
                ->with('success', 'Gmail connected successfully.');

        } catch (\Exception $e) {

            Log::error('Google OAuth Error', [
                'message' => $e->getMessage(),
            ]);

            return redirect()
                ->route('admin-dashboard')
                ->with('error', $e->getMessage());
        }
    }
}
