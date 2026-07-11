<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\CampaignController;
use App\Jobs\StartCampaignJob;
use App\Models\CampaignLog;
use Illuminate\Support\Facades\DB;
use App\Models\Lead;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;



class LeadsController extends Controller
{
    public function index()
    {
        if (Auth::user()->is_admin == 1) {
            return view('admin.Leads.index');
        }

        return view('user.Leads.index');
    }

    public function leadStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'name' => 'required|string',
            'lastname' => 'required|string',
            'type' => 'required|in:B2B,B2C',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        DB::beginTransaction();
        try {
            $userId = Auth::id();
            $exists = Lead::where('user_id', $userId)->where('email', $request->email)->exists();
            if ($exists) {
                return response()->json([
                    'status' => false,
                    'errors' => [
                        'email' => ['Email already exists ❌']
                    ]
                ], 422);
            }

            $lead = Lead::create([
                'user_id' => $userId,
                'email' => $request->email,
                'name' => $request->name,
                'lastname' => $request->lastname,
                'company_name' => $request->company_name,
                'type' => strtoupper($request->type),
                'created_at' => now(),
            ]);
            app(CampaignController::class)->start($lead->id);
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Contact Added & Campaign Started 🚀'
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // public function leadList(Request $request)
    // {
    //     $userId = Auth::id();

    //     // =========================
    //     // ✅ AUTH WISE DATA
    //     // =========================
    //     $query = Lead::query()->where('user_id', $userId)->select('id', 'user_id', 'email', 'name', 'lastname', 'company_name', 'type', 'is_unsubscribed', 'created_at');

    //     // =========================
    //     // ✅ DATE FILTER
    //     // =========================
    //     if ($request->filled('start_date') && $request->filled('end_date')) {
    //         $query->whereBetween('created_at', [
    //             $request->start_date . ' 00:00:00',
    //             $request->end_date . ' 23:59:59'
    //         ]);
    //     } elseif ($request->filled('today_only') || !$request->filled('start_date')) {
    //         $today = now()->format('Y-m-d');
    //         $query->whereDate('created_at', $today);
    //     }

    //     $todayCount = Lead::where('user_id', $userId)->whereDate('created_at', now()->format('Y-m-d'))->count();
    //     $leads = $query->latest('created_at')->paginate(10);
    //     $leadIds = collect($leads->items())->pluck('id')->toArray();

    //     $campaignLogs = CampaignLog::whereIn('lead_id', $leadIds)->orderBy('id', 'asc')->get()->groupBy('lead_id');

    //     $formattedData = collect($leads->items())->map(function ($lead) use ($campaignLogs) {
    //         $tracking = 'pending';
    //         // latest log
    //         $firstLog = null;
    //         if (isset($campaignLogs[$lead->id])) {
    //             //  $firstLog = $campaignLogs[$lead->id]->first();
    //             $firstLog = $campaignLogs[$lead->id]->first() ?? null;
    //         }
    //         if ($lead->is_unsubscribed) {
    //             $tracking = 'Not Interested';
    //         } elseif ($firstLog) {
    //             switch ($firstLog->status) {
    //                 case 'pending':
    //                     $tracking = ' <span class="badge bg-warning">
    //                      Pending
    //                     </span>';
    //                     break;
    //                 case 'send':
    //                     $tracking = ' <span class="badge bg-success">
    //                         Sent
    //                     </span>';
    //                     break;
    //                 case 'seen':
    //                     $tracking = ' <span class="badge bg-info">
    //                         Seen
    //                     </span>';
    //                     break;
    //                 case 'failed':
    //                     $tracking = ' <span class="badge bg-secondary">
    //                         Failed
    //                     </span>';
    //                     break;
    //                 case 'interested':
    //                     $tracking = ' <span class="badge bg-primary">
    //                         Interested
    //                     </span>';
    //                     break;
    //                 case 'Not Interested':
    //                     $tracking = ' <span class="badge bg-danger">
    //                         Not Interested
    //                     </span>';
    //                     break;
    //                 default:
    //                     $tracking = ucfirst($firstLog->status);
    //                     break;
    //             }
    //         }
    //         return [
    //             'id' => $lead->id,
    //             'user_id' => $lead->user_id,
    //             'email' => $lead->email,
    //             'name' => $lead->name,
    //             'lastname' => $lead->lastname,
    //             'company_name' => $lead->company_name,
    //             'type' => $lead->type,
    //             'tracking' => $tracking,
    //             'created_at' => $lead->created_at,
    //         ];
    //     });
    //     return response()->json([
    //         'status' => true,
    //         'data' => $formattedData->values(),
    //         'today_count' => $todayCount,
    //         'pagination' => [
    //             'current_page' => $leads->currentPage(),
    //             'last_page' => $leads->lastPage(),
    //             'per_page' => $leads->perPage(),
    //             'total' => $leads->total(),
    //             'from' => $leads->firstItem(),
    //             'to' => $leads->lastItem(),
    //             'has_more_pages' => $leads->hasMorePages(),
    //             'prev_page_url' => $leads->previousPageUrl(),
    //             'next_page_url' => $leads->nextPageUrl(),
    //         ]
    //     ]);
    // }

    public function leadList(Request $request)
    {
        $userId = Auth::id();

        // Get filter parameters
        $filter = $request->input('filter', 'today');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // dd($filter);

        // Base query
        $query = Lead::query()
            ->where('user_id', $userId)
            ->select('id', 'user_id', 'email', 'name', 'lastname', 'company_name', 'type', 'is_unsubscribed', 'created_at');

        // =========================
        // ✅ DATE FILTER (Carbon)
        // =========================
        switch ($filter) {
            case 'today':
                $query->whereDate('created_at', Carbon::today());
                break;
            case 'weekly':
                $query->whereBetween('created_at', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek()
                ]);
                break;
            case 'monthly':
                $query->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year);
                break;
            case 'yearly':
                $query->whereYear('created_at', Carbon::now()->year);
                break;
            case 'custom':
                if ($startDate && $endDate) {
                    $start = Carbon::parse($startDate)->startOfDay();
                    $end = Carbon::parse($endDate)->endOfDay();
                    $query->whereBetween('created_at', [$start, $end]);
                } else {
                    // fallback to today if invalid custom
                    $query->whereDate('created_at', Carbon::today());
                }
                break;
            default:
                $query->whereDate('created_at', Carbon::today());
        }

