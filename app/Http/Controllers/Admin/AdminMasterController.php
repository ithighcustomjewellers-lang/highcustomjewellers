<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CampaignLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminMasterController extends Controller
{
    public function userSequenceList()
    {

        $users = User::select('id', 'name', 'email')
        ->orderBy('name')
        ->get();
        return view('admin.master.UserSequenceTable',  compact('users'));
    }

public function userSequenceData(Request $request)
{
    $columns = [
        0 => 'campaign_logs.id',
        1 => 'leads.name',
        2 => 'leads.email',
        3 => 'sequences.step',
        4 => 'sequences.subject',
        5 => 'campaign_logs.scheduled_at',
        6 => 'campaign_logs.status',
        7 => 'campaign_logs.sent_at',
        8 => 'campaign_logs.seen_at',
        9 => 'whatsapp_clicks',        // Not searchable
        10 => 'instagram_clicks',      // Not searchable
        11 => 'facebook_messenger_clicks', // Not searchable
        12 => 'threads_clicks',        // Not searchable
        13 => 'telegram_clicks',       // Not searchable
        14 => 'snapchat_clicks',       // Not searchable
        15 => 'x_clicks',              // Not searchable
        16 => 'linkedin_clicks',       // Not searchable
        17 => 'other_clicks',       // Not searchable
        18 => 'total_clicks',          // Not searchable
        19 => 'users.name',
        20 => 'users.email',
    ];

    // Define which columns are searchable
    $searchableColumns = [
        1 => 'leads.name',
        2 => 'leads.email',
        3 => 'sequences.step',
        4 => 'sequences.subject',
        5 => 'campaign_logs.scheduled_at',
        6 => 'campaign_logs.status',
        7 => 'campaign_logs.sent_at',
        8 => 'campaign_logs.seen_at',
        19 => 'users.name',
        20 => 'users.email',
    ];

    $query = CampaignLog::query()
        ->leftJoin('leads', 'campaign_logs.lead_id', '=', 'leads.id')
        ->leftJoin('sequences', 'campaign_logs.sequence_id', '=', 'sequences.id')
        ->leftJoin('users', 'campaign_logs.user_id', '=', 'users.id')
        ->select(
            'campaign_logs.*',
            'leads.name as lead_name',
            'leads.email as lead_email',
            'sequences.step',
            'sequences.subject',
            'users.name as user_first_name',
            'users.lastname as user_last_name',
            'users.email as user_email'
        );

    // Role-based filtering
    // if (!Auth::user()->is_admin) {
    //     $query->where('campaign_logs.user_id', Auth::id());
    // }

    // --- INDIVIDUAL COLUMN SEARCH ---
    // Check for individual column search


    if ($request->has('user_name') && !empty($request->user_name)) {
        $query->where('users.name', '=', $request->user_name);
    }

    if ($request->has('user_email') && !empty($request->user_email)) {
        $query->where('users.email', '=', $request->user_email);
    }

  // --- INDIVIDUAL COLUMN SEARCH ---
    $hasColumnSearch = false;
    foreach ($searchableColumns as $index => $column) {
        $searchValue = $request->input("columns.{$index}.search.value");
        if (!empty($searchValue)) {
            $hasColumnSearch = true;
            $query->where($column, 'LIKE', '%' . $searchValue . '%');
        }
    }

    // Only use global search if no column-specific search is applied
    if (!$hasColumnSearch && $request->has('search') && $request->search['value'] != '') {
        $search = $request->search['value'];
        $query->where(function ($q) use ($search) {
            $q->where('leads.name', 'LIKE', "%$search%")
                ->orWhere('leads.email', 'LIKE', "%$search%")
                ->orWhere('sequences.step', 'LIKE', "%$search%")
                ->orWhere('sequences.subject', 'LIKE', "%$search%")
                ->orWhere('campaign_logs.status', 'LIKE', "%$search%")
                ->orWhere('campaign_logs.scheduled_at', 'LIKE', "%$search%")
                ->orWhere('campaign_logs.sent_at', 'LIKE', "%$search%")
                ->orWhere('campaign_logs.seen_at', 'LIKE', "%$search%")
                ->orWhere('users.name', 'LIKE', "%$search%")
                ->orWhere('users.email', 'LIKE', "%$search%");
        });
    }

    // Total records count
    $totalData = $query->count();

    // Ordering
    $orderColumn = $columns[$request->input('order.0.column', 0)];
    $orderDir = $request->input('order.0.dir', 'desc');
    $query->orderBy($orderColumn, $orderDir);

    // Pagination
    $limit = $request->input('length', 25);
    $start = $request->input('start', 0);
    $campaignLogs = $query->offset($start)->limit($limit)->get();

    // Process data
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

        $knownPlatforms = ['whatsapp', 'telegram', 'linkedin', 'instagram', 'snapchat', 'x', 'threads', 'facebook_messenger'];
        $otherClicks = $log->linkClicks
            ->whereNotIn('platform_name', $knownPlatforms)
            ->sum('click_count');

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
            'other_clicks' => $otherClicks > 0 ? '<span class="badge bg-secondary">' . $otherClicks . '</span>' : '0',
            'total_clicks' => $totalClicks > 0 ? '<span class="badge bg-dark fs-6">' . $totalClicks . '</span>' : '0',
            'full_name' => trim($log->user_first_name . ' ' . $log->user_last_name),
            'email' => $log->user_email
        ];
    }

    return response()->json([
        'draw' => intval($request->draw),
        'recordsTotal' => $totalData,
        'recordsFiltered' => $totalData, // This should be the filtered count
        'data' => $data
    ]);
}

/**
 * Get status badge HTML
 */
private function getStatusBadge($status)
{
    $badgeClasses = [
        'sent' => 'badge bg-success',
        'scheduled' => 'badge bg-warning',
        'seen' => 'badge bg-info',
        'failed' => 'badge bg-danger',
        'pending' => 'badge bg-secondary',
        'delivered' => 'badge bg-primary',
        'opened' => 'badge bg-primary',
        'clicked' => 'badge bg-info',
        'bounced' => 'badge bg-danger',
        'unsubscribed' => 'badge bg-secondary',
    ];

    $class = $badgeClasses[strtolower($status)] ?? 'badge bg-secondary';
    return '<span class="' . $class . '">' . ucfirst($status) . '</span>';
}
}
