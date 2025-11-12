@extends('layouts.navigation')
@section('title','แก้ไขประกาศ • Engenius Group')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-10">
  <div class="rounded-2xl bg-white ring-1 ring-black/5 shadow-card overflow-hidden">
    <div class="px-6 md:px-8 py-6 border-b border-gray-100">
      <h1 class="text-2xl font-extrabold text-gray-900">แก้ไขประกาศ</h1>
    </div>

    <div class="px-6 md:px-8 py-8">
      @if ($errors->any())
        <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700">
          กรุณาตรวจสอบข้อมูลที่กรอก
        </div>
      @endif

      <form method="POST" action="{{ route('admin.announcements.update', $announcement) }}" enctype="multipart/form-data" class="grid gap-5">
        @csrf @method('PUT')

        {{-- <div>
          <label class="block text-sm font-medium text-gray-700">Slug (ไม่บังคับ)</label>
          <input name="slug" value="{{ old('slug', $announcement->slug) }}"
                 class="mt-1 w-full rounded-2xl border border-gray-200 px-4 py-3 bg-gray-50 focus:bg-white focus:border-brand-700 focus:ring-2 focus:ring-brand-700/20 outline-none">
          <p class="text-xs text-gray-400 mt-1">ปล่อยว่างเพื่อตามเดิม</p>
        </div> --}}

        <div>
          <label class="block text-sm font-medium text-gray-700">หัวข้อ</label>
          <input name="title" value="{{ old('title', $announcement->title) }}" required
                 class="mt-1 w-full rounded-2xl border border-gray-200 px-4 py-3 bg-gray-50 focus:bg-white focus:border-brand-700 focus:ring-2 focus:ring-brand-700/20 outline-none">
        </div>

        <div class="grid sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">หมวดหมู่</label>
            <input name="category" value="{{ old('category', $announcement->category) }}"
                   class="mt-1 w-full rounded-2xl border border-gray-200 px-4 py-3 bg-gray-50 focus:bg-white focus:border-brand-700 focus:ring-2 focus:ring-brand-700/20 outline-none">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">ภาพหน้าปก (อัปโหลดใหม่เพื่อแทนที่)</label>
            <input type="file" name="cover_image" accept="image/*"
                   class="mt-1 block w-full text-sm file:mr-3 file:px-3 file:py-2 file:rounded-full file:border-0 file:bg-brand-700 file:text-white hover:file:brightness-110" />
            @if($announcement->cover_image)
              <img src="{{ Storage::url($announcement->cover_image) }}" class="mt-2 h-24 rounded-lg object-cover">
            @endif
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">คำโปรย</label>
          <textarea name="excerpt" rows="2"
                    class="mt-1 w-full rounded-2xl border border-gray-200 px-4 py-3 bg-gray-50 focus:bg-white focus:border-brand-700 focus:ring-2 focus:ring-brand-700/20 outline-none">{{ old('excerpt', $announcement->excerpt) }}</textarea>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">เนื้อหา</label>
          <textarea name="body" rows="10" required
                    class="mt-1 w-full rounded-2xl border border-gray-200 px-4 py-3 bg-gray-50 focus:bg-white focus:border-brand-700 focus:ring-2 focus:ring-brand-700/20 outline-none">{{ old('body', $announcement->body) }}</textarea>
        </div>

        <div class="flex items-center justify-between">
          <label class="inline-flex items-center gap-2 text-sm text-gray-700">
            <input type="checkbox" name="is_published" value="1" {{ old('is_published', $announcement->is_published) ? 'checked' : '' }}
                   class="rounded border-gray-300 text-brand-700 focus:ring-brand-700">
            เผยแพร่
          </label>
          <button class="px-6 py-3 rounded-full bg-brand-700 text-white hover:brightness-110 focus:outline-none focus:ring-2 focus:ring-brand-700/30">
            บันทึก
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
