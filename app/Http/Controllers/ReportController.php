<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CampaignLog;
use App\Models\EmailLinkClick;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;



class ReportController extends Controller
{
    public function index(Request $request)
    {

        $status = $request->status;
        $filter = $request->filter;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $from = $request->from;

        if (Auth::user()->is_admin == 1) {
            return view('admin.reports.campaign');
        }
        return view('reports.campaign', compact('status','filter', 'start_date', 'end_date', 'from'));
    }


    // live code
    // public function getCampaignLogsData(Request $request)
    // {
    //     $columns = [
    //         0 => 'campaign_logs.id',
    //         1 => 'lead_name',
    //         2 => 'lead_email',
    //         3 => 'step',
    //         4 => 'subject',
    //         5 => 'campaign_logs.status',
    //         6 => 'campaign_logs.scheduled_at',
    //         7 => 'campaign_logs.sent_at',
    //         8 => 'campaign_logs.seen_at',
    //         9 => 'campaign_logs.total_clicks',
    //     ];

    //     $query = CampaignLog::with('linkClicks')
    //         ->leftJoin('leads', 'campaign_logs.lead_id', '=', 'leads.id')
    //         ->leftJoin('sequences', 'campaign_logs.sequence_id', '=', 'sequences.id')
    //         ->where('campaign_logs.user_id', Auth::id())
    //         ->select([
    //             'campaign_logs.*',
    //             'leads.name as lead_name',
    //             'leads.email as lead_email',
    //             'sequences.step as step',
    //             'sequences.subject as subject',
    //         ]);


    //     if ($request->filled('status')) {
    //         $status = $request->status;
    //         $query->where('campaign_logs.status', $status);
    //     }

    //     // Total Records
    //     $totalData = (clone $query)->count();

    //     // Search
    //     if (!empty($request->search['value'])) {
    //         $search = $request->search['value'];
    //         $query->where(function ($q) use ($search) {
    //             $q->where('leads.name', 'LIKE', "%{$search}%")
    //                 ->orWhere('leads.email', 'LIKE', "%{$search}%")
    //                 ->orWhere('sequences.step', 'LIKE', "%{$search}%")
    //                 ->orWhere('sequences.subject', 'LIKE', "%{$search}%")
    //                 ->orWhere('campaign_logs.status', 'LIKE', "%{$search}%");
    //         });
    //     }
    //     $totalFiltered = (clone $query)->count();
    //     // Sorting
    //     $orderColumn = $columns[$request->input('order.0.column', 0)]
    //         ?? 'campaign_logs.id';
    //     $orderDir = $request->input('order.0.dir', 'desc');
    //     $query->orderBy($orderColumn, $orderDir);

    //     // Pagination
    //     $limit = $request->input('length', 25);
    //     $start = $request->input('start', 0);
    //     $campaignLogs = $query
    //         ->offset($start)
    //         ->limit($limit)
    //         ->get();
    //     $data = [];
    //     foreach ($campaignLogs as $log) {
    //         $whatsappClicks = $log->linkClicks->where('platform_name', 'whatsapp')->sum('click_count');
    //         $telegramClicks = $log->linkClicks->where('platform_name', 'telegram')->sum('click_count');
    //         $linkedinClicks = $log->linkClicks->where('platform_name', 'linkedin')->sum('click_count');
    //         $instagramClicks = $log->linkClicks->where('platform_name', 'instagram')->sum('click_count');
    //         $snapchatClicks = $log->linkClicks->where('platform_name', 'snapchat')->sum('click_count');
    //         $xClicks = $log->linkClicks->where('platform_name', 'x')->sum('click_count');
    //         $threadsClicks = $log->linkClicks->where('platform_name', 'threads')->sum('click_count');
    //         $fbMessengerClicks = $log->linkClicks->where('platform_name', 'facebook_messenger')->sum('click_count');

    //         $knownPlatforms = ['whatsapp', 'telegram', 'linkedin', 'instagram', 'snapchat', 'x', 'threads', 'facebook_messenger'];
    //         $otherClicks = $log->linkClicks
    //         ->whereNotIn('platform_name', $knownPlatforms)
    //         ->sum('click_count');

    //         // ✅ Total clicks - sum of all click_count
    //         $totalClicks = $log->linkClicks->sum('click_count');

