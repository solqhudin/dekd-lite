<?php

namespace App\Http\Controllers;

use App\Models\AboutSetting;

class AboutController extends Controller
{
    public function index()
    {
        $about = AboutSetting::first(); // อาจเป็น null ถ้ายังไม่เคยบันทึก
        return view('about.index', compact('about'));
    }
}
