<x-app-layout>
    <div class="max-w-5xl mx-auto py-10 px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <div class="flex items-center gap-2 text-sm text-orange-500 font-semibold">
                    <span class="w-2 h-2 rounded-full bg-orange-400"></span>
                    <span>พื้นที่แชร์ประสบการณ์ & ถาม-ตอบ</span>
                </div>

                <h1 class="mt-1 text-3xl font-bold text-slate-900">
                    กระทู้ล่าสุด
                </h1>

                <p class="text-sm text-slate-500 mt-1">
                    โพสต์ที่เห็นในหน้านี้คือกระทู้ที่ผ่านการอนุมัติแล้วจากทีมแอดมิน
                    <span class="inline-flex items-center text-emerald-500">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                  d="M16.707 5.293a1 1 0 010 1.414l-7.5 7.5a1 1 0 01-1.414 0l-3-3A1 1 0 015.207 9.793L8.5 13.086l6.793-6.793a1 1 0 011.414 0z"
                                  clip-rule="evenodd" />
                        </svg>
                        ผ่านการตรวจแล้ว
                    </span>
                </p>
            </div>

            {{-- ปุ่มตั้งกระทู้ + (เผื่ออนาคตใส่ search/filter) --}}
            <div class="flex flex-col items-stretch md:items-end gap-3 w-full md:w-auto">
                @auth
                    <a href="{{ route('posts.create') }}"
                       class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-full
                              bg-orange-500 text-white text-sm font-semibold shadow-md
                              hover:bg-orange-600 hover:-translate-y-0.5 transition">
                        <span class="text-lg leading-none">＋</span>
                        <span>ตั้งกระทู้ใหม่</span>
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-full
                              bg-slate-800 text-white text-sm font-semibold shadow-md
                              hover:bg-slate-900 hover:-translate-y-0.5 transition">
                        <span>ล็อกอินเพื่อตั้งกระทู้</span>
                    </a>
                @endauth

                {{-- ช่องค้นหา (ตอนนี้เป็น UI อย่างเดียว ต่อไปค่อยผูก logic ได้) --}}
                <div class="relative">
                    <input type="text"
                           class="w-full md:w-64 rounded-full border border-slate-200 pl-9 pr-3 py-2 text-xs
                                  focus:outline-none focus:ring-2 focus:ring-orange-500/40 focus:border-orange-400"
                           placeholder="ค้นหากระทู้ (ยังไม่ผูกระบบค้นหา)" disabled>
                    <span class="absolute left-3 top-2.5 text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z"/>
                        </svg>
                    </span>
                </div>
            </div>
        </div>

        {{-- Flash message --}}
        @if (session('success'))
            <div class="mb-4 px-4 py-3 rounded-xl bg-emerald-50 text-emerald-700 text-sm border border-emerald-100">
                {{ session('success') }}
            </div>
        @endif

        {{-- รายการกระทู้ --}}
        <div class="space-y-4">
            @forelse ($posts as $post)
                @php
                    // ใช้ค่าที่ withCount เตรียมไว้ ถ้าไม่มีให้ fallback ไปนับจากความสัมพันธ์
                    $commentsCount = $post->comments_count
                        ?? ($post->relationLoaded('comments')
                            ? $post->comments->count()
                            : $post->comments()->count());

                    // likes_count อาจมาจาก withCount หรือคำนวณจาก reactions
                    $likesCount = $post->likes_count
                        ?? ($post->relationLoaded('reactions')
                            ? $post->reactions->where('type', 'like')->count()
                            : $post->reactions()->where('type', 'like')->count());
                @endphp

                <a href="{{ route('posts.show', $post->slug) }}"
                   class="block group rounded-3xl bg-white/90 border border-slate-100 px-5 py-4
                          shadow-sm hover:shadow-md hover:-translate-y-0.5
                          transition-all duration-150">
                    <div class="flex flex-col gap-2">

                        {{-- หัวข้อ --}}
                        <div class="flex items-start justify-between gap-3">
                            <h2 class="text-base md:text-lg font-semibold text-slate-900 group-hover:text-orange-500">
                                {{ $post->title }}
                            </h2>
                        </div>

                        {{-- เนื้อหา (ตัดย่อ) --}}
                        <p class="text-sm text-slate-500 line-clamp-2">
                            {{ \Illuminate\Support\Str::limit(strip_tags($post->content), 130) }}
                        </p>

                        {{-- ผู้เขียน + เวลา + สถิติ --}}
                        <div class="mt-1 flex flex-wrap items-center justify-between gap-3 text-[11px] md:text-xs text-slate-500">
                            <div class="flex items-center gap-2">
                                <span class="font-semibold text-slate-700">
                                    โดย {{ $post->author?->name ?? 'ไม่ทราบ' }}
                                </span>
                                <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                <span>{{ $post->created_at?->diffForHumans() }}</span>
                            </div>

                            <div class="flex items-center gap-4">
                                {{-- จำนวนคอมเมนต์ --}}
                                <span class="inline-flex items-center gap-1">
                                    💬 <span>{{ $commentsCount }}</span>
                                </span>

                                {{-- ไลก์ --}}
                                <span class="inline-flex items-center gap-1 text-emerald-500">
                                    👍 <span>{{ $likesCount }}</span>
                                </span>

                                {{-- จำนวนวิว (ถ้ามี field view_count) --}}
                                @if (isset($post->view_count))
                                    <span class="inline-flex items-center gap-1">
                                        👁️ <span>{{ $post->view_count }}</span>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                {{-- กรณีไม่มีกระทู้ --}}
                <div class="mt-10 flex flex-col items-center gap-3 text-slate-400">
                    <div class="text-4xl">📝</div>
                    <div class="text-sm">ยังไม่มีกระทู้ที่เผยแพร่</div>
                    @auth
                        <a href="{{ route('posts.create') }}"
                           class="text-xs text-orange-600 hover:text-orange-500">
                            เริ่มตั้งกระทู้แรกของเว็บนี้เลย →
                        </a>
                    @endauth
                </div>
            @endforelse
        </div>

        {{-- Pagination (รองรับกรณีไม่ใช่ paginator ก็ไม่ error) --}}
        @if (isset($posts) && method_exists($posts, 'hasPages') && $posts->hasPages())
            <div class="mt-6">
                {{ $posts->onEachSide(1)->links() }}
            </div>
        @endif

    </div>
</x-app-layout>