    //         $statusBadge = $this->getStatusBadge($log->status);
    //         $data[] = [
    //             'id' => $log->id,
    //             'lead_name' => $log->lead_name ?? 'N/A',
    //             'lead_email' => $log->lead_email ?? 'N/A',
    //             'step' => $log->step ?? '-',
    //             'subject' => $log->subject
    //                 ? Str::limit($log->subject, 40)
    //                 : '-',
    //             'status_badge' => $statusBadge,
    //             'scheduled_at' => $this->convertToIST($log->scheduled_at),
    //             'sent_at' => $this->convertToIST($log->sent_at),
    //             'seen_at' => $this->convertToIST($log->seen_at),
    //             'whatsapp_clicks' => $whatsappClicks > 0
    //                 ? '<span class="badge bg-success">'.$whatsappClicks.'</span>'
    //                 : '0',
    //             'instagram_clicks' => $instagramClicks > 0
    //                 ? '<span class="badge bg-danger">'.$instagramClicks.'</span>'
    //                 : '0',
    //             'facebook_messenger_clicks' => $fbMessengerClicks > 0
    //                 ? '<span class="badge bg-primary">'.$fbMessengerClicks.'</span>'
    //                 : '0',
    //             'threads_clicks' => $threadsClicks > 0
    //                 ? '<span class="badge bg-secondary">'.$threadsClicks.'</span>'
    //                 : '0',
    //             'telegram_clicks' => $telegramClicks > 0
    //                 ? '<span class="badge bg-info">'.$telegramClicks.'</span>'
    //                 : '0',
    //             'snapchat_clicks' => $snapchatClicks > 0
    //                 ? '<span class="badge bg-warning">'.$snapchatClicks.'</span>'
    //                 : '0',
    //             'x_clicks' => $xClicks > 0
    //                 ? '<span class="badge bg-dark">'.$xClicks.'</span>'
    //                 : '0',
    //             'linkedin_clicks' => $linkedinClicks > 0
    //                 ? '<span class="badge bg-primary">'.$linkedinClicks.'</span>'
    //                 : '0',
    //             'other_clicks' => $otherClicks > 0 ? '<span class="badge bg-secondary">' . $otherClicks . '</span>' : '0',
    //             'total_clicks' => $totalClicks > 0
    //                 ? '<span class="badge bg-dark fs-6">'.$totalClicks.'</span>'
    //                 : '0',
    //         ];
    //     }

    //     return response()->json([
    //         'draw' => intval($request->draw),
    //         'recordsTotal' => $totalData,
    //         'recordsFiltered' => $totalFiltered,
    //         'data' => $data,
    //     ]);
    // }

    // end


