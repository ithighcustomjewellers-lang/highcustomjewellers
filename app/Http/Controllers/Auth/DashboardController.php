<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AnalyticsTracking;
use App\Models\CampaignLog;
use App\Models\EmailLinkClick;
use App\Models\Lead;
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

            'total_leads' => Lead::where('user_id', $user->id)->count(),

            /*
        |--------------------------------------------------------------------------
        | Total Created
        |--------------------------------------------------------------------------
        */

            'total_mail' => CampaignLog::where('user_id', $user->id)
                ->when($from && $to, function ($q) use ($from, $to) {
                    $q->whereBetween('created_at', [$from, $to]);
                })
                ->count(),

            /*
        |--------------------------------------------------------------------------
        | Pending
        |--------------------------------------------------------------------------
        */

            'pending' => CampaignLog::where('user_id', $user->id)
                ->where('status', 'pending')
                ->when($from && $to, function ($q) use ($from, $to) {
                    $q->whereBetween('scheduled_at', [$from, $to]);
                })
                ->count(),

            /*
        |--------------------------------------------------------------------------
        | Sent
        |--------------------------------------------------------------------------
        */

            'sent' => CampaignLog::where('user_id', $user->id)
                ->where('status', 'send')
                ->when($from && $to, function ($q) use ($from, $to) {
                    $q->whereBetween('sent_at', [$from, $to]);
                })
                ->count(),

            /*
        |--------------------------------------------------------------------------
        | Seen
        |--------------------------------------------------------------------------
        */

            'seen' => CampaignLog::where('user_id', $user->id)
                ->where('status', 'seen')
                ->when($from && $to, function ($q) use ($from, $to) {
                    $q->whereBetween('seen_at', [$from, $to]);
                })
                ->count(),

            /*
        |--------------------------------------------------------------------------
        | Failed
        |--------------------------------------------------------------------------
        */

            'fail' => CampaignLog::where('user_id', $user->id)
                ->where('status', 'failed')
                ->when($from && $to, function ($q) use ($from, $to) {
                    $q->whereBetween('updated_at', [$from, $to]);
                })
                ->count(),

            /*
        |--------------------------------------------------------------------------
        | Interested
        |--------------------------------------------------------------------------
        */

            'interested' => CampaignLog::where('user_id', $user->id)
                ->where('status', 'interested')
                ->when($from && $to, function ($q) use ($from, $to) {
                    $q->whereBetween('updated_at', [$from, $to]);
                })
                ->count(),

            /*
        |--------------------------------------------------------------------------
        | Not Interested
        |--------------------------------------------------------------------------
        */

            'not_interested' => CampaignLog::where('user_id', $user->id)
                ->whereIn('status', ['not_interested', 'Not Interested'])
                ->when($from && $to, function ($q) use ($from, $to) {
                    $q->whereBetween('updated_at', [$from, $to]);
                })
                ->count(),

            /*
        |--------------------------------------------------------------------------
        | QR Stats
        |--------------------------------------------------------------------------
        */

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
    | Pending (scheduled_at)
    |--------------------------------------------------------------------------
    */

        $pending = CampaignLog::where('user_id', $userId)
            ->where('status', 'pending')
            ->when($from && $to, function ($q) use ($from, $to) {
                $q->whereBetween('scheduled_at', [$from, $to]);
            })
            ->count();



        /*
    |--------------------------------------------------------------------------
    | Sent (sent_at)
    |--------------------------------------------------------------------------
    */

        $sent = CampaignLog::where('user_id', $userId)
            ->where('status', 'send')
            ->when($from && $to, function ($q) use ($from, $to) {
                $q->whereBetween('sent_at', [$from, $to]);
            })
            ->count();

        /*
    |--------------------------------------------------------------------------
    | Seen (seen_at)
    |--------------------------------------------------------------------------
    */

        $seen = CampaignLog::where('user_id', $userId)
            ->where('status', 'seen')
            ->when($from && $to, function ($q) use ($from, $to) {
                $q->whereBetween('seen_at', [$from, $to]);
            })
            ->count();

        /*
    |--------------------------------------------------------------------------
    | Failed (sent_at)
    |--------------------------------------------------------------------------
    */

        $fail = CampaignLog::where('user_id', $userId)
            ->where('status', 'failed')
            ->when($from && $to, function ($q) use ($from, $to) {
                $q->whereBetween('sent_at', [$from, $to]);
            })
            ->count();
        /*

    |--------------------------------------------------------------------------
    | Interested (updated_at)
    |--------------------------------------------------------------------------
    */

        $interested = CampaignLog::where('user_id', $userId)
            ->where('status', 'interested')
            ->when($from && $to, function ($q) use ($from, $to) {
                $q->whereBetween('updated_at', [$from, $to]);
            })
            ->count();

        /*
    |--------------------------------------------------------------------------
    | Not Interested (updated_at)
    |--------------------------------------------------------------------------
    */

        $notInterested = CampaignLog::where('user_id', $userId)
            ->whereIn('status', ['not_interested', 'Not Interested'])
            ->when($from && $to, function ($q) use ($from, $to) {
                $q->whereBetween('updated_at', [$from, $to]);
            })
            ->count();

        return response()->json([
            'pending'         => $pending,
            'sent'            => $sent,
            'seen'            => $seen,
            'fail'            => $fail,
            'interested'      => $interested,
            'not_interested'  => $notInterested,
            'total_mail'      => $pending + $sent + $seen + $fail + $interested + $notInterested,
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


    public function dashboardTotalLeadsList()
    {
        return view('user.Leads.total-leads');
    }

    public function totalLeadsDataList(Request $request)
    {
        $columns = [
            0 => 'email',
            1 => 'name',
            2 => 'lastname',
            3 => 'company_name',
            4 => 'type',
            5 => 'created_at',
            6 => 'updated_at',
        ];

        $query = Lead::where('user_id', Auth::id());

        $totalData = $query->count();

        if ($request->filled('search.value')) {

            $search = $request->search['value'];

            $query->where(function ($q) use ($search) {

                $q->where('email', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('lastname', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%")
                    ->orWhere('type', 'like', "%{$search}%");
            });
        }

        $totalFiltered = $query->count();

        $orderColumn = $columns[$request->input('order.0.column', 0)];
        $orderDir = $request->input('order.0.dir', 'desc');

        $query->orderBy($orderColumn, $orderDir);

        $leads = $query
            ->skip($request->start)
            ->take($request->length)
            ->get();

        $data = [];

        foreach ($leads as $lead) {

            $data[] = [
                'email'        => $lead->email,
                'name'         => $lead->name,
                'lastname'     => $lead->lastname,
                'company_name' => $lead->company_name,
                'type'         => $lead->type,
                'created_at'   => $lead->created_at->format('d-m-Y H:i'),
                'updated_at'   => $lead->updated_at->format('d-m-Y H:i'),
            ];
        }

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $data
        ]);
    }

    // public function dashboardButtonClickChart(Request $request)
    // {
    //     $query = AnalyticsTracking::where('event_type', 'button_click');
    //     $filter = $request->get('filter', 'today');
    //     $from = null;
    //     $to = null;

    //     switch ($filter) {
    //         case 'today':
    //             $from = Carbon::today()->startOfDay();
    //             $to = Carbon::today()->endOfDay();
    //             break;

    //         case 'weekly':
    //             $from = Carbon::now()->startOfWeek();
    //             $to = Carbon::now()->endOfWeek();
    //             break;

    //         case 'monthly':
    //             $from = Carbon::now()->startOfMonth();
    //             $to = Carbon::now()->endOfMonth();
    //             break;

    //         case 'yearly':
    //             $from = Carbon::now()->startOfYear();
    //             $to = Carbon::now()->endOfYear();
    //             break;

    //         case 'custom':
    //             if ($request->filled('start_date') && $request->filled('end_date')) {
    //                 $from = Carbon::parse($request->start_date)->startOfDay();
    //                 $to   = Carbon::parse($request->end_date)->endOfDay();
    //             }
    //             break;
    //     }

    //     if ($from && $to) {
    //         $query->whereBetween('created_at', [$from, $to]);
    //     }

    //     $buttons = $query
    //         ->whereNotNull('platform')
    //         ->where('platform', '!=', '')
    //         ->selectRaw('platform, COUNT(*) as total')
    //         ->groupBy('platform')
    //         ->orderByDesc('total')
    //         ->get();

    //     return response()->json([
    //         'labels' => $buttons->pluck('platform')->values(),
    //         'series' => $buttons->pluck('total')->values(),
    //         'total'  => $buttons->sum('total'),
    //     ]);
    // }

    // public function dashboardButtonClickChart(Request $request)
    // {
    //     $user = Auth::user();

    //     // Logged-in user ke QR codes
    //     $userSetting = UserSetting::where('user_id', $user->id)->first();
    //     $multiQrCodes = json_decode($userSetting->value ?? '[]', true);

    //     dd($multiQrCodes);

    //     $trackingSlugs = collect($multiQrCodes)
    //         ->pluck('tracking_slug')
    //         ->filter()
    //         ->values()
    //         ->toArray();

    //     $query = AnalyticsTracking::whereIn('profile_slug', $trackingSlugs)
    //         ->where('event_type', 'button_click');

    //     $filter = $request->get('filter', 'today');

    //     $from = null;
    //     $to = null;

    //     switch ($filter) {

    //         case 'today':
    //             $from = Carbon::today()->startOfDay();
    //             $to = Carbon::today()->endOfDay();
    //             break;

    //         case 'weekly':
    //             $from = Carbon::now()->startOfWeek();
    //             $to = Carbon::now()->endOfWeek();
    //             break;

    //         case 'monthly':
    //             $from = Carbon::now()->startOfMonth();
    //             $to = Carbon::now()->endOfMonth();
    //             break;

    //         case 'yearly':
    //             $from = Carbon::now()->startOfYear();
    //             $to = Carbon::now()->endOfYear();
    //             break;

    //         case 'custom':

    //             if ($request->filled('start_date') && $request->filled('end_date')) {

    //                 $from = Carbon::parse($request->start_date)->startOfDay();
    //                 $to   = Carbon::parse($request->end_date)->endOfDay();
    //             }

    //             break;
    //     }

    //     if ($from && $to) {
    //         $query->whereBetween('created_at', [$from, $to]);
    //     }

    //     $buttons = $query
    //         ->whereNotNull('platform')
    //         ->where('platform', '!=', '')
    //         ->selectRaw('platform, COUNT(*) as total')
    //         ->groupBy('platform')
    //         ->orderByDesc('total')
    //         ->get();

    //     return response()->json([
    //         'labels' => $buttons->pluck('platform')->values(),
    //         'series' => $buttons->pluck('total')->values(),
    //         'total'  => $buttons->sum('total'),
    //     ]);
    // }

    public function dashboardButtonClickChart(Request $request)
    {
        // Logged-in User
        $user = Auth::user();

        // User ke Multi QR Codes
        $userSetting = UserSetting::where('user_id', $user->id)
            ->where('key', 'multi_qr_codes')
            ->first();

       $multiQrCodes = $userSetting->value ?? [];

        // Sirf current QR tracking slugs
        $trackingSlugs = collect($multiQrCodes)
            ->pluck('tracking_slug')
            ->filter()
            ->values()
            ->toArray();

        // Agar user ke paas koi QR nahi hai
        if (empty($trackingSlugs)) {
            return response()->json([
                'labels' => [],
                'series' => [],
                'total'  => 0,
            ]);
        }

        $query = AnalyticsTracking::where('event_type', 'button_click')
            ->whereIn('profile_slug', $trackingSlugs);

        // Date Filter
        $filter = $request->get('filter', 'today');

        $from = null;
        $to   = null;

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

        if ($from && $to) {
            $query->whereBetween('created_at', [$from, $to]);
        }

        // Platform-wise Button Clicks
        $buttons = $query
            ->whereNotNull('platform')
            ->where('platform', '!=', '')
            ->selectRaw('platform, COUNT(*) as total')
            ->groupBy('platform')
            ->orderByDesc('total')
            ->get();

        return response()->json([
            'labels' => $buttons->pluck('platform')->toArray(),
            'series' => $buttons->pluck('total')->toArray(),
            'total'  => $buttons->sum('total'),
        ]);
    }
}
