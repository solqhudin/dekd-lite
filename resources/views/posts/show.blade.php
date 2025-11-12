@extends('layouts.navigation')
@section('title', 'กระทู้: ' . ($post->title ?? 'ไม่ทราบ') . ' • Engenius Group')

@section('content')
@php
    /** @var \App\Models\Post $post */
    /** @var \Illuminate\Support\Collection|\App\Models\Comment[] $comments */
    $currentUser = auth()->user();
@endphp

<div class="max-w-5xl mx-auto py-8 space-y-8">

    {{-- ========= ส่วนหัวกระทู้ ========= --}}
    <section class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-start justify-between gap-4">
            <div>
                {{-- แท็กเล็กด้านบน (ใช้โทนแบรนด์) --}}
                <div class="inline-flex items-center gap-2 text-[11px] text-brand-700 font-semibold mb-1">
                    <span class="w-1.5 h-1.5 bg-brand-700 rounded-full"></span>
                    พื้นที่แชร์ประสบการณ์ &amp; ถาม-ตอบ
                </div>

                {{-- หัวข้อกระทู้ --}}
                <h1 class="text-2xl font-bold text-slate-900 leading-snug">
                    {{ $post->title }}
                </h1>

                {{-- ผู้เขียน / เวลา / สถิติ --}}
                <div class="mt-2 flex flex-wrap items-center gap-3 text-xs text-slate-500">
                    <span>
                        โดย
                        <span class="font-semibold text-slate-800">
                            {{ $post->author->name ?? 'ไม่ทราบ' }}
                        </span>
                    </span>

                    <span>• {{ $post->created_at?->diffForHumans() }}</span>

                    <span class="inline-flex items-center gap-1">
                        👁️ {{ number_format($post->view_count ?? 0) }} เข้าชม
                    </span>

                    <span class="inline-flex items-center gap-1">
                        💬 {{ $post->comments()->count() }} ความคิดเห็น
                    </span>
                </div>
            </div>
        </div>

        {{-- เนื้อหากระทู้ --}}
        <div class="mt-6 prose max-w-none text-slate-800 leading-relaxed">
            {!! nl2br(e($post->content)) !!}
        </div>

        {{-- ปุ่ม like / dislike ของกระทู้ --}}
        @auth
            @php
                $post->loadMissing('reactions');
                $postReaction = $post->reactions->firstWhere('user_id', $currentUser->id);
                $postLikes    = $post->likes_count    ?? $post->reactions->where('type', 'like')->count();
                $postDislikes = $post->dislikes_count ?? $post->reactions->where('type', 'dislike')->count();
            @endphp

            <div class="mt-4 flex gap-3 text-sm text-slate-500">
                {{-- Like = ปุ่มหลักโทนแบรนด์ --}}
                <form method="POST" action="{{ route('posts.react', $post->slug) }}">
                    @csrf
                    <input type="hidden" name="type" value="like">
                    <button type="submit"
                            class="px-3 py-1.5 rounded-full border flex items-center gap-1 transition
                                   {{ $postReaction?->type === 'like'
                                        ? 'bg-brand-700 text-white border-brand-700'
                                        : 'border-gray-300 text-slate-700 hover:border-brand-700/40 hover:text-brand-700' }}">
                        👍 {{ $postLikes }}
                    </button>
                </form>

                {{-- Dislike = โทนกลาง/แดงอ่อน เพื่อไม่ไปชนธีมหลัก --}}
                <form method="POST" action="{{ route('posts.react', $post->slug) }}">
                    @csrf
                    <input type="hidden" name="type" value="dislike">
                    <button type="submit"
                            class="px-3 py-1.5 rounded-full border flex items-center gap-1 transition
                                   {{ $postReaction?->type === 'dislike'
                                        ? 'bg-red-50 text-red-600 border-red-300'
                                        : 'border-gray-300 text-slate-700 hover:bg-slate-50' }}">
                        👎 {{ $postDislikes }}
                    </button>
                </form>
            </div>
        @endauth
    </section>

    {{-- ========= ฟอร์มคอมเมนต์ใหม่ ========= --}}
    @auth
        <section class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <h2 class="text-lg font-semibold mb-3 text-slate-900">แสดงความคิดเห็น</h2>

            @if (session('success'))
                <div class="mb-3 px-4 py-2 rounded-xl bg-emerald-50 text-emerald-700 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @error('content')
                <div class="mb-2 px-3 py-2 rounded-lg bg-red-50 text-red-600 text-xs">
                    {{ $message }}
                </div>
            @enderror

            <form method="POST"
                  action="{{ route('comments.store', $post) }}"
                  class="mt-2 space-y-3">
                @csrf
                <textarea
                    name="content"
                    rows="3"
                    class="w-full border rounded-lg px-4 py-3 text-sm
                           focus:outline-none focus:ring-2 focus:ring-brand-700/30 focus:border-brand-700"
                    placeholder="พิมพ์ความคิดเห็นของคุณ...">{{ old('content') }}</textarea>

                <button type="submit"
                        class="px-6 py-2 bg-brand-700 text-white rounded-full font-medium hover:brightness-110 shadow-sm">
                    ส่งความคิดเห็น
                </button>
            </form>
        </section>
    @else
        <section class="bg-white rounded-2xl shadow-sm p-4 border border-gray-100 text-center text-sm text-slate-500">
            กรุณา
            <a href="{{ route('login') }}" class="text-brand-700 hover:underline">เข้าสู่ระบบ</a>
            เพื่อแสดงความคิดเห็น
        </section>
    @endauth

    {{-- ========= รายการความคิดเห็น ========= --}}
    <section class="mt-6">
        <h2 class="text-base font-semibold text-slate-800 mb-4">
            ความคิดเห็น ({{ $post->comments()->count() }})
        </h2>

        @if (session('comment_message'))
            <div class="mb-3 px-4 py-2 rounded-xl bg-emerald-50 text-emerald-700 text-sm">
                {{ session('comment_message') }}
            </div>
        @endif

        @php
            $comments = $comments ?? collect();
        @endphp

        @if ($comments->isEmpty())
            <p class="text-sm text-slate-500">
                ยังไม่มีความคิดเห็น มาเป็นคนแรกได้เลย 🎉
            </p>
        @else
            <div class="space-y-4">
                @foreach ($comments as $comment)
                    @include('posts.partials.comment', [
                        'comment'     => $comment,
                        'currentUser' => $currentUser ?? null,
                        'isChild'     => false,
                    ])
                @endforeach
            </div>
        @endif
    </section>
</div>
@endsection
