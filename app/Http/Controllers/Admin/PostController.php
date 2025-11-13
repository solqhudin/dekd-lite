<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Announcement; // ใช้งานตารางประกาศ
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * แสดงหน้าแอดมิน: รวมทั้งกระทู้ (pending/published) และประชาสัมพันธ์ (pending/published)
     */
    public function index()
    {
        // ----- กระทู้ -----
        // รออนุมัติ (ไม่ต้อง paginate ก็ได้ เอาแบบ list เต็ม)
        $pendingPosts = Post::with('author')
            ->where('is_published', false)
            ->latest()
            ->get();

        // เผยแพร่แล้ว (paginate)
        $publishedPosts = Post::with('author')
            ->where('is_published', true)
            ->latest()
            ->paginate(15, ['*'], 'posts_page');

        // เพื่อความเข้ากันได้กับ view เดิมที่เคยใช้ $pending / $published
        $pending   = $pendingPosts;
        $published = $publishedPosts;

        // ----- ประชาสัมพันธ์ -----
        // รอเผยแพร่ (โชว์สั้น ๆ)
        $annPending = Announcement::query()
            ->where('is_published', false)
            ->orderByDesc('created_at')
            ->take(8)
            ->get();

        // เผยแพร่แล้ว (paginate แยก page name)
        $annPublished = Announcement::query()
            ->where('is_published', true)
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->paginate(10, ['*'], 'announcements_page');

        return view('admin.posts.index', compact(
            'pendingPosts',
            'publishedPosts',
            'pending',
            'published',
            'annPending',
            'annPublished'
        ));
    }

    /**
     * อนุมัติให้โพสต์ไปหน้าบ้าน (คงไว้ตามที่คุณใช้)
     */
    public function approve(Post $post)
    {
        $post->update(['is_published' => true]);

        return back()->with('success', 'อนุมัติกระทู้เรียบร้อยแล้ว');
    }

    /**
     * สลับสถานะเผยแพร่/ซ่อนกระทู้ (ถ้าคุณมีปุ่ม toggle)
     */
    public function toggleStatus(Post $post)
    {
        $post->is_published = ! $post->is_published;
        $post->save();

        return back()->with(
            'success',
            $post->is_published
                ? 'อนุมัติและเผยแพร่กระทู้เรียบร้อยแล้ว'
                : 'ซ่อนกระทู้ออกจากหน้าเว็บเรียบร้อยแล้ว'
        );
    }

    // ---------- ส่วนที่เหลือคงไว้เรียบ ๆ ตามเดิม ----------

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
