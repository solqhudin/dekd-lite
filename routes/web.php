<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CommentReactionController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\PostController as AdminPostController;

/*
|--------------------------------------------------------------------------
| Public: หน้าแรก & รายการกระทู้
|--------------------------------------------------------------------------
*/

Route::get('/', [PostController::class, 'index'])->name('home');
Route::get('/threads', [PostController::class, 'index'])->name('posts.index');


/*
|--------------------------------------------------------------------------
| Authenticated: Dashboard / โปรไฟล์ / ตั้งกระทู้ / คอมเมนต์ / ปฏิกิริยา
|--------------------------------------------------------------------------
|
| NOTE:
| - routes ที่เป็น path คงที่ เช่น /threads/create
|   ต้องมาก่อน /threads/{slug} (ซึ่งอยู่ด้านล่าง)
|
*/

Route::middleware('auth')->group(function () {

    // Dashboard (ของผู้ใช้ทั่วไป/สมาชิก)
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');

    // โปรไฟล์
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |-------------------- ตั้งกระทู้ --------------------
    */

    // ฟอร์มตั้งกระทู้ใหม่
    Route::get('/threads/create', [PostController::class, 'create'])->name('posts.create');

    // บันทึกกระทู้ใหม่ (สร้างเป็น draft / รออนุมัติ)
    Route::post('/threads', [PostController::class, 'store'])->name('posts.store');

    /*
    |-------------------- คอมเมนต์ --------------------
    */

    // คอมเมนต์ใหม่บนโพสต์
    // ใช้ route model binding: {post} จะ bind ด้วย slug (จาก Post::getRouteKeyName())
    Route::post('/threads/{post}/comments', [CommentController::class, 'store'])
        ->name('comments.store');

    // ตอบกลับคอมเมนต์
    Route::post('/comments/{comment}/reply', [CommentController::class, 'reply'])
        ->name('comments.reply');

    // แก้ไขคอมเมนต์
    Route::put('/comments/{comment}', [CommentController::class, 'update'])
        ->name('comments.update');

    // ลบคอมเมนต์
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])
        ->name('comments.destroy');

    /*
    |-------------------- ปฏิกิริยา like / dislike --------------------
    */

    // ปฏิกิริยากับโพสต์
    Route::post('/threads/{slug}/react', [PostController::class, 'react'])
        ->name('posts.react');

    // ปฏิกิริยากับคอมเมนต์
    Route::post('/comments/{comment}/react', [CommentReactionController::class, 'toggle'])
        ->name('comments.react');
});


/*
|--------------------------------------------------------------------------
| Public: ดูกระทู้เดี่ยว
|--------------------------------------------------------------------------
|
| ต้องวางหลัง /threads/create และ routes ที่เป็น path คงที่
|
*/

Route::get('/threads/{slug}', [PostController::class, 'show'])->name('posts.show');


/*
|--------------------------------------------------------------------------
| Admin: ต้อง role:admin (จัดการโพสต์)
|--------------------------------------------------------------------------
|
| - ครอบทุกอย่างใต้ /admin
| - ใช้ prefix + name prefix = admin.
| - ใช้ resource สำหรับ posts ให้ยืดหยุ่นสุด
|
*/

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Admin dashboard
        Route::get('/', fn () => view('admin.dashboard'))->name('dashboard');

        // Resource สำหรับจัดการโพสต์ในแอดมิน
        // (index/create/store/edit/update/destroy)
        Route::resource('posts', AdminPostController::class)->except(['show']);

        // toggle สถานะเผยแพร่โพสต์ (เช่น เปิด/ปิดการแสดงผล)
        Route::patch('/posts/{post}/toggle', [AdminPostController::class, 'toggleStatus'])
            ->name('posts.toggle');

        // อนุมัติโพสต์ (เช่น จากรออนุมัติ -> เผยแพร่)
        Route::post('/posts/{post}/approve', [AdminPostController::class, 'approve'])
            ->name('posts.approve');
    });


/*
|--------------------------------------------------------------------------
| Public: ประกาศ (Announcements)
|--------------------------------------------------------------------------
*/

Route::get('/announcements', [AnnouncementController::class, 'publicIndex'])
    ->name('announcements.index');

Route::get('/announcements/{announcement:slug}', [AnnouncementController::class, 'publicShow'])
    ->name('announcements.show');


/*
|--------------------------------------------------------------------------
| Admin / Manager: จัดการประกาศ
|--------------------------------------------------------------------------
|
| - ใช้ middleware can:manage-announcements
| - ยืดหยุ่น: จะให้เฉพาะ admin ก็ได้ (กำหนดสิทธิ์ใน Gate/Policy/Permission)
|   หรือมอบสิทธิ์ให้ role อื่นเพิ่มได้ โดยไม่ต้องแก้ route
|
*/

Route::middleware(['auth', 'can:manage-announcements'])->prefix('admin')->group(function () {

    Route::get('/announcements', [AnnouncementController::class, 'index'])
        ->name('admin.announcements.index');

    Route::get('/announcements/create', [AnnouncementController::class, 'create'])
        ->name('admin.announcements.create');

    Route::post('/announcements', [AnnouncementController::class, 'store'])
        ->name('admin.announcements.store');

    Route::get('/announcements/{announcement}/edit', [AnnouncementController::class, 'edit'])
        ->name('admin.announcements.edit');

    Route::put('/announcements/{announcement}', [AnnouncementController::class, 'update'])
        ->name('admin.announcements.update');

    Route::delete('/announcements/{announcement}', [AnnouncementController::class, 'destroy'])
        ->name('admin.announcements.destroy');
});


/*
|--------------------------------------------------------------------------
| Auth routes (Breeze)
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';
