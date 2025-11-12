<x-app-layout>
    <div class="max-w-6xl mx-auto px-4 py-10">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-extrabold text-gray-900">จัดการประกาศ</h1>
            <a href="{{ route('admin.announcements.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-orange-500 text-white text-sm font-semibold hover:bg-orange-600">
                + สร้างประกาศ
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 px-4 py-3 rounded-xl bg-emerald-50 text-emerald-700 text-sm border border-emerald-100">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                <tr class="text-left text-xs font-semibold text-gray-500">
                    <th class="px-4 py-3">หัวข้อ</th>
                    <th class="px-4 py-3 w-32">หมวด</th>
                    <th class="px-4 py-3 w-40">สถานะ</th>
                    <th class="px-4 py-3 w-40">อัปเดต</th>
                    <th class="px-4 py-3 w-36"></th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($announcements as $a)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="font-semibold text-gray-900">{{ $a->title }}</div>
                            <div class="text-xs text-gray-500">slug: {{ $a->slug }}</div>
                        </td>
                        <td class="px-4 py-3">{{ $a->category ?: 'ทั่วไป' }}</td>
                        <td class="px-4 py-3">
                            @if($a->is_published)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                    เผยแพร่
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                    ฉบับร่าง
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-500">
                            {{ $a->updated_at?->format('Y-m-d H:i') }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('announcements.show', $a->slug) }}" class="text-xs text-gray-600 hover:text-gray-900 mr-3">ดูหน้า public</a>
                            <a href="{{ route('admin.announcements.edit', $a) }}" class="text-xs text-indigo-600 hover:text-indigo-800 mr-3">แก้ไข</a>
                            <form class="inline" method="POST" action="{{ route('admin.announcements.destroy', $a) }}"
                                  onsubmit="return confirm('ลบประกาศนี้แน่ไหม?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-red-600 hover:text-red-700">ลบ</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td class="px-4 py-6 text-center text-gray-400" colspan="5">ยังไม่มีประกาศ</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($announcements, 'hasPages') && $announcements->hasPages())
            <div class="mt-6">
                {{ $announcements->onEachSide(1)->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
