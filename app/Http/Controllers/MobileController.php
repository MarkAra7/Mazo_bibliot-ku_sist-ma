<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class MobileController extends Controller
{
    public function home(): View
    {
        return view('mobile.home');
    }
}
