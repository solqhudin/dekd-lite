<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('about_settings', function (Blueprint $table) {
            $table->id();
            $table->string('hero_badge')->nullable();
            $table->string('hero_title_th')->nullable();
            $table->text('hero_intro_th')->nullable();
            $table->string('hero_title_en')->nullable();
            $table->text('hero_intro_en')->nullable();

            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('line_url')->nullable();

            // ใช้ค่านี้ฝังใน Google Maps Iframe แบบ q=<map_query>
            $table->string('map_query')->nullable()->default('Bangkok Thailand');

            $table->json('affiliates')->nullable(); // ["Engenius International Co., Ltd.", "Engenius English Co., Ltd."]
            $table->json('programs')->nullable();   // [{title,desc,tag}]
            $table->json('team')->nullable();       // [{name,role,bio,photo_url}]
            $table->json('faqs')->nullable();       // [{q,a}]
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('about_settings');
    }
};
