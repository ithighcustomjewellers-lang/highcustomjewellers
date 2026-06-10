<?php
// app/Http/Controllers/SocialLinksController.php

namespace App\Http\Controllers;

use App\Models\SocialLink;
use App\Models\UserSetting;
use App\Models\AnalyticsTracking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use App\Models\MultiQrLink;
use App\Models\Sequence;

class SocialLinksController extends Controller
{

    public function index()
    {
        $user = Auth::user();

        $quickLinkPlatforms = [
            'whatsapp',
            'telegram',
            'facebook',
            'youtube',
            'linkedin',
            'instagram',
            'x',
            'threads',
            'snapchat',
            'reddit',
            'discord',
            'pinterest',
            'quora',
            'messenger',
            'twitch',
            'rumble',
            'viber'
        ];


        /*
        |--------------------------------------------------------------------------
        | SOCIAL LINKS
        |--------------------------------------------------------------------------
        */

        if ($user->is_admin == 1) {

            /*
            |--------------------------------------------------------------------------
            | ADMIN LINKS
            |--------------------------------------------------------------------------
            */

            // $socialLinks = SocialLink::where('user_id', $user->id)
            //     ->orderBy('sort_order')
            //     ->get();

            $socialLinks = SocialLink::where('user_id', $user->id)
                ->whereNotIn(DB::raw('LOWER(platform_name)'), $quickLinkPlatforms)
                ->orderBy('sort_order')
                ->get();
        } else {

            /*
            |--------------------------------------------------------------------------
            | ADMIN FIND
            |--------------------------------------------------------------------------
            */

            $admin = User::where('is_admin', 1)->first();

            $adminLinks = collect();

            if ($admin) {

                $adminLinks = SocialLink::where('user_id', $admin->id)
                    ->whereRaw('LOWER(platform_name) != ?', ['whatsapp'])
                    ->orderBy('sort_order')
                    ->get();
            }

            /*
            |--------------------------------------------------------------------------
            | USER LINKS
            |--------------------------------------------------------------------------
            */

            $userLinks = SocialLink::where('user_id', $user->id)
                ->orderBy('sort_order')
                ->get();

            /*
            |--------------------------------------------------------------------------
            | MERGE ADMIN + USER LINKS
            |--------------------------------------------------------------------------
            */

            $socialLinks = $adminLinks->map(function ($adminLink) use ($userLinks) {
                $userLink = $userLinks->first(function ($item) use ($adminLink) {
                    return strtolower(trim($item->platform_name)) == strtolower(trim($adminLink->platform_name));
                });
                return $userLink ? $userLink : $adminLink;
            });

            /*
            |--------------------------------------------------------------------------
            | USER EXTRA CUSTOM LINKS
            |--------------------------------------------------------------------------
            */

            $adminPlatformNames = $adminLinks->map(function ($item) {
                return strtolower(trim($item->platform_name));
            })->toArray();

            $extraUserLinks = $userLinks->filter(function ($link) use ($adminPlatformNames, $quickLinkPlatforms) {
                // Skip if platform is in quick links
                if (in_array(strtolower(trim($link->platform_name)), $quickLinkPlatforms)) {
                    return false;
                }
                return !in_array(strtolower(trim($link->platform_name)), $adminPlatformNames);
            });

            $socialLinks = $socialLinks->merge($extraUserLinks);


            $socialLinks = $socialLinks->unique(function ($item) {
                return strtolower(trim($item->platform_name));
            })->filter(function ($link) use ($quickLinkPlatforms) {
                return !in_array(strtolower(trim($link->platform_name)), $quickLinkPlatforms);
            });
        }

        /*
            |--------------------------------------------------------------------------
            | QUICK LINKS
            |--------------------------------------------------------------------------
            */

        if ($user->is_admin == 1) {

            $quickLinks = UserSetting::getValue(
                $user->id,
                'quick_links',
                []
            );
        } else {

            /*
            |--------------------------------------------------------------------------
            | ADMIN QUICK LINKS
            |--------------------------------------------------------------------------
            */

            $admin = User::where('is_admin', 1)->first();

            $adminQuickLinks = [];

            if ($admin) {

                $adminQuickLinks = UserSetting::getValue(
                    $admin->id,
                    'quick_links',
                    []
                );
            }

            /*
            |--------------------------------------------------------------------------
            | USER QUICK LINKS
            |--------------------------------------------------------------------------
            */

            $userQuickLinks = UserSetting::getValue(
                $user->id,
                'quick_links',
                []
            );

            /*
            |--------------------------------------------------------------------------
            | MERGE QUICK LINKS
            |--------------------------------------------------------------------------
            */
            unset($adminQuickLinks['whatsapp_url']);
            $quickLinks = array_merge(
                $adminQuickLinks,
                $userQuickLinks
            );
        }

        /*
        |--------------------------------------------------------------------------
        | PROFILE SLUG
        |--------------------------------------------------------------------------
        */

        $profileSlug = UserSetting::getValue(
            $user->id,
            'profile_slug'
        );

        if (!$profileSlug) {

            $profileSlug = Str::slug($user->name) . '-' . $user->id;

            UserSetting::setValue(
                $user->id,
                'profile_slug',
                $profileSlug
            );
        }

        /*
        |--------------------------------------------------------------------------
        | PROFILE URL
        |--------------------------------------------------------------------------
        */

        $profileUrl = url('/profile/' . $profileSlug);

        $multiQrs = UserSetting::getValue(
            $user->id,
            'multi_qr_codes',
            []
        );

        foreach ($multiQrs as &$qr) {
            $trackingSlug = $qr['tracking_slug'] ?? $qr['id'];
            $qr['qr_scans'] = AnalyticsTracking::getQRWiseScans(
                $trackingSlug
            );
            $qr['button_clicks'] = AnalyticsTracking::getQRWiseClicks(
                $trackingSlug
            );
        }

        /*
        |--------------------------------------------------------------------------
        | ANALYTICS
        |--------------------------------------------------------------------------
        */

        $qrScans = AnalyticsTracking::getQRScansCount(
            $profileSlug
        );

        $btnClicks = AnalyticsTracking::getButtonClicksCount(
            $profileSlug
        );

        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        if ($user->is_admin == 1) {
            return view('admin.social.index', compact(
                'socialLinks',
                'quickLinks',
                'profileUrl',
                'qrScans',
                'btnClicks',
                'profileSlug'
            ));
        } else {
            return view('user.user-social.index', compact(
                'socialLinks',
                'quickLinks',
                'profileUrl',
                'qrScans',
                'btnClicks',
                'profileSlug',
                'multiQrs'
            ));
        }
    }

