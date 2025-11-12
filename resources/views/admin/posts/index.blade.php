<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-10">

        {{-- Header --}}
        <div class="flex items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900">จัดการกระทู้</h1>
                <p class="text-sm text-gray-500">สำหรับแอดมินเท่านั้น</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('posts.index') }}" class="text-sm text-gray-600 hover:text-gray-900">ดูหน้าสาธารณะ</a>
            </div>
        </div>

        {{-- Flash --}}
        @if (session('success'))
            <div class="mb-4 px-4 py-3 rounded-xl bg-emerald-50 text-emerald-700 text-sm border border-emerald-100">
                {{ session('success') }}
            </div>
        @endif

        {{-- สรุปจำนวน --}}
        <div class="grid sm:grid-cols-2 gap-4 mb-6">
            <div class="rounded-2xl border border-gray-100 bg-white p-4">
                <div class="text-sm text-gray-500 mb-1">รออนุมัติ</div>
                <div class="text-2xl font-extrabold text-gray-900">{{ $pending->total() }}</div>
            </div>
            <div class="rounded-2xl border border-gray-100 bg-white p-4">
                <div class="text-sm text-gray-500 mb-1">เผยแพร่แล้ว</div>
                <div class="text-2xl font-extrabold text-gray-900">{{ $published->total() }}</div>
            </div>
        </div>

        {{-- รออนุมัติ --}}
        <div class="rounded-2xl overflow-hidden border border-gray-100 bg-white shadow-sm mb-8">
            <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-semibold text-gray-900">รายการรออนุมัติ</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr class="text-left text-xs font-semibold text-gray-500">
                            <th class="px-4 py-3 w-[48px]">#</th>
                            <th class="px-4 py-3">หัวข้อ</th>
                            <th class="px-4 py-3 w-48">ผู้เขียน</th>
                            <th class="px-4 py-3 w-40">สร้างเมื่อ</th>
                            <th class="px-4 py-3 w-48"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse ($pending as $p)
                            <tr>
                                <td class="px-4 py-3 text-xs text-gray-500">#{{ $p->id }}</td>
                                <td class="px-4 py-3">
                                    <div class="font-semibold text-gray-900">{{ $p->title }}</div>
                                    <div class="text-xs text-gray-500">slug: {{ $p->slug }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    {{ $p->author?->name ?? 'ไม่ทราบ' }}
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-500">
                                    {{ $p->created_at?->format('Y-m-d H:i') }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    {{-- ดูหน้า public (จะ 404 จนกว่าจะอนุมัติ) --}}
                                    <a href="{{ route('posts.show', $p->slug) }}"
                                       class="text-xs text-gray-600 hover:text-gray-900 mr-3">ดู</a>

                                    {{-- อนุมัติ --}}
                                    <form class="inline" method="POST" action="{{ route('admin.posts.approve', $p) }}">
                                        @csrf
                                        <button class="text-xs text-emerald-700 hover:text-emerald-800 mr-3">
                                            อนุมัติ
                                        </button>
                                    </form>

                                    {{-- ลบ --}}
                                    <form class="inline" method="POST" action="{{ route('admin.posts.destroy', $p) }}"
                                          onsubmit="return confirm('ลบโพสต์นี้แน่ไหม?')">
                                        @csrf @method('DELETE')
                                        <button class="text-xs text-red-600 hover:text-red-700">
                                            ลบ
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-gray-400">
                                    ไม่มีรายการรออนุมัติ
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (method_exists($pending, 'hasPages') && $pending->hasPages())
                <div class="px-4 py-3 border-t border-gray-100">
                    {{ $pending->appends(request()->except('pending_page'))->onEachSide(1)->links() }}
                </div>
            @endif
        </div>

        {{-- เผยแพร่แล้ว --}}
        <div class="rounded-2xl overflow-hidden border border-gray-100 bg-white shadow-sm">
            <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-semibold text-gray-900">รายการเผยแพร่แล้ว</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr class="text-left text-xs font-semibold text-gray-500">
                            <th class="px-4 py-3 w-[48px]">#</th>
                            <th class="px-4 py-3">หัวข้อ</th>
                            <th class="px-4 py-3 w-48">ผู้เขียน</th>
                            <th class="px-4 py-3 w-40">สถิติ</th>
                            <th class="px-4 py-3 w-40">อัปเดต</th>
                            <th class="px-4 py-3 w-56"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse ($published as $p)
                            <tr>
                                <td class="px-4 py-3 text-xs text-gray-500">#{{ $p->id }}</td>
                                <td class="px-4 py-3">
                                    <div class="font-semibold text-gray-900">{{ $p->title }}</div>
                                    <div class="text-xs text-gray-500">slug: {{ $p->slug }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    {{ $p->author?->name ?? 'ไม่ทราบ' }}
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-700">
                                    👍 {{ $p->likes_count ?? 0 }}
                                    <span class="mx-1 text-gray-300">|</span>
                                    💬 {{ $p->comments_count ?? 0 }}
                                    @if(isset($p->view_count))
                                        <span class="mx-1 text-gray-300">|</span>
                                        👁️ {{ $p->view_count }}
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-500">
                                    {{ $p->updated_at?->format('Y-m-d H:i') }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('posts.show', $p->slug) }}"
                                       class="text-xs text-gray-600 hover:text-gray-900 mr-3">ดูหน้า public</a>

                                    {{-- toggle สถานะ (ปิดการเผยแพร่) --}}
                                    <form class="inline" method="POST" action="{{ route('admin.posts.toggle', $p) }}">
                                        @csrf @method('PATCH')
                                        <button class="text-xs text-amber-700 hover:text-amber-800 mr-3">
                                            ปิดการเผยแพร่
                                        </button>
                                    </form>

                                    {{-- ลบ --}}
                                    <form class="inline" method="POST" action="{{ route('admin.posts.destroy', $p) }}"
                                          onsubmit="return confirm('ลบโพสต์นี้แน่ไหม?')">
                                        @csrf @method('DELETE')
                                        <button class="text-xs text-red-600 hover:text-red-700">
                                            ลบ
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-gray-400">
                                    ยังไม่มีโพสต์ที่เผยแพร่
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (method_exists($published, 'hasPages') && $published->hasPages())
                <div class="px-4 py-3 border-t border-gray-100">
                    {{ $published->appends(request()->except('published_page'))->onEachSide(1)->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