    public function getCampaignLogsData(Request $request)
    {


        $columns = [
            0 => 'campaign_logs.id',
            1 => 'lead_name',
            2 => 'lead_email',
            3 => 'step',
            4 => 'subject',
            5 => 'campaign_logs.status',
            6 => 'campaign_logs.scheduled_at',
            7 => 'campaign_logs.sent_at',
            8 => 'campaign_logs.seen_at',
            9 => 'campaign_logs.total_clicks',
        ];

        $query = CampaignLog::with('linkClicks')
            ->leftJoin('leads', 'campaign_logs.lead_id', '=', 'leads.id')
            ->leftJoin('sequences', 'campaign_logs.sequence_id', '=', 'sequences.id')
            ->where('campaign_logs.user_id', Auth::id())
            ->select([
                'campaign_logs.*',
                'leads.name as lead_name',
                'leads.email as lead_email',
                'sequences.step as step',
                'sequences.subject as subject',
            ]);

        /*
|--------------------------------------------------------------------------
| Apply Dashboard Filter Only
|--------------------------------------------------------------------------
*/

   // Decide which date column to use based on status
if ($request->from == 'dashboard') {

    $filter = $request->filter ?? 'today';

    /*
    |--------------------------------------------------------------------------
    | Select Date Column According To Dashboard Card
    |--------------------------------------------------------------------------
    */

    switch ($request->status) {

        case 'pending':
            $dateColumn = 'campaign_logs.scheduled_at';
            break;

        case 'send':
            $dateColumn = 'campaign_logs.sent_at';
            break;

        case 'seen':
            $dateColumn = 'campaign_logs.seen_at';
            break;

        case 'failed':
            $dateColumn = 'campaign_logs.updated_at';
            break;

        case 'interested':
            $dateColumn = 'campaign_logs.updated_at';
            break;

        case 'not_interested':
            $dateColumn = 'campaign_logs.updated_at';
            break;

        default:
            $dateColumn = 'campaign_logs.created_at';
            break;
    }

    /*
    |--------------------------------------------------------------------------
    | Apply Date Filter
    |--------------------------------------------------------------------------
    */

    switch ($filter) {

        case 'today':

            $query->whereBetween($dateColumn, [
                Carbon::today()->startOfDay(),
                Carbon::today()->endOfDay()
            ]);

            break;

        case 'weekly':

            $query->whereBetween($dateColumn, [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ]);

            break;

        case 'monthly':

            $query->whereBetween($dateColumn, [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ]);

            break;

        case 'yearly':

            $query->whereBetween($dateColumn, [
                Carbon::now()->startOfYear(),
                Carbon::now()->endOfYear()
            ]);

            break;

        case 'custom':

            if ($request->filled('start_date') && $request->filled('end_date')) {

                $query->whereBetween($dateColumn, [
                    Carbon::parse($request->start_date)->startOfDay(),
                    Carbon::parse($request->end_date)->endOfDay(),
                ]);
            }

            break;
    }
}
        /*
    |--------------------------------------------------------------------------
    | Status Filter
    |--------------------------------------------------------------------------
    */

        if ($request->filled('status')) {

            if ($request->status == 'not_interested') {

                $query->whereIn('campaign_logs.status', [
                    'not_interested',
                    'Not Interested'
                ]);
            } else {

                $query->where('campaign_logs.status', $request->status);
            }
        }

        /*
    |--------------------------------------------------------------------------
    | Total Records
    |--------------------------------------------------------------------------
    */

        $totalData = (clone $query)->count();

        /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */

        if (!empty($request->search['value'])) {

            $search = $request->search['value'];

            $query->where(function ($q) use ($search) {

                $q->where('leads.name', 'LIKE', "%{$search}%")
                    ->orWhere('leads.email', 'LIKE', "%{$search}%")
                    ->orWhere('sequences.step', 'LIKE', "%{$search}%")
                    ->orWhere('sequences.subject', 'LIKE', "%{$search}%")
                    ->orWhere('campaign_logs.status', 'LIKE', "%{$search}%");
            });
        }

        $totalFiltered = (clone $query)->count();

        /*
    |--------------------------------------------------------------------------
    | Sorting
    |--------------------------------------------------------------------------
    */

        $orderColumn = $columns[$request->input('order.0.column', 0)]
            ?? 'campaign_logs.id';

        $orderDir = $request->input('order.0.dir', 'desc');

        $query->orderBy($orderColumn, $orderDir);

        /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    */

        $limit = $request->input('length', 25);
        $start = $request->input('start', 0);

        $campaignLogs = $query
            ->offset($start)
            ->limit($limit)
            ->get();

        $data = [];

        foreach ($campaignLogs as $log) {

            $whatsappClicks = $log->linkClicks->where('platform_name', 'whatsapp')->sum('click_count');
            $telegramClicks = $log->linkClicks->where('platform_name', 'telegram')->sum('click_count');
            $linkedinClicks = $log->linkClicks->where('platform_name', 'linkedin')->sum('click_count');
            $instagramClicks = $log->linkClicks->where('platform_name', 'instagram')->sum('click_count');
            $snapchatClicks = $log->linkClicks->where('platform_name', 'snapchat')->sum('click_count');
            $xClicks = $log->linkClicks->where('platform_name', 'x')->sum('click_count');
            $threadsClicks = $log->linkClicks->where('platform_name', 'threads')->sum('click_count');
            $fbMessengerClicks = $log->linkClicks->where('platform_name', 'facebook_messenger')->sum('click_count');

            $knownPlatforms = [
                'whatsapp',
                'telegram',
                'linkedin',
                'instagram',
                'snapchat',
                'x',
                'threads',
                'facebook_messenger'
            ];

            $otherClicks = $log->linkClicks
                ->whereNotIn('platform_name', $knownPlatforms)
                ->sum('click_count');

            $totalClicks = $log->linkClicks->sum('click_count');

            $data[] = [

                'id' => $log->id,

                'lead_name' => $log->lead_name ?? 'N/A',

                'lead_email' => $log->lead_email ?? 'N/A',

                'step' => $log->step ?? '-',

                'subject' => $log->subject
                    ? Str::limit($log->subject, 40)
                    : '-',

                'status_badge' => $this->getStatusBadge($log->status),

                'scheduled_at' => $this->convertToIST($log->scheduled_at),

                'sent_at' => $this->convertToIST($log->sent_at),

                'seen_at' => $this->convertToIST($log->seen_at),

                'whatsapp_clicks' => $whatsappClicks
                    ? '<span class="badge bg-success">' . $whatsappClicks . '</span>'
                    : '0',

                'instagram_clicks' => $instagramClicks
                    ? '<span class="badge bg-danger">' . $instagramClicks . '</span>'
                    : '0',

                'facebook_messenger_clicks' => $fbMessengerClicks
                    ? '<span class="badge bg-primary">' . $fbMessengerClicks . '</span>'
                    : '0',

                'threads_clicks' => $threadsClicks
                    ? '<span class="badge bg-secondary">' . $threadsClicks . '</span>'
                    : '0',

                'telegram_clicks' => $telegramClicks
                    ? '<span class="badge bg-info">' . $telegramClicks . '</span>'
                    : '0',

                'snapchat_clicks' => $snapchatClicks
                    ? '<span class="badge bg-warning">' . $snapchatClicks . '</span>'
                    : '0',

                'x_clicks' => $xClicks
                    ? '<span class="badge bg-dark">' . $xClicks . '</span>'
                    : '0',

                'linkedin_clicks' => $linkedinClicks
                    ? '<span class="badge bg-primary">' . $linkedinClicks . '</span>'
                    : '0',

                'other_clicks' => $otherClicks
                    ? '<span class="badge bg-secondary">' . $otherClicks . '</span>'
                    : '0',

                'total_clicks' => $totalClicks
                    ? '<span class="badge bg-dark fs-6">' . $totalClicks . '</span>'
                    : '0',

            ];
        }

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $data,

        ]);
    }

    private function convertToIST($datetime)
    {
        if (empty($datetime)) {
            return '-';
        }

        try {
            $date = Carbon::parse($datetime);
            $date->setTimezone('Asia/Kolkata');

            // Format: 15/06/26 12:21 PM
            return $date->format('d/m/y h:i A');
        } catch (\Exception $e) {
            return '-';
        }
    }

    private function getStatusBadge($status)
    {
        $badges = [
            'pending' => '<span class="badge bg-secondary">⏳ Pending</span>',
            'sent' => '<span class="badge bg-info">📤 Sent</span>',
            'seen' => '<span class="badge bg-primary">👁️ Seen</span>',
            'interested' => '<span class="badge bg-success">❤️ Interested</span>',
            'not_interested' => '<span class="badge bg-danger">💔 Not Interested</span>',
            'failed' => '<span class="badge bg-dark">❌ Failed</span>'
        ];

        return $badges[$status] ?? '<span class="badge bg-secondary">' . $status . '</span>';
    }

    /**
     * Get analytics data for dashboard cards
     */
    // public function getAnalytics()
    // {
    //     $userId = Auth::id();

    //     $totalSent = CampaignLog::where('user_id', $userId)
    //         ->whereIn('status', ['sent', 'seen', 'interested', 'not_interested'])
    //         ->count();

    //     $totalOpens = CampaignLog::where('user_id', $userId)
    //         ->where('status', 'seen')
    //         ->count();

    //     $totalInterested = CampaignLog::where('user_id', $userId)
    //         ->where('status', 'interested')
    //         ->count();

    //     $totalNotInterested = CampaignLog::where('user_id', $userId)
    //         ->where('status', 'not_interested')
    //         ->count();

    //     $totalClicks = EmailLinkClick::where('user_id', $userId)->count();

    //     $openRate = $totalSent > 0 ? round(($totalOpens / $totalSent) * 100, 2) : 0;
    //     $clickRate = $totalOpens > 0 ? round(($totalClicks / $totalOpens) * 100, 2) : 0;

    //     // Platform wise statistics for chart
    //     $platformStats = EmailLinkClick::where('user_id', $userId)
    //         ->select('platform_name', DB::raw('count(*) as total'))
    //         ->groupBy('platform_name')
    //         ->get();

    //     return response()->json([
    //         'total_sent' => $totalSent,
    //         'total_opens' => $totalOpens,
    //         'total_interested' => $totalInterested,
    //         'total_not_interested' => $totalNotInterested,
    //         'total_clicks' => $totalClicks,
    //         'open_rate' => $openRate,
    //         'click_rate' => $clickRate,
    //         'platform_stats' => $platformStats
    //     ]);
    // }

    /**
     * Get status badge HTML
     */


    /**
     * Get action buttons HTML
     */
    // private function getActionButtons($log)
    // {
    //     $buttons = '<div class="btn-group btn-group-sm" role="group">';
    //     $buttons .= '<button type="button" class="btn btn-info" onclick="viewDetails(' . $log->id . ')" title="View Details">👁️</button>';

    //     if ($log->status != 'interested' && $log->status != 'not_interested') {
    //         $buttons .= '<a href="' . route('lead-response', ['log' => $log->id, 'status' => 'interested']) . '" class="btn btn-success" title="Mark Interested">👍</a>';
    //         $buttons .= '<a href="' . route('lead-response', ['log' => $log->id, 'status' => 'not_interested']) . '" class="btn btn-danger" title="Mark Not Interested">👎</a>';
    //     }

    //     $buttons .= '</div>';

    //     return $buttons;
    // }

    /**
     * Get single campaign log details for modal
     */
    public function getDetails($id)
    {
        $log = CampaignLog::with(['lead', 'sequence', 'linkClicks'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        $clickDetails = [];
        foreach ($log->linkClicks as $click) {
            $clickDetails[] = [
                'platform' => ucfirst($click->platform_name),
                'clicked_at' => $click->clicked_at ? date('d M Y h:i A', strtotime($click->clicked_at)) : 'Not clicked',
                'ip_address' => $click->ip_address ?? '-',
                'click_count' => $click->click_count
            ];
        }

        return response()->json([
            'id' => $log->id,
            'lead_name' => $log->lead ? $log->lead->name : 'N/A',
            'lead_email' => $log->lead ? $log->lead->email : 'N/A',
            'step' => $log->sequence ? $log->sequence->step : 'N/A',
            'subject' => $log->sequence ? $log->sequence->subject : 'N/A',
            'status' => $log->status,
            'scheduled_at' => $log->scheduled_at ? date('d M Y h:i A', strtotime($log->scheduled_at)) : '-',
            'sent_at' => $log->sent_at ? date('d M Y h:i A', strtotime($log->sent_at)) : '-',
            'seen_at' => $log->seen_at ? date('d M Y h:i A', strtotime($log->seen_at)) : '-',
            'ip_address' => $log->ip_address ?? '-',
            'user_agent' => $log->user_agent ?? '-',
            'total_clicks' => $log->linkClicks->count(),
            'clicks' => $clickDetails
        ]);
    }


    /**
     * ✅ TRACK LINK CLICK - Sirf tab count karega jab user actually click kare
     */
    public function trackClick($token)
    {
        // Find the click record by token
        $click = EmailLinkClick::where('click_token', $token)->first();

        if (!$click) {
            Log::warning('Invalid tracking token', ['token' => $token]);
            abort(404, 'Invalid tracking link');
        }

        // ✅ IMPORTANT: Sirf tab update karo jab user ne click kiya ho
        // Clicked_at NULL hai matlab user ne abhi click kiya hai
        if (is_null($click->clicked_at)) {
            // First time click - Update with click details
            $click->update([
                'clicked_at' => now(),           // ✅ Click ka time
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'click_count' => 1               // ✅ Pehla click - count = 1
            ]);

            // Update campaign_log total clicks
            if ($click->campaignLog) {
                $currentTotal = $click->campaignLog->total_clicks ?? 0;
                $click->campaignLog->update([
                    'total_clicks' => $currentTotal + 1
                ]);
            }

            Log::info('✅ First Time Click Recorded', [
                'click_id' => $click->id,
                'platform' => $click->platform_name,
                'campaign_log_id' => $click->campaign_log_id,
                'lead_id' => $click->lead_id,
                'click_count' => 1,
                'ip' => request()->ip()
            ]);
        } else {
            // ✅ User ne dobara click kiya (duplicate click)
            $click->increment('click_count');

            Log::info('🔄 Duplicate Click Recorded', [
                'click_id' => $click->id,
                'platform' => $click->platform_name,
                'new_click_count' => $click->click_count,
                'ip' => request()->ip()
            ]);
        }

        // Redirect to original destination URL
        return redirect($click->destination_url);
    }

    public function exportCsv(Request $request)
    {
        // ---------- Replicate the DataTable query (exactly as in getCampaignLogsData) ----------
        $query = CampaignLog::with('linkClicks')
            ->leftJoin('leads', 'campaign_logs.lead_id', '=', 'leads.id')
            ->leftJoin('sequences', 'campaign_logs.sequence_id', '=', 'sequences.id')
            ->where('campaign_logs.user_id', Auth::id())
            ->select([
                'campaign_logs.*',
                'leads.name as lead_name',
                'leads.email as lead_email',
                'sequences.step as step',
                'sequences.subject as subject',
            ]);

        // Apply status filter if provided
        if ($request->filled('status')) {
            $query->where('campaign_logs.status', $request->status);
        }

        // Apply search filter if provided
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('leads.name', 'LIKE', "%{$search}%")
                    ->orWhere('leads.email', 'LIKE', "%{$search}%")
                    ->orWhere('sequences.step', 'LIKE', "%{$search}%")
                    ->orWhere('sequences.subject', 'LIKE', "%{$search}%")
                    ->orWhere('campaign_logs.status', 'LIKE', "%{$search}%");
            });
        }

        // Order by ID descending (same as DataTable default order)
        $query->orderBy('campaign_logs.id', 'desc');

        // ---------- Generate CSV ----------
        $fileName = 'campaign_logs_' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($query) {
            $handle = fopen('php://output', 'w');

            // Write CSV header row
            fputcsv($handle, [
                'ID',
                'Lead Name',
                'Lead Email',
                'Step',
                'Subject',
                'Status',
                'Scheduled At',
                'Sent At',
                'Seen At',
                'WhatsApp Clicks',
                'Instagram Clicks',
                'Facebook Messenger Clicks',
                'Threads Clicks',
                'Telegram Clicks',
                'Snapchat Clicks',
                'X Clicks',
                'LinkedIn Clicks',
                'Other Clicks',
                'Total Clicks',
            ]);

            // Process in chunks to avoid memory issues
            $query->chunk(200, function ($logs) use ($handle) {
                foreach ($logs as $log) {
                    // Aggregate clicks (same logic as in DataTable)
                    $whatsappClicks   = $log->linkClicks->where('platform_name', 'whatsapp')->sum('click_count');
                    $telegramClicks   = $log->linkClicks->where('platform_name', 'telegram')->sum('click_count');
                    $linkedinClicks   = $log->linkClicks->where('platform_name', 'linkedin')->sum('click_count');
                    $instagramClicks  = $log->linkClicks->where('platform_name', 'instagram')->sum('click_count');
                    $snapchatClicks   = $log->linkClicks->where('platform_name', 'snapchat')->sum('click_count');
                    $xClicks          = $log->linkClicks->where('platform_name', 'x')->sum('click_count');
                    $threadsClicks    = $log->linkClicks->where('platform_name', 'threads')->sum('click_count');
                    $fbMessengerClicks = $log->linkClicks->where('platform_name', 'facebook_messenger')->sum('click_count');

                    $knownPlatforms = ['whatsapp', 'telegram', 'linkedin', 'instagram', 'snapchat', 'x', 'threads', 'facebook_messenger'];
                    $otherClicks = $log->linkClicks->whereNotIn('platform_name', $knownPlatforms)->sum('click_count');

                    $totalClicks = $log->linkClicks->sum('click_count');

                    // Write one row
                    fputcsv($handle, [
                        $log->id,
                        $log->lead_name ?? 'N/A',
                        $log->lead_email ?? 'N/A',
                        $log->step ?? '-',
                        $log->subject ?? '-',
                        ucfirst($log->status),
                        $this->excelConvertToIST($log->scheduled_at),
                        $this->excelConvertToIST($log->sent_at),
                        $this->excelConvertToIST($log->seen_at),
                        $whatsappClicks,
                        $instagramClicks,
                        $fbMessengerClicks,
                        $threadsClicks,
                        $telegramClicks,
                        $snapchatClicks,
                        $xClicks,
                        $linkedinClicks,
                        $otherClicks,
                        $totalClicks,
                    ]);
                }
            });

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Helper to convert UTC to IST (copy from your existing method)
     */
    private function excelConvertToIST($date)
    {
        if (!$date) return null;
        $datetime = new \DateTime($date, new \DateTimeZone('UTC'));
        $datetime->setTimezone(new \DateTimeZone('Asia/Kolkata'));
        return $datetime->format('Y-m-d H:i:s');
    }
}
