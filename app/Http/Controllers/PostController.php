<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostReaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function __construct()
    {
        // ต้องล็อกอินสำหรับสร้างโพสต์ / ส่งโพสต์ / โหวตโพสต์ / คอมเมนต์ผ่านเมธอดนี้
        // (routes ที่ใช้ CommentController แยกไปแล้วไม่โดนตรงนี้)
        $this->middleware('auth')->only([
            'create',
            'store',
            'comment',
            'react',
        ]);
    }

    /**
     * หน้าแสดงกระทู้ทั้งหมด (เฉพาะที่อนุมัติแล้ว)
     */
    public function index()
    {
        $posts = Post::with('author')
            ->where('is_published', true)
            ->withCount([
                'comments as comments_count',
                'reactions as likes_count' => function ($q) {
                    $q->where('type', 'like');
                },
            ])
            ->latest()
            ->paginate(10);
    
        return view('posts.index', compact('posts'));
    }
    


    /**
     * หน้าอ่านกระทู้เดี่ยว
     * ใช้ slug ใน URL
     */
    public function show($slug)
    {
        $post = Post::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();
    
        // นับวิว
        $post->increment('view_count');
    
        // โหลด comment top-level + tree replies + reactions + user
        $comments = $post->comments()
            ->whereNull('parent_id')
            ->with([
                'user',
                'reactions',
                'replies.user',
                'replies.reactions',
                'replies.replies',        // ถ้ามีหลายชั้น ให้เพิ่มแบบนี้ต่อได้
                'replies.replies.user',
                'replies.replies.reactions',
            ])
            ->latest()
            ->get();
    
        return view('posts.show', [
            'post' => $post,
            'comments' => $comments,
        ]);
    }
    

    /**
     * ฟอร์มตั้งกระทู้ใหม่ (auth แล้ว — บังคับใน __construct)
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * บันทึกกระทู้ใหม่ (สถานะรออนุมัติจากแอดมิน)
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'   => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
        ]);

        Post::create([
            'user_id'      => Auth::id(),
            'title'        => $data['title'],
            'slug'         => uniqid(),
            'content'      => $data['content'],
            'is_published' => false,
            'view_count'   => 0,
        ]);

        return redirect()
            ->route('posts.index')
            ->with('success', 'ส่งกระทู้เรียบร้อยแล้ว รอทีมงานอนุมัติ');
    }

    /**
     * คอมเมนต์กระทู้ผ่าน PostController (สำหรับ route เก่า)
     * แนะนำระยะยาวใช้ CommentController@store แทน
     */
    public function comment(Request $request, string $slug)
    {
        $post = Post::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $data = $request->validate([
            'content' => ['required', 'string', 'max:2000'],
        ]);

        $post->comments()->create([
            'user_id' => Auth::id(),
            'content' => $data['content'],
        ]);

        return back()->with('success', 'แสดงความคิดเห็นเรียบร้อยแล้ว');
    }

    /**
     * กด like / dislike ให้ "โพสต์"
     * (คนละส่วนกับ like/dislike คอมเมนต์ ซึ่งใช้ CommentReactionController)
     */
    public function react(Request $request, string $slug)
    {
        $post = Post::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $data = $request->validate([
            'type' => ['required', 'in:like,dislike'],
        ]);

        $userId = Auth::id();

        $reaction = PostReaction::where('post_id', $post->id)
            ->where('user_id', $userId)
            ->first();

        if ($reaction && $reaction->type === $data['type']) {
            // ถ้ากดซ้ำชนิดเดิม => ยกเลิก
            $reaction->delete();
        } else {
            if ($reaction) {
                $reaction->update(['type' => $data['type']]);
            } else {
                PostReaction::create([
                    'post_id' => $post->id,
                    'user_id' => $userId,
                    'type'    => $data['type'],
                ]);
            }
        }

        // นับใหม่
        $post->loadCount([
            'reactions as likes_count' => function ($q) {
                $q->where('type', 'like');
            },
            'reactions as dislikes_count' => function ($q) {
                $q->where('type', 'dislike');
            },
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'likes'    => $post->likes_count,
                'dislikes' => $post->dislikes_count,
            ]);
        }

        return back();
    }
}
