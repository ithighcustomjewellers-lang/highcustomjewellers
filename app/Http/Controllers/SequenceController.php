<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sequence;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\CampaignLog;
use App\Jobs\SendCampaignJob;
use App\Models\Contact;

class SequenceController extends Controller
{
    // 📄 Show list
    public function sequencesIndex()
    {
        $sequences = Sequence::orderBy('step')->get();
        return view('admin.sequences.index', compact('sequences'));
    }

    // Show form
    public function sequencesCreate()
    {
        return view('admin.sequences.create');
    }

    // public function sequencesStore(Request $request)
    // {
    //     // 🔍 Debug log
    //     Log::info('Store sequence request received', [
    //         'data' => $request->all()
    //     ]);

    //     // ✅ VALIDATION
    //     $request->validate([
    //         'step' => 'required|integer',
    //         'subject' => 'required|string',
    //         'message' => 'required|string',
    //         'gap_days' => 'required|integer',
    //         'variant' => 'nullable|string',
    //         'type' => 'required|in:B2B,B2C',
    //         'hero_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    //         'attachments_image' => 'nullable|file|max:5120',
    //         'whatsapp_link' => 'nullable|url',
    //         'telegram_link' => 'nullable|url',
    //         'business_link' => 'nullable|url'
    //     ]);

    //     $data = $request->except(['hero_image', 'attachments_image']);

    //     // =========================
    //     // ✅ HERO IMAGE UPLOAD
    //     // =========================
    //     if ($request->hasFile('hero_image')) {

    //         $file = $request->file('hero_image');
    //         $filename = time() . '_hero_' . uniqid() . '.' . $file->getClientOriginalExtension();

    //         $destination = public_path('hero_image');

    //         if (!file_exists($destination)) {
    //             mkdir($destination, 0777, true);
    //         }

    //         $file->move($destination, $filename);

    //         $data['hero_image'] = 'hero_image/' . $filename;
    //     }

    //     // =========================
    //     // ✅ ATTACHMENT UPLOAD
    //     // =========================
    //     if ($request->hasFile('attachments_image')) {

    //         $file = $request->file('attachments_image');

    //         $originalName = $file->getClientOriginalName();
    //         $fileSize = $file->getSize();

    //         $filename = date('Ymd_His') . '_attach_' . uniqid() . '.' . $file->getClientOriginalExtension();

    //         $destination = public_path('attachments_image');

    //         if (!file_exists($destination)) {
    //             mkdir($destination, 0777, true);
    //         }

    //         $file->move($destination, $filename);

    //         $data['attachments_image'] = 'attachments_image/' . $filename;
    //         $data['attachment_name'] = $originalName;
    //         $data['attachment_size'] = $fileSize;
    //     }

    //     // =========================
    //     // ✅ MAIN LOGIC
    //     // =========================
    //     try {

    //         // 🔥 STEP SAVE
    //         $sequence = Sequence::create($data);

    //         // 🔥 all contacts of same type
    //         $contacts = Contact::whereRaw('UPPER(type) = ?', [strtoupper($sequence->type)])
    //             ->get();

    //         foreach ($contacts as $contact) {

    //             // ❌ duplicate check
    //             $alreadySent = CampaignLog::where('contact_id', $contact->id)
    //                 ->where('sequence_id', $sequence->id)
    //                 ->exists();

    //             if ($alreadySent) continue;

    //             $delay = now()->addDays($sequence->gap_days);

    //             SendCampaignJob::dispatch($contact, $sequence)
    //                 ->delay($delay);
    //         }

    //         return response()->json([
    //             'status' => true,
    //             'message' => 'Sequence added & scheduled 🚀'
    //         ]);
    //     } catch (\Exception $e) {

    //         Log::error('Sequence error', [
    //             'error' => $e->getMessage()
    //         ]);

    //         return response()->json([
    //             'status' => false,
    //             'message' => $e->getMessage()
    //         ], 500);
    //     }
    // }

