<x-app-layout>
    <div class="max-w-6xl mx-auto px-4 py-10">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-6">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900">ประชาสัมพันธ์</h1>
                <p class="text-sm text-gray-500">ข่าว/กิจกรรมที่เผยแพร่แล้ว</p>
            </div>

            <div class="flex items-center gap-2 sm:gap-3">
                @auth
                    @can('manage-announcements')
                        <a href="{{ route('admin.announcements.create') }}"
                           class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-orange-500 text-white text-sm font-semibold hover:bg-orange-600">
                            + สร้างประกาศ
                        </a>
                    @endcan
                @endauth

                <form method="GET" action="{{ route('announcements.index') }}" class="relative">
                    <input name="q" value="{{ request('q') }}" placeholder="ค้นหา..."
                           class="w-64 rounded-full border border-gray-200 pl-9 pr-3 py-2 text-sm focus:outline-none focus:border-orange-400 focus:ring-2 focus:ring-orange-500/20">
                    <span class="absolute left-3 top-2.5 text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z"/>
                        </svg>
                    </span>
                </form>
            </div>
        </div>

        @if($announcements->count())
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($announcements as $a)
                    <a href="{{ route('announcements.show', $a->slug) }}"
                       class="block p-4 rounded-2xl border border-gray-100 bg-white shadow-sm hover:shadow-md hover:-translate-y-0.5 transition">
                        @if($a->cover_image)
                            <img src="{{ asset('storage/'.$a->cover_image) }}" class="rounded-xl w-full h-40 object-cover mb-3" alt="">
                        @endif
                        <div class="text-xs text-gray-500 mb-1">
                            {{ $a->category ?: 'ทั่วไป' }}
                            @if($a->published_at) · {{ $a->published_at->format('Y-m-d H:i') }} @endif
                        </div>
                        <h2 class="font-semibold text-gray-900">
                            {{ $a->title }}
                        </h2>
                        @if($a->excerpt)
                            <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ $a->excerpt }}</p>
                        @endif
                    </a>
                @endforeach
            </div>

            @if(method_exists($announcements, 'hasPages') && $announcements->hasPages())
                <div class="mt-6">
                    {{ $announcements->onEachSide(1)->links() }}
                </div>
            @endif
        @else
            <div class="mt-10 text-center text-gray-400">
                ยังไม่มีประกาศที่เผยแพร่
            </div>
        @endif
    </div>
</x-app-layout>
