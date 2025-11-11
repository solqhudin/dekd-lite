@extends('layouts.navigation')
@section('title', ($announcement->title ?? 'ประกาศ').' • Engenius Group')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-10">
  <a href="{{ route('announcements.index') }}" class="text-sm text-brand-700 hover:underline">← กลับไปหน้ารวม</a>

  <h1 class="mt-3 text-2xl font-extrabold text-gray-900">{{ $announcement->title }}</h1>
  <div class="mt-1 text-xs text-gray-500">
    หมวด: {{ $announcement->category ?: 'ทั่วไป' }}
    @if($announcement->published_at) · เผยแพร่ {{ $announcement->published_at->format('Y-m-d H:i') }} @endif
  </div>

  @if($announcement->cover_image)
    <img src="{{ asset('storage/'.$announcement->cover_image) }}" class="mt-4 rounded-2xl w-full object-cover" alt="">
  @endif

  @if($announcement->excerpt)
    <p class="mt-4 text-gray-700">{{ $announcement->excerpt }}</p>
  @endif

  <article class="prose max-w-none mt-6">
    {!! $announcement->body !!}
  </article>
</div>
@endsection
