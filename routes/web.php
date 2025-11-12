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
| Authenticated: dashboard / โปรไฟล์ / ตั้งกระทู้ / คอมเมนต์ / ปฏิกิริยา
|--------------------------------------------------------------------------
|
| **สำคัญ:** route ที่เป็น path คงที่ เช่น /threads/create
| ต้องมาก่อน /threads/{slug} เสมอ
|
*/
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ตั้งกระทู้
    Route::get('/threads/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/threads', [PostController::class, 'store'])->name('posts.store');

    // คอมเมนต์
    Route::post('/threads/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::post('/comments/{comment}/reply', [CommentController::class, 'reply'])->name('comments.reply');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // ปฏิกิริยา
    Route::post('/threads/{slug}/react', [PostController::class, 'react'])->name('posts.react');
    Route::post('/comments/{comment}/react', [CommentReactionController::class, 'toggle'])->name('comments.react');
});

/*
|--------------------------------------------------------------------------
| Public: ดูกระทู้เดี่ยว (ต้องวางหลัง /threads/create)
|--------------------------------------------------------------------------
*/
Route::get('/threads/{slug}', [PostController::class, 'show'])->name('posts.show');

/*
|--------------------------------------------------------------------------
| Admin: ต้อง role:admin (สำหรับโพสต์)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::view('/', 'admin.dashboard')->name('dashboard');

        // index แยกเอง เพื่อคุม query
        Route::get('/posts', [AdminPostController::class, 'index'])->name('posts.index');

        // สลับสถานะ + อนุมัติ
        Route::patch('/posts/{post}/toggle', [AdminPostController::class, 'toggleStatus'])->name('posts.toggle');
        Route::post('/posts/{post}/approve', [AdminPostController::class, 'approve'])->name('posts.approve');

        // CRUD อื่น ๆ (ยกเว้น index, show)
        Route::resource('posts', AdminPostController::class)->except(['index', 'show']);
    });

/* ---------- Public: ประชาสัมพันธ์ ---------- */
Route::get('/announcements', [AnnouncementController::class, 'publicIndex'])->name('announcements.index');
Route::get('/announcements/{announcement:slug}', [AnnouncementController::class, 'publicShow'])->name('announcements.show');

/* ---------- Admin: จัดการประกาศ ---------- */
Route::middleware(['auth', 'can:manage-announcements'])->group(function () {
    Route::get('/admin/announcements', [AnnouncementController::class, 'index'])->name('admin.announcements.index');
    Route::get('/admin/announcements/create', [AnnouncementController::class, 'create'])->name('admin.announcements.create');
    Route::post('/admin/announcements', [AnnouncementController::class, 'store'])->name('admin.announcements.store');
    Route::get('/admin/announcements/{announcement}/edit', [AnnouncementController::class, 'edit'])->name('admin.announcements.edit');
    Route::put('/admin/announcements/{announcement}', [AnnouncementController::class, 'update'])->name('admin.announcements.update');
    Route::delete('/admin/announcements/{announcement}', [AnnouncementController::class, 'destroy'])->name('admin.announcements.destroy');
});

/*
|--------------------------------------------------------------------------
| Auth routes (Breeze)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
