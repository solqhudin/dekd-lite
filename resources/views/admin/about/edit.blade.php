@extends('layouts.navigation')
@section('title','แก้ไขข้อมูลเกี่ยวกับเรา • Engenius Group')

@section('content')
@php
  $brand = '#020263';
  use Illuminate\Support\Str;
  use Illuminate\Support\Facades\Storage;

  // คำนวณรูป hero ปัจจุบันจาก hero_image_url เพียงคอลัมน์เดียว
  $currentHero = null;
  if (!empty($about->hero_image_url)) {
      $v = (string) $about->hero_image_url;
      $currentHero = Str::startsWith($v, ['http://','https://']) ? $v : Storage::url($v);
  }
@endphp

<div class="max-w-5xl mx-auto px-4 py-10">
  <div class="flex items-start justify-between gap-3 mb-6">
    <div>
      <h1 class="text-2xl font-extrabold text-gray-900">แก้ไขข้อมูลเกี่ยวกับเรา</h1>
      <p class="text-sm text-gray-500">ข้อมูลทั้งหมดเป็น “ข้อความปกติ” เพื่อให้กรอกได้ง่าย</p>
    </div>

    <div class="flex items-center gap-2">
      <a href="{{ route('about.index') }}" target="_blank"
         class="px-3 py-1.5 text-xs rounded-full border border-gray-200 text-gray-700 hover:bg-gray-50">
        ดูหน้า Public
      </a>
    </div>
  </div>

  @if (session('success'))
    <div class="mb-4 p-4 rounded-xl bg-emerald-50 text-emerald-700 text-sm">
      {{ session('success') }}
    </div>
  @endif

  @if ($errors->any())
    <div class="mb-4 p-4 rounded-xl bg-red-50 text-red-600 text-sm">
      <ul class="list-disc list-inside">
        @foreach($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  {{-- ✅ ฟอร์มเดียว + รองรับอัปโหลดไฟล์ --}}
  <form method="POST" action="{{ route('admin.about.update', $about) }}" class="space-y-6" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    {{-- HERO --}}
    <div class="rounded-2xl bg-white border border-gray-100 p-5">
      <div class="text-sm font-semibold mb-3" style="color: {{$brand}}">ส่วนหัว (HERO)</div>

      <div class="grid sm:grid-cols-2 gap-4">
        <div>
          <label class="text-xs text-gray-600">Badge (เช่น ABOUT US)</label>
          <input name="hero_badge" value="{{ old('hero_badge', $about->hero_badge) }}" class="mt-1 w-full border rounded-lg px-3 py-2" placeholder="ABOUT US">
        </div>
        <div>
          <label class="text-xs text-gray-600">โทรศัพท์</label>
          <input name="phone" value="{{ old('phone', $about->phone) }}" class="mt-1 w-full border rounded-lg px-3 py-2" placeholder="02-123-4567">
        </div>

        <div>
          <label class="text-xs text-gray-600">อีเมล</label>
          <input name="email" type="email" value="{{ old('email', $about->email) }}" class="mt-1 w-full border rounded-lg px-3 py-2" placeholder="info@engenius.co.th">
        </div>
        <div>
          <label class="text-xs text-gray-600">Line URL</label>
          <input name="line_url" value="{{ old('line_url', $about->line_url) }}" class="mt-1 w-full border rounded-lg px-3 py-2" placeholder="https://line.me/R/ti/p/@engenius">
        </div>

        <div class="sm:col-span-2">
          <label class="text-xs text-gray-600">ที่อยู่</label>
          <input name="address" value="{{ old('address', $about->address) }}" class="mt-1 w-full border rounded-lg px-3 py-2" placeholder="Bangkok, Thailand">
        </div>
      </div>

      <div class="grid sm:grid-cols-2 gap-4 mt-4">
        <div>
          <label class="text-xs text-gray-600">หัวข้อ (TH)</label>
          <input name="hero_title_th" value="{{ old('hero_title_th', $about->hero_title_th) }}" class="mt-1 w-full border rounded-lg px-3 py-2" placeholder="บริษัท เอ็นจีเนียส กรุ๊ป จำกัด">
        </div>
        <div>
          <label class="text-xs text-gray-600">หัวข้อ (EN)</label>
          <input name="hero_title_en" value="{{ old('hero_title_en', $about->hero_title_en) }}" class="mt-1 w-full border rounded-lg px-3 py-2" placeholder="Engenius Group Co., Ltd.">
        </div>

        <div>
          <label class="text-xs text-gray-600">คำอธิบาย (TH)</label>
          <textarea name="hero_intro_th" rows="4" class="mt-1 w-full border rounded-lg px-3 py-2" placeholder="ผู้นำด้านการศึกษานานาชาติ...">{{ old('hero_intro_th', $about->hero_intro_th) }}</textarea>
        </div>
        <div>
          <label class="text-xs text-gray-600">คำอธิบาย (EN)</label>
          <textarea name="hero_intro_en" rows="4" class="mt-1 w-full border rounded-lg px-3 py-2" placeholder="Engenius Group has over the years...">{{ old('hero_intro_en', $about->hero_intro_en) }}</textarea>
        </div>
      </div>

      <div class="grid sm:grid-cols-2 gap-4 mt-4">
        {{-- ✅ อัปโหลดจากเครื่องแทน URL + พรีวิว (เก็บลง hero_image_url เดิม) --}}
        <div>
          <label class="text-xs text-gray-600">Hero Image (อัปโหลดจากเครื่อง)</label>
          <input
            id="heroImageInput"
            type="file"
            name="hero_image"
            accept="image/*"
            class="mt-1 block w-full text-sm file:mr-3 file:px-3 file:py-2 file:rounded-full
                   file:border-0 file:bg-brand-700 file:text-white hover:file:brightness-110"
          />
          @error('hero_image')
            <p class="text-[11px] text-red-600 mt-1">{{ $message }}</p>
          @enderror

          <div id="heroPreviewWrap" class="mt-2 {{ $currentHero ? '' : 'hidden' }}">
            <div class="flex items-center justify-between mb-2">
              <span class="text-[11px] text-gray-500">
                {{ $currentHero ? 'แสดงรูปเดิม • เลือกไฟล์ใหม่เพื่อพรีวิวแทนที่' : 'เลือกไฟล์เพื่อแสดงตัวอย่าง' }}
              </span>
              <button type="button" id="clearHeroSelected" class="text-[11px] text-red-600 hover:underline">
                ลบรูปที่เลือก
              </button>
            </div>
            <img
              id="heroPreview"
              src="{{ $currentHero }}"
              data-default-src="{{ $currentHero }}"
              alt="Hero image"
              class="w-full h-48 md:h-56 rounded-2xl object-cover ring-1 ring-black/10"
            />
          </div>
        </div>

        <div>
          <label class="text-xs text-gray-600">Map Query</label>
          <input name="map_query" value="{{ old('map_query', $about->map_query) }}" class="mt-1 w-full border rounded-lg px-3 py-2" placeholder="Bangkok Thailand">
          <p class="text-[11px] text-gray-500 mt-1">ใช้ใน iframe: https://www.google.com/maps?q=&lt;map_query&gt;&amp;output=embed</p>
        </div>
      </div>
    </div>

    {{-- เนื้อหาหลัก (ข้อความธรรมดา) --}}
    <div class="rounded-2xl bg-white border border-gray-100 p-5">
      <div class="text-sm font-semibold mb-3" style="color: {{$brand}}">ข้อมูลองค์กร (ข้อความธรรมดา)</div>
      <div class="grid sm:grid-cols-2 gap-4">
        <div>
          <label class="text-xs text-gray-600">คำอธิบายบริษัท (TH)</label>
          <textarea name="intro_th" rows="5" class="mt-1 w-full border rounded-lg px-3 py-2" placeholder="รายละเอียดภาษาไทย...">{{ old('intro_th', $about->intro_th) }}</textarea>
        </div>
        <div>
          <label class="text-xs text-gray-600">Company Intro (EN)</label>
          <textarea name="intro_en" rows="5" class="mt-1 w-full border rounded-lg px-3 py-2" placeholder="English introduction...">{{ old('intro_en', $about->intro_en) }}</textarea>
        </div>
        <div>
          <label class="text-xs text-gray-600">วิสัยทัศน์ (Vision)</label>
          <textarea name="vision" rows="4" class="mt-1 w-full border rounded-lg px-3 py-2" placeholder="วิสัยทัศน์องค์กร...">{{ old('vision', $about->vision) }}</textarea>
        </div>
        <div>
          <label class="text-xs text-gray-600">พันธกิจ (Mission)</label>
          <textarea name="mission" rows="4" class="mt-1 w-full border rounded-lg px-3 py-2" placeholder="พันธกิจองค์กร...">{{ old('mission', $about->mission) }}</textarea>
        </div>
      </div>
    </div>

    {{-- บริษัทในเครือ --}}
    <div class="rounded-2xl bg-white border border-gray-100 p-5">
      <div class="text-sm font-semibold" style="color: {{$brand}}">บริษัทในเครือ</div>
      <p class="text-[11px] text-gray-500 mt-1">พิมพ์ทีละบรรทัด (หนึ่งรายการต่อหนึ่งบรรทัด)</p>
      <textarea name="companies_text" rows="4" class="mt-2 w-full border rounded-lg px-3 py-2" placeholder="Engenius International Co., Ltd.&#10;Engenius English Co., Ltd.">{{ old('companies_text', $about->companies_text) }}</textarea>
    </div>

    {{-- โครงการ / Programs --}}
    <div class="rounded-2xl bg-white border border-gray-100 p-5">
      <div class="text-sm font-semibold" style="color: {{$brand}}">โครงการ / Programs</div>
      <p class="text-[11px] text-gray-500 mt-1">พิมพ์ทีละบรรทัด เช่น “High School Exchange Program — แลกเปลี่ยนมัธยม 1 ปี”</p>
      <textarea name="programs_text" rows="6" class="mt-2 w-full border rounded-lg px-3 py-2" placeholder="High School Exchange Program — แลกเปลี่ยนมัธยม 1 ปี&#10;Work &amp; Travel — ทำงาน/ท่องเที่ยว 2–4 เดือน">{{ old('programs_text', $about->programs_text) }}</textarea>
    </div>

    {{-- ค่านิยมองค์กร / ทีม --}}
    <div class="rounded-2xl bg-white border border-gray-100 p-5">
      <div class="text-sm font-semibold" style="color: {{$brand}}">ค่านิยมองค์กร / ทีม</div>
      <p class="text-[11px] text-gray-500 mt-1">พิมพ์ทีละบรรทัด เช่น “One Team”, “Customer First”, หรือ “Dr. Jane Doe — CEO &amp; Founder”</p>
      <textarea name="values_text" rows="6" class="mt-2 w-full border rounded-lg px-3 py-2" placeholder="One Team&#10;Impact over Output&#10;Dr. Jane Doe — CEO &amp; Founder">{{ old('values_text', $about->values_text) }}</textarea>
    </div>

    {{-- FAQ / ช่องทางติดต่อเสริม --}}
    <div class="rounded-2xl bg-white border border-gray-100 p-5">
      <div class="text-sm font-semibold" style="color: {{$brand}}">FAQ / ช่องทางติดต่อเสริม</div>
      <p class="text-[11px] text-gray-500 mt-1">พิมพ์ทีละบรรทัด เช่น “มีทุนอะไรบ้าง? — มีหลายประเภท เปิดรับทุกปี” หรือ “Facebook — https://facebook.com/...”</p>
      <textarea name="contacts_text" rows="6" class="mt-2 w-full border rounded-lg px-3 py-2" placeholder="มีทุนอะไรบ้าง? — มีหลายประเภท เปิดรับทุกปี&#10;Facebook — https://facebook.com/engenius">{{ old('contacts_text', $about->contacts_text) }}</textarea>
    </div>

    <div class="pt-2 flex items-center gap-2">
      <button class="px-5 py-2.5 rounded-full text-white font-semibold hover:brightness-110" style="background: {{$brand}}">
        บันทึกการเปลี่ยนแปลง
      </button>
      <a href="{{ route('admin.about.index') }}" class="px-4 py-2 rounded-full text-xs border border-gray-200 text-gray-700 hover:bg-gray-50">
        กลับหน้ารายการ
      </a>
    </div>
  </form>
</div>
@endsection

@push('scripts')
<script>
(function () {
  const input   = document.getElementById('heroImageInput');
  const wrap    = document.getElementById('heroPreviewWrap');
  const img     = document.getElementById('heroPreview');
  const clearBtn= document.getElementById('clearHeroSelected');
  const defaultSrc = img?.getAttribute('data-default-src') || '';
  let objectUrl = null;

  function show(el){ el && el.classList.remove('hidden'); }
  function hide(el){ el && el.classList.add('hidden'); }
  function setPreview(src){
    if(!src){ hide(wrap); return; }
    img.src = src;
    show(wrap);
  }
  function clearSelected(){
    if (input) input.value = '';
    if (objectUrl){ URL.revokeObjectURL(objectUrl); objectUrl = null; }
    if (defaultSrc){ setPreview(defaultSrc); }
    else { hide(wrap); img?.removeAttribute('src'); }
  }

  input?.addEventListener('change', (e) => {
    const file = e.target.files?.[0];
    if(!file){ clearSelected(); return; }
    if(!file.type.startsWith('image/')){ alert('กรุณาเลือกเป็นไฟล์รูปภาพเท่านั้น'); clearSelected(); return; }
    if(objectUrl){ URL.revokeObjectURL(objectUrl); }
    objectUrl = URL.createObjectURL(file);
    setPreview(objectUrl);
    img.onload = () => { if(objectUrl){ URL.revokeObjectURL(objectUrl); objectUrl = null; } };
  });

  clearBtn?.addEventListener('click', clearSelected);
  if(!defaultSrc){ hide(wrap); }
})();
</script>
@endpush
