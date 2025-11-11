@props([
    'comment',
    'currentUser' => null,
    'isChild' => false,
])

@php
    /** @var \App\Models\Comment $comment */

    $currentUser = $currentUser ?? Auth::user();

    $userReaction = $currentUser
        ? $comment->reactions->firstWhere('user_id', $currentUser->id)
        : null;

    $likesCount = $comment->reactions->where('type', 'like')->count();
    $dislikesCount = $comment->reactions->where('type', 'dislike')->count();

    $canManage = $currentUser
        && ($currentUser->id === $comment->user_id || $currentUser->hasRole('admin'));

    // margin สำหรับ reply
    $wrapperClasses = $isChild ? 'ml-8 mt-3' : 'mt-4';
@endphp

<div class="{{ $wrapperClasses }}" x-data="{ showReply: false, showEdit: false }">
    <div class="bg-white {{ $isChild ? 'border border-gray-100 rounded-2xl px-3.5 py-2.5 shadow-sm' : 'border border-gray-100 rounded-2xl p-4 shadow-sm' }}">
        <div class="flex items-start gap-3">

            {{-- Avatar --}}
            <div
                class="{{ $isChild ? 'w-8 h-8 text-[10px]' : 'w-9 h-9 text-xs' }} rounded-full bg-slate-100 flex items-center justify-center font-semibold text-slate-600">
                {{ mb_substr($comment->user->name, 0, 1) }}
            </div>

            <div class="flex-1">

                {{-- Header --}}
                <div class="flex items-center gap-2 text-[10px] text-slate-500">
                    <span class="font-semibold text-slate-800">
                        {{ $comment->user->name }}
                    </span>
                    <span>• {{ $comment->created_at->diffForHumans() }}</span>
                </div>

                {{-- เนื้อหาคอมเมนต์ (ปกติ) --}}
                <div x-show="!showEdit" class="mt-1 text-sm text-slate-800 whitespace-pre-line">
                    {{ $comment->content }}
                </div>

                {{-- ฟอร์มแก้ไขคอมเมนต์ --}}
                @auth
                    @if ($canManage)
                        <form x-show="showEdit"
                              x-cloak
                              method="POST"
                              action="{{ route('comments.update', $comment) }}"
                              class="mt-2 space-y-2">
                            @csrf
                            @method('PUT')
                            <textarea
                                name="content"
                                rows="2"
                                class="w-full rounded-xl border-gray-200 focus:border-orange-400 focus:ring-orange-300 text-xs">{{ $comment->content }}</textarea>
                            <div class="flex justify-end gap-2">
                                <button type="button"
                                        @click="showEdit = false"
                                        class="px-3 py-1.5 rounded-full border text-xs text-slate-500 hover:bg-slate-50">
                                    ยกเลิก
                                </button>
                                <button type="submit"
                                        class="px-3 py-1.5 rounded-full bg-orange-500 text-white text-xs hover:bg-orange-600">
                                    บันทึก
                                </button>
                            </div>
                        </form>
                    @endif
                @endauth

                {{-- Action bar: like / dislike / reply / edit / delete --}}
                <div class="mt-2 flex flex-wrap items-center gap-3 text-[10px] text-slate-500">

                    {{-- Like --}}
                    <form action="{{ route('comments.react', $comment) }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="type" value="like">
                        <button type="submit"
                                class="inline-flex items-center gap-1
                                {{ $userReaction?->type === 'like'
                                    ? 'text-orange-500'
                                    : 'hover:text-orange-500' }}">
                            👍 <span>{{ $likesCount }}</span>
                        </button>
                    </form>

                    {{-- Dislike --}}
                    <form action="{{ route('comments.react', $comment) }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="type" value="dislike">
                        <button type="submit"
                                class="inline-flex items-center gap-1
                                {{ $userReaction?->type === 'dislike'
                                    ? 'text-red-500'
                                    : 'hover:text-red-500' }}">
                            👎 <span>{{ $dislikesCount }}</span>
                        </button>
                    </form>

                    @auth
                        {{-- ตอบกลับ --}}
                        <button type="button"
                                @click="showReply = !showReply; showEdit = false"
                                class="hover:text-orange-500">
                            ตอบกลับ
                        </button>

                        {{-- แก้ไข / ลบ (เจ้าของหรือแอดมิน) --}}
                        @if ($canManage)
                            <button type="button"
                                    @click="showEdit = !showEdit; showReply = false"
                                    class="hover:text-sky-500">
                                แก้ไข
                            </button>

                            <form action="{{ route('comments.destroy', $comment) }}"
                                  method="POST"
                                  class="inline"
                                  onsubmit="return confirm('ลบความคิดเห็นนี้แน่ใจไหม?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="text-red-400 hover:text-red-600">
                                    ลบ
                                </button>
                            </form>
                        @endif
                    @endauth
                </div>

                {{-- ฟอร์มตอบกลับ --}}
                @auth
                    <form x-show="showReply"
                          x-cloak
                          action="{{ route('comments.reply', $comment) }}"
                          method="POST"
                          class="mt-3">
                        @csrf
                        <textarea
                            name="content"
                            rows="2"
                            class="w-full rounded-xl border-gray-200 focus:border-orange-400 focus:ring-orange-300 text-xs"
                            placeholder="ตอบกลับความคิดเห็นนี้..."></textarea>
                        <div class="mt-2 flex justify-end">
                            <button type="submit"
                                    class="px-3 py-1.5 rounded-full bg-slate-800 text-white text-xs hover:bg-black">
                                ส่งคำตอบ
                            </button>
                        </div>
                    </form>
                @endauth

                {{-- Replies (recursive) --}}
                @if ($comment->replies && $comment->replies->count())
                    <div class="mt-3 space-y-2">
                        @foreach ($comment->replies as $reply)
                            @include('posts.partials.comment', [
                                'comment' => $reply,
                                'currentUser' => $currentUser,
                                'isChild' => true,
                            ])
                        @endforeach
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
