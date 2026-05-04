<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\CampaignController;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    // form page
    public function create()
    {
        return view('admin.contacts.create');
    }

    // save data
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:contacts,email',
            'company_name' => 'nullable|string|max:255',
            'type' => 'required|in:b2b,b2c',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {

            $contact = Contact::create([
                'name' => $request->name,
                'email' => $request->email,
                'company_name' => $request->company_name,
                'type' => $request->type,
            ]);

            app(CampaignController::class)->start($contact->id);



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

    public function index()
    {
        $contacts = Contact::all();
        return view('admin.contacts.index', compact('contacts'));
    }
}
