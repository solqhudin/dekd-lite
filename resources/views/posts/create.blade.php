{{-- resources/views/posts/create.blade.php --}}
@extends('layouts.navigation')
@section('title','ตั้งกระทู้ใหม่ • Engenius Group')

@section('content')
  <div class="flex items-center justify-center px-4 py-10">
    <div class="w-full max-w-3xl rounded-[20px] bg-white shadow-card ring-1 ring-black/5 overflow-hidden">

      {{-- หัวการ์ด --}}
      <div class="px-6 md:px-8 py-6 border-b border-gray-100 flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-extrabold text-gray-900">ตั้งกระทู้ใหม่</h1>
          <p class="text-sm text-gray-600 mt-1">แชร์ไอเดีย/คำถามของคุณให้ทุกคนได้เห็น</p>
        </div>
        <img src="{{ asset('images/logo.png') }}" alt="Engenius Group" class="h-8 w-auto object-contain hidden sm:block">
      </div>

      {{-- เนื้อหา --}}
      <div class="px-6 md:px-8 py-8">
        {{-- สรุปข้อผิดพลาดรวม --}}
        @if ($errors->any())
          <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700">
            กรุณาตรวจสอบข้อมูลที่กรอกให้ถูกต้อง
          </div>
        @endif

        <form method="POST" action="{{ route('posts.store') }}" class="grid gap-5">
          @csrf

          {{-- หัวข้อ --}}
          <div>
            <label for="title" class="block text-sm font-medium text-gray-700">หัวข้อ</label>
            <input
              id="title"
              type="text"
              name="title"
              value="{{ old('title') }}"
              required
              class="mt-1 w-full rounded-2xl border border-gray-200 px-4 py-3 bg-gray-50
                     focus:bg-white focus:border-brand-700 focus:ring-2 focus:ring-brand-700/20 outline-none transition" />
            @error('title')
              <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
          </div>

          {{-- เนื้อหา --}}
          <div>
            <div class="flex items-center justify-between">
              <label for="content" class="block text-sm font-medium text-gray-700">เนื้อหา</label>
              <span class="text-xs text-gray-400">รองรับ Markdown เบื้องต้น</span>
            </div>
            <textarea
              id="content"
              name="content"
              rows="10"
              required
              class="mt-1 w-full rounded-2xl border border-gray-200 px-4 py-3 bg-gray-50
                     focus:bg-white focus:border-brand-700 focus:ring-2 focus:ring-brand-700/20 outline-none transition">{{ old('content') }}</textarea>
            @error('content')
              <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror

            <p class="mt-2 text-xs text-gray-500">
              * กระทู้ของคุณจะถูกส่งให้แอดมินตรวจสอบก่อนแสดงบนหน้าเว็บ
            </p>
          </div>

          {{-- ปุ่มแอ็กชัน --}}
          <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ url()->previous() }}"
               class="px-5 py-2.5 rounded-full border border-gray-300 text-gray-700 hover:border-brand-700/50">
              ยกเลิก
            </a>
            <button
              class="px-5 py-2.5 rounded-full bg-brand-700 text-white font-semibold hover:brightness-110
                     focus:outline-none focus:ring-2 focus:ring-brand-700/30">
              ส่งกระทู้
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
