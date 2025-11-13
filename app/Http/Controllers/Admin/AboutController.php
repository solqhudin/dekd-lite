<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AboutController extends Controller
{
    private function isHttpUrl(?string $v): bool
    {
        if (!$v) return false;
        return str_starts_with($v, 'http://') || str_starts_with($v, 'https://');
    }

    public function index()
    {
        $about = AboutSetting::query()->latest('id')->first();
        return view('admin.about.index', compact('about'));
    }

    public function create()
    {
        if (AboutSetting::query()->exists()) {
            $about = AboutSetting::first();
            return redirect()->route('admin.about.edit', $about);
        }
        return view('admin.about.create');
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        // ใช้ไฟล์ -> เก็บพาธใน hero_image_url
        if ($request->hasFile('hero_image')) {
            $path = $request->file('hero_image')->store('about', 'public');
            $data['hero_image_url'] = $path;
        }

        // กันพลาด: ห้ามส่งคีย์ hero_image เข้า DB
        unset($data['hero_image']);

        $about = AboutSetting::create($data);

        return redirect()
            ->route('admin.about.edit', $about)
            ->with('success', 'บันทึกข้อมูลเกี่ยวกับเราเรียบร้อยแล้ว');
    }

    public function edit(AboutSetting $about)
    {
        return view('admin.about.edit', compact('about'));
    }

    public function update(Request $request, AboutSetting $about)
    {
        $data = $this->validated($request);

        // ถ้าอัปโหลดไฟล์ใหม่ ให้ลบไฟล์เก่า (เฉพาะกรณีที่ hero_image_url เป็น storage path)
        if ($request->hasFile('hero_image')) {
            if (! $this->isHttpUrl($about->hero_image_url)
                && $about->hero_image_url
                && Storage::disk('public')->exists($about->hero_image_url)) {
                Storage::disk('public')->delete($about->hero_image_url);
            }

            $path = $request->file('hero_image')->store('about', 'public');
            $data['hero_image_url'] = $path; // ใช้คอลัมน์เดียว
        }

        // กันพลาด: ห้ามส่งคีย์ hero_image เข้า DB
        unset($data['hero_image']);

        $about->update($data);

        return back()->with('success', 'อัปเดตข้อมูลเรียบร้อยแล้ว');
    }

    private function validated(Request $request): array
    {
        $v = $request->validate([
            'hero_badge'     => ['nullable','string','max:100'],
            'hero_title_th'  => ['nullable','string','max:255'],
            'hero_intro_th'  => ['nullable','string'],
            'hero_title_en'  => ['nullable','string','max:255'],
            'hero_intro_en'  => ['nullable','string'],

            // ใช้คอลัมน์เดียวเก็บได้ทั้ง URL หรือ storage path
            'hero_image_url' => ['nullable','string','max:255'],

            // ช่องอัปโหลดไฟล์ (ไม่ลง DB โดยตรง)
            'hero_image'     => ['sometimes','nullable','file','image','max:5120'],

            'phone'          => ['nullable','string','max:100'],
            'email'          => ['nullable','email','max:255'],
            'address'        => ['nullable','string','max:500'],
            'line_url'       => ['nullable','string','max:255'],
            'map_query'      => ['nullable','string','max:255'],
            'intro_th'       => ['nullable','string'],
            'intro_en'       => ['nullable','string'],
            'vision'         => ['nullable','string'],
            'mission'        => ['nullable','string'],
            'companies_text' => ['nullable','string'],
            'programs_text'  => ['nullable','string'],
            'values_text'    => ['nullable','string'],
            'contacts_text'  => ['nullable','string'],
        ]);

        // (ถ้าคุณมี fields แบบ *_text อื่น ๆ ที่ต้องแปลงเป็น array JSON ค่อยเติม logic ต่อจากนี้ได้)
        return $v;
    }
}
