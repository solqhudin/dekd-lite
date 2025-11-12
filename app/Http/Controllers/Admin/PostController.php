<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Announcement; // ใช้ใน index เพื่อป้อนข้อมูลประกาศ
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * แดชบอร์ดโพสต์ฝั่งแอดมิน
     * route: GET /admin/posts  name: admin.posts.index
     */
    public function index(Request $request)
    {
        // สถิติรวม
        $pending   = Post::where('is_published', 0)->count();
        $published = Post::where('is_published', 1)->count();

        // รายการที่รออนุมัติ
        $pendingPosts = Post::with('author')
            ->where('is_published', 0)
            ->latest()
            ->get();

        // รายการที่เผยแพร่แล้ว
        $publishedPosts = Post::with('author')
            ->withCount([
                'comments',
                'reactions as likes_count'    => fn($q) => $q->where('type','like'),
                'reactions as dislikes_count' => fn($q) => $q->where('type','dislike'),
            ])
            ->where('is_published', 1)
            ->latest()
            ->paginate(10, ['*'], 'posts_page');

        // ประชาสัมพันธ์
        $annPending = Announcement::whereNull('published_at')->latest()->get();
        $annPublished = Announcement::whereNotNull('published_at')
            ->latest()
            ->paginate(10, ['*'], 'announcements_page');

        return view('admin.posts.index', compact(
            'pending','published',
            'pendingPosts','publishedPosts',
            'annPending','annPublished'
        ));
    }

    /**
     * สลับสถานะเผยแพร่
     */
    public function toggleStatus(Post $post)
    {
        $post->is_published = (int)!$post->is_published;
        $post->save();
        return back()->with('success','อัปเดตสถานะเรียบร้อย');
    }

    /**
     * อนุมัติโพสต์ (บังคับเผยแพร่)
     */
    public function approve(Post $post)
    {
        $post->is_published = 1;
        $post->save();
        return back()->with('success','อนุมัติโพสต์แล้ว');
    }

    /** ฟอร์มสร้าง */
    public function create()
    {
        return view('admin.posts.create');
    }

    /** บันทึกสร้าง */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'   => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'slug'    => ['nullable', 'string', 'max:255', 'unique:posts,slug'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = \Str::slug(mb_substr($data['title'], 0, 60)) . '-' . \Str::random(6);
        }

        $data['user_id'] = $request->user()->id;
        $data['is_published'] = (int) ($data['is_published'] ?? 0);

        $post = Post::create($data);

        return redirect()->route('admin.posts.index')->with('success', 'สร้างโพสต์เรียบร้อย');
    }

    /** ฟอร์มแก้ไข */
    public function edit(Post $post)
    {
        return view('admin.posts.edit', compact('post'));
    }

    /** บันทึกแก้ไข */
    public function update(Request $request, Post $post)
    {
        $data = $request->validate([
            'title'   => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'slug'    => ['nullable', 'string', 'max:255', 'unique:posts,slug,' . $post->id],
            'is_published' => ['nullable', 'boolean'],
        ]);

        if (!empty($data['slug'])) {
            $post->slug = $data['slug'];
        }

        $post->title        = $data['title'];
        $post->content      = $data['content'];
        $post->is_published = (int) ($data['is_published'] ?? $post->is_published);
        $post->save();

        return redirect()->route('admin.posts.index')->with('success', 'แก้ไขโพสต์เรียบร้อย');
    }

    /** ลบ */
    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('admin.posts.index')->with('success', 'ลบโพสต์เรียบร้อย');
    }
}
