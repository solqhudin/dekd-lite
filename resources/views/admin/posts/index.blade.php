<x-app-layout>
    <div class="max-w-6xl mx-auto py-8 px-4">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
@extends('layouts.navigation')
@section('title','จัดการกระทู้ • Engenius Group')
@section('content')

    <div class="max-w-6xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">
                    จัดการกระทู้ <span class="text-slate-500 text-lg">(แอดมิน)</span>
                </h1>
                <p class="text-sm text-slate-500 mt-1">
                    ตรวจสอบกระทู้ที่ผู้ใช้ส่ง แล้วอนุมัติให้แสดงบนหน้าเว็บ หรือซ่อนออกได้จากตรงนี้
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('posts.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-orange-500 text-white text-sm font-medium shadow-sm hover:bg-orange-600">
                    <span class="text-lg leading-none">+</span>
                    ตั้งกระทู้ใหม่
                </a>
            </div>
        </div>

        {{-- Alert --}}
        @if(session('success'))
            <div class="mb-5 px-4 py-3 rounded-xl bg-emerald-50 text-emerald-800 text-sm border border-emerald-100">
                {{ session('success') }}
            </div>
        @endif

        {{-- Pending Posts --}}
        <section class="mb-10">
            <div class="flex items-center gap-2 mb-3">
                <h2 class="text-lg font-semibold text-slate-900">กระทู้ที่รออนุมัติ</h2>
                <span class="px-2 py-0.5 text-[10px] rounded-full bg-amber-50 text-amber-700 border border-amber-100">
                    {{ $pendingPosts->count() }} กระทู้
                </span>
            </div>

            @if($pendingPosts->isEmpty())
                <div class="px-4 py-3 rounded-xl bg-slate-50 text-slate-500 text-sm">
                    ยังไม่มีกระทู้ที่รออนุมัติ
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-slate-100">
                    <div class="grid grid-cols-12 px-5 py-3 text-[11px] font-semibold text-slate-500 uppercase tracking-wide bg-slate-50">
                        <div class="col-span-6">หัวข้อ</div>
                        <div class="col-span-2">ผู้ส่ง</div>
                        <div class="col-span-2">สร้างเมื่อ</div>
                        <div class="col-span-2 text-right">จัดการ</div>
                    </div>

                    @foreach($pendingPosts as $post)
                        <div class="grid grid-cols-12 px-5 py-3 border-t border-slate-100 items-center hover:bg-slate-50/70">
                            <div class="col-span-6">
                                <div class="font-medium text-slate-900">
                                    {{ $post->title }}
                                </div>
                                <div class="text-[11px] text-slate-500 line-clamp-1">
                                    {{ $post->content }}
                                </div>
                            </div>

                            <div class="col-span-2 text-sm text-slate-700">
                                {{ $post->author?->name ?? '-' }}
                            </div>

                            <div class="col-span-2 text-xs text-slate-500">
                                {{ $post->created_at->format('d/m/Y H:i') }}
                            </div>

                            <div class="col-span-2 flex justify-end">
                                <form method="POST" action="{{ route('admin.posts.toggle', $post) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button
                                        class="px-3 py-1.5 text-[11px] rounded-full bg-emerald-500 text-white hover:bg-emerald-600 shadow-sm">
                                        อนุมัติ & เผยแพร่
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

        {{-- Published Posts --}}
        <section>
            <div class="flex items-center gap-2 mb-3">
                <h2 class="text-lg font-semibold text-slate-900">กระทู้ที่เผยแพร่แล้ว</h2>
                <span class="px-2 py-0.5 text-[10px] rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100">
                    {{ $publishedPosts->count() }} กระทู้
                </span>
            </div>
        {{-- กระทู้ที่อนุมัติแล้ว --}}
        <div class="mb-12">
            <h2 class="text-lg font-semibold text-gray-800 mb-3">
                กระทู้ที่เผยแพร่แล้ว
            </h2>

            @if($publishedPosts->isEmpty())
                <div class="px-4 py-3 rounded-xl bg-slate-50 text-slate-500 text-sm">
                    ยังไม่มีกระทู้ที่เผยแพร่
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-slate-100">
                    <div class="grid grid-cols-12 px-5 py-3 text-[11px] font-semibold text-slate-500 uppercase tracking-wide bg-slate-50">
                        <div class="col-span-6">หัวข้อ</div>
                        <div class="col-span-2">ผู้ส่ง</div>
                        <div class="col-span-2">สร้างเมื่อ</div>
                        <div class="col-span-2 text-right">สถานะ / จัดการ</div>
                    </div>

                    @foreach($publishedPosts as $post)
                        <div class="grid grid-cols-12 px-5 py-3 border-t border-slate-100 items-center hover:bg-slate-50/70">
                            <div class="col-span-6">
                                <a href="{{ route('posts.show', $post->slug) }}"
                                   class="font-medium text-slate-900 hover:text-orange-500">
                                    {{ $post->title }}
                                </a>
                                <div class="text-[11px] text-slate-500 line-clamp-1">
                                    {{ $post->content }}
                                </div>
                            </div>

                            <div class="col-span-2 text-sm text-slate-700">
                                {{ $post->author?->name ?? '-' }}
                            </div>

                            <div class="col-span-2 text-xs text-slate-500">
                                {{ $post->created_at->format('d/m/Y H:i') }}
                            </div>

                            <div class="col-span-2 flex items-center justify-end gap-2">
                                <span class="px-2.5 py-1 text-[10px] rounded-full bg-emerald-50 text-emerald-700">
                                    เผยแพร่แล้ว
                                </span>
                                <form method="POST" action="{{ route('admin.posts.toggle', $post) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button
                                        class="px-3 py-1.5 text-[10px] rounded-full bg-rose-50 text-rose-600 border border-rose-200 hover:bg-rose-100">
                                        ซ่อนจากหน้าเว็บ
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>
        </div>

        {{-- ===================== ประชาสัมพันธ์ (ใหม่) ===================== --}}

        {{-- ประกาศที่รอเผยแพร่ --}}
        <div class="mb-8">
            <div class="mb-3 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-800">ประกาศที่รอเผยแพร่</h2>
                <a href="{{ route('admin.announcements.index') }}"
                   class="text-xs text-brand-700 hover:underline">ไปหน้าจัดการประกาศ</a>
            </div>

            @if($annPending->isEmpty())
                <div class="px-4 py-3 rounded-xl bg-gray-50 text-gray-500 text-sm">
                    ยังไม่มีประกาศที่รอเผยแพร่
                </div>
            @else
                <div class="grid sm:grid-cols-2 gap-3">
                    @foreach($annPending as $a)
                        <div class="p-4 rounded-2xl bg-white border border-amber-200 shadow-sm">
                            <div class="font-semibold text-gray-900">{{ $a->title }}</div>
                            <div class="text-xs text-gray-500 mt-0.5">
                                หมวด: {{ $a->category ?: '-' }}
                                · สร้างเมื่อ {{ $a->created_at->format('d/m/Y H:i') }}
                            </div>
                            <div class="mt-3 flex items-center gap-2">
                                <a href="{{ route('admin.announcements.edit', $a) }}"
                                   class="px-3 py-1.5 text-xs rounded-xl border border-gray-200 text-gray-700 hover:bg-gray-50">
                                    แก้ไข
                                </a>
                                {{-- ปุ่มดูหน้า public จะซ่อนไว้เพราะยังไม่เผยแพร่ --}}
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ประกาศที่เผยแพร่แล้ว --}}
        <div class="mb-2">
            <h2 class="text-lg font-semibold text-gray-800 mb-3">
                ประกาศที่เผยแพร่แล้ว
            </h2>

            @if($annPublished->isEmpty())
                <div class="px-4 py-3 rounded-xl bg-gray-50 text-gray-500 text-sm">
                    ยังไม่มีประกาศที่เผยแพร่
                </div>
            @else
                <div class="overflow-x-auto rounded-2xl border border-gray-100 bg-white shadow-sm">
                    <table class="min-w-full text-xs">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500">
                                <th class="px-4 py-2 text-left">หัวข้อ</th>
                                <th class="px-4 py-2 text-left w-40">หมวด</th>
                                <th class="px-4 py-2 text-left w-40">เผยแพร่เมื่อ</th>
                                <th class="px-4 py-2 text-left w-32">การจัดการ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($annPublished as $a)
                                <tr class="hover:bg-gray-50/60">
                                    <td class="px-4 py-2">
                                        <a href="{{ route('announcements.show', $a->slug) }}" target="_blank"
                                           class="text-gray-900 hover:text-brand-700 font-medium">
                                           {{ $a->title }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-2 text-gray-700">{{ $a->category ?: '-' }}</td>
                                    <td class="px-4 py-2 text-gray-500">
                                        {{ optional($a->published_at)->format('d/m/Y H:i') ?: '-' }}
                                    </td>
                                    {{-- แก้ไข --}}
                                    <td class="px-4 py-2">
                                        <a href="{{ route('admin.announcements.edit', $a) }}"
                                           class="px-3 py-1.5 text-xs rounded-xl border border-gray-200 text-gray-700 hover:bg-gray-50">
                                           แก้ไข
                                        </a>
                                    </td>
                                    {{-- ลบ --}}
                                    <td class="px-4 py-2">
                                        <form action="{{ route('admin.announcements.destroy', $a) }}" method="POST" onsubmit="return confirm('ยืนยันการลบประกาศนี้?');">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="px-3 py-1.5 text-xs rounded-xl border border-red-200 text-red-700 hover:bg-red-50">
                                                ลบ
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{-- ใช้ชื่อพารามิเตอร์ announcements_page เพื่อไม่ชนกับ pagination ของโพสต์ --}}
                    {{ $annPublished->appends(request()->except('announcements_page'))->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
