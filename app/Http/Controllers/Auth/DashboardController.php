<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AnalyticsTracking;
use App\Models\CampaignLog;
use App\Models\EmailLinkClick;
use App\Models\MultiQrLink;
use App\Models\UserSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function Dashboard(Request $request)
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Date Filter
        |--------------------------------------------------------------------------
        */

        $filter = $request->get('filter', 'today');

        $from = null;
        $to = null;

        switch ($filter) {

            case 'today':
                $from = Carbon::today()->startOfDay();
                $to   = Carbon::today()->endOfDay();
                break;

            case 'weekly':
                $from = Carbon::now()->startOfWeek();
                $to   = Carbon::now()->endOfWeek();
                break;

            case 'monthly':
                $from = Carbon::now()->startOfMonth();
                $to   = Carbon::now()->endOfMonth();
                break;

            case 'yearly':
                $from = Carbon::now()->startOfYear();
                $to   = Carbon::now()->endOfYear();
                break;

            case 'custom':

                if ($request->filled('start_date') && $request->filled('end_date')) {

                    $from = Carbon::parse($request->start_date)->startOfDay();
                    $to   = Carbon::parse($request->end_date)->endOfDay();
                }

                break;
        }

        /*
    |--------------------------------------------------------------------------
    | Get User QR Codes
    |--------------------------------------------------------------------------
    */

        $multiQrSetting = UserSetting::where('user_id', $user->id)
            ->where('key', 'multi_qr_codes')
            ->first();

        $trackingSlugs = [];

        if ($multiQrSetting && is_array($multiQrSetting->value)) {

            foreach ($multiQrSetting->value as $qr) {

                if (!empty($qr['tracking_slug'])) {
                    $trackingSlugs[] = $qr['tracking_slug'];
                }
            }
        }

        /*
    |--------------------------------------------------------------------------
    | Campaign Query
    |--------------------------------------------------------------------------
    */

        $campaignQuery = CampaignLog::where('user_id', $user->id);

        if ($from && $to) {
            $campaignQuery->whereBetween('created_at', [$from, $to]);
        }

        /*
    |--------------------------------------------------------------------------
    | Analytics Query
    |--------------------------------------------------------------------------
    */

        $analyticsQuery = AnalyticsTracking::whereIn('profile_slug', $trackingSlugs);

        if ($from && $to) {
            $analyticsQuery->whereBetween('created_at', [$from, $to]);
        }

        /*
    |--------------------------------------------------------------------------
    | Dashboard Stats
    |--------------------------------------------------------------------------
    */

        $stats = [
            'total_mail' => (clone $campaignQuery)->count(),

            'pending' => (clone $campaignQuery)
                ->where('status', 'pending')
                ->count(),

            'sent' => (clone $campaignQuery)
                ->where('status', 'send')
                ->count(),

            'seen' => (clone $campaignQuery)
                ->where('status', 'seen')
                ->count(),

            'fail' => (clone $campaignQuery)
                ->where('status', 'failed')
                ->count(),

            'interested' => (clone $campaignQuery)
                ->where('status', 'interested')
                ->count(),

            'not_interested' => (clone $campaignQuery)
                ->where('status', 'not_interested')
                ->count(),

            'qr_scans' => (clone $analyticsQuery)
                ->where('event_type', 'qr_scan')
                ->count(),

            'button_clicks' => (clone $analyticsQuery)
                ->where('event_type', 'button_click')
                ->count(),
        ];
        return view('user.main-dashboard', compact('stats', 'filter', 'from', 'to'));
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


    public function chartData(Request $request)
    {
        $userId = Auth::id();

        $filter = $request->get('filter', 'today');

        $from = null;
        $to = null;

        switch ($filter) {

            case 'today':
                $from = Carbon::today()->startOfDay();
                $to = Carbon::today()->endOfDay();
                break;

            case 'weekly':
                $from = Carbon::now()->startOfWeek();
                $to = Carbon::now()->endOfWeek();
                break;

            case 'monthly':
                $from = Carbon::now()->startOfMonth();
                $to = Carbon::now()->endOfMonth();
                break;

            case 'yearly':
                $from = Carbon::now()->startOfYear();
                $to = Carbon::now()->endOfYear();
                break;

            case 'custom':
                if ($request->filled('start_date') && $request->filled('end_date')) {
                    $from = Carbon::parse($request->start_date)->startOfDay();
                    $to = Carbon::parse($request->end_date)->endOfDay();
                }
                break;
        }

        $query = CampaignLog::where('user_id', $userId);

        if ($from && $to) {
            $query->whereBetween('created_at', [$from, $to]);
        }

        $pending = (clone $query)->where('status', 'pending')->count();
        $sent = (clone $query)->where('status', 'send')->count();
        $seen = (clone $query)->where('status', 'seen')->count();
        $fail = (clone $query)->where('status', 'failed')->count();
        $interested = (clone $query)->where('status', 'interested')->count();
        $notInterested = (clone $query)->where('status', 'not_interested')->count();

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


    // live
    // public function chartData()
    // {
    //     $userId = Auth::id();

    //     $pending = CampaignLog::where('user_id', $userId)
    //         ->where('status', 'pending')
    //         ->count();

    //     $sent = CampaignLog::where('user_id', $userId)
    //         ->where('status', 'send')
    //         ->count();

    //     $seen = CampaignLog::where('user_id', $userId)
    //         ->where('status', 'seen')
    //         ->count();

    //     $fail = CampaignLog::where('user_id', $userId)
    //         ->where('status', 'failed')
    //         ->count();

    //     $interested = CampaignLog::where('user_id', $userId)
    //         ->where('status', 'interested')
    //         ->count();

    //     $notInterested = CampaignLog::where('user_id', $userId)
    //         ->where('status', 'not_interested')
    //         ->count();

    //     return response()->json([
    //         'pending' => $pending,
    //         'sent' => $sent,
    //         'seen' => $seen,
    //         'fail' => $fail,
    //         'interested' => $interested,
    //         'not_interested' => $notInterested,
    //         'total_mail' => $pending + $sent + $seen + $fail + $interested + $notInterested
    //     ]);
    // }

    public function platformClickChart(Request $request)
    {
        $userId = Auth::id();

        $filter = $request->get('filter', 'today');

        $from = null;
        $to = null;

        switch ($filter) {

            case 'today':
                $from = Carbon::today()->startOfDay();
                $to = Carbon::today()->endOfDay();
                break;

            case 'weekly':
                $from = Carbon::now()->startOfWeek();
                $to = Carbon::now()->endOfWeek();
                break;

            case 'monthly':
                $from = Carbon::now()->startOfMonth();
                $to = Carbon::now()->endOfMonth();
                break;

            case 'yearly':
                $from = Carbon::now()->startOfYear();
                $to = Carbon::now()->endOfYear();
                break;

            case 'custom':
                if ($request->filled('start_date') && $request->filled('end_date')) {
                    $from = Carbon::parse($request->start_date)->startOfDay();
                    $to = Carbon::parse($request->end_date)->endOfDay();
                }
                break;
        }

        $platforms = [
            'whatsapp',
            'instagram',
            'facebook_messenger',
            'telegram',
            'linkedin',
            'x',
            'threads'
        ];

        $query = EmailLinkClick::where('user_id', $userId);

        if ($from && $to) {
            $query->whereBetween('created_at', [$from, $to]);
        }

        $data = [];

        foreach ($platforms as $platform) {

            $data[$platform] = (clone $query)
                ->where('platform_name', $platform)
                ->sum('click_count');
        }

        $data['other'] = (clone $query)
            ->whereNotIn('platform_name', $platforms)
            ->sum('click_count');

        Log::info('Platform Click Data:', $data);

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
                (int) $data['whatsapp'],
                (int) $data['instagram'],
                (int) $data['facebook_messenger'],
                (int) $data['telegram'],
                (int) $data['linkedin'],
                (int) $data['x'],
                (int) $data['threads'],
                (int) $data['other'],
            ],
            'total' => (int) array_sum($data)
        ]);
    }


    // live
    // public function platformClickChart()
    // {
    //     $userId = Auth::id();

    //     $platforms = [
    //         'whatsapp',
    //         'instagram',
    //         'facebook_messenger',
    //         'telegram',
    //         'linkedin',
    //         'x',
    //         'threads'
    //     ];

    //     $data = [];

    //     foreach ($platforms as $platform) {
    //         $data[$platform] = EmailLinkClick::where('user_id', $userId)
    //             ->where('platform_name', $platform)
    //             ->sum('click_count');
    //     }

    //     $data['other'] = EmailLinkClick::where('user_id', $userId)
    //         ->whereNotIn('platform_name', $platforms)
    //         ->sum('click_count');


    //          Log::info('Platform Click Data: ', $data); // Log the data for debugging

    //     return response()->json([
    //         'labels' => [
    //             'WhatsApp',
    //             'Instagram',
    //             'Facebook Messenger',
    //             'Telegram',
    //             'LinkedIn',
    //             'X (Twitter)',
    //             'Threads',
    //             'Other'
    //         ],
    //         'series' => [
    //             (int)$data['whatsapp'],
    //             (int)$data['instagram'],
    //             (int)$data['facebook_messenger'],
    //             (int)$data['telegram'],
    //             (int)$data['linkedin'],
    //             (int)$data['x'],
    //             (int)$data['threads'],
    //             (int)$data['other'],
    //         ],
    //         'total' => (int)array_sum($data)
    //     ]);
    // }
}
