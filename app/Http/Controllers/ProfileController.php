<?php
// app/Http/Controllers/ProfileController.php

namespace App\Http\Controllers;

use App\Models\SocialLink;
use App\Models\UserSetting;
use App\Models\AnalyticsTracking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function show($slug)
    {
        /*
        |--------------------------------------------------------------------------
        | FIND USER
        |--------------------------------------------------------------------------
        */

        $user = null;

        // METHOD 1 => FIND BY PROFILE SLUG
        $setting = UserSetting::where('key', 'profile_slug')
            ->where('value', $slug)
            ->first();

        if ($setting) {
            $user = $setting->user;
        }

        // METHOD 2 => FIND BY USER ID FROM SLUG
        if (!$user && preg_match('/-(\d+)$/', $slug, $matches)) {
            $user = User::find($matches[1]);
        }

        // USER NOT FOUND
        if (!$user) {
            abort(404, 'Profile not found');
        }

        /*
        |--------------------------------------------------------------------------
        | ENSURE PROFILE SLUG EXISTS
        |--------------------------------------------------------------------------
        */

        $profileSlug = UserSetting::getValue(
            $user->id,
            'profile_slug'
        );

        if (!$profileSlug) {
            $profileSlug = $slug;
            UserSetting::setValue($user->id, 'profile_slug', $profileSlug);
        }

        /*
        |--------------------------------------------------------------------------
        | TRACK QR SCAN
        |--------------------------------------------------------------------------
        */

        try {
            AnalyticsTracking::trackQRScan($slug);
        } catch (\Exception $e) {
            Log::error(
                'Analytics tracking error: ' . $e->getMessage()
            );
        }

        /*
        |--------------------------------------------------------------------------
        | HIDDEN PLATFORMS
        |--------------------------------------------------------------------------
        */

        $hiddenPlatforms = [];

        if ($user->is_admin != 1) {
            $hiddenPlatforms = ['whatsapp'];
        }

        /*
        |--------------------------------------------------------------------------
        | GET ADMIN
        |--------------------------------------------------------------------------
        */

        $admin = User::where('is_admin', 1)->first();

        /*
        |--------------------------------------------------------------------------
        | ADMIN LINKS
        |--------------------------------------------------------------------------
        */

        $adminLinks = collect();

        if ($admin) {
            $adminLinks = SocialLink::where('user_id', $admin->id)
                ->where('is_active', true)
                ->whereNotIn( DB::raw('LOWER(platform_name)'),
                    $hiddenPlatforms
                )
                ->orderBy('sort_order')
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | USER LINKS
        |--------------------------------------------------------------------------
        */


        $userLinks = SocialLink::where('user_id', $user->id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | MERGE LINKS
        |--------------------------------------------------------------------------
        |
        | ADMIN LINKS DEFAULT
        | USER SAME PLATFORM CHANGE KAR SAKTA HAI
        |--------------------------------------------------------------------------
        */

        $socialLinks = $adminLinks->map(function ($adminLink) use ($userLinks) {
            // SAME PLATFORM USER LINK
            $userLink = $userLinks->first(function ($item) use ($adminLink) {
                return strtolower(trim($item->platform_name))
                    == strtolower(trim($adminLink->platform_name));
            });
            // USER UPDATED LINK
            if ($userLink) {
                return $userLink;
            }
            // OTHERWISE ADMIN LINK
            return $adminLink;
        });

        /*
        |--------------------------------------------------------------------------
        | USER EXTRA CUSTOM LINKS
        |--------------------------------------------------------------------------
        */

        $adminPlatformNames = $adminLinks->map(function ($item) {
            return strtolower(trim($item->platform_name));
        })->toArray();
        $extraUserLinks = $userLinks->filter(function ($link) use ($adminPlatformNames) {
            return !in_array(
                strtolower(trim($link->platform_name)),
                $adminPlatformNames
            );
        });
        $socialLinks = $socialLinks->merge($extraUserLinks);

        /*
        |--------------------------------------------------------------------------
        | QUICK LINKS
        |--------------------------------------------------------------------------
        */

        $quickLinks = UserSetting::getValue(
            $user->id,
            'quick_links',
            []
        );

        /*
        |--------------------------------------------------------------------------
        | REMOVE WHATSAPP FROM QUICK LINKS
        |--------------------------------------------------------------------------
        */

        unset($quickLinks['whatsapp_url']);

        /*
        |--------------------------------------------------------------------------
        | USER QUICK LINKS EMPTY
        |--------------------------------------------------------------------------
        */

        if (empty($quickLinks) && $admin) {

            $quickLinks = UserSetting::getValue(
                $admin->id,
                'quick_links',
                []
            );

            unset($quickLinks['whatsapp_url']);
        }

        /*
        |--------------------------------------------------------------------------
        | QR URL
        |--------------------------------------------------------------------------
        |
        | FIRST ACTIVE LINK QR ME SHOW HOGI
        |--------------------------------------------------------------------------
        */

        $qrUrl = '';

        if ($socialLinks->count() > 0) {

            $qrUrl = $socialLinks->first()->platform_url;

        } else {

            $qrUrl = url('/profile/' . $profileSlug);
        }

        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view('profile.show', compact(
            'user',
            'socialLinks',
            'quickLinks',
            'slug',
            'qrUrl',
            'profileSlug'
        ));
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
