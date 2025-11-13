@extends('layouts.navigation')
@section('title','เกี่ยวกับเรา (แอดมิน) • Engenius Group')

@section('content')
@php
  $brand = '#020263';
  use Illuminate\Support\Str;
  use Illuminate\Support\Facades\Storage;

  // แปลง hero_image_url ให้เป็น src พร้อมใช้
  $heroSrc = null;
  if ($about && !empty($about->hero_image_url)) {
      $v = (string) $about->hero_image_url;
      $heroSrc = Str::startsWith($v, ['http://','https://']) ? $v : Storage::url($v);
  }

  // นับจำนวนรายการ array เดิม ๆ (affiliates / programs)
  $affCount = is_array($about->affiliates ?? null) ? count($about->affiliates) : 0;
  $progCount = is_array($about->programs ?? null) ? count($about->programs) : 0;
@endphp

<div class="max-w-6xl mx-auto px-4 py-10 space-y-6">

  {{-- Header + Actions --}}
  <div class="flex items-end justify-between gap-3">
    <div>
      <h1 class="text-2xl font-extrabold text-gray-900">เกี่ยวกับเรา (แอดมิน)</h1>
      <p class="text-sm text-gray-500">จัดการข้อมูลที่ใช้แสดงในหน้า
        <a href="{{ route('about.index') }}" target="_blank" class="text-[{{ $brand }}] underline">/about</a>
      </p>
    </div>

    <div class="flex items-center gap-2">
      @if(!$about)
        <a href="{{ route('admin.about.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-white text-sm font-semibold shadow"
           style="background: {{ $brand }}">
          + สร้างข้อมูลเกี่ยวกับเรา
        </a>
      @else
        <a href="{{ route('admin.about.edit', $about) }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-white text-sm font-semibold shadow"
           style="background: {{ $brand }}">
          ✏️ แก้ไขข้อมูล
        </a>
        <a href="{{ route('about.index') }}" target="_blank"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-full border text-sm font-semibold hover:bg-gray-50">
          ดูหน้า Public
        </a>
      @endif
    </div>
  </div>

  {{-- Flash --}}
  @if(session('success'))
    <div class="px-4 py-3 rounded-xl bg-emerald-50 text-emerald-800 text-sm border border-emerald-100">
      {{ session('success') }}
    </div>
  @endif

  {{-- Empty State --}}
  @if(!$about)
    <div class="rounded-2xl border border-dashed border-gray-300 bg-white p-8 text-center">
      <div class="text-3xl mb-2">ℹ️</div>
      <div class="text-gray-800 font-semibold">ยังไม่มีข้อมูลเกี่ยวกับบริษัท</div>
      <p class="text-gray-500 text-sm mt-1">กดปุ่ม “สร้างข้อมูลเกี่ยวกับเรา” เพื่อเริ่มต้นกรอกข้อมูล</p>
      <a href="{{ route('admin.about.create') }}"
         class="inline-flex items-center gap-2 mt-4 px-4 py-2 rounded-full text-white text-sm font-semibold shadow"
         style="background: {{ $brand }}">
        + สร้างข้อมูลเกี่ยวกับเรา
      </a>
    </div>
  @else
    {{-- Summary Card --}}
    <div class="rounded-2xl border border-gray-100 bg-white shadow p-6">
      <div class="grid md:grid-cols-3 gap-6">
        <div class="md:col-span-2">
          <div class="text-xs font-semibold" style="color: {{ $brand }}">ABOUT SUMMARY</div>

          <h2 class="mt-2 text-xl font-bold text-gray-900">
            {{ $about->hero_title_th ?: ($about->hero_title_en ?: '—') }}
          </h2>
          @if($about->hero_title_en)
            <div class="text-sm text-gray-500">
              {{ $about->hero_title_en }}
            </div>
          @endif

          <dl class="mt-4 grid sm:grid-cols-2 gap-4 text-sm">
            <div class="p-3 rounded-xl bg-gray-50">
              <dt class="text-gray-500">คำโปรย (Hero Intro)</dt>
              <dd class="text-gray-900 font-medium line-clamp-2">
                {{ $about->hero_intro_th ?: ($about->hero_intro_en ?: '—') }}
              </dd>
            </div>
            <div class="p-3 rounded-xl bg-gray-50">
              <dt class="text-gray-500">โทรศัพท์</dt>
              <dd class="text-gray-900 font-medium">{{ $about->phone ?: '—' }}</dd>
            </div>
            <div class="p-3 rounded-xl bg-gray-50">
              <dt class="text-gray-500">อีเมล</dt>
              <dd class="text-gray-900 font-medium">{{ $about->email ?: '—' }}</dd>
            </div>
            <div class="p-3 rounded-xl bg-gray-50">
              <dt class="text-gray-500">LINE</dt>
              <dd class="text-gray-900 font-medium">
                @if($about->line_url)
                  <a class="text-[{{ $brand }}] underline" href="{{ $about->line_url }}" target="_blank">{{ $about->line_url }}</a>
                @else
                  —
                @endif
              </dd>
            </div>
          </dl>

          <div class="mt-4 text-xs text-gray-500">
            อัปเดตล่าสุด: {{ optional($about->updated_at)->format('d/m/Y H:i') ?? '-' }}
          </div>
        </div>

        <div>
          <div class="aspect-video rounded-xl overflow-hidden border border-gray-100 bg-gray-50">
            @if($heroSrc)
              <img src="{{ $heroSrc }}" class="w-full h-full object-cover" alt="Hero image">
            @else
              <div class="w-full h-full flex items-center justify-center text-gray-400 text-sm">ไม่มีภาพหน้าปก</div>
            @endif
          </div>

          {{-- Counters --}}
          <div class="mt-4 grid grid-cols-2 gap-3">
            <div class="rounded-xl border border-gray-100 bg-white p-3 text-center">
              <div class="text-2xl font-bold text-gray-900">{{ $affCount }}</div>
              <div class="text-xs text-gray-500">บริษัทในเครือ</div>
            </div>
            <div class="rounded-xl border border-gray-100 bg-white p-3 text-center">
              <div class="text-2xl font-bold text-gray-900">{{ $progCount }}</div>
              <div class="text-xs text-gray-500">โครงการ</div>
            </div>
          </div>

          <div class="mt-4 flex gap-2">
            <a href="{{ route('admin.about.edit', $about) }}"
               class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl text-white text-sm font-semibold shadow"
               style="background: {{ $brand }}">
              ✏️ แก้ไข
            </a>
            <a href="{{ route('about.index') }}" target="_blank"
               class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl border text-sm font-semibold hover:bg-gray-50">
              ดูหน้า Public
            </a>
          </div>
        </div>
      </div>
    </div>

    {{-- Preview Snippet --}}
    <div class="rounded-2xl border border-gray-100 bg-white shadow p-6">
      <h3 class="text-lg font-semibold mb-3" style="color: {{ $brand }}">ตัวอย่างเนื้อหา (TH)</h3>
      <div class="prose max-w-none">
        {!! nl2br(e($about->intro_th ?: ($about->hero_intro_th ?: '—'))) !!}
      </div>
    </div>
  @endif

</div>
@endsection
