<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Announcement; // ✅ เพิ่ม: ใช้งานตารางประกาศ
use Illuminate\Http\Request;

class PostController extends Controller
{
    // แสดงรายการกระทู้ทั้งหมด (เน้นที่ pending ด้านบน) + แสดงประชาสัมพันธ์
    public function index()
    {
        // ----- กระทู้ -----
        $pending = Post::where('is_published', false)
            ->latest()
            ->get();

        $published = Post::where('is_published', true)
            ->latest()
            ->paginate(15);

        // ----- ประชาสัมพันธ์ -----
        // รายการรอเผยแพร่ (โชว์สั้น ๆ พอเห็นภาพรวม)
        $annPending = Announcement::query()
            ->where('is_published', false)
            ->orderByDesc('created_at')
            ->take(8)
            ->get();

        // รายการเผยแพร่แล้ว (มีเพจจิเนชันแยก ไม่ชนกับของโพสต์)
        $annPublished = Announcement::query()
            ->where('is_published', true)
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->paginate(10, ['*'], 'announcements_page'); // ใช้ชื่อ page แยก

        return view('admin.posts.index', compact(
            'pending',
            'published',
            'annPending',
            'annPublished'
        ));
    }

    // อนุมัติให้โพสต์ไปหน้าบ้าน
    public function approve(Post $post)
    {
        $post->update(['is_published' => true]);

        return back()->with('success', 'อนุมัติกระทู้เรียบร้อยแล้ว');
    }

    // ที่เหลือเอาแบบเรียบๆ ไว้เผื่อใช้ทีหลัง

    public function create()
    {
        return view('admin.posts.create');
    }

    public function store(Request $request)
    {
        // ถ้ายังไม่ใช้ ไม่ต้องทำอะไร
        abort(404);
    }

    public function edit(Post $post)
    {
        return view('admin.posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        // ยังไม่ทำ ก็กันไว้เฉยๆ
        abort(404);
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return back()->with('success', 'ลบกระทู้แล้ว');
    }
}
