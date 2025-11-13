<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutSetting extends Model
{
    protected $fillable = [
        'hero_badge',
        'hero_title_th',
        'hero_intro_th',
        'hero_title_en',
        'hero_intro_en',
        'hero_image_url',
        'hero_image',        // ✅ เพิ่มบรรทัดนี้ เพื่อรองรับ path ไฟล์รูป
        'phone',
        'email',
        'address',
        'line_url',
        'map_query',
        'intro_th',
        'intro_en',
        'vision',
        'mission',
        'companies_text',
        'programs_text',
        'values_text',
        'contacts_text',
    ];

    protected $casts = [
        'affiliates' => 'array',
        'programs'   => 'array',
        'team'       => 'array',
        'faqs'       => 'array',
    ];
}