    public function sequencesStore(Request $request)
    {
        Log::info('Store sequence request received', [
            'data' => $request->all()
        ]);

        // =========================
        // ✅ VALIDATION
        // =========================
        $request->validate([
            'step' => 'required|integer',
            'subject' => 'required|string',
            'message' => 'required|string',
            'gap_days' => 'required|integer',
            'variant' => 'nullable|string|regex:/^[A-Za-z]+$/',
            'type' => 'required|in:B2B,B2C',
            'hero_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'attachments_image' => 'nullable|file|max:5120',
            'whatsapp_link' => 'nullable|url',
            'telegram_link' => 'nullable|url',
            'business_link' => 'nullable|url'
        ]);

        // normalize type (important)
        $type = strtoupper($request->type);

        // =========================
        // ❌ DUPLICATE CHECK
        // =========================
        $exists = Sequence::where('step', $request->step)
            ->where('gap_days', $request->gap_days)
            ->whereRaw('UPPER(type) = ?', [$type])
            ->where(function ($q) use ($request) {
                if ($request->variant) {
                    $q->where('variant', $request->variant);
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
        $data = $request->except(['hero_image', 'attachments_image']);
        $data['type'] = $type;

        // =========================
        // ✅ HERO IMAGE UPLOAD
        // =========================
        if ($request->hasFile('hero_image')) {

            $file = $request->file('hero_image');
            $filename = time() . '_hero_' . uniqid() . '.' . $file->getClientOriginalExtension();

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

            // ✅ GET CONTACTS (TYPE BASED)
            $contacts = Contact::whereRaw('UPPER(type) = ?', [$type])->get();

            foreach ($contacts as $contact) {

                // ❌ prevent duplicate job
                $alreadySent = CampaignLog::where('contact_id', $contact->id)
                    ->where('sequence_id', $sequence->id)
                    ->exists();

                if ($alreadySent) continue;

                // ✅ DIRECT GAP (NO SUM)
                $delay = now()->addDays((int) $sequence->gap_days);

                // 🚀 DISPATCH JOB
                // SendCampaignJob::dispatch($contact, $sequence)
                //     ->delay($delay);

                SendCampaignJob::dispatch($contact, $sequence, auth()->id())
                ->delay($delay);
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

    public function AdminGetSequences(Request $request)
    {
        $columns_list = [
            0 => 'id',
            1 => 'step',
            2 => 'subject',
            3 => 'type',
            4 => 'gap_days',
            5 => 'variant',
            6 => 'created_at',
            7 => 'updated_at'
        ];

        $query = Sequence::query();

        // Total records BEFORE search
        $totalData = $query->count();

        // Search filter
        if ($request->has('search') && $request->search['value'] != '') {
            $searchValue = $request->search['value'];
            $query->where(function ($q) use ($searchValue) {
                $q->where('subject', 'LIKE', "%$searchValue%")
                    ->orWhere('step', 'LIKE', "%$searchValue%")
                    ->orWhere('type', 'LIKE', "%$searchValue%")
                    ->orWhere('variant', 'LIKE', "%$searchValue%");
            });
        }

        // Total AFTER search
        $totalFiltered = $query->count();

        // Pagination
        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);
        $draw = $request->input('draw', 1);

        // Sorting
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDir = $request->input('order.0.dir', 'desc');

        if (isset($columns_list[$orderColumnIndex])) {
            $query->orderBy($columns_list[$orderColumnIndex], $orderDir);
        } else {
            $query->orderBy('id', 'desc');
        }

        $sequences = $query->offset($start)->limit($limit)->get();

        $data = [];

        foreach ($sequences as $seq) {
            $row = [];

            // Edit button (first column)
            $row['view'] = '<button class="btn btn-sm btn-info" onclick="viewSequence(' . $seq->id . ')">👁️ View</button>';
            $row['edit'] = '<a href="' . route('admin-sequences-edit', $seq->id) . '" class="btn btn-sm btn-primary">Edit</a>';
            $row['id'] = $seq->id;
            $row['step'] = $seq->step;
            $row['subject'] = $seq->subject;
            $row['type'] = $seq->type;
            $row['gap_days'] = $seq->gap_days ?? '-';
            $row['variant'] = $seq->variant ?? '-';
            $row['created_at'] = date('Y-m-d', strtotime($seq->created_at));
            $row['updated_at'] = date('Y-m-d', strtotime($seq->updated_at));

            // Delete button (last column)
            $row['delete'] = '<button onclick="deleteSequence(' . $seq->id . ')" class="btn btn-sm btn-danger">Delete</button>';

            $data[] = $row;
        }

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => intval($totalData),
            'recordsFiltered' => intval($totalFiltered),
            'data' => $data
        ]);
    }


    public function getSequenceDetails(Request $request) {}


    // public function sequencesStore(Request $request)
    // {
    //     // Log incoming request for debugging
    //     Log::info('Store sequence request received', [
    //         'all_data' => $request->all(),
    //         'has_hero_image' => $request->hasFile('hero_image'),
    //         'has_attachment' => $request->hasFile('attachments_image')
    //     ]);

    //     $request->validate([
    //         'step' => 'required|integer',
    //         'subject' => 'required|string',
    //         'message' => 'required|string',
    //         'gap_days' => 'nullable|integer',
    //         'variant' => 'nullable|string',
    //         'type' => 'required|in:B2B,B2C',
    //         'hero_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    //         'attachments_image' => 'nullable|file|max:5120',
    //         'whatsapp_link' => 'nullable|url',
    //         'telegram_link' => 'nullable|url',
    //         'business_link' => 'nullable|url'
    //     ]);

    //     $data = $request->except(['hero_image', 'attachments_image']);
    //     // Handle hero image upload with unique filename
    //     if ($request->hasFile('hero_image')) {

    //         $file = $request->file('hero_image');

    //         $filename = time() . '_hero_' . uniqid() . '.' . $file->getClientOriginalExtension();

    //         // 👇 public_html me save
    //         $destination = base_path('../public_html/hero_image');

    //         if (!file_exists($destination)) {
    //             mkdir($destination, 0777, true);
    //         }

    //         $file->move($destination, $filename);

    //         $data['hero_image'] = 'hero_image/' . $filename;
    //     }

    //     // ✅ ATTACHMENT IMAGE UPLOAD
    //     if ($request->hasFile('attachments_image')) {

    //         $file = $request->file('attachments_image');

    //         // ✅ name & size
    //         $originalName = $file->getClientOriginalName();
    //         $fileSize = $file->getSize();

    //         // ✅ unique filename
    //         $filename = date('Ymd_His') . '_attach_' . uniqid() . '.' . $file->getClientOriginalExtension();

    //         // ✅ IMPORTANT: public_html path
    //         $destination = base_path('../public_html/attachments_image');

    //         // folder create
    //         if (!file_exists($destination)) {
    //             mkdir($destination, 0777, true);
    //         }

    //         // move file
    //         $file->move($destination, $filename);

    //         // save DB
    //         $data['attachments_image'] = 'attachments_image/' . $filename;
    //         $data['attachment_name'] = $originalName;
    //         $data['attachment_size'] = $fileSize;
    //     }

    //     try {
    //         $sequence = Sequence::create($data);
    //         Log::info('Sequence created successfully', ['id' => $sequence->id]);
    //         return redirect()->back()->with('success', 'Sequence added successfully!');
    //     } catch (\Exception $e) {
    //         Log::error('Error creating sequence', ['error' => $e->getMessage()]);
    //         return redirect()->back()->with('error', 'Error saving sequence: ' . $e->getMessage());
    //     }
    // }
}
