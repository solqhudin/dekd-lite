{{-- resources/views/posts/show.blade.php --}}
<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 py-10">

        {{-- Back --}}
        <div class="mb-4">
            <a href="{{ route('posts.index') }}" class="text-sm text-orange-600 hover:underline">← กลับไปหน้ากระทู้</a>
        </div>

        {{-- Header --}}
        <header class="mb-4">
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900">
                {{ $post->title }}
            </h1>
            <div class="mt-2 flex flex-wrap items-center gap-3 text-xs text-slate-500">
                <span>โดย {{ $post->author->name ?? 'ไม่ทราบ' }}</span>
                <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                <span>{{ $post->created_at?->diffForHumans() }}</span>
                @if (isset($post->view_count))
                    <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                    <span>👁️ {{ $post->view_count }}</span>
                @endif
            </div>
        </header>

        {{-- Reactions --}}
        @php
            $likes = $post->likes_count
                ?? ($post->relationLoaded('reactions') ? $post->reactions->where('type','like')->count() : $post->reactions()->where('type','like')->count());
            $dislikes = $post->dislikes_count
                ?? ($post->relationLoaded('reactions') ? $post->reactions->where('type','dislike')->count() : $post->reactions()->where('type','dislike')->count());
        @endphp

        <div class="mb-6 flex items-center gap-3">
            @auth
                <form method="POST" action="{{ route('posts.react', $post->slug) }}">
                    @csrf
                    <input type="hidden" name="type" value="like">
                    <button type="submit"
                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs rounded-full bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-100">
                        👍 ถูกใจ <span class="ml-1 font-semibold">{{ $likes }}</span>
                    </button>
                </form>

                <form method="POST" action="{{ route('posts.react', $post->slug) }}">
                    @csrf
                    <input type="hidden" name="type" value="dislike">
                    <button type="submit"
                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs rounded-full bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-100">
                        👎 ไม่ถูกใจ <span class="ml-1 font-semibold">{{ $dislikes }}</span>
                    </button>
                </form>
            @else
                <div class="text-xs text-slate-500">
                    ต้อง <a class="text-orange-600 hover:underline" href="{{ route('login') }}">ล็อกอิน</a> ก่อนถึงจะกดถูกใจ/ไม่ถูกใจได้
                    <span class="ml-3">👍 {{ $likes }}</span>
                    <span class="ml-2">👎 {{ $dislikes }}</span>
                </div>
            @endauth
        </div>

        {{-- Body --}}
        <article class="prose max-w-none">
            {!! $post->content !!}
        </article>

        {{-- Divider --}}
        <div class="my-8 h-px bg-slate-100"></div>

        {{-- Comments --}}
        @php
            // พยายามใช้ตัวแปร $comments ถ้ามี; ถ้าไม่มีก็ดึงจากความสัมพันธ์/คิวรีให้จบที่นี่
            $rootComments = isset($comments) ? $comments
                : ($post->relationLoaded('comments')
                    ? $post->comments->whereNull('parent_id')->sortByDesc('created_at')
                    : \App\Models\Comment::where('post_id', $post->id)->whereNull('parent_id')->latest()->get());
        @endphp

        <section id="comments">
            <h2 class="text-lg font-bold text-slate-900 mb-4">คอมเมนต์ ({{ $rootComments instanceof \Illuminate\Support\Collection ? $rootComments->count() : (is_countable($rootComments) ? count($rootComments) : 0) }})</h2>

            {{-- ฟอร์มคอมเมนต์ใหม่ --}}
            @auth
                <div class="mb-6 rounded-xl border border-slate-200 bg-white p-4">
                    <form method="POST" action="{{ route('comments.store', $post) }}" class="space-y-3">
                        @csrf
                        <textarea name="content" rows="3" class="w-full rounded-lg border-slate-300"
                                  placeholder="พิมพ์ความคิดเห็นของคุณ...">{{ old('content') }}</textarea>
                        <div class="flex items-center justify-between">
                            <div class="text-xs text-slate-500">โปรดรักษามารยาทในการสนทนา</div>
                            <button class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-slate-800 text-white text-sm font-semibold hover:bg-slate-900">
                                ส่งคอมเมนต์
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div class="mb-6 text-sm text-slate-600">
                    กรุณา <a class="text-orange-600 hover:underline" href="{{ route('login') }}">ล็อกอิน</a> เพื่อแสดงความคิดเห็น
                </div>
            @endauth

            {{-- แสดงคอมเมนต์ระดับบน --}}
            <div class="space-y-4">
                @forelse ($rootComments as $comment)
                    @includeIf('posts.partials.comment', ['comment' => $comment])
                @empty
                    <div class="text-slate-400 text-sm">ยังไม่มีคอมเมนต์</div>
                @endforelse
            </div>
        </section>

    </div>
</x-app-layout>
