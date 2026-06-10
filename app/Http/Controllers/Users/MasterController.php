<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BusinessLink;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Sequence;
use App\Models\CampaignLog;
use App\Jobs\SendCampaignJob;
use App\Models\Lead;
use App\Models\SocialLink;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;


class MasterController extends Controller
{
    public function masterViewPage()
    {
        return  view('user.master-mail');
    }

    public function masterLinkDocument()
    {
        $userId = Auth::id();
        $sociallinks = SocialLink::where('user_id', $userId)
            ->where('platform_name', '!=', 'WhatsApp')
            ->select('id', 'platform_name', 'platform_url')
            ->get();

        $business = BusinessLink::where('user_id', $userId)->first();
        return view('user.link-document', compact('business', 'sociallinks'));
    }


    public function submitBusinessLinks(Request $request)
    {
        // VALIDATION
        $validator = Validator::make($request->all(), [
            'whatsapp_link' => 'required|url|max:1000',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);
        // VALIDATION ERROR
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        // LOGIN USER ID
        $userId = Auth::id();
        // CHECK USER RECORD
        $business = BusinessLink::where('user_id', $userId)->first();
        // CREATE NEW RECORD IF NOT EXISTS
        if (!$business) {
            $business = new BusinessLink();
            $business->user_id = $userId;
        }
        // UPDATE DATA
        $business->whatsapp_link = $request->whatsapp_link;

        $selectedLinks = [];

        if ($request->filled('social_link_ids')) {
            $selectedLinks = SocialLink::where('user_id', $userId)
                ->whereIn('id', $request->social_link_ids)
                ->select('id', 'platform_name', 'platform_url')
                ->get()
                ->toArray();
        }
        $business->action_links = $selectedLinks;

        // IMAGE UPLOAD
        if ($request->hasFile('company_logo')) {
            $companyPath = public_path('uploads/company_logo');
            if (!file_exists($companyPath)) {
                mkdir($companyPath, 0777, true);
            }
            if ($business->company_logo && file_exists(public_path($business->company_logo))) {
                unlink(public_path($business->company_logo));
            }
            $file = $request->file('company_logo');
            $filename = time() . '_logo.' .  $file->getClientOriginalExtension();

            $file->move($companyPath, $filename);
            $business->company_logo = 'uploads/company_logo/' . $filename;
        }
        $business->save();
        return response()->json([
            'success' => true,
            'message' => 'Business information saved successfully!',
            'data' => $business,
        ], 200);
    }

    // public function getBusinessLinks()
    // {
    //     $userId = Auth::id();
    //     $businessLinks = DB::table('business_links')->where('user_id', $userId)->first();
    //     if (!$businessLinks) {
    //         return response()->json([
    //             'image_type' => 'logo'
    //         ]);
    //     }
    //     if (!empty($businessLinks->company_logo)) {
    //         $imagePath = public_path($businessLinks->company_logo);
    //         if (file_exists($imagePath)) {
    //             list($width, $height) = getimagesize($imagePath);
    //             $isBanner = ($width > 400) || (($width / $height) > 2);
    //             $businessLinks->image_type = $isBanner ? 'banner' : 'logo';
    //         } else {
    //             $businessLinks->image_type = 'logo';
    //         }
    //     } else {
    //         $businessLinks->image_type = 'logo';
    //     }
    //     return response()->json($businessLinks);
    // }



    public function getBusinessLinks()
    {
        $userId = Auth::id();
        $businessLinks = DB::table('business_links')
            ->where('user_id', $userId)
            ->first();

        if (!$businessLinks) {
            return response()->json([
                'image_type' => 'logo',
                'action_links' => []
            ]);
        }

        if (!empty($businessLinks->company_logo)) {
            $imagePath = public_path($businessLinks->company_logo);
            if (file_exists($imagePath)) {
                list($width, $height) = getimagesize($imagePath);
                $businessLinks->image_type =
                    ($width > 400 || ($width / $height) > 2)
                    ? 'banner'
                    : 'logo';
            } else {
                $businessLinks->image_type = 'logo';
            }
        } else {
            $businessLinks->image_type = 'logo';
        }
        $businessLinks->action_links = json_decode($businessLinks->action_links, true) ?? [];
        return response()->json($businessLinks);
    }

    public function sequencesStore(Request $request)
    {
        // dd($request);
        // die();
        Log::info('Store sequence request received', [
            'data' => $request->all()
        ]);
        $userId = Auth::id();
        // =========================
        // ✅ VALIDATION
        // =========================
        $request->validate([
            'step' => 'required|integer|min:1',
            'gap_days' => 'required|integer|min:0',
            'variant' => 'nullable|string|regex:/^[A-Z]+$/',
            'type' => 'required|in:B2B,B2C',
            'subject' => 'required|string',
            'existing_company_logo' => 'nullable|string',
            'image_type' => 'nullable|string',
            'logo_position' => 'nullable|string',
            'message' => 'required|string',
            'hero_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'attachments_image' => 'nullable|file|max:5120',
            'whatsapp_link' => 'nullable|url',
            'action_links' => 'nullable|array',
            'action_links.*.platform_name' => 'required|string',
            'action_links.*.platform_url' => 'required|string',
            'action_links.*.id' => 'nullable',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        try {
            // =========================
            // ✅ NORMALIZE
            // =========================
            $type = strtoupper($request->type);
            $variant = $request->variant
                ? strtoupper($request->variant)
                : null;
            // =========================
            // ❌ DUPLICATE CHECK
            // =========================
            $exists = Sequence::where('user_id', $userId)
                ->where('step', $request->step)
                ->where('gap_days', $request->gap_days)
                ->whereRaw('UPPER(type) = ?', [$type])
                ->where(function ($q) use ($variant) {
                    if ($variant) {
                        $q->where('variant', $variant);
                    } else {
                        $q->whereNull('variant');
                    }
                })
                ->exists();
            if ($exists) {
                return response()->json([
                    'errors' => [
                        'step' => ['Sequence already exists ❌']
                    ]
                ], 422);
            }

            if ($request->filled('action_links')) {
                $data['action_links'] = json_encode($request->action_links);
            } else {
                $data['action_links'] = json_encode([]);
            }

            // =========================
            // ✅ COMPANY LOGO
            // =========================
            $existingCompanyLogo = $request->existing_company_logo;

            if (empty($existingCompanyLogo) && $request->hasFile('company_logo')) {
                $file = $request->file('company_logo');
                $filename = time() . '_logo_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $destination = public_path('uploads/company_logo');
                if (!file_exists($destination)) {
                    mkdir($destination, 0755, true);
                }
                $file->move($destination, $filename);
                $existingCompanyLogo = 'uploads/company_logo/' . $filename;
            }

            // =========================
            // ✅ PREPARE DATA
            // =========================
            $data = $request->except([
                'hero_image',
                'attachments_image',
                'existing_company_logo'
            ]);

            $data['user_id'] = $userId;
            $data['type'] = $type;
            $data['variant'] = $variant;
            $data['existing_company_logo'] = $existingCompanyLogo;

            // =========================
            // ✅ HERO IMAGE
            // =========================
            if ($request->hasFile('hero_image')) {
                $file = $request->file('hero_image');
                $filename = time() . '_hero_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $destination = public_path('hero_image');
                if (!file_exists($destination)) {
                    mkdir($destination, 0755, true);
                }
                $file->move($destination, $filename);
                $data['hero_image'] = 'hero_image/' . $filename;
            }

            // =========================
            // ✅ ATTACHMENT
            // =========================
            if ($request->hasFile('attachments_image')) {
                $file = $request->file('attachments_image');
                $originalName = $file->getClientOriginalName();
                $fileSize = $file->getSize();
                $filename = date('Ymd_His') . '_attach_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $destination = public_path('attachments_image');
                if (!file_exists($destination)) {
                    mkdir($destination, 0755, true);
                }
                $file->move($destination, $filename);
                $data['attachments_image'] = 'attachments_image/' . $filename;
                $data['attachment_name'] = $originalName;
                $data['attachment_size'] = $fileSize;
            }

            // =========================
            // ✅ CREATE SEQUENCE
            // =========================
            $sequence = Sequence::create($data);

            // =========================
            // ✅ GET USER LEADS
            // =========================
            $leads = Lead::where('user_id', $userId)
                ->whereRaw('UPPER(type) = ?', [$type])
                ->get();

            foreach ($leads as $lead) {
                // =========================
                // ❌ PREVENT DUPLICATE
                // =========================
                $alreadyQueued = CampaignLog::where('user_id', $userId)
                    ->where('lead_id', $lead->id)
                    ->where('sequence_id', $sequence->id)
                    ->exists();

                if ($alreadyQueued) {
                    continue;
                }

                // =========================
                // ✅ BASE DELAY (DAYS)
                // =========================
                $baseDelay = now()->addDays((int) $sequence->gap_days);

                // =========================
                // ✅ RANDOM STAGGER DELAY
                // =========================
                if (!isset($delaySeconds)) {
                    $delaySeconds = 0;
                }

                // Add stagger delay
                $finalDelay = $baseDelay->copy()->addSeconds($delaySeconds);

                // Next lead random delay
                $delaySeconds += rand(20, 40);

                // =========================
                // ✅ CREATE LOG
                // =========================
                CampaignLog::create([
                    'user_id' => $userId,
                    'lead_id' => $lead->id,
                    'sequence_id' => $sequence->id,
                    'status' => 'pending',
                    'scheduled_at' => $finalDelay,
                ]);

                // =========================
                // ✅ DISPATCH JOB
                // =========================
                SendCampaignJob::dispatch(
                    $lead->id,
                    $sequence->id,
                    $userId
                )->delay($finalDelay);
            }

            return response()->json([
                'status' => true,
                'message' => 'Sequence added & scheduled successfully 🚀'
            ]);
        } catch (\Throwable $e) {

            Log::error('Sequence Store Error', [
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function masterDataList()
    {
        return view('user.master-list');
    }

    public function inlineUpdate(Request $request)
    {
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

    public function getSequencesList(Request $request)
    {
        $columns = [
            0 => 'id',
            1 => 'step',
            2 => 'gap_days',
            3 => 'variant',
            4 => 'message',
            5 => 'subject',
            6 => 'type',
            7 => 'whatsapp_link',
            8 => 'created_at',
            9 => 'updated_at',
        ];

        // ✅ Base query with user_id filter
        $query = Sequence::where('user_id', Auth::id());

        // Total records (user wise)
        $totalData = $query->count();

        // Search filter (same but on filtered query)
        if ($request->has('search') && $request->search['value'] != '') {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('step', 'LIKE', "%$search%")
                    ->orWhere('gap_days', 'LIKE', "%$search%")
                    ->orWhere('variant', 'LIKE', "%$search%")
                    ->orWhere('subject', 'LIKE', "%$search%")
                    ->orWhere('type', 'LIKE', "%$search%");
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
                'edit' => '<a href="' . route('sequences-list-edit', $seq->id) . '" class="btn btn-sm btn-primary">Edit</a>',
                'id' => $seq->id,
                'step' => $seq->step,
                'gap_days' => $seq->gap_days ?? '-',
                'variant' => $seq->variant ?? '-',
                'message' => Str::limit($seq->message, 50),
                'subject' => $seq->subject,
                'type' => $seq->type,
                'whatsapp_link' => $seq->whatsapp_link ? '<a href="' . $seq->whatsapp_link . '" target="_blank">Link</a>' : '-',
                'created_at' => date('Y-m-d', strtotime($seq->created_at)),
                'updated_at' => date('Y-m-d', strtotime($seq->updated_at)),
                'delete' => '
                <button type="button"
                    onclick="deleteList('.$seq->id.')"
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

    public function sequencesListEdit($id)
    {
        $sequence = Sequence::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('user.sequences-edit', compact('sequence'));
    }

    public function sequencesListUpdate(Request $request, $id)
    {

        $userId = Auth::id();
        DB::beginTransaction();
        try {
            $sequence = Sequence::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();
            // =========================
            // VALIDATION
            // =========================
            $validated = $request->validate([
                'step'           => 'required|integer|min:1',
                'gap_days'       => 'required|integer|min:0',
                'variant'        => 'nullable|string|max:10|regex:/^[A-Z0-9]+$/i',
                'type'           => 'required|in:B2B,B2C',
                'subject'        => 'required|string|max:255',
                'message'        => 'required|string',
                'whatsapp_link'  => 'nullable|url',
                'telegram_link'  => 'nullable|url',
                'business_link'  => 'nullable|url',
                'logo_position'  => 'nullable|string',
                'existing_company_logo' => 'nullable|string',
                'image_type'     => 'nullable|string',
                'hero_image'     => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'attachments_image' => 'nullable|file|max:5120',
                'company_logo'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ]);
            // =========================
            // NORMALIZE
            // =========================
            $validated['type'] = strtoupper($validated['type']);
            $validated['variant'] = !empty($validated['variant']) ? strtoupper($validated['variant']) : null;
            // =========================
            // DUPLICATE CHECK
            // =========================
            $exists = Sequence::where('user_id', Auth::id())
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
                    'errors' => [
                        'step' => ['Sequence already exists ❌']
                    ]
                ], 422);
            }

            if ($request->hasFile('hero_image')) {
                $request->validate(['hero_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048']);
                $file = $request->file('hero_image');
                $filename = time() . '_hero_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $destination = public_path('hero_image');
                if (!file_exists($destination)) mkdir($destination, 0777, true);
                $file->move($destination, $filename);
                $validated['hero_image'] = 'hero_image/' . $filename;
            } else {
                // Keep existing if no new file uploaded
                $validated['hero_image'] = $sequence->hero_image;
            }

            // Handle attachment upload (replace)
            // if ($request->hasFile('attachments_image')) {
            //     $request->validate(['attachments_image' => 'file|max:5120']);
            //     $file = $request->file('attachments_image');
            //     $filename = date('Ymd_His') . '_attach_' . uniqid() . '.' . $file->getClientOriginalExtension();
            //     $destination = public_path('attachments_image');
            //     if (!file_exists($destination)) mkdir($destination, 0777, true);
            //     $file->move($destination, $filename);
            //     $validated['attachments_image'] = 'attachments_image/' . $filename;
            //     $validated['attachment_name'] = $file->getClientOriginalName();
            //     $validated['attachment_size'] = $file->getSize();
            // } else {
            //     $validated['attachments_image'] = $sequence->attachments_image;
            //     $validated['attachment_name'] = $sequence->attachment_name;
            //     $validated['attachment_size'] = $sequence->attachment_size;
            // }

            // Handle attachment upload (replace)
            if ($request->hasFile('attachments_image')) {
                $request->validate([ 'attachments_image' => 'file|max:5120']);
                $file = $request->file('attachments_image');
                // =========================
                // ✅ FILE INFO FIRST
                // =========================
                $originalName = $file->getClientOriginalName();
                $fileSize = $file->getSize();
                $extension = $file->getClientOriginalExtension();
                // =========================
                // ✅ FILE NAME
                // =========================
                $filename = date('Ymd_His') .'_attach_' . uniqid() .'.' . $extension;
                // =========================
                // ✅ DESTINATION
                // =========================
                $destination = public_path('attachments_image');
                if (!file_exists($destination)) {
                    mkdir($destination, 0777, true);
                }
                // =========================
                // ✅ MOVE FILE
                // =========================
                $file->move($destination, $filename);
                // =========================
                // ✅ SAVE DATA
                // =========================
                $validated['attachments_image'] = 'attachments_image/' . $filename;
                $validated['attachment_name'] = $originalName;
                $validated['attachment_size'] = $fileSize;
            } else {
                $validated['attachments_image'] = $sequence->attachments_image;
                $validated['attachment_name'] = $sequence->attachment_name;
                $validated['attachment_size'] = $sequence->attachment_size;
            }

            // Handle company logo upload (replace)
            if ($request->hasFile('company_logo')) {
                $request->validate(['company_logo' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048']);
                $file = $request->file('company_logo');
                $filename = time() . '_logo_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $destination = public_path('uploads/company_logo');
                if (!file_exists($destination)) mkdir($destination, 0777, true);
                $file->move($destination, $filename);
                $validated['existing_company_logo'] = 'uploads/company_logo/' . $filename;
            } else {
                // Keep existing (if not removed)
                if ($request->input('remove_logo') == 1) {
                    $validated['existing_company_logo'] = null;
                } else {
                    $validated['existing_company_logo'] = $sequence->existing_company_logo;
                }
            }

            // =========================
            // UPDATE SEQUENCE
            // =========================
            $sequence->update($validated);
            // =========================
            // DELETE OLD PENDING LOGS
            // =========================
            // CampaignLog::where('sequence_id', $sequence->id)
            //     ->where('status', 'pending')
            //     ->delete();

            CampaignLog::where('sequence_id', $sequence->id)
                ->where('status', 'pending')
                ->update([
                    'status' => 'cancelled'
                ]);
            // =========================
            // GET MATCHING LEADS
            // =========================
            $leads = Lead::where('user_id', Auth::id())
                ->whereRaw('UPPER(type) = ?', [$sequence->type])
                ->get();


            // =========================
            // RECREATE JOBS
            // =========================
            // foreach ($leads as $lead) {
            //     // Prevent duplicate
            //     $alreadyQueued = CampaignLog::where('lead_id', $lead->id)
            //         ->where('sequence_id', $sequence->id)
            //         ->where('status', 'pending')
            //         ->exists();
            //     if ($alreadyQueued) {
            //         continue;
            //     }
            //     // Delay
            //     $delay = now()->addDays((int) $sequence->gap_days);
            //     // Create fresh log
            //     CampaignLog::create([
            //         'user_id' => Auth::id(),
            //         'lead_id' => $lead->id,
            //         'sequence_id' => $sequence->id,
            //         'status' => 'pending',
            //         'scheduled_at' => $delay,
            //     ]);
            //     // Dispatch fresh job
            //     SendCampaignJob::dispatch(
            //         $lead->id,
            //         $sequence->id,
            //         Auth::id()
            //     )->delay($delay);
            // }

            foreach ($leads as $lead) {
                // =========================
                // ❌ PREVENT DUPLICATE
                // =========================
                $alreadyQueued = CampaignLog::where('user_id', $userId)
                    ->where('lead_id', $lead->id)
                    ->where('sequence_id', $sequence->id)
                    ->exists();

                if ($alreadyQueued) {
                    continue;
                }

                // =========================
                // ✅ BASE DELAY (DAYS)
                // =========================
                $baseDelay = now()->addDays((int) $sequence->gap_days);

                // =========================
                // ✅ RANDOM STAGGER DELAY
                // =========================
                if (!isset($delaySeconds)) {
                    $delaySeconds = 0;
                }

                // Add stagger delay
                $finalDelay = $baseDelay->copy()
                    ->addSeconds($delaySeconds);

                // Next lead random delay
                $delaySeconds += rand(20, 40);

                // =========================
                // ✅ CREATE LOG
                // =========================
                CampaignLog::create([
                    'user_id' => $userId,
                    'lead_id' => $lead->id,
                    'sequence_id' => $sequence->id,
                    'status' => 'pending',
                    'scheduled_at' => $finalDelay,
                ]);

                // =========================
                // ✅ DISPATCH JOB
                // =========================
                SendCampaignJob::dispatch(
                    $lead->id,
                    $sequence->id,
                    $userId
                )->delay($finalDelay);
            }
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Sequence updated & campaign restarted successfully 🚀'
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Sequence Update Error', [
                'message' => $e->getMessage()
            ]);
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }


}
