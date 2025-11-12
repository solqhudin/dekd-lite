{{-- resources/views/announcements/show.blade.php --}}
@extends('layouts.navigation')

@section('title', ($announcement->title ?? 'ประกาศ').' • Engenius Group')

@section('content')
<div class="bg-white">
  <div class="max-w-3xl mx-auto py-10 px-4 sm:px-6 lg:px-8">

    {{-- Breadcrumb --}}
    <nav class="text-[11px] text-slate-500 mb-4">
      <a href="{{ route('announcements.index') }}" class="hover:text-brand-700">ประชาสัมพันธ์</a>
      <span class="mx-1.5">/</span>
      <span class="text-slate-700">{{ Str::limit($announcement->title, 80) }}</span>
    </nav>

    {{-- Title + meta --}}
    <h1 class="text-2xl sm:text-3xl font-bold text-slate-900">{{ $announcement->title }}</h1>

    <div class="mt-2 flex flex-wrap items-center gap-3 text-[11px] text-slate-500">
      @if($announcement->category)
        <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-600">{{ $announcement->category }}</span>
      @endif

      @if($announcement->published_at)
        <span>เผยแพร่ {{ $announcement->published_at->format('d M Y') }}</span>
      @endif

      @if($announcement->author?->name)
        <span>โดย <span class="text-slate-700 font-medium">{{ $announcement->author->name }}</span></span>
      @endif
    </div>

    {{-- Cover Image --}}
    @if($announcement->cover_image)
      <img
        src="{{ asset('storage/'.$announcement->cover_image) }}"
        class="mt-6 w-full rounded-2xl object-cover ring-1 ring-black/5"
        alt="{{ $announcement->title }}">
    @endif

    {{-- Excerpt (optional) --}}
    @if($announcement->excerpt)
      <p class="mt-6 text-slate-600 text-sm bg-slate-50 border border-slate-100 rounded-xl p-4">
        {{ $announcement->excerpt }}
      </p>
    @endif

    {{-- Body (เนื้อหาหลัก) --}}
    <article class="prose prose-sm sm:prose max-w-none mt-6">
      {{-- ถ้าคอนเทนต์จากแอดมินไว้ใจได้ ให้แสดง HTML ได้เลย --}}
      {!! $announcement->body !!}
      {{-- ถ้าอยากบังคับเป็นข้อความล้วน ให้ใช้บรรทัดล่างแทน และลบบรรทัดบน
      {!! nl2br(e($announcement->body)) !!}
      --}}
    </article>

    {{-- Back button --}}
    <div class="mt-10">
      <a href="{{ route('announcements.index') }}"
         class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-slate-200 text-slate-700 hover:border-brand-700/40 hover:text-brand-700">
        ← กลับไปหน้าประชาสัมพันธ์
      </a>
    </div>

  </div>
</div>
@endsection
