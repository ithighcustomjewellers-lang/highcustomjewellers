<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CampaignLog;
use App\Models\EmailLinkClick;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.campaign');
    }

    public function getCampaignLogsData(Request $request)
    {
        $columns = [
            0 => 'id',
            1 => 'lead_name',
            2 => 'lead_email',
            3 => 'step',
            4 => 'subject',
            5 => 'status',
            6 => 'scheduled_at',
            7 => 'sent_at',
            8 => 'seen_at',
            9 => 'whatsapp_clicks',
            10 => 'instagram_clicks',
            11 => 'facebook_messenger_clicks',
            12 => 'threads_clicks',
            13 => 'telegram_clicks',
            14 => 'snapchat_clicks',
            15 => 'x_clicks',
            16 => 'linkedin_clicks',
            17 => 'total_clicks',
        ];

        // Base query with eager loading
        $query = CampaignLog::query()
        ->leftJoin('leads', 'campaign_logs.lead_id', '=', 'leads.id')
        ->leftJoin('sequences', 'campaign_logs.sequence_id', '=', 'sequences.id')
        ->select(
            'campaign_logs.*',
            'leads.name as lead_name',
            'leads.email as lead_email',
            'sequences.step',
            'sequences.subject'
        );


        // Total records
        $totalData = $query->count();

        // Search filter
        if ($request->has('search') && $request->search['value'] != '') {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->whereHas('lead', function ($q2) use ($search) {
                    $q2->where('name', 'LIKE', "%$search%")
                        ->orWhere('email', 'LIKE', "%$search%");
                })->orWhereHas('sequence', function ($q2) use ($search) {
                    $q2->where('step', 'LIKE', "%$search%")
                        ->orWhere('subject', 'LIKE', "%$search%");
                })->orWhere('status', 'LIKE', "%$search%");
            });
        }

        // Total after search
        $totalFiltered = $query->count();

        // Ordering
        $orderColumn = $columns[$request->input('order.0.column', 0)];
        $orderDir = $request->input('order.0.dir', 'desc');
        $query->orderBy($orderColumn, $orderDir);

        // Pagination
        $limit = $request->input('length', 25);
        $start = $request->input('start', 0);
        $campaignLogs = $query->offset($start)->limit($limit)->get();


        $data = [];
        foreach ($campaignLogs as $log) {
            // Get click counts by platform
            $whatsappClicks = $log->linkClicks->where('platform_name', 'whatsapp')->count();
            $instagramClicks = $log->linkClicks->where('platform_name', 'instagram')->count();
            $fbMessengerClicks = $log->linkClicks->where('platform_name', 'facebook_messenger')->count();
            $threadsClicks = $log->linkClicks->where('platform_name', 'threads')->count();
            $telegramClicks = $log->linkClicks->where('platform_name', 'telegram')->count();
            $snapchatClicks = $log->linkClicks->where('platform_name', 'snapchat')->count();
            $xClicks = $log->linkClicks->where('platform_name', 'x')->count();
            $linkedinClicks = $log->linkClicks->where('platform_name', 'linkedin')->count();
            $totalClicks = $log->linkClicks->count();

            // Status badge HTML
            $statusBadge = $this->getStatusBadge($log->status);

            $data[] = [
                'id' => $log->id,
                'lead_name' => $log->lead ? $log->lead->name : 'N/A',
                'lead_email' => $log->lead ? $log->lead->email : 'N/A',
                'step' => $log->sequence ? $log->sequence->step : 'N/A',
                'subject' => $log->sequence ? Str::limit($log->sequence->subject, 40) : 'N/A',
                'status_badge' => $statusBadge,
                'scheduled_at' => $log->scheduled_at ? date('d M Y h:i A', strtotime($log->scheduled_at)) : '-',
                'sent_at' => $log->sent_at ? date('d M Y h:i A', strtotime($log->sent_at)) : '-',
                'seen_at' => $log->seen_at ? date('d M Y h:i A', strtotime($log->seen_at)) : '-',
                'whatsapp_clicks' => $whatsappClicks > 0 ? '<span class="badge bg-success">' . $whatsappClicks . '</span>' : '0',
                'instagram_clicks' => $instagramClicks > 0 ? '<span class="badge bg-danger">' . $instagramClicks . '</span>' : '0',
                'facebook_messenger_clicks' => $fbMessengerClicks > 0 ? '<span class="badge bg-primary">' . $fbMessengerClicks . '</span>' : '0',
                'threads_clicks' => $threadsClicks > 0 ? '<span class="badge bg-secondary">' . $threadsClicks . '</span>' : '0',
                'telegram_clicks' => $telegramClicks > 0 ? '<span class="badge bg-info">' . $telegramClicks . '</span>' : '0',
                'snapchat_clicks' => $snapchatClicks > 0 ? '<span class="badge bg-warning">' . $snapchatClicks . '</span>' : '0',
                'x_clicks' => $xClicks > 0 ? '<span class="badge bg-dark">' . $xClicks . '</span>' : '0',
                'linkedin_clicks' => $linkedinClicks > 0 ? '<span class="badge bg-primary">' . $linkedinClicks . '</span>' : '0',
                'total_clicks' => $totalClicks > 0 ? '<span class="badge bg-dark fs-6">' . $totalClicks . '</span>' : '0',
            ];
        }

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $data
        ]);
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
}