    // public function index()
    // {
    //     $user = Auth::user();
    //     if ($user->is_admin == 1) {
    //         $socialLinks = SocialLink::where('user_id', $user->id)->orderBy('sort_order')->get();
    //     } else {
    //         $admin = User::where('is_admin', 1)->first();
    //         $adminLinks = collect();
    //         if ($admin) {
    //             $adminLinks = SocialLink::where('user_id', $admin->id)->whereRaw('LOWER(platform_name) != ?', ['whatsapp'])->orderBy('sort_order')->get();
    //         }
    //         $userLinks = SocialLink::where('user_id', $user->id)->orderBy('sort_order')->get();
    //         $socialLinks = $adminLinks->map(function ($adminLink) use ($userLinks) {
    //             $userLink = $userLinks->first(function ($item) use ($adminLink) {
    //                 return strtolower(trim($item->platform_name))
    //                     == strtolower(trim($adminLink->platform_name));
    //             });
    //             if ($userLink) {
    //                 return $userLink;
    //             }
    //             return $adminLink;
    //         });
    //         $adminPlatformNames = $adminLinks->map(function ($item) {
    //             return strtolower(trim($item->platform_name));
    //         })->toArray();
    //         $extraUserLinks = $userLinks->filter(function ($link) use ($adminPlatformNames) {
    //             return !in_array(
    //                 strtolower(trim($link->platform_name)),
    //                 $adminPlatformNames
    //             );
    //         });
    //         $socialLinks = $socialLinks->merge($extraUserLinks);
    //     }
    //     if ($user->is_admin == 1) {
    //         $quickLinks = UserSetting::getValue($user->id,'quick_links',[]);
    //     } else {
    //         $admin = User::where('is_admin', 1)->first();
    //         $adminQuickLinks = [];
    //         if ($admin) {
    //             $adminQuickLinks = UserSetting::getValue($admin->id,'quick_links',[]);
    //         }
    //         $userQuickLinks = UserSetting::getValue($user->id, 'quick_links',[]);
    //         unset($adminQuickLinks['whatsapp_url']);
    //         $quickLinks = array_merge(
    //             $adminQuickLinks,
    //             $userQuickLinks
    //         );
    //     }
    //     $profileSlug = UserSetting::getValue($user->id,'profile_slug');
    //     if (!$profileSlug) {
    //         $profileSlug = Str::slug($user->name) . '-' . $user->id;
    //         UserSetting::setValue($user->id, 'profile_slug', $profileSlug);
    //     }

