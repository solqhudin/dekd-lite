{{-- @extends('layouts.navigation')
@section('title','ประชาสัมพันธ์ • Engenius Group')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-10">
  <div class="flex items-end justify-between gap-3 mb-6">
    <div>
      <h1 class="text-2xl font-extrabold text-gray-900">ประชาสัมพันธ์</h1>
      <p class="text-sm text-gray-500">ข่าว/กิจกรรมที่เผยแพร่แล้ว</p>
    </div>
    <form method="GET" action="{{ route('announcements.index') }}" class="relative">
      <input name="q" value="{{ request('q') }}" placeholder="ค้นหา..."
             class="w-64 rounded-full border border-gray-200 px-4 py-2 text-sm focus:border-brand-700 focus:ring-2 focus:ring-brand-700/20">
    </form>
  </div>

  @if($announcements->count())
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
      @foreach($announcements as $a)
        <a href="{{ route('announcements.show', $a->slug) }}"
           class="block p-4 rounded-2xl border border-gray-100 bg-white shadow-[0_6px_18px_rgba(0,0,0,.05)] hover:-translate-y-0.5 hover:shadow-[0_12px_30px_rgba(0,0,0,.08)] transition">
          @if($a->cover_image)
            <img src="{{ asset('storage/'.$a->cover_image) }}"
                 class="w-full h-36 object-cover rounded-xl mb-3" alt="">
          @endif
          <div class="text-xs text-gray-500">{{ $a->category ?: 'ทั่วไป' }}</div>
          <h2 class="mt-1 font-semibold text-gray-900 line-clamp-2">{{ $a->title }}</h2>
          <p class="mt-1 text-sm text-gray-600 line-clamp-2">{{ $a->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($a->body), 120) }}</p>
          <div class="mt-2 text-xs text-gray-400">
            เผยแพร่: {{ optional($a->published_at)->format('Y-m-d H:i') ?: '-' }}
          </div>
        </a>
      @endforeach
    </div>

    <div class="mt-6">{{ $announcements->links() }}</div>
  @else
    <div class="mt-10 text-center text-gray-500">ยังไม่มีประกาศ</div>
  @endif
</div>
@endsection --}}
