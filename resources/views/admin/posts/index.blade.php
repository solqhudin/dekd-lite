<x-app-layout>
    <div class="max-w-6xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    จัดการกระทู้ (แอดมิน)
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    ตรวจสอบกระทู้ที่ผู้ใช้ตั้ง แล้วกดอนุมัติให้แสดงหน้าเว็บได้จากหน้านี้
                </p>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 px-4 py-3 rounded-xl bg-green-50 text-green-800 text-sm border border-green-100">
                {{ session('success') }}
            </div>
        @endif

        {{-- กระทู้ที่รออนุมัติ --}}
        <div class="mb-8">
            <h2 class="text-lg font-semibold text-gray-800 mb-3">
                กระทู้ที่รออนุมัติ
            </h2>

            @if($pending->isEmpty())
                <div class="px-4 py-3 rounded-xl bg-gray-50 text-gray-500 text-sm">
                    ยังไม่มีกระทู้ที่รออนุมัติ
                </div>
            @else
                <div class="space-y-3">
                    @foreach($pending as $post)
                        <div class="p-4 rounded-2xl bg-white border border-yellow-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div>
                                <div class="font-semibold text-gray-900">
                                    {{ $post->title }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    โดย {{ $post->author?->name ?? 'ไม่ทราบ' }}
                                    · {{ $post->created_at->diffForHumans() }}
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <a href="{{ route('posts.show', $post->slug) }}"
                                   class="px-3 py-1.5 text-xs rounded-xl border border-gray-200 text-gray-700 hover:bg-gray-50">
                                    ดูหน้า public
                                </a>

                                <form action="{{ route('admin.posts.approve', $post) }}" method="POST">
                                    @csrf
                                    <button
                                        type="submit"
                                        class="px-4 py-1.5 text-xs font-semibold rounded-xl
                                               bg-emerald-500 text-white hover:bg-emerald-600
                                               shadow-sm">
                                        อนุมัติ
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- กระทู้ที่อนุมัติแล้ว --}}
        <div>
            <h2 class="text-lg font-semibold text-gray-800 mb-3">
                กระทู้ที่เผยแพร่แล้ว
            </h2>

            @if($published->isEmpty())
                <div class="px-4 py-3 rounded-xl bg-gray-50 text-gray-500 text-sm">
                    ยังไม่มีกระทู้ที่เผยแพร่
                </div>
            @else
                <div class="overflow-x-auto rounded-2xl border border-gray-100 bg-white shadow-sm">
                    <table class="min-w-full text-xs">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500">
                                <th class="px-4 py-2 text-left">หัวข้อ</th>
                                <th class="px-4 py-2 text-left w-40">ผู้ตั้ง</th>
                                <th class="px-4 py-2 text-left w-32">สถานะ</th>
                                <th class="px-4 py-2 text-left w-40">สร้างเมื่อ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($published as $post)
                                <tr class="hover:bg-gray-50/60">
                                    <td class="px-4 py-2">
                                        <a href="{{ route('posts.show', $post->slug) }}"
                                           class="text-gray-900 hover:text-orange-600 font-medium">
                                            {{ $post->title }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-2 text-gray-700">
                                        {{ $post->author?->name ?? '-' }}
                                    </td>
                                    <td class="px-4 py-2">
                                        <span class="inline-flex items-center px-2 py-0.5 text-[10px] rounded-full bg-emerald-50 text-emerald-700">
                                            เผยแพร่แล้ว
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 text-gray-500">
                                        {{ $post->created_at->format('d/m/Y H:i') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $published->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
