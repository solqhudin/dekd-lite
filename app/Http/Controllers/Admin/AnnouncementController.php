<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AnnouncementController extends Controller
{
    /* ------------------- PUBLIC ------------------- */
    public function publicIndex(Request $request)
    {
        $q = trim($request->get('q', ''));
        $announcements = Announcement::query()
            ->when($q, fn($s) => $s->where(fn($w) => $w
                ->where('title','like',"%$q%")
                ->orWhere('excerpt','like',"%$q%")
                ->orWhere('body','like',"%$q%")))
            ->where('is_published', true)
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.announcements.public-index', compact('announcements','q'));
    }

    public function publicShow(Announcement $announcement)
    {
        abort_if(!$announcement->is_published, 404);
        return view('admin.announcements.public-show', compact('announcement'));
    }

    /* ------------------- ADMIN ------------------- */
    public function index(Request $request)
    {
        $q = trim($request->get('q', ''));
        $announcements = Announcement::query()
            ->with('author')
            ->when($q, fn($s) => $s->where(fn($w) => $w
                ->where('title','like',"%$q%")
                ->orWhere('excerpt','like',"%$q%")
                ->orWhere('body','like',"%$q%")))
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->paginate(12)
            ->withQueryString();

        return view('admin.announcements.index', compact('announcements','q'));
    }

    public function create() { return view('admin.announcements.create'); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'        => ['required','string','max:200'],
            'category'     => ['nullable','string','max:60'],
            'excerpt'      => ['nullable','string','max:300'],
            'body'         => ['required','string'],
            'cover_image'  => ['nullable','image','max:4096'],
            'is_published' => ['sometimes','boolean'],
            'published_at' => ['nullable','date'],
        ]);

        $data['author_id'] = $request->user()->id;

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('announcements','public');
        }
        if (!empty($data['is_published']) && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        $a = Announcement::create($data);

        return redirect()->route('announcements.show', $a->slug)
            ->with('success','เผยแพร่ประกาศเรียบร้อยแล้ว');
    }

    public function edit(Announcement $announcement)
    {
        return view('admin.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $data = $request->validate([
            'title'        => ['required','string','max:200'],
            'category'     => ['nullable','string','max:60'],
            'excerpt'      => ['nullable','string','max:300'],
            'body'         => ['required','string'],
            'cover_image'  => ['nullable','image','max:4096'],
            'is_published' => ['sometimes','boolean'],
            'published_at' => ['nullable','date'],
            'slug'         => ['nullable','string','max:255', Rule::unique('announcements','slug')->ignore($announcement->id)],
        ]);

        if ($request->hasFile('cover_image')) {
            if ($announcement->cover_image) {
                Storage::disk('public')->delete($announcement->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('announcements','public');
        }

        if (!empty($data['is_published']) && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        $announcement->update($data);

        return redirect()->route('announcements.show', $announcement->slug)
            ->with('success','บันทึกการเปลี่ยนแปลงแล้ว');
    }

    public function destroy(Announcement $announcement)
    {
        if ($announcement->cover_image) {
            Storage::disk('public')->delete($announcement->cover_image);
        }
        $announcement->delete();

        return redirect()->route('admin.announcements.index')
            ->with('success','ลบประกาศแล้ว');
    }
}
