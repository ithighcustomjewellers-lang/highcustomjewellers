<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\CampaignController;
use App\Jobs\StartCampaignJob;
use Illuminate\Support\Facades\DB;
use App\Models\Lead;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;



class LeadsController extends Controller
{
    public function index()
    {
        return view('user.Leads.index');
    }

    public function leadStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'name' => 'required|string',
            'lastname' => 'required|string',
            'company_name' => 'required|string',
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
            // =========================
            // ❌ DUPLICATE EMAIL CHECK
            // AUTH WISE
            // =========================
            $exists = Lead::where('user_id', $userId)
                ->where('email', $request->email)
                ->exists();
            if ($exists) {
                return response()->json([
                    'status' => false,
                    'errors' => [
                        'email' => ['Email already exists ❌']
                    ]
                ], 422);
            }

            // =========================
            // ✅ CREATE LEAD
            // =========================
            $lead = Lead::create([
                'user_id' => $userId,
                'email' => $request->email,
                'name' => $request->name,
                'lastname' => $request->lastname,
                'company_name' => $request->company_name,
                'type' => strtoupper($request->type),
                'created_at' => now(),
            ]);

            // =========================
            // 🚀 START CAMPAIGN
            // =========================
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

    public function leadList(Request $request)
    {
        $userId = Auth::id();
        // =========================
        // ✅ AUTH WISE DATA
        // =========================
        $query = Lead::query()
            ->where('user_id', $userId)
            ->select(
                'id',
                'user_id',
                'email',
                'name',
                'lastname',
                'company_name',
                'type',
                'created_at'
            );
        // =========================
        // ✅ DATE FILTER
        // =========================
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }
        // =========================
        // ✅ TODAY ONLY
        // =========================
        elseif ($request->filled('today_only') || !$request->filled('start_date')) {
            $today = now()->format('Y-m-d');
            $query->whereDate('created_at', $today);
        }
        // =========================
        // ✅ TODAY COUNT AUTH WISE
        // =========================
        $todayCount = Lead::where('user_id', $userId)
            ->whereDate('created_at', now()->format('Y-m-d'))
            ->count();

        // =========================
        // ✅ PAGINATION
        // =========================
        $leads = $query->latest('created_at')->paginate(10);
        return response()->json([
            'status' => true,
            'data' => $leads->items(),
            'today_count' => $todayCount,
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
            // =========================
            // STORE OLD VALUE
            // =========================
            $oldValue = $lead->$field;
            // =========================
            // EMAIL VALIDATION
            // =========================
            if ($field === 'email') {
                $request->validate([
                    'value' => 'required|email|unique:leads,email,' . $id,
                ]);
                $value = strtolower($value);
            }
            // =========================
            // TYPE VALIDATION
            // =========================
            if ($field === 'type') {
                $value = strtoupper($value);
                if (!in_array($value, ['B2B', 'B2C'])) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid type'
                    ], 422);
                }
            }
            // =========================
            // UPDATE LEAD
            // =========================
            $lead->$field = $value;
            $lead->save();
            // =========================
            // IMPORTANT FIELDS
            // =========================
            $restartFields = [
                'email',
                'name',
                'lastname',
                'company_name',
                'type'
            ];
            // =========================
            // RESTART CAMPAIGN
            // =========================
            if (in_array($field, $restartFields) && $oldValue != $value) {
                // Delete pending campaign logs
                DB::table('campaign_logs')
                    ->where('lead_id', $lead->id)
                    ->where('status', 'pending')
                    ->delete();
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

            // =========================
            // READ EXCEL
            // =========================

            $rows = Excel::toArray([], $request->file('excel_file'));

            if (empty($rows) || empty($rows[0])) {

                return redirect()->back()->with(
                    'error',
                    'Excel file is empty ❌'
                );
            }

            $dataRows = $rows[0];

            // Remove heading row
            unset($dataRows[0]);

            $userId = Auth::id();

            $inserted = 0;
            $duplicates = 0;
            $invalid = 0;

            // =========================
            // DELAY START
            // =========================

            $delaySeconds = 0;

            foreach ($dataRows as $row) {

                // =========================
                // SKIP EMPTY ROW
                // =========================

                if (empty($row[0]) && empty($row[1]) && empty($row[2]))
                {
                    continue;
                }

                // =========================
                // NORMALIZE DATA
                // =========================

                $email = strtolower(trim($row[0] ?? ''));
                $name = trim($row[1] ?? '');
                $lastname = trim($row[2] ?? '');
                $company = trim($row[3] ?? '');
                $type = strtoupper(trim($row[4] ?? 'B2B'));

                // =========================
                // VALIDATION
                // =========================

                if (empty($email) || empty($name) ||empty($lastname) ||empty($company)) {
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

                StartCampaignJob::dispatch($lead->id)
                    ->delay(now()->addSeconds($delaySeconds));

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
}
