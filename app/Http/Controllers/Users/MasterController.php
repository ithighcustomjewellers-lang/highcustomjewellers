<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BusinessLink;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        return view('user.link-document',compact('business'));
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
        $userId = auth()->id();
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
        dd($request);
        die();
    }


}
