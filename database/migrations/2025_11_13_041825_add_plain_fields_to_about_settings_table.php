<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('about_settings', function (Blueprint $table) {
            // รูป (ลิงก์เป็นข้อความธรรมดา)
            if (!Schema::hasColumn('about_settings', 'hero_image_url')) {
                $table->string('hero_image_url', 255)->nullable()->after('hero_intro_en');
            }

            // เนื้อหาหลัก (ข้อความธรรมดา)
            foreach (['intro_th','intro_en','vision','mission'] as $col) {
                if (!Schema::hasColumn('about_settings', $col)) {
                    $table->text($col)->nullable()->after('map_query');
                }
            }

            // กลุ่มที่เคยคิดจะเก็บแบบ JSON → เก็บเป็นข้อความยาวธรรมดา
            foreach (['companies_text','programs_text','values_text','contacts_text'] as $col) {
                if (!Schema::hasColumn('about_settings', $col)) {
                    $table->longText($col)->nullable()->after('mission');
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('about_settings', function (Blueprint $table) {
            $drops = [
                'hero_image_url',
                'intro_th','intro_en','vision','mission',
                'companies_text','programs_text','values_text','contacts_text',
            ];
            foreach ($drops as $col) {
                if (Schema::hasColumn('about_settings', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
