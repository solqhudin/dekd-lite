<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * แสดงโพสต์ฝั่งแอดมิน: แยก Pending / Published
     */
    public function index(Request $request)
    {
        // รออนุมัติ
        $pending = Post::query()
            ->with(['author'])
            ->where('is_published', false)
            ->latest()
            ->paginate(10, ['*'], 'pending_page');

        // เผยแพร่แล้ว
        $published = Post::query()
            ->with(['author'])
            ->withCount([
                'comments',
                'reactions as likes_count' => function ($q) {
                    $q->where('type', 'like');
                },
                'reactions as dislikes_count' => function ($q) {
                    $q->where('type', 'dislike');
                },
            ])
            ->where('is_published', true)
            ->latest()
            ->paginate(10, ['*'], 'published_page');

        return view('admin.posts.index', [
            'pending'   => $pending,
            'published' => $published,
        ]);
    }

    /**
     * อนุมัติโพสต์ (ร่าง -> เผยแพร่)
     */
    public function approve(Post $post)
    {
        $post->forceFill(['is_published' => true])->save();

        return back()->with('success', 'อนุมัติโพสต์แล้ว');
    }

    /**
     * Toggle สถานะเผยแพร่
     */
    public function toggleStatus(Post $post)
    {
        $post->is_published = ! $post->is_published;
        $post->save();

        return back()->with('success', 'อัปเดตสถานะเรียบร้อย');
    }

    /**
     * ลบโพสต์
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return back()->with('success', 'ลบโพสต์แล้ว');
    }

    // เมธอดที่ยังไม่ใช้ ให้ 404 ไว้ก่อน จะค่อย ๆ เติมในอนาคต
    public function create()  { abort(404); }
    public function store()   { abort(404); }
    public function edit()    { abort(404); }
    public function update(Request $request, Post $post) { abort(404); }
    public function show(Post $post) { abort(404); }
}
