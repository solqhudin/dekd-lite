@extends('layouts.navigation')
@section('title','กระทู้ล่าสุด • Engenius Group')

@section('content')
  <div class="bg-brand-50/40 min-h-[calc(100vh-4rem)]">
    <div class="max-w-5xl mx-auto py-10 px-4 sm:px-6 lg:px-8">

      {{-- Header --}}
      <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
        <div>
          <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand-700/8 text-brand-700 text-xs font-semibold ring-1 ring-brand-700/15">
            <span class="h-1.5 w-1.5 rounded-full bg-brand-700/90"></span>
            พื้นที่แชร์ประสบการณ์ & ถาม-ตอบ
          </div>
          <h1 class="mt-3 text-2xl sm:text-3xl font-bold text-gray-900">กระทู้ล่าสุด</h1>
          <p class="mt-1 text-sm text-gray-500">โพสต์ที่เห็นในหน้านี้คือกระทู้ที่ผ่านการอนุมัติแล้วจากทีมแอดมิน ✅</p>
        </div>

        <div class="flex flex-col items-end gap-2 w-full sm:w-auto">
          @auth
            <a href="{{ route('posts.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-full
                      bg-brand-700 text-white text-sm font-semibold shadow-sm
                      hover:brightness-110 transition">
              <span class="text-lg leading-none">＋</span>
              ตั้งกระทู้ใหม่
            </a>
          @else
            <a href="{{ route('login') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-full
                      bg-slate-800 text-white text-sm font-semibold shadow-sm
                      hover:bg-slate-900 transition">
              ล็อกอินเพื่อตั้งกระทู้
            </a>
          @endauth

          <form method="GET" action="{{ route('posts.index') }}" class="relative w-full sm:w-64">
            <input type="text" name="q" value="{{ request('q') }}"
                   placeholder="ค้นหากระทู้"
                   class="w-full pl-9 pr-3 py-1.5 text-xs rounded-full border border-gray-200
                          bg-white/90 placeholder:text-gray-400 focus:outline-none focus:ring-2
                          focus:ring-brand-700/30 focus:border-brand-700/50">
            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 text-xs">🔍</span>
          </form>
        </div>
      </div>

      {{-- Flash message --}}
      @if(session('success'))
        <div class="mb-6 px-4 py-3 rounded-xl bg-emerald-50 text-emerald-700 text-sm border border-emerald-100">
          {{ session('success') }}
        </div>
      @endif

      {{-- Posts list --}}
      @if($posts->count())
        <div class="space-y-3">
          @foreach($posts as $post)
            <a href="{{ route('posts.show', $post->slug) }}" class="block group">
              <div class="flex flex-col gap-1.5 p-4 rounded-2xl border border-gray-100 bg-white
                          shadow-[0_4px_14px_rgba(2,2,99,0.04)]
                          hover:-translate-y-0.5 hover:shadow-[0_10px_30px_rgba(2,2,99,0.10)]
                          hover:border-brand-700/20 transition">
                <div class="flex items-center justify-between gap-3">
                  <h2 class="text-sm sm:text-base font-semibold text-gray-900 group-hover:text-brand-700 line-clamp-1">
                    {{ $post->title }}
                  </h2>
                  @if(!$post->is_published)
                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-gray-100 text-gray-500">
                      รอแอดมินอนุมัติ
                    </span>
                  @endif
                </div>

                <p class="text-xs text-gray-500 line-clamp-2">
                  {{ \Illuminate\Support\Str::limit(strip_tags($post->content), 140) }}
                </p>

                <div class="flex items-center justify-between text-[10px] text-gray-400 mt-1.5">
                  <div>
                    โดย
                    <span class="font-medium text-gray-700">
                      {{ $post->author?->name ?? 'ไม่ระบุผู้เขียน' }}
                    </span>
                    · {{ $post->created_at->diffForHumans() }}
                  </div>
                  <div class="flex items-center gap-3">
                    <span class="flex items-center gap-1">
                      💬 <span>{{ $post->comments_count ?? 0 }}</span>
                    </span>
                    <span class="flex items-center gap-1">
                      👍 <span>{{ $post->likes_count ?? 0 }}</span>
                    </span>
                    <span class="flex items-center gap-1">
                      👁️ <span>{{ $post->views ?? 0 }}</span>
                    </span>
                  </div>
                </div>
              </div>
            </a>
          @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
          {{ $posts->onEachSide(1)->links() }}
        </div>
      @else
        <div class="mt-10 flex flex-col items-center gap-3 text-gray-400">
          <div class="text-4xl">📝</div>
          <div class="text-sm">ยังไม่มีกระทู้ที่เผยแพร่</div>
          @auth
            <a href="{{ route('posts.create') }}" class="text-xs text-brand-700 hover:text-brand-700/80">
              เริ่มตั้งกระทู้แรกของเว็บนี้เลย →
            </a>
          @endauth
        </div>
      @endif
    </div>
  </div>
@endsection