        // Clone for total count (filtered)
        $countQuery = clone $query;
        $totalCount = $countQuery->count();

        // Paginate
        $leads = $query->latest('created_at')->paginate(10);

        // Collect lead IDs for campaign logs
        $leadIds = collect($leads->items())->pluck('id')->toArray();

        // Get campaign logs (grouped by lead_id)
        $campaignLogs = CampaignLog::whereIn('lead_id', $leadIds)
            ->orderBy('id', 'asc')
            ->get()
            ->groupBy('lead_id');

        // Format data with tracking status
        $formattedData = collect($leads->items())->map(function ($lead) use ($campaignLogs) {
            $tracking = 'pending';
            $firstLog = null;
            if (isset($campaignLogs[$lead->id])) {
                $firstLog = $campaignLogs[$lead->id]->first();
            }


            if ($lead->is_unsubscribed) {
                // $tracking = 'Not Interested';
                $tracking = ' <span class="badge bg-danger">Not Interested</span>';
            } elseif ($firstLog) {
                switch ($firstLog->status) {
                    case 'pending':
                        $tracking = ' <span class="badge bg-warning">Pending</span>';
                        break;
                    case 'send':
                        $tracking = ' <span class="badge bg-success">Sent</span>';
                        break;
                    case 'seen':
                        $tracking = ' <span class="badge bg-info">Seen</span>';
                        break;
                    case 'failed':
                        $tracking = ' <span class="badge bg-secondary">Failed</span>';
                        break;
                    case 'interested':
                        $tracking = ' <span class="badge bg-primary">Interested</span>';
                        break;
                    case 'Not Interested':
                        $tracking = ' <span class="badge bg-secondary">Not Interested</span>';
                        break;
                    default:
                        $tracking = ucfirst($firstLog->status);
                        break;
                }
            }

            return [
                'id' => $lead->id,
                'user_id' => $lead->user_id,
                'email' => $lead->email,
                'name' => $lead->name,
                'lastname' => $lead->lastname,
                'company_name' => $lead->company_name,
                'type' => $lead->type,
                'tracking' => $tracking,
                'created_at' => $lead->created_at,
            ];
        });

