@extends('layouts.navigation')
@section('title','เพิ่มประกาศ • Engenius Group')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-10">
  <div class="rounded-2xl bg-white ring-1 ring-black/5 shadow-card overflow-hidden">
    <div class="px-6 md:px-8 py-6 border-b border-gray-100">
      <h1 class="text-2xl font-extrabold text-gray-900">เพิ่มประกาศ</h1>
      <p class="text-sm text-gray-600 mt-1">แอดมินโพสต์ข่าว/กิจกรรมของบริษัท</p>
    </div>

    <div class="px-6 md:px-8 py-8">
      @if ($errors->any())
        <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700">
          กรุณาตรวจสอบข้อมูลที่กรอกให้ครบถ้วน
        </div>
      @endif

      <form method="POST" action="{{ route('admin.announcements.store') }}" enctype="multipart/form-data" class="grid gap-5">
        @csrf

        {{-- หัวข้อ --}}
        <div>
          <label class="block text-sm font-medium text-gray-700">หัวข้อ</label>
          <input
            name="title"
            value="{{ old('title') }}"
            required
            class="mt-1 w-full rounded-2xl border border-gray-200 px-4 py-3 bg-gray-50
                   focus:bg-white focus:border-brand-700 focus:ring-2 focus:ring-brand-700/20 outline-none"
          >
          @error('title')
            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div class="grid sm:grid-cols-2 gap-4">
          {{-- หมวดหมู่ (ไม่บังคับ) --}}
          <div>
            <label class="block text-sm font-medium text-gray-700">หมวดหมู่ (ไม่บังคับ)</label>
            <input
              name="category"
              value="{{ old('category') }}"
              class="mt-1 w-full rounded-2xl border border-gray-200 px-4 py-3 bg-gray-50
                     focus:bg-white focus:border-brand-700 focus:ring-2 focus:ring-brand-700/20 outline-none"
            >
            @error('category')
              <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
          </div>

          {{-- ภาพหน้าปก + พรีวิวทันที --}}
          <div>
            <label class="block text-sm font-medium text-gray-700">ภาพหน้าปก (ไม่บังคับ)</label>
            <input
              id="coverInputCreate"
              type="file"
              name="cover_image"
              accept="image/*"
              class="mt-1 block w-full text-sm file:mr-3 file:px-3 file:py-2 file:rounded-full
                     file:border-0 file:bg-brand-700 file:text-white hover:file:brightness-110"
            />
            @error('cover_image')
              <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror

            <div id="coverPreviewWrapCreate" class="mt-2 hidden">
              <div class="flex items-center justify-between mb-2">
                <span class="text-xs text-gray-500">ตัวอย่างรูปภาพ</span>
                <button type="button" id="clearSelectedCoverCreate" class="text-xs text-red-600 hover:underline">
                  ลบรูปที่เลือก
                </button>
              </div>
              <img
                id="coverPreviewCreate"
                alt="ภาพหน้าปก"
                class="h-24 w-full rounded-lg object-cover ring-1 ring-black/10"
              />
            </div>
          </div>
        </div>

        {{-- คำโปรย (ไม่บังคับ) --}}
        <div>
          <label class="block text-sm font-medium text-gray-700">คำโปรย (ไม่บังคับ)</label>
          <textarea
            name="excerpt"
            rows="2"
            class="mt-1 w-full rounded-2xl border border-gray-200 px-4 py-3 bg-gray-50
                   focus:bg-white focus:border-brand-700 focus:ring-2 focus:ring-brand-700/20 outline-none"
          >{{ old('excerpt') }}</textarea>
          @error('excerpt')
            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
          @enderror
        </div>

        {{-- เนื้อหา --}}
        <div>
          <label class="block text-sm font-medium text-gray-700">เนื้อหา</label>
          <textarea
            name="body"
            rows="10"
            required
            class="mt-1 w-full rounded-2xl border border-gray-200 px-4 py-3 bg-gray-50
                   focus:bg-white focus:border-brand-700 focus:ring-2 focus:ring-brand-700/20 outline-none"
          >{{ old('body') }}</textarea>
          @error('body')
            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
          @enderror
        </div>

        {{-- เผยแพร่ทันที + ปุ่มบันทึก --}}
        <div class="flex items-center justify-between">
          <label class="inline-flex items-center gap-2 text-sm text-gray-700">
            <input
              type="checkbox"
              name="is_published"
              value="1"
              class="rounded border-gray-300 text-brand-700 focus:ring-brand-700"
              {{ old('is_published') ? 'checked' : '' }}
            >
            เผยแพร่ทันที
          </label>
          <button
            class="px-6 py-3 rounded-full bg-brand-700 text-white hover:brightness-110
                   focus:outline-none focus:ring-2 focus:ring-brand-700/30"
          >
            บันทึก
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
  const input   = document.getElementById('coverInputCreate');
  const wrap    = document.getElementById('coverPreviewWrapCreate');
  const img     = document.getElementById('coverPreviewCreate');
  const clearBtn= document.getElementById('clearSelectedCoverCreate');
  let objectUrl = null;

  function show(el){ el && el.classList.remove('hidden'); }
  function hide(el){ el && el.classList.add('hidden'); }

  function clearSelected(){
    // ล้างไฟล์ที่เลือกและซ่อนพรีวิว
    if (input) input.value = '';
    if (objectUrl){ URL.revokeObjectURL(objectUrl); objectUrl = null; }
    hide(wrap);
    if (img) img.removeAttribute('src');
  }

  input?.addEventListener('change', (e) => {
    const file = e.target.files?.[0];
    if(!file){ clearSelected(); return; }

    // ตรวจชนิดไฟล์ให้เป็นรูปภาพ
    if(!file.type.startsWith('image/')){
      alert('กรุณาเลือกเป็นไฟล์รูปภาพเท่านั้น');
      clearSelected();
      return;
    }

    // (ทางเลือก) ตรวจขนาดไฟล์ เช่น ไม่เกิน 4MB
    // if (file.size > 4 * 1024 * 1024) {
    //   alert('ขนาดไฟล์ใหญ่เกินไป (เกิน 4MB)');
    //   clearSelected();
    //   return;
    // }

    if(objectUrl){ URL.revokeObjectURL(objectUrl); }
    objectUrl = URL.createObjectURL(file);
    img.src = objectUrl;
    show(wrap);

    // คืน memory หลังโหลดสำเร็จ
    img.onload = () => {
      if(objectUrl){ URL.revokeObjectURL(objectUrl); objectUrl = null; }
    };
  });

  clearBtn?.addEventListener('click', clearSelected);
})();
</script>
@endpush
