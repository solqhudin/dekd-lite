@php
    /** @var \App\Models\Comment $comment */
    $likes = $comment->likes_count
        ?? ($comment->relationLoaded('reactions') ? $comment->reactions->where('type','like')->count() : $comment->reactions()->where('type','like')->count());
    $dislikes = $comment->dislikes_count
        ?? ($comment->relationLoaded('reactions') ? $comment->reactions->where('type','dislike')->count() : $comment->reactions()->where('type','dislike')->count());
@endphp

<div class="rounded-xl border border-slate-200 bg-white p-4">
    <div class="flex items-center justify-between gap-3">
        <div class="flex items-center gap-2 text-xs text-slate-500">
            <span class="font-semibold text-slate-700">{{ $comment->user?->name ?? 'ไม่ทราบ' }}</span>
            <span class="w-1 h-1 rounded-full bg-slate-300"></span>
            <span>{{ $comment->created_at?->diffForHumans() }}</span>
        </div>
        <div class="flex items-center gap-2 text-[11px]">
            <span>👍 {{ $likes }}</span>
            <span>👎 {{ $dislikes }}</span>
        </div>
    </div>

    <div class="mt-2 text-sm text-slate-800">
        {!! nl2br(e($comment->content)) !!}
    </div>

    <div class="mt-3 flex flex-wrap items-center gap-3 text-xs">
        @auth
            {{-- React --}}
            <form method="POST" action="{{ route('comments.react', $comment) }}">
                @csrf
                <input type="hidden" name="type" value="like">
                <button class="px-2 py-1 rounded border border-emerald-200 text-emerald-700 hover:bg-emerald-50">
                    👍 ถูกใจ
                </button>
            </form>

            <form method="POST" action="{{ route('comments.react', $comment) }}">
                @csrf
                <input type="hidden" name="type" value="dislike">
                <button class="px-2 py-1 rounded border border-rose-200 text-rose-700 hover:bg-rose-50">
                    👎 ไม่ถูกใจ
                </button>
            </form>

            {{-- Reply --}}
            <form method="POST" action="{{ route('comments.reply', $comment) }}" class="w-full sm:w-auto">
                @csrf
                <div class="mt-2 sm:mt-0 flex gap-2">
                    <input name="content" class="flex-1 rounded border-slate-300" placeholder="ตอบกลับ...">
                    <button class="px-3 py-1 rounded bg-slate-800 text-white">ส่ง</button>
                </div>
            </form>

            {{-- Owner/Admin actions --}}
            @if(auth()->id() === ($comment->user_id ?? null) || auth()->user()?->hasRole('admin'))
                <form method="POST" action="{{ route('comments.destroy', $comment) }}"
                      onsubmit="return confirm('ลบคอมเมนต์นี้?')">
                    @csrf @method('DELETE')
                    <button class="px-2 py-1 rounded border border-red-200 text-red-700 hover:bg-red-50">
                        ลบ
                    </button>
                </form>
            @endif
        @else
            <a href="{{ route('login') }}" class="text-orange-600 hover:underline">ล็อกอิน</a>
            เพื่อโต้ตอบคอมเมนต์
        @endauth
    </div>

    {{-- replies (recursive) --}}
    @php $replies = $comment->relationLoaded('replies') ? $comment->replies : $comment->replies()->latest()->get(); @endphp
    @if($replies->count())
        <div class="mt-4 space-y-3 border-l-2 border-slate-100 pl-3">
            @foreach($replies as $reply)
                @include('posts.partials.comment', ['comment' => $reply])
            @endforeach
        </div>
    @endif
</div>
