<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class privacyPolicyController extends Controller
{
    public function privacyPolicy(){
        return view('privacy-policy');
    }

    public function terms(){
        return view('terms');
    }

    public function landingPage(){
        return view('landing-page');
    }
}