    //     $profileUrl = url('/profile/' . $profileSlug);
    //     $multiQrs = UserSetting::getValue($user->id, 'multi_qr_codes',[]);
    //     foreach ($multiQrs as &$qr) {
    //         $trackingSlug = $qr['tracking_slug'] ?? $qr['id'];
    //         $qr['qr_scans'] = AnalyticsTracking::getQRWiseScans($trackingSlug);
    //         $qr['button_clicks'] = AnalyticsTracking::getQRWiseClicks($trackingSlug);
    //     }
    //     $qrScans = AnalyticsTracking::getQRScansCount($profileSlug);
    //     $btnClicks = AnalyticsTracking::getButtonClicksCount($profileSlug);

    //     if ($user->is_admin == 1) {
    //         return view('admin.social.index', compact('socialLinks','quickLinks','profileUrl','qrScans','btnClicks','profileSlug'));
    //     } else {
    //         return view('user.user-social.index', compact('socialLinks','quickLinks','profileUrl','qrScans','btnClicks','profileSlug','multiQrs'));
    //     }
    // }

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
            'is_active' => true,
            'is_default' => $user->is_admin == 1 ? 1 : 0
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


        if ($user->is_admin == 1) {
            return view('admin.social.print', compact('user', 'socialLinks', 'quickLinks', 'profileUrl'));
        } else {
            return view('user.user-social.print', compact('user', 'socialLinks', 'quickLinks', 'profileUrl'));
        }
    }

    public function saveMultipleQR(Request $request)
    {
        $user = Auth::user();

        $links = $request->links;
        if (!$links || count($links) == 0) {
            return response()->json([
                'success' => false,
                'message' => 'No links selected'
            ]);
        }

        $savedQrs = UserSetting::getValue(
            $user->id,
            'multi_qr_codes',
            []
        );

        $qrId = 'qr_' . time();

        $savedQrs[] = [
            'id' => $qrId,
            'title' => $request->title ?? '',
            'tracking_slug' => $qrId,
            'links' => $links,
            'created_at' => now()->toDateTimeString()
        ];

        UserSetting::setValue(
            $user->id,
            'multi_qr_codes',
            $savedQrs
        );

        return response()->json([
            'success' => true,
            'qr_id' => $qrId,
            'url' => url('/multi-qr/' . $user->id . '/' . $qrId),
            'all_qrs' => $savedQrs
        ]);
    }

    public function showMultiQR($userId, $qrId)
    {
        $qrs = UserSetting::getValue(
            $userId,
            'multi_qr_codes',
            []
        );

        $selectedQr = collect($qrs)->firstWhere('id', $qrId);

        if (!$selectedQr) {
            abort(404);
        }

        $links = $selectedQr['links'];
        AnalyticsTracking::create([
            'profile_slug' => $selectedQr['tracking_slug'] ?? $selectedQr['id'],
            'event_type' => 'qr_scan',
            'platform' => 'multi_qr',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
        return view('profile.multi-qr-links', compact('links', 'selectedQr'));
    }

    public function trackMultiQRClick(Request $request)
    {
        AnalyticsTracking::create([
            'profile_slug' => $request->slug,
            'event_type' => 'button_click',
            'platform' => $request->platform,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);
        return redirect($request->url);
    }


    public function updateMultiQR(Request $request)
    {
        $request->validate([
            'qr_id' => 'required',
            'links' => 'required|array'
        ]);

        $user = Auth::user();
        $qrs = UserSetting::getValue(
            $user->id,
            'multi_qr_codes',
            []
        );

        foreach ($qrs as &$qr) {
            if ($qr['id'] == $request->qr_id) {
                $qr['title'] = $request->title ?? $qr['title'] ?? '';
                $updatedLinks = [];
                foreach ($request->links as $link) {
                    if (!empty($link['platform']) && !empty($link['url'])) {
                        $updatedLinks[] = [
                            'platform' => $link['platform'],
                            'url' => $link['url']
                        ];
                    }
                }
                $qr['links'] = $updatedLinks;
                $qr['updated_at'] = now()->toDateTimeString();
            }
        }

        UserSetting::setValue(
            $user->id,
            'multi_qr_codes',
            $qrs
        );

        foreach ($qrs as &$qr) {
            $trackingSlug = $qr['tracking_slug'] ?? $qr['id'];
            $qr['qr_scans'] = AnalyticsTracking::getQRWiseScans($trackingSlug);
            $qr['button_clicks'] = AnalyticsTracking::getQRWiseClicks($trackingSlug);
        }
        return response()->json([
            'success' => true,
            'message' => 'QR updated successfully',
            'all_qrs' => $qrs
        ]);
    }

    public function updateMultiQRTitle(Request $request)
    {
        $user = Auth::user();

        $qrs = UserSetting::getValue(
            $user->id,
            'multi_qr_codes',
            []
        );

        foreach ($qrs as &$qr) {
            if ($qr['id'] == $request->qr_id) {
                $qr['title'] = $request->title;
                $qr['updated_at'] = now()->toDateTimeString();
                break;
            }
        }

        UserSetting::setValue(
            $user->id,
            'multi_qr_codes',
            $qrs
        );

        return response()->json([
            'success' => true
        ]);
    }

    public function multiQrDestroy($id)
    {
        $user = auth::user();

        $multiQrCodes = UserSetting::getValue(
            $user->id,
            'multi_qr_codes',
            []
        );

        $multiQrCodes = collect($multiQrCodes)
            ->reject(function ($qr) use ($id) {
                return isset($qr['id']) && $qr['id'] === $id;
            })
            ->values()
            ->toArray();

            UserSetting::setValue($user->id,'multi_qr_codes', $multiQrCodes);

        return response()->json([
            'success' => true,
            'message' => 'QR deleted successfully'
        ]);
    }

    public function getMultiQrCodes()
    {
        $user = auth::user();
        $qrs = UserSetting::getValue(
            $user->id,
            'multi_qr_codes',
            []
        );

        return response()->json([
            'success' => true,
            'qrs' => $qrs
        ]);
    }

    public function userSocialLinksDestroy($id)
    {
        $user = Auth::user();
        $socialLink = SocialLink::where('user_id', $user->id)->findOrFail($id);
        $socialLink->delete();

        return response()->json([
            'success' => true,
            'message' => 'Link deleted successfully'
        ]);
    }

    public function updateSecondary(Request $request)
    {
        $link = SocialLink::findOrFail($request->id);

        $link->update([
            'platform_name' => $request->platform_name,
            'platform_url'  => $request->platform_url,
        ]);

        return response()->json([
            'success' => true
        ]);

    }


}
