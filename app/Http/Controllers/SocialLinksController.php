<?php
// app/Http/Controllers/SocialLinksController.php

namespace App\Http\Controllers;

use App\Models\SocialLink;
use App\Models\UserSetting;
use App\Models\AnalyticsTracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class SocialLinksController extends Controller
{

    public function index()
    {
        $user = Auth::user();

        // Get social links
        $socialLinks = SocialLink::where('user_id', $user->id)
            ->orderBy('sort_order')
            ->get();

        // Get quick links from settings
        $quickLinks = UserSetting::getValue($user->id, 'quick_links', []);

        // Get or generate profile slug
        $profileSlug = UserSetting::getValue($user->id, 'profile_slug');
        if (!$profileSlug) {
            $profileSlug = Str::slug($user->name) . '-' . $user->id;
            UserSetting::setValue($user->id, 'profile_slug', $profileSlug);
        }

        // IMPORTANT: Use the public profile URL, not admin URL
        $profileUrl = url('/profile/' . $profileSlug);

        // Get analytics
        $qrScans = AnalyticsTracking::getQRScansCount($profileSlug);
        $btnClicks = AnalyticsTracking::getButtonClicksCount($profileSlug);

        return view('admin.social.index', compact(
            'socialLinks',
            'quickLinks',
            'profileUrl',
            'qrScans',
            'btnClicks',
            'profileSlug'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'platform_name' => 'required|string|max:255',
            'platform_url' => 'required|url|max:500'
        ]);

        $user = Auth::user();

        // Check if platform is predefined
        $predefinedPlatforms = [
            'whatsapp',
            'telegram',
            'facebook',
            'youtube',
            'linkedin',
            'instagram',
            'twitter',
            'x',
            'tiktok',
            'snapchat',
            'reddit',
            'discord',
            'pinterest',
            'twitch',
            'quora',
            'github',
            'spotify',
            'medium'
        ];

        $isPredefined = in_array(strtolower($request->platform_name), $predefinedPlatforms);
        $iconType = $isPredefined ? 'fa' : 'custom';

        $socialLink = SocialLink::create([
            'user_id' => $user->id,
            'platform_name' => $request->platform_name,
            'platform_url' => $request->platform_url,
            'icon_type' => $iconType,
            'sort_order' => SocialLink::where('user_id', $user->id)->count(),
            'is_active' => true
        ]);

        $totalLinks = SocialLink::where('user_id', $user->id)->count();

        // Get profile slug
        $profileSlug = UserSetting::getValue($user->id, 'profile_slug');
        if (!$profileSlug) {
            $profileSlug = Str::slug($user->name) . '-' . $user->id;
            UserSetting::setValue($user->id, 'profile_slug', $profileSlug);
        }

        $profileUrl = url('/profile/' . $profileSlug);

        return response()->json([
            'success' => true,
            'message' => 'Platform added successfully',
            'link' => $socialLink,
            'totalLinks' => $totalLinks,
            'profile_url' => $profileUrl
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'platform_url' => 'required|url|max:500'
        ]);

        $socialLink = SocialLink::where('user_id', Auth::id())->findOrFail($id);
        $socialLink->update(['platform_url' => $request->platform_url]);

        $user = Auth::user();
        $totalLinks = SocialLink::where('user_id', $user->id)->count();

        $profileSlug = UserSetting::getValue($user->id, 'profile_slug');
        if (!$profileSlug) {
            $profileSlug = Str::slug($user->name) . '-' . $user->id;
            UserSetting::setValue($user->id, 'profile_slug', $profileSlug);
        }

        $profileUrl = url('/profile/' . $profileSlug);

        return response()->json([
            'success' => true,
            'message' => 'Link updated successfully',
            'totalLinks' => $totalLinks,
            'profile_url' => $profileUrl,
            'link' => $socialLink
        ]);
    }

    // public function destroy($id)
    // {
    //     $socialLink = SocialLink::where('user_id', Auth::id())->findOrFail($id);
    //     $socialLink->delete();

    //     Cache::flush();

    //     $user = Auth::user();
    //     $totalLinks = SocialLink::where('user_id', $user->id)->count();

    //     $profileSlug = UserSetting::getValue($user->id, 'profile_slug');
    //     if (!$profileSlug) {
    //         $profileSlug = Str::slug($user->name) . '-' . $user->id;
    //         UserSetting::setValue($user->id, 'profile_slug', $profileSlug);
    //     }

    //     $profileUrl = url('/profile/' . $profileSlug);

    //     // IMPORTANT: Clear cache for this profile
    //     $this->clearProfileCache($profileSlug);

    //     // Also clear QR code cache if exists
    //     Cache::forget('qr_code_' . $profileSlug);

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Link deleted successfully',
    //         'totalLinks' => $totalLinks,
    //         'profile_url' => $profileUrl
    //     ]);
    // }

    // // Add this helper method to clear cache
    // private function clearProfileCache($profileSlug)
    // {
    //     // Clear Laravel cache
    //     Cache::forget('profile_data_' . $profileSlug);
    //     Cache::forget('user_links_' . Auth::id());

    //     // Clear view cache if using
    //     View::flushFinderCache();
    // }

    public function updateQuickLink(Request $request)
    {
        $request->validate([
            'platform_key' => 'required|string',
            'platform_url' => 'required|url|max:500',
            'platform_name' => 'required|string'
        ]);

        $user = Auth::user();
        $quickLinks = UserSetting::getValue($user->id, 'quick_links', []);
        $quickLinks[$request->platform_key] = $request->platform_url;
        UserSetting::setValue($user->id, 'quick_links', $quickLinks);

        // Determine icon type for quick link
        $predefinedPlatforms = ['whatsapp', 'telegram', 'facebook', 'youtube', 'linkedin', 'instagram', 'x', 'threads', 'snapchat', 'reddit', 'discord', 'pinterest', 'twitch', 'quora', 'messenger', 'rumble', 'viber'];
        $isPredefined = in_array(strtolower($request->platform_name), $predefinedPlatforms);
        $iconType = $isPredefined ? 'fa' : 'custom';

        // Create or update in social_links table
        $socialLink = SocialLink::updateOrCreate(
            [
                'user_id' => $user->id,
                'platform_name' => $request->platform_name
            ],
            [
                'platform_url' => $request->platform_url,
                'icon_type' => $iconType,
                'sort_order' => 0,
                'is_active' => true
            ]
        );

        $totalLinks = SocialLink::where('user_id', $user->id)->count();

        $profileSlug = UserSetting::getValue($user->id, 'profile_slug');
        if (!$profileSlug) {
            $profileSlug = Str::slug($user->name) . '-' . $user->id;
            UserSetting::setValue($user->id, 'profile_slug', $profileSlug);
        }

        $profileUrl = url('/profile/' . $profileSlug);

        return response()->json([
            'success' => true,
            'message' => $request->platform_name . ' link updated successfully',
            'totalLinks' => $totalLinks,
            'profile_url' => $profileUrl,
            'social_link' => $socialLink
        ]);
    }

    public function getProfileUrlAjax()
    {
        $user = Auth::user();

        $profileSlug = UserSetting::getValue($user->id, 'profile_slug');
        if (!$profileSlug) {
            $profileSlug = Str::slug($user->name) . '-' . $user->id;
            UserSetting::setValue($user->id, 'profile_slug', $profileSlug);
        }

        $profileUrl = url('/profile/' . $profileSlug);

        return response()->json([
            'profile_url' => $profileUrl
        ]);
    }

    public function printBusinessCard()
    {
        $user = Auth::user();
        $socialLinks = SocialLink::where('user_id', $user->id)->get();
        $quickLinks = UserSetting::getValue($user->id, 'quick_links', []);

        $profileSlug = UserSetting::getValue($user->id, 'profile_slug');
        if (!$profileSlug) {
            $profileSlug = Str::slug($user->name) . '-' . $user->id;
            UserSetting::setValue($user->id, 'profile_slug', $profileSlug);
        }

        $profileUrl = url('/profile/' . $profileSlug);

        return view('admin.social.print', compact('user', 'socialLinks', 'quickLinks', 'profileUrl'));
    }
}
