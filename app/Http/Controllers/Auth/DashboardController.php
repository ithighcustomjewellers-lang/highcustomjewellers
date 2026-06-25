<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AnalyticsTracking;
use App\Models\CampaignLog;
use App\Models\EmailLinkClick;
use App\Models\MultiQrLink;
use App\Models\UserSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function Dashboard()
    {
        $userId = Auth::user();

        // Get user's QR codes
        $multiQrSetting = UserSetting::where('user_id', $userId->id)
        ->where('key', 'multi_qr_codes')
        ->first();


        $trackingSlugs = [];

        if ($multiQrSetting) {

            foreach ($multiQrSetting->value as $qr) {

                if (!empty($qr['tracking_slug'])) {
                    $trackingSlugs[] = $qr['tracking_slug'];
                }
            }
        }


        $qrScans = AnalyticsTracking::whereIn('profile_slug', $trackingSlugs)
            ->where('event_type', 'qr_scan')
            ->count();

        $buttonClicks = AnalyticsTracking::whereIn('profile_slug', $trackingSlugs)
            ->where('event_type', 'button_click')
            ->count();

        $stats = [
            'total_mail' => CampaignLog::where('user_id', $userId->id)
            ->where('status', '!=', 'pending')
            ->count(),

            'sent' => CampaignLog::where('user_id', $userId->id)
                ->where('status', 'send')
                ->count(),

            'seen' => CampaignLog::where('user_id', $userId->id)
                ->where('status', 'seen')
                ->count(),

            'fail' => CampaignLog::where('user_id', $userId->id)
                ->where('status', 'failed')
                ->count(),

            'interested' => CampaignLog::where('user_id', $userId->id)
                ->where('status', 'interested')
                ->count(),

            'not_interested' => CampaignLog::where('user_id', $userId->id)
                ->where('status', 'not_interested')
                ->count(),

                'qr_scans' => $qrScans,
                'button_clicks' => $buttonClicks,
        ];

        return view('user.main-dashboard', compact('stats'));
    }

    public function UserProfile()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    public function submitProfileUpdate(Request $request)
    {
        $request->validate([
            'first_name'   => 'required|string|max:255',
            'last_name'   => 'required|string|max:255',
            'phone' => 'required|string|regex:/^\+[1-9]\d{6,14}$/',
            'image'  => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $user = Auth::user();

        $user->name     = $request->first_name;
        $user->lastname = $request->last_name;
        $user->mobile   = $request->phone;

        // ✅ Password update
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // ✅ Image upload
        if ($request->hasFile('user_image')) {
            // delete old image (optional)
            if ($user->image && file_exists(public_path($user->image))) {
                unlink(public_path($user->image));
            }
            $file = $request->file('user_image');
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


    public function chartData()
    {
        $userId = Auth::id();

        $pending = CampaignLog::where('user_id', $userId)
            ->where('status', 'pending')
            ->count();

        $sent = CampaignLog::where('user_id', $userId)
            ->where('status', 'send')
            ->count();

        $seen = CampaignLog::where('user_id', $userId)
            ->where('status', 'seen')
            ->count();

        $fail = CampaignLog::where('user_id', $userId)
            ->where('status', 'failed')
            ->count();

        $interested = CampaignLog::where('user_id', $userId)
            ->where('status', 'interested')
            ->count();

        $notInterested = CampaignLog::where('user_id', $userId)
            ->where('status', 'not_interested')
            ->count();

        return response()->json([
            'pending' => $pending,
            'sent' => $sent,
            'seen' => $seen,
            'fail' => $fail,
            'interested' => $interested,
            'not_interested' => $notInterested,
            'total_mail' => $pending + $sent + $seen + $fail + $interested + $notInterested
        ]);
    }

    public function platformClickChart()
    {
        $userId = Auth::id();

        $platforms = [
            'whatsapp',
            'instagram',
            'facebook_messenger',
            'telegram',
            'linkedin',
            'x',
            'threads'
        ];

        $data = [];

        foreach ($platforms as $platform) {
            $data[$platform] = EmailLinkClick::where('user_id', $userId)
                ->where('platform_name', $platform)
                ->sum('click_count');
        }

        $data['other'] = EmailLinkClick::where('user_id', $userId)
            ->whereNotIn('platform_name', $platforms)
            ->sum('click_count');

        return response()->json([
            'labels' => [
                'WhatsApp',
                'Instagram',
                'Facebook Messenger',
                'Telegram',
                'LinkedIn',
                'X (Twitter)',
                'Threads',
                'Other'
            ],
            'series' => [
                (int)$data['whatsapp'],
                (int)$data['instagram'],
                (int)$data['facebook_messenger'],
                (int)$data['telegram'],
                (int)$data['linkedin'],
                (int)$data['x'],
                (int)$data['threads'],
                (int)$data['other'],
            ],
            'total' => (int)array_sum($data)
        ]);
    }


}
