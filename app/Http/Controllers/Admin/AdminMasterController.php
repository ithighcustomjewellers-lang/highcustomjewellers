<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendCampaignJob;
use App\Models\CampaignLog;
use App\Models\Lead;
use App\Models\Sequence;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
            9 => 'whatsapp_clicks',
            10 => 'instagram_clicks',
            11 => 'facebook_messenger_clicks',
            12 => 'threads_clicks',
            13 => 'telegram_clicks',
            14 => 'snapchat_clicks',
            15 => 'x_clicks',
            16 => 'linkedin_clicks',
            17 => 'other_clicks',
            18 => 'total_clicks',
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
        if (!Auth::user()->is_admin) {
            $query->where('campaign_logs.user_id', Auth::id());
        }

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


    public function userMasterList(){
           $users = User::select('id', 'name', 'email')
            ->orderBy('name')
            ->get();
        return view('admin.master.userMasterList', compact('users'));
    }

    public function userMasterDataList(Request $request)
    {
        $columns = [
            0 => 'sequences.id',
            1 => 'sequences.step',
            2 => 'sequences.gap_days',
            3 => 'sequences.variant',
            4 => 'sequences.message',
            5 => 'sequences.subject',
            6 => 'sequences.type',
            7 => 'sequences.whatsapp_link',
            8 => 'users.name',      // User Name
            9 => 'users.email',     // User Email
            10 => 'sequences.created_at',
            11 => 'sequences.updated_at',
        ];

        // ✅ Base query - Get ALL sequences with user information
        $query = Sequence::query()
            ->leftJoin('users', 'sequences.user_id', '=', 'users.id')
            ->select(
                'sequences.*',
                'users.name as user_name',
                'users.email as user_email'
            );

        if (!Auth::user()->is_admin) {
            $query->where('sequences.user_id', Auth::id());
        }

        // --- USER NAME AND EMAIL FILTERS ---
        if ($request->has('user_name') && !empty($request->user_name)) {
            $query->where('users.name', '=', $request->user_name);
        }

        if ($request->has('user_email') && !empty($request->user_email)) {
            $query->where('users.email', '=', $request->user_email);
        }

        // Total records (with role-based filter)
        $totalQuery = Sequence::query()
            ->leftJoin('users', 'sequences.user_id', '=', 'users.id');

        if (!Auth::user()->is_admin) {
            $totalQuery->where('sequences.user_id', Auth::id());
        }
        $totalData = $totalQuery->count();

        // Search filter
        if ($request->has('search') && $request->search['value'] != '') {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('sequences.step', 'LIKE', "%$search%")
                    ->orWhere('sequences.gap_days', 'LIKE', "%$search%")
                    ->orWhere('sequences.variant', 'LIKE', "%$search%")
                    ->orWhere('sequences.subject', 'LIKE', "%$search%")
                    ->orWhere('sequences.type', 'LIKE', "%$search%")
                    ->orWhere('sequences.message', 'LIKE', "%$search%")
                    ->orWhere('users.name', 'LIKE', "%$search%")
                    ->orWhere('users.email', 'LIKE', "%$search%");
            });
        }

        // Total after search
        $totalFiltered = $query->count();

        // Ordering
        $orderColumn = $columns[$request->input('order.0.column', 0)];
        $orderDir = $request->input('order.0.dir', 'desc');
        $query->orderBy($orderColumn, $orderDir);

        // Pagination
        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);
        $sequences = $query->offset($start)->limit($limit)->get();

        $data = [];
        foreach ($sequences as $seq) {
            $data[] = [
                'edit' => '<a href="' . route('user-master-sequences-list-edit', $seq->id) . '" class="btn btn-sm btn-primary">Edit</a>',
                'id' => $seq->id,
                'step' => $seq->step,
                'gap_days' => $seq->gap_days ?? '-',
                'variant' => $seq->variant ?? '-',
                'message' => Str::limit($seq->message, 50),
                'subject' => $seq->subject,
                'type' => $seq->type,
                'whatsapp_link' => $seq->whatsapp_link ? '<a href="' . $seq->whatsapp_link . '" target="_blank">Link</a>' : '-',
                'user_name' => $seq->user_name ?? 'N/A',
                'user_email' => $seq->user_email ?? 'N/A',
                'created_at' => date('Y-m-d', strtotime($seq->created_at)),
                'updated_at' => date('Y-m-d', strtotime($seq->updated_at)),
                'delete' => '
                <button type="button"
                    onclick="userMasterDeleteList('.$seq->id.')"
                    class="btn btn-sm btn-danger">
                    <i class="fas fa-trash"></i> Delete
                </button>',
            ];
        }

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $data
        ]);
    }

    public function userMasterinlineUpdate(Request $request){
        $request->validate([
            'id'    => 'required|exists:sequences,id',
            'field' => 'required|in:step,gap_days,variant',
            'value' => 'required|string|max:255'
        ]);

        $sequence = Sequence::findOrFail($request->id);
        $field = $request->field;
        $value = $request->value;

        if ($field === 'step') {
            $request->validate(['value' => 'required|integer|min:1']);
        }
        if ($field === 'gap_days') {
            $request->validate(['value' => 'required|integer|min:0']);
        }
        if ($field === 'variant') {
            $value = strtoupper($value); // optional
        }

        $sequence->$field = $value;
        $sequence->save();

        return response()->json(['success' => true, 'message' => 'Updated successfully']);
    }

    public function userMastersequencesListEdit($id){
        $sequence = Sequence::where('id', $id)->firstOrFail();
        return view('admin.master.user-master-sequences-edit', compact('sequence'));
    }

    public function userMasterSequencesListUpdate(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $sequence = Sequence::where('id', $id)->firstOrFail();
            $userId = $sequence->user_id;

            $validated = $request->validate([
                'step'           => 'required|integer|min:1',
                'gap_days'       => 'required|integer|min:0',
                'variant'        => 'nullable|string|max:10|regex:/^[A-Z0-9]+$/i',
                'type'           => 'required|in:B2B,B2C',
                'subject'        => 'required|string|max:255',
                'message'        => 'required|string',
                'whatsapp_link'  => 'nullable|url',
                'logo_position'  => 'nullable|string',
                'existing_company_logo' => 'nullable|string',
                'image_type'     => 'nullable|string',
                'hero_image'     => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'attachments_image' => 'nullable|file|max:5120',
                'company_logo'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ]);

            $validated['type'] = strtoupper($validated['type']);
            $validated['variant'] = !empty($validated['variant']) ? strtoupper($validated['variant']) : null;

            $exists = Sequence::where('user_id', $userId)
                ->where('id', '!=', $sequence->id)
                ->where('step', $validated['step'])
                ->where('gap_days', $validated['gap_days'])
                ->whereRaw('UPPER(type) = ?', [$validated['type']])
                ->where(function ($q) use ($validated) {
                    if (!empty($validated['variant'])) {
                        $q->where('variant', $validated['variant']);
                    } else {
                        $q->whereNull('variant');
                    }
                })
                ->exists();

            if ($exists) {
                return response()->json([
                    'status' => false,
                    'errors' => ['step' => ['Sequence already exists ❌']]
                ], 422);
            }

            $actionLinks = [];
            if ($request->filled('action_links')) {
                foreach ($request->action_links as $link) {
                    if (!empty($link['platform_url'])) {
                        $actionLinks[] = [
                            'id' => $link['id'] ?? null,
                            'platform_name' => $link['platform_name'] ?? 'Link',
                            'platform_url' => trim($link['platform_url']),
                        ];
                    }
                }
            }
            $validated['action_links'] = $actionLinks;

            // Handle files...
            if ($request->hasFile('hero_image')) {
                $request->validate(['hero_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048']);
                $file = $request->file('hero_image');
                $filename = time() . '_hero_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $destination = public_path('hero_image');
                if (!file_exists($destination)) mkdir($destination, 0777, true);
                $file->move($destination, $filename);
                $validated['hero_image'] = 'hero_image/' . $filename;
            } else {
                $validated['hero_image'] = $sequence->hero_image;
            }

            if ($request->hasFile('attachments_image')) {
                $request->validate(['attachments_image' => 'file|max:5120']);
                $file = $request->file('attachments_image');
                $originalName = $file->getClientOriginalName();
                $fileSize = $file->getSize();
                $extension = $file->getClientOriginalExtension();
                $filename = date('Ymd_His') .'_attach_' . uniqid() .'.' . $extension;
                $destination = public_path('attachments_image');
                if (!file_exists($destination)) mkdir($destination, 0777, true);
                $file->move($destination, $filename);
                $validated['attachments_image'] = 'attachments_image/' . $filename;
                $validated['attachment_name'] = $originalName;
                $validated['attachment_size'] = $fileSize;
            } else {
                $validated['attachments_image'] = $sequence->attachments_image;
                $validated['attachment_name'] = $sequence->attachment_name;
                $validated['attachment_size'] = $sequence->attachment_size;
            }

            if ($request->hasFile('company_logo')) {
                $request->validate(['company_logo' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048']);
                $file = $request->file('company_logo');
                $filename = time() . '_logo_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $destination = public_path('uploads/company_logo');
                if (!file_exists($destination)) mkdir($destination, 0777, true);
                $file->move($destination, $filename);
                $validated['existing_company_logo'] = 'uploads/company_logo/' . $filename;
            } else {
                if ($request->input('remove_logo') == 1) {
                    $validated['existing_company_logo'] = null;
                } else {
                    $validated['existing_company_logo'] = $sequence->existing_company_logo;
                }
            }

            // Update sequence
            $sequence->update($validated);

            // ============ SET ADMIN UPDATE TIMESTAMP ============
            $sequence->admin_updated_at = now();
            $sequence->save();

            // Update campaign logs
            CampaignLog::where('sequence_id', $sequence->id)
                ->where('status', 'pending')
                ->update(['status' => 'cancelled']);

            $leads = Lead::where('user_id', $userId)
                ->whereRaw('UPPER(type) = ?', [$sequence->type])
                ->get();

            foreach ($leads as $lead) {
                $alreadyQueued = CampaignLog::where('user_id', $userId)
                    ->where('lead_id', $lead->id)
                    ->where('sequence_id', $sequence->id)
                    ->exists();

                if ($alreadyQueued) continue;

                $baseDelay = now()->addDays((int) $sequence->gap_days);
                if (!isset($delaySeconds)) $delaySeconds = 0;
                $finalDelay = $baseDelay->copy()->addSeconds($delaySeconds);
                $delaySeconds += rand(20, 40);

                CampaignLog::create([
                    'user_id' => $userId,
                    'lead_id' => $lead->id,
                    'sequence_id' => $sequence->id,
                    'status' => 'pending',
                    'scheduled_at' => $finalDelay,
                ]);

                SendCampaignJob::dispatch($lead->id, $sequence->id, $userId)->delay($finalDelay);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Sequence updated & campaign restarted successfully 🚀',
                'sequence_id' => $sequence->id,
                'admin_updated_at' => $sequence->admin_updated_at
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Sequence Update Error', ['message' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function userMasterSequenceDelete(Request $request)
    {
        try {
            $sequence = Sequence::findOrFail($request->id);
            $sequence->delete();
            return response()->json([
                'success' => true,
                'message' => 'Sequence deleted successfully.'
            ]);

        } catch (\Exception $e) {
            Log::error('Sequence Delete Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Unable to delete sequence.'
            ], 500);
        }
    }





}
