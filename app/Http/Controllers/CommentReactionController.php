<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\CommentReaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentReactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Toggle ปฏิกิริยากับคอมเมนต์
     *
     * รูปแบบการใช้:
     *  - POST /comments/{comment}/react  โดยส่ง type=like หรือ type=dislike
     *  - ถ้ากดซ้ำ type เดิม -> ยกเลิก
     *  - ถ้าเคยกดอีกฝั่ง -> สลับฝั่ง
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment       $comment  (มาจาก route model binding)
     */
    public function toggle(Request $request, Comment $comment)
    {
        $data = $request->validate([
            'type' => ['required', 'in:like,dislike'],
        ]);

        $userId = Auth::id();
        $type   = $data['type'];

        $reaction = CommentReaction::where('comment_id', $comment->id)
            ->where('user_id', $userId)
            ->first();

        if ($reaction && $reaction->type === $type) {
            // ถ้ากดซ้ำอันเดิม -> ยกเลิกโหวต
            $reaction->delete();
        } else {
            // เปลี่ยนหรือสร้างใหม่
            if ($reaction) {
                $reaction->update(['type' => $type]);
            } else {
                CommentReaction::create([
                    'comment_id' => $comment->id,
                    'user_id'    => $userId,
                    'type'       => $type,
                ]);
            }
        }

        if ($request->wantsJson()) {
            return response()->json([
                'likes'    => $comment->reactions()->where('type', 'like')->count(),
                'dislikes' => $comment->reactions()->where('type', 'dislike')->count(),
            ]);
        }

        return back();
    }
}
