<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function __construct()
    {
        // ต้องล็อกอินสำหรับจัดการคอมเมนต์
        $this->middleware('auth')->only(['store', 'reply', 'update', 'destroy']);
    }

    /**
     * สร้างคอมเมนต์ใหม่ (หรือ reply ถ้าส่ง parent_id มา)
     * Route แนะนำ: POST /threads/{post}/comments -> comments.store
     */
    public function store(Request $request, Post $post)
    {
        $data = $request->validate([
            'content'   => ['required', 'string', 'max:2000'],
            'parent_id' => ['nullable', 'exists:comments,id'],
        ]);

        Comment::create([
            'post_id'   => $post->id,
            'user_id'   => Auth::id(),
            'content'   => $data['content'],
            'parent_id' => $data['parent_id'] ?? null,
        ]);

        return back()->with('success', 'เพิ่มความคิดเห็นเรียบร้อยแล้ว');
    }

    /**
     * ตอบกลับคอมเมนต์ (ใช้ content เหมือนเดิม)
     * Route แนะนำ: POST /comments/{comment}/reply -> comments.reply
     */
    public function reply(Request $request, Comment $comment)
    {
        $data = $request->validate([
            'content' => ['required', 'string', 'max:2000'],
        ]);

        Comment::create([
            'post_id'   => $comment->post_id,
            'user_id'   => Auth::id(),
            'content'   => $data['content'],
            'parent_id' => $comment->id,
        ]);

        return back()->with('success', 'ตอบกลับเรียบร้อยแล้ว');
    }

    /**
     * แก้ไขคอมเมนต์ (เจ้าของหรือ admin)
     * Route แนะนำ: PUT /comments/{comment} -> comments.update
     */
    public function update(Request $request, Comment $comment)
    {
        $this->authorizeComment($comment);

        $data = $request->validate([
            'content' => ['required', 'string', 'max:2000'],
        ]);

        $comment->update(['content' => $data['content']]);

        return back()->with('success', 'แก้ไขความคิดเห็นเรียบร้อยแล้ว');
    }

    /**
     * ลบคอมเมนต์ (เจ้าของหรือ admin)
     * Route แนะนำ: DELETE /comments/{comment} -> comments.destroy
     */
    public function destroy(Comment $comment)
    {
        $this->authorizeComment($comment);

        // ถ้าอยากให้ลบ replies ทั้งหมดด้วย แนะนำตั้ง onDelete('cascade') ที่ FK หรือ:
        // $comment->replies()->delete();

        $comment->delete();

        return back()->with('success', 'ลบความคิดเห็นเรียบร้อยแล้ว');
    }

    /**
     * เช็คสิทธิ์คอมเมนต์: ต้องเป็นเจ้าของ หรือมี role:admin
     */
    protected function authorizeComment(Comment $comment): void
    {
        $user = Auth::user();

        if (!$user) {
            abort(403);
        }

        if ($user->id !== $comment->user_id && !$user->hasRole('admin')) {
            abort(403);
        }
    }
}
