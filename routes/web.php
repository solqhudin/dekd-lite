<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
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
| Authenticated: โปรไฟล์ / Dashboard / ตั้งกระทู้
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/threads/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/threads', [PostController::class, 'store'])->name('posts.store');
});

/*
|--------------------------------------------------------------------------
| Public: ดูกระทู้เดี่ยว
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
        Route::get('/', fn () => view('admin.dashboard'))->name('dashboard');
        Route::resource('posts', AdminPostController::class)->except(['show']);
        Route::post('posts/{post}/approve', [AdminPostController::class, 'approve'])->name('posts.approve');
    });


/* ---------- Public: ประชาสัมพันธ์ ---------- */
Route::get('/announcements', [AnnouncementController::class, 'publicIndex'])
    ->name('announcements.index');

Route::get('/announcements/{announcement:slug}', [AnnouncementController::class, 'publicShow'])
    ->name('announcements.show');

/* ---------- Admin: จัดการประกาศ ---------- */
Route::middleware(['auth', 'can:manage-announcements'])->group(function () {
    Route::get('/admin/announcements', [AnnouncementController::class, 'index'])
        ->name('admin.announcements.index');

    Route::get('/admin/announcements/create', [AnnouncementController::class, 'create'])
        ->name('admin.announcements.create');

    Route::post('/admin/announcements', [AnnouncementController::class, 'store'])
        ->name('admin.announcements.store');

    Route::get('/admin/announcements/{announcement}/edit', [AnnouncementController::class, 'edit'])
        ->name('admin.announcements.edit');

    Route::put('/admin/announcements/{announcement}', [AnnouncementController::class, 'update'])
        ->name('admin.announcements.update');

    Route::delete('/admin/announcements/{announcement}', [AnnouncementController::class, 'destroy'])
        ->name('admin.announcements.destroy');
});

/*
|--------------------------------------------------------------------------
| Auth (Breeze)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