        // Return JSON with the same structure
        return response()->json([
            'status' => true,
            'data' => $formattedData->values(),
            'today_count' => $totalCount,  // count for the selected period
            'pagination' => [
                'current_page' => $leads->currentPage(),
                'last_page' => $leads->lastPage(),
                'per_page' => $leads->perPage(),
                'total' => $leads->total(),
                'from' => $leads->firstItem(),
                'to' => $leads->lastItem(),
                'has_more_pages' => $leads->hasMorePages(),
                'prev_page_url' => $leads->previousPageUrl(),
                'next_page_url' => $leads->nextPageUrl(),
            ]
        ]);
    }

    public function leadsUpdate(Request $request, $id)
    {
        $request->validate([
            'field' => 'required|in:email,name,lastname,company_name,type',
            'value' => 'required|string|max:255',
        ]);
        DB::beginTransaction();
        try {
            $lead = Lead::findOrFail($id);
            $field = $request->field;
            $value = trim($request->value);
            $oldValue = $lead->$field;
            if ($field === 'email') {
                $request->validate([
                    'value' => 'required|email|unique:leads,email,' . $id,
                ]);
                $value = strtolower($value);
            }
            if ($field === 'type') {
                $value = strtoupper($value);
                if (!in_array($value, ['B2B', 'B2C'])) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid type'
                    ], 422);
                }
            }
            $lead->$field = $value;
            $lead->save();
            $restartFields = [
                'email',
                'name',
                'lastname',
                'company_name',
                'type'
            ];
            if (in_array($field, $restartFields) && $oldValue != $value) {
                // Delete pending campaign logs
                DB::table('campaign_logs')
                    ->where('lead_id', $lead->id)
                    ->update([
                        'status' => 'pending'
                    ]);
                // ->where('status', 'Pending')
                // ->delete();
                // Restart campaign
                app(CampaignController::class)->start($lead->id);
            }
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => ucfirst($field) . 'updated successfully'
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function bulkLeadsUpload(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            $rows = Excel::toArray([], $request->file('excel_file'));
            if (empty($rows) || empty($rows[0])) {
                return redirect()->back()->with(
                    'error',
                    'Excel file is empty ❌'
                );
            }

            $dataRows = $rows[0];
            unset($dataRows[0]);
            $userId = Auth::id();
            $inserted = 0;
            $duplicates = 0;
            $invalid = 0;
            $delaySeconds = 0;

            foreach ($dataRows as $row) {
                if (empty($row[0]) && empty($row[1]) && empty($row[2])) {
                    continue;
                }
                $email = strtolower(trim($row[0] ?? ''));
                $name = trim($row[1] ?? '');
                $lastname = trim($row[2] ?? '');
                $company = trim($row[3] ?? '');
                $type = strtoupper(trim($row[4] ?? 'B2B'));

                if (empty($email) || empty($name) || empty($lastname) || empty($company)) {
                    $invalid++;
                    continue;
                }
                // Validate email
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $invalid++;
                    continue;
                }

                // Validate type
                if (!in_array($type, ['B2B', 'B2C'])) {
                    $invalid++;
                    continue;
                }
                // =========================
                // DUPLICATE CHECK
                // =========================

                $exists = Lead::where('user_id', $userId)
                    ->where('email', $email)
                    ->exists();
                if ($exists) {
                    $duplicates++;
                    continue;
                }
                // =========================
                // CREATE LEAD
                // =========================

                $lead = Lead::create([
                    'user_id' => $userId,
                    'email' => $email,
                    'name' => $name,
                    'lastname' => $lastname,
                    'company_name' => $company,
                    'type' => $type,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // =========================
                // START CAMPAIGN WITH DELAY
                // =========================
                StartCampaignJob::dispatch($lead->id)->delay(now()->addSeconds($delaySeconds));
                // Random delay
                $delaySeconds += rand(20, 40);
                $inserted++;
            }

            return redirect()->back()->with(
                'success',
                "Upload completed 🚀
                Inserted: {$inserted},
                Duplicates Skipped: {$duplicates},
                Invalid Rows: {$invalid}"
            );
        } catch (\Throwable $e) {

            return redirect()->back()->with(
                'error',
                $e->getMessage()
            );
        }
    }

    public function downloadDemo()
    {
        $path = public_path('excel/bulk-lead.xlsx');
        while (ob_get_level()) ob_end_clean();
        return response()->download($path, 'bulk-lead.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function destroy($id)
    {
        try {
            $lead = Lead::findOrFail($id);
            if ($lead->user_id != Auth::id()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }
            $lead->delete();
            return response()->json([
                'status' => true,
                'message' => 'Lead deleted successfully'
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
