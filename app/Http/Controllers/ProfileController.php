<?php
// app/Http/Controllers/ProfileController.php

namespace App\Http\Controllers;

use App\Models\SocialLink;
use App\Models\UserSetting;
use App\Models\AnalyticsTracking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function show($slug)
    {
        // Find user by profile slug
        $user = null;

        // Method 1: Find by profile_slug in user_settings
        $setting = UserSetting::where('key', 'profile_slug')
            ->where('value', $slug)
            ->first();

        if ($setting) {
            $user = $setting->user;
        }

        // Method 2: If not found, try to find by user ID (if slug contains ID)
        if (!$user && preg_match('/-(\d+)$/', $slug, $matches)) {
            $user = User::find($matches[1]);
        }

        // If still no user found, return 404
        if (!$user) {
            abort(404, 'Profile not found');
        }

        // Ensure profile_slug exists in settings
        $profileSlug = UserSetting::getValue($user->id, 'profile_slug');
        if (!$profileSlug) {
            $profileSlug = $slug;
            UserSetting::setValue($user->id, 'profile_slug', $profileSlug);
        }

        // Track QR scan
        try {
            AnalyticsTracking::trackQRScan($slug);
        } catch (\Exception $e) {
            Log::error('Analytics tracking error: ' . $e->getMessage());
        }

        $socialLinks = SocialLink::where('user_id', $user->id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $quickLinks = UserSetting::getValue($user->id, 'quick_links', []);

        return view('profile.show', compact('user', 'socialLinks', 'quickLinks', 'slug'));
    }

    public function trackClick(Request $request, $slug)
    {
        try {
            $request->validate([
                'platform' => 'required|string'
            ]);

            AnalyticsTracking::trackButtonClick($slug, $request->platform);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Track click error: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
