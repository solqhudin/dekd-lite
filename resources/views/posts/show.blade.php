<x-app-layout>
    <div class="max-w-3xl mx-auto py-8">
        <h1 class="text-3xl font-bold mb-2">{{ $post->title }}</h1>
        <div class="text-xs text-gray-500 mb-6">
            โดย {{ $post->author?->name ?? 'ไม่ทราบ' }}
            · {{ $post->created_at->format('d/m/Y H:i') }}
        </div>
        <div class="prose max-w-none">
            {!! nl2br(e($post->content)) !!}
        </div>
    </div>
</x-app-layout>
