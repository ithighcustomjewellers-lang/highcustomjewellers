<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sequence;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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

    // Store data
    public function sequencesStore(Request $request)
    {
        // Log incoming request for debugging
        Log::info('Store sequence request received', [
            'all_data' => $request->all(),
            'has_hero_image' => $request->hasFile('hero_image'),
            'has_attachment' => $request->hasFile('attachments_image')
        ]);

        $request->validate([
            'step' => 'required|integer',
            'subject' => 'required|string',
            'message' => 'required|string',
            'gap_days' => 'nullable|integer',
            'variant' => 'nullable|string',
            'type' => 'required|in:B2B,B2C',
            'hero_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'attachments_image' => 'nullable|file|max:5120',
            'whatsapp_link' => 'nullable|url',
            'telegram_link' => 'nullable|url',
            'business_link' => 'nullable|url'
        ]);

        $data = $request->except(['hero_image', 'attachments_image']);
        // Handle hero image upload with unique filename
        if ($request->hasFile('hero_image')) {

            $file = $request->file('hero_image');

            $filename = time() . '_hero_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $destination = public_path('hero_image');

            // folder create if not exists
            if (!file_exists($destination)) {
                mkdir($destination, 0777, true);
            }

            $file->move($destination, $filename);

            // save path in DB
            $data['hero_image'] = 'hero_image/' . $filename;
        }

        // ✅ ATTACHMENT IMAGE UPLOAD
        if ($request->hasFile('attachments_image')) {
            $file = $request->file('attachments_image');

            // ✅ Get name and size BEFORE moving
            $originalName = $file->getClientOriginalName();
            $fileSize = $file->getSize();  // Works before move

            // Generate unique filename
            $filename = date('Ymd_His') . '_attach_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $destination = public_path('attachments_image');

            if (!file_exists($destination)) {
                mkdir($destination, 0777, true);
            }

            // Move the file
            $file->move($destination, $filename);

            // Save to database
            $data['attachments_image'] = 'attachments_image/' . $filename;
            $data['attachment_name'] = $originalName;
            $data['attachment_size'] = $fileSize;  // Now it's safe
        }

        try {
            $sequence = Sequence::create($data);
            Log::info('Sequence created successfully', ['id' => $sequence->id]);
            return redirect()->back()->with('success', 'Sequence added successfully!');
        } catch (\Exception $e) {
            Log::error('Error creating sequence', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Error saving sequence: ' . $e->getMessage());
        }
    }

}
