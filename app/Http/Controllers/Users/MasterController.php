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
        $business = BusinessLink::where('user_id', $userId)->first();
        return view('user.link-document', compact('business'));
    }

    public function submitBusinessLinks(Request $request)
    {
        // VALIDATION
        $validator = Validator::make($request->all(), [
            'whatsapp_link' => 'required|url|max:255',
            'telegram_link' => 'required|url|max:255',
            'business_link' => 'required|url|max:255',
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
        $business->telegram_link = $request->telegram_link;
        $business->business_link = $request->business_link;
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

    public function getBusinessLinks()
    {
        $userId = Auth::id();
        $businessLinks = DB::table('business_links')->where('user_id', $userId)->first();
        if (!$businessLinks) {
            return response()->json([
                'image_type' => 'logo'
            ]);
        }
        if (!empty($businessLinks->company_logo)) {
            $imagePath = public_path($businessLinks->company_logo);
            if (file_exists($imagePath)) {
                list($width, $height) = getimagesize($imagePath);
                $isBanner = ($width > 400) || (($width / $height) > 2);
                $businessLinks->image_type = $isBanner ? 'banner' : 'logo';
            } else {
                $businessLinks->image_type = 'logo';
            }
        } else {
            $businessLinks->image_type = 'logo';
        }
        return response()->json($businessLinks);
    }

    public function sequencesStore(Request $request)
    {
        Log::info('Store sequence request received', [
            'data' => $request->all()
        ]);

        // =========================
        // ✅ VALIDATION
        // =========================
        $request->validate([
            'step' => 'required|integer|min:1',
            'gap_days' => 'required|integer|min:0',
            'variant' => 'nullable|string|regex:/^[A-Z]+$/',
            'type' => 'required|in:B2B,B2C',
            'subject' => 'required|string',
            // ✅ existing uploaded image path
            'existing_company_logo' => 'nullable|string',
            'image_type' => 'nullable|string',
            'logo_position' => 'nullable|string',
            'message' => 'required|string',
            // ✅ new upload
            'hero_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'attachments_image' => 'nullable|file|max:5120',
            'whatsapp_link' => 'nullable|url',
            'telegram_link' => 'nullable|url',
            'business_link' => 'nullable|url',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // =========================
        // ✅ NORMALIZE DATA
        // =========================
        $type = strtoupper($request->type);
        $variant = $request->variant
            ? strtoupper($request->variant)
            : null;


        // ✅ existing image path
        $existingCompanyLogo = $request->existing_company_logo;
        // ✅ If existing_company_logo is null
        // then upload company_logo and store its path
        if (empty($existingCompanyLogo) && $request->hasFile('company_logo')) {
            $file = $request->file('company_logo');
            // image name
            $filename = time() . '_logo_' . uniqid() . '.' . $file->getClientOriginalExtension();
            // uploads/company_logo folder
            $destination = public_path('uploads/company_logo');
            // create folder if not exists
            if (!file_exists($destination)) {
                mkdir($destination, 0777, true);
            }
            // move file
            $file->move($destination, $filename);
            // save path in database
            $existingCompanyLogo = 'uploads/company_logo/' . $filename;
        }

        // =========================
        // ❌ DUPLICATE CHECK
        // =========================
        $exists = Sequence::where('step', $request->step)
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
                    'step' => ['Step already exists ❌']
                ]
            ], 422);
        }

        // =========================
        // 📦 PREPARE DATA
        // =========================
        $data = $request->except([
            'hero_image',
            'attachments_image'
        ]);
        $data['user_id'] = Auth::id();
        $data['type'] = $type;
        $data['variant'] = $variant;
        $data['existing_company_logo'] = $existingCompanyLogo;

        // =========================
        // ✅ HERO IMAGE UPLOAD
        // =========================
        if ($request->hasFile('hero_image')) {
            $file = $request->file('hero_image');
            $filename = time() . '_hero_' . uniqid() . '.' .$file->getClientOriginalExtension();
            $destination = public_path('hero_image');
            if (!file_exists($destination)) {
                mkdir($destination, 0777, true);
            }
            $file->move($destination, $filename);
            $data['hero_image'] = 'hero_image/' . $filename;
        }

        // =========================
        // ✅ ATTACHMENT UPLOAD
        // =========================
        if ($request->hasFile('attachments_image')) {
            $file = $request->file('attachments_image');
            $originalName = $file->getClientOriginalName();
            $fileSize = $file->getSize();
            $filename = date('Ymd_His') . '_attach_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $destination = public_path('attachments_image');
            if (!file_exists($destination)) {
                mkdir($destination, 0777, true);
            }
            $file->move($destination, $filename);
            $data['attachments_image'] = 'attachments_image/' . $filename;
            $data['attachment_name'] = $originalName;
            $data['attachment_size'] = $fileSize;
        }

        // =========================
        // 🚀 MAIN LOGIC
        // =========================
        try {
            // ✅ CREATE SEQUENCE
            $sequence = Sequence::create($data);
            // ✅ GET CONTACTS
            $leads = Lead::whereRaw('UPPER(type) = ?',[$type])->get();
            foreach ($leads as $lead) {
                // ❌ PREVENT DUPLICATE JOB
                $alreadySent = CampaignLog::where('lead_id', $lead->id)
                    ->where('sequence_id',$sequence->id)
                    ->exists();

                if ($alreadySent) {
                    continue;
                }
                // ✅ DELAY
                $delay = now()->addDays((int) $sequence->gap_days);

                // 🚀 DISPATCH JOB
                SendCampaignJob::dispatch($lead,$sequence,Auth::id())->delay($delay);
            }

            return response()->json([
                'status' => true,
                'message' => 'Sequence added & scheduled 🚀'
            ]);
        } catch (\Exception $e) {
            Log::error('Sequence error', [
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
