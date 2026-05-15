<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\CampaignController;
use Illuminate\Support\Facades\DB;
use App\Models\Lead;
use Illuminate\Support\Facades\Auth;

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
}
