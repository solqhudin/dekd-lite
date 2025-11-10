<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\Admin\PostController as AdminPostController;

/*
|--------------------------------------------------------------------------
| Public: หน้าแรก & รายการกระทู้
|--------------------------------------------------------------------------
*/

// หน้าแรก = รายการกระทู้ที่อนุมัติแล้ว
Route::get('/', [PostController::class, 'index'])->name('home');

// /threads -> รายการกระทู้เหมือนกัน
Route::get('/threads', [PostController::class, 'index'])->name('posts.index');


/*
|--------------------------------------------------------------------------
| Authenticated: โปรไฟล์ / Dashboard / ตั้งกระทู้
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // dashboard (จะใช้หรือไม่ใช้ก็ได้)
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');

    // โปรไฟล์
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ตั้งกระทู้ใหม่ -> ต้องมาก่อน /threads/{slug}
    Route::get('/threads/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/threads', [PostController::class, 'store'])->name('posts.store');
});


/*
|--------------------------------------------------------------------------
| Public: ดูกระทู้เดี่ยว
|--------------------------------------------------------------------------
|
| ต้องวาง "หลัง" /threads/create เสมอ
|
*/

Route::get('/threads/{slug}', [PostController::class, 'show'])->name('posts.show');


/*
|--------------------------------------------------------------------------
| Admin: ต้อง role:admin
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', fn () => view('admin.dashboard'))->name('dashboard');

        Route::resource('posts', AdminPostController::class)->except(['show']);

        Route::post('posts/{post}/approve', [AdminPostController::class, 'approve'])
            ->name('posts.approve');
    });


/*
|--------------------------------------------------------------------------
| Auth (Breeze)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
