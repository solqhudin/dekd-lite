<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    // หน้าแสดงกระทู้ทั้งหมด (เฉพาะที่อนุมัติแล้ว)
    public function index()
    {
        $posts = Post::where('is_published', true)
            ->latest()
            ->paginate(10);

        return view('posts.index', compact('posts'));
    }

    // หน้าอ่านกระทู้เดี่ยว
    public function show($slug)
    {
        $post = Post::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        return view('posts.show', compact('post'));
    }

    // ฟอร์มตั้งกระทู้ (ต้องล็อกอิน)
    public function create()
    {
        return view('posts.create');
    }

    // บันทึกกระทู้ใหม่ (รออนุมัติ)
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'   => 'required|max:255',
            'content' => 'required',
        ]);

        $data['user_id'] = Auth::id();
        $data['is_published'] = false; // ให้ admin อนุมัติก่อน

        Post::create($data);

        return redirect()
            ->route('posts.index')
            ->with('success', 'ส่งกระทู้เรียบร้อย รอแอดมินตรวจสอบก่อนแสดงบนหน้าเว็บ');
    }
}
