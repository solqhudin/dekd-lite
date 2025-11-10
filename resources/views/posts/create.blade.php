<x-app-layout>
    <div class="max-w-3xl mx-auto py-8">
        <h1 class="text-2xl font-bold mb-4">ตั้งกระทู้ใหม่</h1>

        <form method="POST" action="{{ route('posts.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium mb-1">หัวข้อ</label>
                <input type="text" name="title" value="{{ old('title') }}"
                       class="w-full border rounded px-3 py-2" required>
                @error('title')
                <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">เนื้อหา</label>
                <textarea name="content" rows="8"
                          class="w-full border rounded px-3 py-2"
                          required>{{ old('content') }}</textarea>
                @error('content')
                <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <p class="text-xs text-gray-500">
                * กระทู้ของคุณจะถูกส่งให้แอดมินตรวจสอบก่อนแสดงบนหน้าเว็บ
            </p>

            <button class="px-4 py-2 bg-black text-white rounded">
                ส่งกระทู้
            </button>
        </form>
    </div>
</x-app-layout>

